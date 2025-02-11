<?php

namespace App\Http\Controllers\API\v1;

use Auth;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Support\Facades\Validator;
use Hash;
use Carbon\Carbon;
use App\Models\Customer;

class AuthController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | API Authentication Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating admin users for the application and
    | redirecting them to your admin dashboard.
    |
     */
    // public function __construct()
    // {
    //     $this->middleware('guest')->except('logout');
    // }

    /**
     * Login User.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function login(Request $request)
    {
        $rules = [
            'email' => 'required|exists:customers,email',
            'password' => 'required|string'
        ];

        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()){
            return response()->json([
                'success' => false,
                'message' => 'Field invalid',
                'result' => $validator->errors(),
            ], 422);

        }else{
            $user = Customer::where('email', $request->email)->first();

            if ($user != null) {
                if (Hash::check($request->password, $user->password)) {
                    $data = [
                        'id' => $user->id,
                        'name' => $user->name,
                        'email' => $user->email,
                        'phone' => $user->phone,
                        'branch' => $user->branch,
                        'token' => $user->createToken('auth-sales')->plainTextToken,
                    ];
                    $browser = Browser::parse($request->userAgent());
                    $device = $browser->platformName() . ' / ' . $browser->browserName();
            
                    $sanctumToken = $user->createToken(
                        $device,
                        ['*'],
                        $request->remember ?
                            now()->addMonth() :
                            now()->addDay()
                    );
            
                    $sanctumToken->accessToken->ip = $request->ip();
                    $sanctumToken->accessToken->save();

                    return response()->json([
                        'success' => true,
                        'message' => 'Login berhasil!',
                        'result' => $data
                    ], 200);
    
                } else {
                    return response()->json([
                        'success' => false, 
                        'message' => 'Password Salah!', 
                        'result' => null
                    ], 401);

                }
            } else {
                return response()->json([
                    'success' => false,
                    'message' => 'Akun Tidak Ditemukan!'
                ], 401);
            }
        }
    }

    /**
     * Logout the admin.
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function logout(Request $request)
    {
        auth('salesman')->user()->tokens()->delete();
        return [
            'success' => true,
            'message' => 'Akun Berhasil Keluar!',
        ];
    }
}
