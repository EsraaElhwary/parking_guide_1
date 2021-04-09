<?php

namespace App\Http\Controllers;



use App\Http\Controllers\Controller;
use JWTAuth;
use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Requests\RegisterAuthRequest;
use Tymon\JWTAuth\Exceptions\JWTException;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class ApiController extends Controller
{

    
    
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            
            
           // 'id' => 'required',
            'first_name' => 'required',
            'last_name' => 'required',
            'phone' => 'required',
            'password' => 'required',
            'is_owner'=>'required',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors());
        }

        User::create([
           // 'id' => $request->get('id'),
            'first_name' => $request->get('first_name'),
            'last_name' => $request->get('last_name'),
            'phone' => $request->get('phone'),
            'password' => bcrypt($request->get('password')),
            'is_owner'=> $request->get('is_owner'),
        ]);

        $user = User::first();
        $token = Auth::fromUser($user);

        return Response()->json(compact('token'));

    }
 
    
    
   
    public function getAuthUser(Request $request)
    {
        $this->validate($request, [
            'token' => 'required'
        ]);
 
        $user = JWTAuth::authenticate($request->token);
 
        return response()->json(['user' => $user]);
    }


   
}
