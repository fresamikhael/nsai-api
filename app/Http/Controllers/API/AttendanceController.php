<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Absent;
use App\Models\ItemTaken;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class AttendanceController extends Controller
{
    public function getHistory($id)
    {
        $data = Absent::with('item')->where('user_id', $id)->get();

        return response()->json(
            [
                'meta' => [
                    'code' => 200,
                    'status' => 'success',
                    'message' => 'Show Attentance from User',
                ],
                'data' => $data,
            ]
        );
    }

    public function clockIn(Request $request)
    {
        $validate = Validator::make(
            $request->all(),
            [
                'distributor_id' => [
                    'required'
                ],
                'clock_in' => [
                    'required'
                ],
                'address' => [
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
            $data = Absent::create([
                'user_id' => auth()->user()->id,
                'distributor_id' => $request->distributor_id,
                'clock_in' => $request->clock_in,
                'address' => $request->address,
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

    public function postPhoto(Request $request, $id)
    {
        $validate = Validator::make(
            $request->all(),
            [
                'item_photo' => [
                    'required'
                ],
                'distributor_photo' => [
                    'required'
                ],
                'item' => [
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
            $data = Absent::with('item')->where('id', $id)->first();

            $url = env('APP_URL');

            $dir = 'clockIn/';

            if ($request->file('item_photo')) {
                $file = $request->file('item_photo');
                $extension = $file->getClientOriginalExtension();
                $filename = Str::random(40) . '.' . $extension;
                $data['item_photo'] = $url.$dir.$filename;
                $file->move('clockIn', $filename);
            }

            if ($request->file('distributor_photo')) {
                $file = $request->file('distributor_photo');
                $extension = $file->getClientOriginalExtension();
                $filename = Str::random(40) . '.' . $extension;
                $data['distributor_photo'] = $url.$dir.$filename;
                $file->move('clockIn', $filename);
            }

            Absent::where('id', $id)
            ->update([
                'item_photo' => $data['item_photo'],
                'distributor_photo' => $data['distributor_photo'],
            ]);

            foreach ($request->item as $row) {
                ItemTaken::create([
                    'absent_id' => $data->id,
                    'product_id' => $row["product_id"],
                    'item_taken' => $row["item_taken"]
                ]);
            }

            return response()->json(
                [
                    'meta' => [
                        'code' => 200,
                        'status' => 'success',
                        'message' => 'Success Post Photo!',
                    ],
                    'data' => $data
                ]
            );
        }
    }

    public function clockOut(Request $request, $id)
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
            $data = Absent::where('id', $id)->first();

            $data['clock_out'] = $request->clock_out;

            Absent::where('id', $id)
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
