<?php

namespace App\Http\Controllers;

use App\Http\Requests\UserRequest;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;


class UserController extends Controller
{
    public function index()
    {
        $user_id = Auth::id();

        $users = User::latest()->get();
        return view('admin.users.index', compact('users'));
    }
    public function create()
    {
        return view('admin.users.create');
    }
    public function store(UserRequest $request)
    {
        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'role' => $request->role,
            'password' => Hash::make($request['password']),
        ]);
        return redirect()->route('admin.users.index');
    }
    public function edit(string $id)
    {
        $user = User::find($id);
        return view('admin.users.edit', compact('user'));
    }

    public function update(UserRequest $request)
    {

        $user = User::findorFail($request->id);

        if (!Hash::check($request->password, $user->password)) {
            return back()->withErrors(['current_password' => 'password is not correct']);
        }
        $user->update([
            'name' => $request->name,
            'email' => $request->email,
            'role' => $request->role,
            'password' => Hash::make($request->input('new_password')),
        ]);


        return redirect()->route('admin.users.index');
    }

    public function delete(User $user)
    {
        if ($user->id === Auth::id()) {
            abort(403, 'You cannot delete yourself!');
        }
        $user->delete();
        return redirect()->route('admin.users.index');
    }
}
