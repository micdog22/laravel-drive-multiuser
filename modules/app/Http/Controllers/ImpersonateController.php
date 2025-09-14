<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use App\Models\User;

class ImpersonateController extends Controller
{
    public function start(User $user)
    {
        if (Auth::user()->cannot('impersonate')) abort(403);
        session(['impersonate_admin' => Auth::id()]);
        Auth::login($user);
        return redirect()->route('dashboard')->with('status', 'Você está visualizando como '.$user->email);
    }

    public function stop()
    {
        $adminId = session('impersonate_admin');
        if ($adminId) {
            Auth::loginUsingId($adminId);
            session()->forget('impersonate_admin');
        }
        return redirect()->route('admin.index');
    }
}
