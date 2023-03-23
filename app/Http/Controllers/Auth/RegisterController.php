<?php

namespace App\Http\Controllers\Auth;

use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use App\Notifications\AccountConfirmation;
use App\Http\Requests\Auth\RegisterRequest;
use Symfony\Component\HttpFoundation\Response;

class RegisterController extends Controller
{
    public function register(RegisterRequest $request)
    {
        $user = User::create([
            'name' => $request->name,
            'lastname' => $request->lastname,
            'email' => Str::lower($request->email),
            'password' => Hash::make($request->password),
            'confirmation_token' => Str::random(60),
            'email_verified_at' => null
        ]);

        $user->notify(new AccountConfirmation($user));

        return response()->json([
            'status' => 1,
            'message' => 'Registro exitoso.',
            'account_confirm' => 'Te hemos enviado instrucciones para confirmar tu cuenta por correo electronico'
        ], Response::HTTP_CREATED);
    }

    public function confirm(Request $request, $token)
    {
        $user = User::where('confirmation_token', $token)->firstOrFail();

        $user->update([
            'email_verified_at' => Carbon::now(),
            'confirmation_token' => null,
        ]);

        return response()->json([
            'status' => 1,
            'message' => 'Cuenta confirmada',
        ], Response::HTTP_OK);
    }
}
