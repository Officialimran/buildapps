<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\BaseController as BaseController;
use Illuminate\Http\Request;
use Validator;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class RegisterController extends BaseController
{
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name'  => 'required|string|max:255',
            'email'  => 'required|string|max:255|unique:users',
            'password' => 'required|min:6',
            'confirm_password' => 'required|same:password',

        ]);


        if ($validator->fails()) {
            return $this->sendError('Validation Error', $validator->errors());
        }

        $password = bcrypt($request->password);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => $password,
        ]);


        $success['token'] = $user->createToken('RestApi')->plainTextToken;
        $success['name'] = $user->name;

        return $this->sendResponse($success, 'User has been successfully registered');
    }



    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email'  => 'required',
            'password' => 'required|min:6',

        ]);


        if ($validator->fails()) {
            return $this->sendError('Validation Error', $validator->errors());
        }

        if (Auth::attempt(['email' => $request->email, 'password' => $request->password])) {
            $user = Auth::user();
            $success['token'] = $user->createToken('RestApi')->plainTextToken;
            $success['name'] = $user->name;

            return $this->sendResponse($success, 'User login successfully');
        } else {
            return $this->sendError('Unathorized', ['error' => 'Unathorized']);
        }
    }

    //logout function 
    public function logout()
    {
        Auth()->user()->tokens()->delete();
        return $this->sendResponse([], 'User logout successfully');
    }
}
