<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use RealRashid\SweetAlert\Facades\Alert;


class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = RouteServiceProvider::HOME;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }
    public function login(Request $request)
{
    $input = $request->all();

    $this->validate($request, [
        'email' => 'required|email',
        'password' => 'required',
    ]);

    if (auth()->attempt(['email' => $input['email'], 'password' => $input['password']])) {
        $request->session()->regenerate(); // regenerate session biar aman

        // Redirect ke halaman yang sebelumnya diminta (jika ada), atau ke default dashboard
        return redirect()->intended($this->defaultRedirect());
    } else {
        return back()->with(['error' => 'Email-Address And Password Are Wrong!']);
    }
}


    protected function defaultRedirect()
    {
        $role = auth()->user()->role;

        return match($role) {
            'Admin', 'Pemilik' => '/welcomeTKB',
            default => '/welcomeTKB',
        };
    }

    protected function sendFailedLoginResponse(Request $request)
    {
        $errors = [$this->username() => trans('auth.failed')];
        Alert::error('error');
        return redirect()->route('login')
            ->withErrors($errors)
            ->withInput($request->only($this->username()))
            ->with('error', 'Login failed');
    }
    
}
