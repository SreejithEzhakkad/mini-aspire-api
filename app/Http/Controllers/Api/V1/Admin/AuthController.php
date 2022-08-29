<?php

namespace App\Http\Controllers\Api\V1\Admin;

use App\Http\Controllers\Api\BaseController;
use Illuminate\Http\Request;
use App\Models\Admin;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class AuthController extends BaseController
{    
    /**
     * register
     *
     * @param  mixed $request
     * @return void
     */
    public function register(Request $request)
    {
        try {
            
            $validator = Validator::make($request->all(), 
            [
                'name'     => ['required', 'string', 'max:255'],
                'email'    => ['required', 'string', 'email', 'max:255', 'unique:admins'],
                'password' => ['required', 'string', 'min:8', 'confirmed'],
            ]);

            if($validator->fails()){
                return $this->sendError(__('Validation Error.'), $validator->errors());
            }

            $user = Admin::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password)
            ]);

            $response = [
                'token' => $user->createToken("API TOKEN",['admin.*'])->plainTextToken
            ];

            return $this->sendResponse($response, __('You have been registered successfully.'));
            
        } catch (\Throwable $th) {

            return $this->sendError($th->getMessage(), [], 500);
        }
    }
    
    /**
     * login
     *
     * @param  mixed $request
     * @return void
     */
    public function login(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), 
            [
                'email'    => 'required|email',
                'password' => 'required'
            ]);

            if($validator->fails()){
                return $this->sendError(__('Validation Error.'), $validator->errors());
            }
        
            $user = Admin::where('email', $request->email)->first();
        
            if (! $user || ! Hash::check($request->password, $user->password)) {
                return $this->sendError(__('Email & Password does not match with our record.'), [], 401);
            }

            $response['token'] =  $user->createToken('API TOKEN',['admin.*'])->plainTextToken; 
                
            return $this->sendResponse($response, __('You have been logged in successfully.'));

        } catch (\Throwable $th) {
            
            return $this->sendError($th->getMessage(), [], 500);
        } 
    }

    /**
     * logout
     *
     * @param  mixed $request
     * @return void
     */
    public function logout(Request $request)
    {
        try {
            $request->user()->currentAccessToken()->delete();
            return $this->sendResponse([], __('You have been logged out successfully.'));

        } catch (\Throwable $th) {
            
            return $this->sendError($th->getMessage(), [], 500);
        } 
    }
}
