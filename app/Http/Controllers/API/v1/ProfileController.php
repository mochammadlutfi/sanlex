<?php

namespace App\Http\Controllers\API\v1;

use App\Http\Controllers\Controller;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Crypt;
use App\Models\Customer;

class ProfileController extends Controller
{
    // public function __construct()
    // {
    //     $this->middleware('auth:api');
    // }

    /**
     * Display the user's profile form.
     */
    public function index(Request $request)
    {
        // dd('auth:sanctum');
        // return response()->json('sadas');
        $user = Customer::with(['branch'])->where('id', auth()->user()->id)->first();
        
        $data = [
            'id' => $user->id,
            'name' => $user->name,
            'email' => $user->email,
            'phone' => $user->phone,
            'branch' => $user->branch,
        ];
        
        return response()->json([
            'success' => true,
            'message' => 'Data Berhasil Ditemukan!',
            'result' => $data
        ], 200);
    }

    /**
     * Update the user's profile information.
     */
    public function update(Request $request): JsonResponse
    {

    }


    public function device(Request $request): JsonResponse
    {
        $user = $request->user();

        $devices = $user->tokens()
            ->select('id', 'name', 'ip', 'last_used_at')
            ->orderBy('last_used_at', 'DESC')
            ->get();

        $currentToken = $user->currentAccessToken();

        foreach ($devices as $device) {
            $device->hash = Crypt::encryptString($device->id);

            if ($currentToken->id === $device->id) {
                $device->is_current = true;
            }

            unset($device->id);
        }
        
        return response()->json($devices);
    }

}
