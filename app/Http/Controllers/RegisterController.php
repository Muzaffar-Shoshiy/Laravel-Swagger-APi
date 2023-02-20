<?php

namespace App\Http\Controllers;

use App\Http\Requests\RegisterRequest;
use App\Repositories\AuthRepository;
use App\Traits\ResponseTrait;
use Exception;
use Illuminate\Http\JsonResponse;

class RegisterController extends Controller
{
    use ResponseTrait;
    public function __construct(private AuthRepository $auth)
    {
        $this->auth = $auth;
    }
    /**
     * @OA\Post(
     *     path="/api/register",
     *     tags={"Authentication"},
     *     summary="Register",
     *     description="Register to system",
     *     operationId="Register",
     *     deprecated=false,
     *     @OA\RequestBody(
     *         description="Register to System",
     *         required=true,
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(
     *                 type="object",
     *                 @OA\Property(
     *                     property="name",
     *                     description="User name",
     *                     type="string",
     *                     example="Muzaffar Doe"
     *                 ),
     *                 @OA\Property(
     *                     property="email",
     *                     description="User email",
     *                     type="string",
     *                     example="muzaffar@gmail.com"
     *                 ),
     *                 @OA\Property(
     *                     property="password",
     *                     description="User password",
     *                     type="string",
     *                     example="12345678"
     *                 ),
     *                 required={"name","email","password"}
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
    public function register(RegisterRequest $request): JsonResponse
    {

        try {
            $data = $this->auth->register($request->all());
            return $this->responseSuccess($data, 'User created successfully!');
        } catch (Exception $exception) {
            return $this->responseError([], $exception->getMessage());
        }
    }
}
