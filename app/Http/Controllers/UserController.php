<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request
     * @return Response
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
            'message' => 'User successfully registered',
            'body' => $user
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param Request $request
     * @return Response
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
     * @param Request $request
     * @param int $id
     * @return Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Request $request
     * @return Response
     */
    public function delete(Request $request)
    {
        $request->validate(['id' => 'required|integer']);
        $data = User::where('id', $request->id)->delete();
        return Response([
            'error' => false,
            'message' => 'User deleted',
            'body' => $data
        ]);
    }
}
