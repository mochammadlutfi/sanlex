<?php

namespace App\Http\Controllers\Panel\Auth;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Auth;
use Session;
use Illuminate\Support\Facades\Validator;
use Illuminate\Foundation\Auth\ThrottlesLogins;
use Inertia\Inertia;
use Route;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating admin users for the application and
    | redirecting them to your admin dashboard.
    |
    */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    /**
     * Show the login form.
     *
     * @return \Illuminate\Http\Response
     */
    public function showLoginForm()
    {
        return Inertia::render('Auth/Login', [
            'canResetPassword' => Route::has('password.request'),
            'status' => session('status'),
        ]);
    }

    /**
     * Login the admin.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function login(Request $request)
    {
        $input = $request->all();

        $fieldType = filter_var($request->email, FILTER_VALIDATE_EMAIL) ? 'email' : 'username';
        $request->merge([
            $fieldType => $request->input('email')
        ]);

        
        $rules = [
            'password' => 'required|string'
        ];

        $pesan = [
            'password.required' => 'Password Wajib Diisi!',
        ];

        if($fieldType == 'email'){
            $rules['email'] = 'required|exists:users,email';

            $pesan['email.required'] = 'Alamat Email Wajib Diisi!';
            $pesan['email.exists'] = 'Alamat Email Belum Terdaftar!';
        }else{
            $rules['username'] = 'required|exists:users,username|string';

            $pesan['username.required'] = 'Username Wajib Diisi!';
            $pesan['username.exists'] = 'Username Belum Terdaftar!';
        }


        $validator = Validator::make($request->all(), $rules, $pesan);
        if ($validator->fails()){
            
            return back()->withErrors($validator->errors());
        }else{
            if(auth()->guard('web')->attempt(array($fieldType => $input['email'], 'password' => $input['password']), true))
            {
                return redirect()->route('dashboard');
            }else{
                $gagal['password'] = array('Password salah!');

                return back()->withErrors($gagal);
            }
        }
    }

    /**
     * Logout the admin.
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function logout()
    {
        Auth::guard('web')->logout();
        return redirect()->route('login');
    }


    /**
     * Redirect back after a failed login.
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    private function loginFailed(){
        return redirect()
            ->back()
            ->withInput()
            ->with('error','Login failed, please try again!');
    }

}

