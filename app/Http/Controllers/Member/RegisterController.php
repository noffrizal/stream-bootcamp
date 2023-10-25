<?php

namespace App\Http\Controllers\Member;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;

class RegisterController extends Controller
{
    public function index()
    {
        return view('member.register');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'email' => 'required|email',
            'phone_number' => 'required|integer',
            'password' => 'required|min:6'
        ]);

        $data = $request->except('_token');


        // cek email
        $isEmailExist = User::where('email', $request->email)->exists();
        if ($isEmailExist) {
            return back()->withErrors(['email' => 'Email already Exist'])->withInput();
        }

        // hash password
        $data['password'] = Hash::make($request->password);
        $data['role'] = 'member';

        User::create($data);

        return redirect()->route('member.login');

    }
}
