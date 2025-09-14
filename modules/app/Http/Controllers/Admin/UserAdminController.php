<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;

class UserAdminController extends Controller
{
    public function index()
    {
        $users = User::orderByDesc('id')->get();
        return view('admin.users', compact('users'));
    }

    public function toggle(User $user)
    {
        $user->is_active = !$user->is_active;
        $user->save();
        return back();
    }

    public function role(User $user, Request $request)
    {
        $role = $request->string('role')->toString();
        $user->role = in_array($role, ['user','admin']) ? $role : 'user';
        $user->save();
        return back();
    }

    public function quota(User $user, Request $request)
    {
        $q = max(1, (int)$request->input('quota_mb', 100));
        $user->quota_mb = $q;
        $user->save();
        return back();
    }
}
