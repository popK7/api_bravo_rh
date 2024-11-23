<?php

namespace App\Http\Resources;

use App\Enums\Messages;
use App\Enums\ApiStatus;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Http\Resources\Json\JsonResource;

class LoginResource extends JsonResource
{

    public static $wrap = null;

    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'response' => [
                'status'      => ApiStatus::SUCCESS,
                'status_code' => Response::HTTP_OK,
                'message'     => Messages::LOGIN_SUCCESSFUL,
                'data' => [
                    'token_type'   => 'bearer',
                    'access_token' => $this->resource,
                    'expires_in'   => auth()->factory()->getTTL() * 60,
                    'account'      => Auth::user()->account,
                    'roles'        => Auth::user()->roles
                ],
            ]
        ];
    }
}
