<?php

namespace App\Http\Controllers;

use App\Character;
use Illuminate\Http\Request;
use JWTAuth;

class CharacterController extends Controller
{
	public function __construct() 
	{
		$this->middleware('jwt.auth', 
			['only' => [
				'store', 
				'update' 
			]
		]);
	}

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
    	$this->validate($request, [
        	'name' => 'required',
        	'descriptions' => 'required',
    		'power' => 'required|numeric'
        ]);
    	
        if (!$user = JWTAuth::parseToken()->authenticate()) {
        	return response()->json(['msg' => 'Invalid Token'], 404); 	
        }
        
        $user_id = $user->id;
        $name = $request->input('name');
        $descriptions = $request->input('descriptions');
        $power = $request->input('power');
        
        $character= new Character([
        	'name' => $name,
        	'descriptions' => $descriptions,
        	'power' => $power
        ]);
        
        if ($character->save()) {
        	$character->users()->attach($user_id);
        	$character->view_character= [
        		'href' => 'api/v1/character/' . $character->id,
        		'method' => 'GET'
        	];
        	$response= [
        		'msg' => 'Character Created',
        		'game' => $character
        	];
        	
        	return response()->json($response, 201);
        }
        
        $response = [
        	'msg' => 'An error occured',
        ];
        
        return response()->json($response, 404);
        
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
    	echo "I am in show: CharacterController";
    }


    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
    	$this->validate($request, [
    		'name' => 'required',
    		'descriptions' => 'required',
   			'power' => 'required|numeric'
    	]);
    	
    	if (!$user = JWTAuth::parseToken()->authenticate()) {
    		return response()->json(['msg' => 'Invalid Token'], 404);
    	}
    	
    	$user_id = $user->id;
    	$name = $request->input('name');
    	$descriptions = $request->input('descriptions');
    	$power = $request->input('power');
    	
    	$character = Character::with('users')->findOrFail($id);
    	
    	if (!$character->users()-where('users.id', $user_id)->first()) {
    		return response()->json([
    			'msg' => 'User not match with charater',
    			401
    		]);
    	}
    	
    	$character->name = $name;
    	$character->descriptions= $descriptions;

    	if ($character->update()) {
    		return response()->json(['msg' => 'Error during Update'], 404);
    	}
    	
    	$character->view_charater = [
    		'href' => 'api/v1/character' + $charater->id,
    		'method' => 'GET'
    	];
    	
    	$response = [
    		'msg' => 'Update Sucessfull',
    		'character' => $character,	
    	];
    	
    	return response()->json($response, 200);
    }
    
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
    	echo "I am in index: CharacterController";
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
    	echo "I am in destroy: CharacterController";
    }
}
