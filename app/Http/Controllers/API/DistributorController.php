<?php

namespace App\Http\Controllers\API;

use App\Models\Region;
use App\Models\Distributor;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;

class DistributorController extends Controller
{
    public function getAll()
    {
        $data = Distributor::get();

        return response()->json(
            [
                'meta' => [
                    'code' => 200,
                    'status' => 'success',
                    'message' => 'Show All Distributors',
                ],
                'data' => $data,
            ]
        );
    }

    public function addDistributor(Request $request)
    {
        $validate = Validator::make($request->all(), [
            'name' => 'required|max:255|unique:distributors,name',
            'lat' => 'required',
            'long' => 'required',
            'address' => 'required'
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
            $data = Distributor::create([
                'name' => $request->name,
                'lat' => $request->lat,
                'long' => $request->long,
                'address' => $request->address
            ]);

            return response()->json(
                [
                    'meta' => [
                        'code' => 200,
                        'status' => 'success',
                        'message' => 'Distributor Created Successfully',
                    ],
                    'data' =>  $data
                ]
            );
        }
    }

    public function deleteDistributor($id)
    {
        Distributor::where('id', $id)
            ->first()
            ->delete();

        return response()->json(
            [
                'meta' => [
                    'code' => 200,
                    'status' => 'success',
                    'message' => 'Distributor Success Deleted!',
                ],
                'data' => [
                    'message' => 'Distributor Success Deleted!'
                ],
            ]
        );
    }
}
