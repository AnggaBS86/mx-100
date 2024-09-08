<?php

namespace App\Http\Controllers;

use App\Repository\UserRepositoryInterface;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Auth;
use Validator;
use App\Models\User;

class AuthController extends Controller
{

    private UserRepositoryInterface $inteface;

    /**
     * constructor initialize var $interface for dependency injection
     */
    public function __construct(UserRepositoryInterface $interface, Request $request)
    {
        $this->inteface = $interface;

    }

    /**
     * this method used for handling registration user
     * the user could be employer or freelancer
     * 
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function register(Request $request): \Illuminate\Http\JsonResponse
    {
        //validation using my own PHP extension library
        //the library was created by using C language
        //validate_email($email): bool
        //un-comment this if you have installed the library
        //information detail could be found at https://github.com/AnggaBS86/validate-email-php-ext 
        /*
        $validEmail = validate_email($request->email);
        if (false == $validEmail) {
            return response()->json('email invalid!');
        }
        */

        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'user_type_id' => 'required|int',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8'
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors());
        }

        $user = $this->inteface->store($request);
        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json(['data' => $user, 'access_token' => $token, 'token_type' => 'Bearer',]);
    }

    /**
     * Login function
     * 
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function login(Request $request): \Illuminate\Http\JsonResponse
    {
        if (!Auth::attempt($request->only('email', 'password'))) {
            return response()
                ->json(['message' => 'Unauthorized'], 401);
        }

        $user = User::where('email', $request['email'])->firstOrFail();

        $token = $user->createToken('auth_token')->plainTextToken;

        return response()
            ->json(['message' => 'Hi ' . $user->name . ', welcome to home', 'access_token' => $token, 'token_type' => 'Bearer',]);
    }

    // method for user logout and delete token
    public function logout()
    {
        auth()->user()->tokens()->delete();

        return [
            'message' => 'You have successfully logged out and the token was successfully deleted'
        ];
    }
}