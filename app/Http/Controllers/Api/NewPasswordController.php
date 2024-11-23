<?php

namespace App\Http\Controllers\Api;

use Exception;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Traits\ResponseHandlerTrait;
use Illuminate\Support\Facades\Hash;
use App\Traits\ApiResponseFormatTrait;
use Illuminate\Support\Facades\Password;
use Illuminate\Auth\Events\PasswordReset;
use App\Http\Requests\ResetPasswordRequest;
use App\Http\Requests\ForgotPasswordRequest;

class NewPasswordController extends Controller
{

    use ApiResponseFormatTrait;

    public function forgotPassword(ForgotPasswordRequest $request) {
        try {

            $status = Password::sendResetLink(
                $request->only('email')
            );

            return $status === Password::RESET_LINK_SENT
                ? $this->successResponse('success', 200, __($status))
                : $this->errorResponse('error', 400, __($status));

        } catch (Exception $exception) {
            $this->recordException($exception);
            return $this->serverErrorResponse($exception);
        }
    }


    public function resetPassword(ResetPasswordRequest $request)
    {
        try{

            $status = Password::reset(
                $request->only('email', 'password', 'password_confirmation', 'token'),
                function ($user) use ($request) {
                    $user->forceFill([
                        'password' => Hash::make($request->password),
                        'remember_token' => Str::random(60),
                    ])->save();

                    $user->save();

                    event(new PasswordReset($user));
                }
            );

            return $status === Password::PASSWORD_RESET
                ? $this->successResponse('success', 200, __($status))
                : $this->errorResponse('error', 400, __($status));


        }catch (Exception $exception) {
            $this->recordException($exception);
            return $this->serverErrorResponse($exception);
        }
    }
}
