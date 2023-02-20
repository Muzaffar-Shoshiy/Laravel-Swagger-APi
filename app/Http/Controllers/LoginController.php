<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoginRequest;
use App\Repositories\AuthRepository;
use App\Traits\ResponseTrait;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Hash;

class LoginController extends Controller
{
    use ResponseTrait;
    public function __construct(private AuthRepository $auth)
    {
        $this->auth = $auth;
    }
     /**
     * @OA\Post(
     *     path="/api/login",
     *     tags={"Authentication"},
     *     summary="Login",
     *     description="Login to system",
     *     operationId="login",
     *     deprecated=false,
     *     @OA\RequestBody(
     *         description="Login to System",
     *         required=true,
     *         @OA\MediaType(
     *             mediaType="application/x-www-form-urlencoded",
     *             @OA\Schema(
     *                 type="object",
     *                 @OA\Property(
     *                     property="email",
     *                     description="User email",
     *                     type="string"
     *                 ),
     *                 @OA\Property(
     *                     property="password",
     *                     description="User password",
     *                     type="string"
     *                 ),
     *                 required={"email","password"}
     *             )
     *         ),
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Successful operation"
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Invalid input"
     *     )
     * )
     */
    public function login(LoginRequest $request): JsonResponse
    {

        try {
            $data = $this->auth->login($request->all());
            return $this->responseSuccess($data, 'Logged in successfully!');
        } catch (Exception $exception) {
            return $this->responseError([], $exception->getMessage());
        }
    }
}
