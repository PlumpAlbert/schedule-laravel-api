<?php

namespace App\Http\Controllers;

use App\Models\Group;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'login' => ['required', 'unique:users,login'],
            'password' => ['required', 'confirmed'],
            'password_confirmation' => 'required',
            'group' => ['required', 'integer']
        ]);

        $user = new User;
        $user->name = $request->name;
        $user->login = $request->login;
        $user->group_id = $request->group;
        $user->type = 0;
        $user->password = Hash::make($request->password);
        $user->save();
        $user->refresh();
        return Response([
            'error' => false,
            'message' => 'User successfuly registered',
            'body' => $user
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'login' => 'required',
            'password' => 'required'
        ]);

        if (Auth::attempt($credentials, true)) {
            $request->session()->regenerate();
            $user = User::with('group')->where('login', $request->login)->first();
            return Response([
                'error' => false,
                'message' => '',
                'body' => $user,
            ]);
        } else {
            throw ValidationException::withMessages([
                'login' => 'Wrong username or password'
            ]);
        }
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
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
