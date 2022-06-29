<?php

namespace App\Http\Controllers\API;

use App\Models\Outlet;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;

class OutletController extends Controller
{
    public function getAll()
    {
        $data = Outlet::get();

        return response()->json(
            [
                'meta' => [
                    'code' => 200,
                    'status' => 'success',
                    'message' => 'Show All Outlets',
                ],
                'data' =>  $data,
            ]
        );
    }

    public function addOutlet(Request $request)
    {
        $validate = Validator::make($request->all(), [
            'name' => 'required|max:255',
            'phone' => 'required',
            'photo' => 'required',
            'lat' => 'required',
            'long' => 'required',
            'address' => 'required',
        ]);

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
            $url = env('APP_URL');
            $dir = 'outlet-photo/';

            if ($request->file('photo')) {
                $file = $request->file('photo');
                $extension = $file->getClientOriginalExtension();
                $filename = Str::random(40) . '.' . $extension;
                $data['photo'] = $url.$dir.$filename;
                $file->move('outlet-photo', $filename);
            }

            $data = Outlet::create([
                'name'      => $request->name,
                'phone'     => $request->phone,
                'photo'     => $data['photo'],
                'lat'       => $request->lat,
                'long'      => $request->long,
                'address'   => $request->address,
            ]);

            return response()->json(
                [
                    'meta' => [
                        'code' => 200,
                        'status' => 'success',
                        'message' => 'Document Created Successfully',
                    ],
                    'data' =>  $data
                ]
            );
        }
    }

    public function deleteOutlet($id)
    {
        Outlet::where('id', $id)
            ->first()
            ->delete();

        return response()->json(
            [
                'meta' => [
                    'code' => 200,
                    'status' => 'success',
                    'message' => 'Outlet Success Deleted!',
                ],
                'data' => [
                    'message' => 'Outlet Success Deleted!'
                ],
            ]
        );
    }
}
