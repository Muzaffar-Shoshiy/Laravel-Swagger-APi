<?php

namespace App\Repositories;

use Carbon\Carbon;
use App\Models\User;
use App\Traits\ResponseTrait;
use TheSeer\Tokenizer\Exception;
use Illuminate\Support\Facades\Hash;
use Laravel\Passport\PersonalAccessTokenResult;

class AuthRepository
{
    use ResponseTrait;
    public function getUserByEmail(string $email): ?User
    {
        return User::where('email', $email)->first();
    }
    public function isValidPassword(User $user, array $data)
    {
        if(Hash::check($data['password'], $user->password)){
            $token = $user->createToken('authToken');
            $data = [
                'user' => $user,
                'access_token' => $token->accessToken,
                'token_type' => 'Bearer',
                'expires_at' => Carbon::parse($token->token->expires_at)->toDateTimeString()
            ];
            return $this->responseSuccess($data, 'Logged in successfully!');
        }
    }
    public function login(array $data): array
    {
        $user = $this->getUserByEmail($data['email']);
        if(!$user){
            throw new Exception('Sorry, user does not exist.', 404);
        }
        if(!$this->isValidPassword($user, $data)){
            throw new Exception('Sorry, password does not match.', 401);
        }
        $token = $this->createAuthToken($user);
        return $this->getAuthData($user, $token);
    }
    public function register(array $data): array
    {
        $user = User::create($this->prepareDataForRegister($data));

        if(!$user){
            throw new Exception('Sorry, user did not registered, please try again.', 500);
        }
        if(!$this->isValidPassword($user, $data)){
            throw new Exception('Sorry, password does not match.', 401);
        }
        $token = $this->createAuthToken($user);
        return $this->getAuthData($user, $token);
    }
    public function createAuthToken(User $user): PersonalAccessTokenResult
    {
        return $user->createToken('authToken');
    }
    public function getAuthData(User $user, PersonalAccessTokenResult $token)
    {
        return [
            'user' => $user,
            'access_token' => $token->accessToken,
            'token_type' => 'Bearer',
            'expires_at' => Carbon::parse($token->token->expires_at)->toDateTimeString()
        ];
    }
    public function prepareDataForRegister(array $data): array
    {
        return [
           'name' => $data['name'],
           'email' => $data['email'],
           'password' => Hash::make($data['password']),
        ];
    }
}
