<?php

namespace App\Http\Controllers;

use App\Models\Group;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;
use const App\Models\USER_ADMIN;
use const App\Models\USER_TEACHER;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param Request $request
     * @return Response
     */
    public function index(Request $request)
    {
        $request->validate([
            'type' => [
                'required',
                Rule::in(['teacher', 'admin'])
            ]
        ]);
        if ($request->type === 'teacher') {
            return Response([
                'error' => false,
                'message' => '',
                'body' => User::with('group')->where('type', USER_TEACHER)->get()
            ]);
        }
        if ($request->type === 'admin') {
            return Response([
                'error' => false,
                'message' => '',
                'body' => User::with('group')->where('type', USER_ADMIN)->get()
            ]);
        }
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
            'group' => ['integer', 'nullable']
        ]);

        $user = new User;
        $user->name = $request->name;
        $user->login = $request->login;
        $user->group_id = $request->group;
        $user->type = 0;
        $user->password = Hash::make($request->password);
        $user->save();
        $user->refresh();
        $user->group = Group::where('id', $user->group_id)->first();
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
     * @throws ValidationException
     */
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'login' => 'required',
            'password' => 'required'
        ]);

        if (Auth::attempt($credentials, true)) {
            $request->session()->regenerate();
            $user = User::with('group')->where('login', $request->login)->firstOrFail();
            $abilities = ['user'];
            if ($user->type === USER_ADMIN) {
                $abilities[] = 'admin';
            }
            return Response([
                'error' => false,
                'message' => '',
                'body' => [
                    'user' => $user,
                    'access_token' => $user->createToken($user->login, $abilities)->plainTextToken
                ]
            ]);
        } else {
            throw ValidationException::withMessages([
                'login' => 'Wrong username or password'
            ]);
        }
    }

    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();
        return Response([
            'error' => false,
            'message' => 'User successfully logout',
            'body' => ['success' => true]
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @return Response
     */
    public function update(Request $request)
    {
        $request->validate(['id' => ['required', 'integer', 'exists:' . User::class]]);
        $user = User::with('group')->findOrFail($request->id);
        $changed = false;
        if ($request->has('group')) {
            if (!Group::find($request->group)) {
                return Response([
                    'error' => true,
                    'message' => '"Teacher with id "' . $request->group . '" does not exist"'
                ]);
            }
            $user->group_id = $request->group;
            $changed = true;
        }
        if ($request->has('name')) {
            $user->name = $request->name;
            $changed = true;
        }
        if ($request->has('type')) {
            $user->type = $request->type;
            $changed = true;
        }
        if ($changed) {
            $user->save();
        }
        return Response([
            'error' => false,
            'message' => '',
            'body' => $user
        ]);
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
        $rowsAffected = User::where('id', $request->id)->delete();
        return Response([
            'error' => false,
            'message' => '',
            'body' => [
                'success' => $rowsAffected === 1
            ]
        ]);
    }
}
