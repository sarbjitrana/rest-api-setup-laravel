<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User; // Import the User model here
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{

    public function signup(Request $request)
{
    
    try {
        $validatedData = $request->validate([
            'name' => 'required|string',
            'email' => 'required|email|unique:users',
            'password' => 'required|string|min:8',
        ]);
    } catch (\Illuminate\Validation\ValidationException $e) {
        return response()->json(['errors' => $e->errors()], 422);
    }

    $user = User::create([
        'name' => $validatedData['name'],
        'email' => $validatedData['email'],
        'password' => bcrypt($validatedData['password']),
    ]);

    return response()->json(['message' => 'User created successfully'], 201);
}

public function login(Request $request)
{
    $credentials = $request->only('email', 'password');

    if (Auth::attempt($credentials)) {
        $user = Auth::user();
        $token = $user->createToken('AccessToken')->accessToken;
        return response()->json(['token' => $token]);
    } else {
        return response()->json(['message' => 'Unauthorized'], 401);
    }
}

public function logout(Request $request)
{
    $request->user()->token()->revoke();
    return response()->json(['message' => 'Logout successful']);
}

public function resetPassword(Request $request)
{
    try {
    $request->validate([
        'email' => 'required|email|exists:users,email',
        'password' => 'required|string|min:8|confirmed',
    ]);
} catch (\Illuminate\Validation\ValidationException $e) {
    return response()->json(['errors' => $e->errors()], 422);
}

    $user = User::where('email', $request->email)->first();
    $user->password = bcrypt($request->password);
    $user->save();

    return response()->json(['message' => 'Password reset successful']);
}

public function refreshToken(Request $request)
{
    $request->user()->token()->delete();
    $token = $request->user()->createToken('AccessToken')->accessToken;
    return response()->json(['token' => $token]);
}


}
