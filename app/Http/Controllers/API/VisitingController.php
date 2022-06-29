<?php

namespace App\Http\Controllers\API;

use App\Models\VisitOutlet;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;

class VisitingController extends Controller
{
    public function getHistory($id)
    {
        $data = VisitOutlet::where('user_id', $id)->get();

        return response()->json(
            [
                'meta' => [
                    'code' => 200,
                    'status' => 'success',
                    'message' => 'Show Visiting from User',
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
                'outlet_id' => [
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
            $data = VisitOutlet::create([
                'user_id' => auth()->user()->id,
                'outlet_id' => $request->outlet_id,
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
                'outlet_photo' => [
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
            $data = VisitOutlet::where('id', $id)->first();

            $url = env('APP_URL');

            $dir = 'visiting/';

            if ($request->file('item_photo')) {
                $file = $request->file('item_photo');
                $extension = $file->getClientOriginalExtension();
                $filename = Str::random(40) . '.' . $extension;
                $data['item_photo'] = $url.$dir.$filename;
                $file->move('visiting', $filename);
            }

            if ($request->file('outlet_photo')) {
                $file = $request->file('outlet_photo');
                $extension = $file->getClientOriginalExtension();
                $filename = Str::random(40) . '.' . $extension;
                $data['outlet_photo'] = $url.$dir.$filename;
                $file->move('visiting', $filename);
            }

            if ($request->file('other_photo')) {
                $file = $request->file('other_photo');
                $extension = $file->getClientOriginalExtension();
                $filename = Str::random(40) . '.' . $extension;
                $data['other_photo'] = $url.$dir.$filename;
                $file->move('visiting', $filename);
            }

            VisitOutlet::where('id', $id)
            ->update([
                'item_photo' => $data['item_photo'],
                'outlet_photo' => $data['outlet_photo'],
                'other_photo' => $data['other_photo'],
            ]);

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
            $data = VisitOutlet::where('id', $id)->first();

            $data['clock_out'] = $request->clock_out;

            VisitOutlet::where('id', $id)
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
