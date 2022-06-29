<?php

namespace App\Http\Controllers\API;

use Exception;
use App\Models\User;
use App\Models\UserProfile;
use Illuminate\Http\Request;
use App\Helpers\ResponseFormatter;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        try {
            $request->validate([
                'email' => 'email|required',
                'password' => 'required'
            ]);

            $credentials = request(['email','password']);
            if(!Auth::attempt($credentials)) {
                return ResponseFormatter::error([
                    'message' => 'Unauthorized'
                ], 'Authentication Failed', 500);
            }

            $user = User::where('email', $request->email)->first();
            if(!Hash::check($request->password, $user->password, [])){
                throw new \Exception('Invalid Credentials');
            }

            $tokenResult = $user->createToken('authToken')->plainTextToken;

            return ResponseFormatter::success([
                'access_token' => $tokenResult,
                'token_type' => 'Bearer',
                'data' => $user
            ], 'Authenticated');
        } catch (Exception $error) {
            return ResponseFormatter::error([
                'message' => 'Something went wrong',
                'error' => $error
            ], 'Authentication Failed', 500);
        }
    }

    public function register(Request $request)
    {
        $validate = Validator::make($request->all(),
            [
            'full_name' => 'required|max:255',
            'nick_name' => 'required|max:10',
            'id_card_number' => 'required|unique:user_profiles,id_card_number',
            'region_id' => 'required',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:8'
            ]
        );

        if($validate->fails()){
            return response()->json(
                [
                    'meta' => [
                        'status' => 'error',
                        'message' => 'Validation Error'
                    ],
                    'data' => [
                        'validation_errors' => $validate->errors()
                    ]
                ]
            );
        } else {
            $check_user_id = User::count();

            if ($check_user_id == 0) {
                $user_id = 'USR' . date('my') . '0001';
            } else {
                $id = $check_user_id + 1;
                if ($id < 10) {
                    $user_id = 'USR' . date('my') . '000' . $id;
                } elseif ($id >= 10 && $id <= 99) {
                    $user_id = 'USR' . date('my') . '00' . $id;
                } elseif ($id >= 100 && $id <= 999) {
                    $user_id = 'USR' . date('my') . '0' . $id;
                } elseif ($id >= 1000 && $id <= 9999) {
                    $user_id = 'USR' . date('my') . $id;
                }
            }

            $check_profile_id = UserProfile::count();

            if ($check_profile_id == 0) {
                $profile_id = 'USP' . date('my') . '0001';
            } else {
                $id = $check_profile_id + 1;
                if ($id < 10) {
                    $profile_id = 'USP' . date('my') . '000' . $id;
                } elseif ($id >= 10 && $id <= 99) {
                    $profile_id = 'USP' . date('my') . '00' . $id;
                } elseif ($id >= 100 && $id <= 999) {
                    $profile_id = 'USP' . date('my') . '0' . $id;
                } elseif ($id >= 1000 && $id <= 9999) {
                    $profile_id = 'USP' . date('my') . $id;
                }
            }

            $user = User::create([
                'id' => $user_id,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'role' => 'sales'
            ]);

            $user_profile = UserProfile::create([
                'id' => $profile_id,
                'user_id' => $user_id,
                'region_id' => $request->region_id,
                'id_card_number' => $request->id_card_number,
                'full_name' => $request->full_name,
                'nick_name' => $request->nick_name,
            ]);

            $response = [
                'email' => $user->email,
                'id_card_number' => $user_profile->id_card_number,
                'full_name' => $user_profile->full_name,
                'nick_name' => $user_profile->nick_name,
                'region' => $user_profile->region->name
            ];

            return response()->json(
                [
                    'meta' => [
                        'code' => 200,
                        'status' => 'success',
                        'message' => 'Account ' . $user_profile->full_name . ' Success Create!',
                    ],
                    'data' => $response,
                ]
            );
        }
    }

    public function getUser($id)
    {
        $data = User::with(['profile'])->where('id', $id)
            ->first();

        $response = ([
            'full_name' => $data->profile->full_name,
            'nick_name' => $data->profile->nick_name,
            'id_card_number' => $data->profile->id_card_number,
            'email' => $data->email,
            'region' => $data->profile->region->name,
            'role' => $data->role,
        ]);

        return ResponseFormatter::success($response, 'Data Profile User');
    }

    public function logout(Request $request)
    {
        $token = $request->user()->currentAccessToken()->delete();

        return ResponseFormatter::success($token, 'Token Revoked');
    }
}
