<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Attendee;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    public function login(Request $request) {
        $validator = Validator::make($request->all(), [
            'lastname' => 'required',
            'registration_code' => 'required'
        ]);

        if ($validator->fails()) return response()->json(['message' => 'Invalid login'], 401);
        
        $attende = Attendee::where('lastname', $request->lastname)->where('registration_code', $request->registration_code)->first();
        
        if (!$attende) return response()->json(['message' => 'Invalid login'], 401);

        $res = [
            'firstname' => $attende->firstname,
            'lastname' => $attende->lastname,
            'username' => $attende->username,
            'email' => $attende->email,
            'token' => md5($attende->username)
        ];

        $attende->update(['login_token' => md5($attende->username)]);

        return response()->json($res, 200);
    }
    
    public function logout(Request $request) {
        if (!$request->token) return response()->json(['message' => 'Invalid token'], 401);

        $attende = Attendee::where('login_token', $request->token)->first();
        
        if (!$attende) return response()->json(['message' => 'Invalid token'], 401);

        $attende->update(['login_token' => '']);

        return response()->json(['message' => 'Logout success'], 200);
    }
}