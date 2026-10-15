<?php

namespace App\Http\Controllers;

use App\Models\Auth;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    public function loginView()
    {
        return view('auth.login');
    }

    public function loginPost(Request $request)
    {
        $users = collect([
            [
                'email' => 'alice.002@ski.sch.id',
                'password' => 'password'
            ],
            [
                'email' => 'alice.111@ski.sch.id',
                'password' => 'password123'
            ]
        ]);

        $user = $users->where('email', '=', $request->email)
            ->where('password', '=', $request->password);


        // Handle
        // foreach ($users as $user) {
        //     if ($request->email === $user['email'] && $request->password === $user['password']) {
        //         return redirect()->route('products.index');
        //     }
        //     continue;

        // }

        if ($user->count() < 1) {
            return back()->withErrors(['email' => 'salah woi']);
        }

        return redirect()->route('products.index');
    }

    public function registerView()
    {
        return view('auth.register');
    }

    public function registerPost(Request $request)
    {
        $users = [
            [
                'email' => 'vido@ski.sch.id',
                'password' => 'password',

            ],
            [
                'email' => 'charles@ski.sch.id',
                'password' => 'password',

            ]
        ];

        $request->validate(([
            'email' => ['required', 'string', 'email'],
            'password' => ['required', 'string', 'min:8']
        ]));

        $users[] = [
            'email' => $request->email,
            'password' => $request->password,
        ];

        print_r($users);
        return;

    }
}
