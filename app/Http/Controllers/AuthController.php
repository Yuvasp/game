<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;
use Tymon\JWTAuth\Exceptions\JWTException;
use JWTAuth;

/**
 * Auth Controller used by JWT | OAuth
 * 
 * @author yuvasp
 *
 */
class AuthController extends Controller
{

	/**
	 * Store a newly created user in storage.
	 *  
	 * @param Request $request
	 * @return \Illuminate\Http\JsonResponse
	 */
    public function store(Request $request) 
    {
    	
    	$this->validate($request, [
	    	'name' => 'required',
    		'email' => 'required|email',
 			'password' => 'required|min:8'
    	]);
    	
    	$name = $request->input('name');
    	$email = $request->input('email');
    	$password = $request->input('password');
    	
    	
    	$user = new User([
    		'name' => $name,
    		'email' => $email,
    		'password' => bcrypt($password)
    	]);
    	
    	if ($user->save()) {
    		$user->signin = [
    			'href' => 'api/v1/user/signin',
    			'method' => 'POST',
    			'params' => 'email, password'
    		];
    		
    		$response = [
    			'msg' => 'User Created',
    			'user' => $user
    		];
    		
    		return response()->json($response, 201);
    	}
    	
    	$response = [
    		'msg' => 'An error occured'
    	];
    	
    	return response()->json($response, 404);
	}
	
	/**
	 * Allow user to singin and get the valid token
	 * 
	 * @param Request $request
	 * @return \Illuminate\Http\JsonResponse
	 */
	public function signin(Request $request)
	{
		$this->validate($request, [
			'email' => 'required|email',
			'password' => 'required'
		]);
 		
		$credentials = $request->only('email', 'password');
		
		try {
			if (! $token = JWTAuth::attempt($credentials)) {
				return response()->json(['msg' => 'Invalid Credential'], 401);
			}
		} catch (JWTException $e) {
			return response()->json(['msg'=>'unable to create token'], 500);
		}
		
		return response()->json(['token' => $token]);
	}
}
