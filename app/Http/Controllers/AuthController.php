<?php

namespace App\Http\Controllers;

use Exception;
use App\Models\Role;
use App\Models\User;
use App\Models\Account;
use Illuminate\Support\Facades\DB;
use App\Http\Requests\LoginRequest;
use App\Http\Controllers\Controller;
use App\Http\Resources\UserResource;
use App\Http\Resources\LoginResource;
use App\Traits\ApiResponseFormatTrait;
use Illuminate\Database\QueryException;
use App\Http\Requests\RegistrationRequest;
use App\Notifications\UserRegistered as NotificationsUserRegistered;

class AuthController extends Controller
{
    use ApiResponseFormatTrait;

    public function login(LoginRequest $request)
    {
        try {
            $credentials = $request->validated();
            if (!$token = auth()->attempt($credentials)) {
                return $this->unauthorizedResponse();
            }
            return new LoginResource($token);
        } catch (QueryException $queryException) {
            return $this->queryExceptionResponse($queryException);
        } catch (Exception $exception) {
            $this->recordException($exception);
            return $this->serverErrorResponse($exception);
        }
    }

    public function register(RegistrationRequest $request)
    {
        DB::beginTransaction();

        try {
            // create account
            $accountData = ['name' => $request->account];
            $account = Account::create($accountData);
            // create user
            $user = User::create( $request->validated());
            $user->account_id = $account->id;
            $user->save();
            // roles
            $role = Role::where('slug', 'admin')->first();
            $user->roles()->attach($role->id, ['start_at' => date('Y-m-d')]);
            // notify user
            $user->notify(new NotificationsUserRegistered($user));

            DB::commit();

            return (new UserResource($user))->additional($this->preparedResponse('store'));
        } catch (QueryException $queryException) {

            DB::rollBack();

            return $this->queryExceptionResponse($queryException);
        }
    }
}
