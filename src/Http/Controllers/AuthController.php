<?php

namespace Bigmom\Poll\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\View;

class AuthController extends Controller
{
    public function getLogin()
    {
        return View::exists('vendor.bigmom.poll.auth.login')
            ? view('vendor.bigmom.poll.auth.login')
            : view('poll::auth.login');
    }

    public function postLogin(Request $request)
    {
        if (method_exists(Auth::guard('poll'), 'attempt')) {
            \Auth::guard('poll')->attempt($request->only(['email', 'username', 'password']), true);
        } else {
            return response('attempt is not a method.');
        }

        return Auth::guard('poll')->user()
            ? redirect()
                ->route('poll.question.getIndex')
            : redirect()
                ->back()
                ->withErrors('Invalid credentials.');
    }

    public function postLogout(Request $request)
    {
        if (Auth::guard('poll')->check()) {
            if (method_exists(Auth::guard('poll'), 'logout')) {
                Auth::guard('poll')->logout();
                return redirect()
                    ->route('poll.getLogin');
            } else {
                return response('logout is not a method.');
            }
        } else {
            abort(401);
        }
    }
}
