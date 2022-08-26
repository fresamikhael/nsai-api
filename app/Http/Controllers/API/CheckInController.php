<?php

namespace App\Http\Controllers\API;

use App\Models\CheckIn;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;

class CheckInController extends Controller
{
    public function getHistory($id)
    {
        $data = CheckIn::where('user_id', $id)->get();

        return response()->json(
            [
                'meta' => [
                    'code' => 200,
                    'status' => 'success',
                    'message' => 'Show Absent from User',
                ],
                'data' => $data,
            ]
        );
    }

    public function getHistoryId($id)
    {
        $data = CheckIn::where('id', $id)->first();

        return response()->json(
            [
                'meta' => [
                    'code' => 200,
                    'status' => 'success',
                    'message' => 'Show Absent from User',
                ],
                'data' => $data,
            ]
        );
    }

    public function checkIn(Request $request)
    {
        $validate = Validator::make(
            $request->all(),
            [
                'clock_in' => [
                    'required'
                ]
            ]
        );

        if ($validate->fails()) {
            return response()->json(
                [
                    'meta' => [
                        'status' => 'error',
                        'message' => 'Validation Error',
                    ],
                    'data' => [
                        'validation_errors' => $validate->errors()
                    ],
                ]
            );
        } else {
            $data = CheckIn::create([
                'user_id' => auth()->user()->id,
                'clock_in' => $request->clock_in,
            ]);

            return response()->json(
                [
                    'meta' => [
                        'code' => 200,
                        'status' => 'success',
                        'message' => 'Success Clock In!',
                    ],
                    'data' => $data
                ]
            );
        }
    }

    public function checkOut(Request $request, $id)
    {
        $validate = Validator::make(
            $request->all(),
            [
                'clock_out' => [
                    'required'
                ]
            ]
        );

        if ($validate->fails()) {
            return response()->json(
                [
                    'meta' => [
                        'status' => 'error',
                        'message' => 'Validation Error',
                    ],
                    'data' => [
                        'validation_errors' => $validate->errors()
                    ],
                ]
            );
        } else {
            $data = CheckIn::where('id', $id)->first();

            $data['clock_out'] = $request->clock_out;

            CheckIn::where('id', $id)
            ->update([
                'clock_out' => $data['clock_out'],
            ]);

            return response()->json(
                [
                    'meta' => [
                        'code' => 200,
                        'status' => 'success',
                        'message' => 'Success Clock Out!',
                    ],
                    'data' => $data
                ]
            );
        }
    }
}
