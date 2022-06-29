<?php

namespace App\Http\Controllers\API;

use App\Models\Region;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;

class RegionController extends Controller
{
    public function getRegion()
    {
        $data = Region::get();

        return response()->json(
            [
                'meta' => [
                    'code' => 200,
                    'status' => 'success',
                    'message' => 'Show All Regions',
                ],
                'data' => [
                    'region' => $data,
                ],
            ]
        );
    }

    public function showRegion($id)
    {
        $data = Region::where('id', $id)
            ->first();

        return response()->json([
            [
                'meta' => [
                    'code' => 200,
                    'status' => 'success',
                    'message' => 'Show Region ' . $data->name,
                ],
                'data' => [
                    'region' => $data
                ],
            ]
        ]);
    }

    public function addRegion(Request $request)
    {
        $validate = Validator::make($request->all(), [
            'name' => 'required|max:255|unique:regions,name'
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
            $dateNow = date('Y-m-d') . ' 00:00:00';
            $check_inventory = Region::select('*')
                ->whereDate('created_at', '>=', $dateNow)
                ->count();

            if ($check_inventory == 0) {
                $id = 'R' . date('my') . '0001';
            } else {
                $item = $check_inventory + 1;
                if ($item < 10) {
                    $id = 'R' . date('my') . '000' . $item;
                } elseif ($item >= 10 && $item <= 99) {
                    $id = 'R' . date('my') . '00' . $item;
                } elseif ($item >= 100 && $item <= 999) {
                    $id = 'R' . date('my') . '0' . $item;
                } elseif ($item >= 1000 && $item <= 9999) {
                    $id = 'R' . date('my') . $item;
                }
            }

            $data = Region::create([
                'id' => $id,
                'name' => $request->name
            ]);

            return response()->json(
                [
                    'meta' => [
                        'code' => 200,
                        'status' => 'success',
                        'message' => 'Region Created Successfully',
                    ],
                    'data' => [
                        'region' => $data
                    ],
                ]
            );
        }
    }

    public function updateRegion(Request $request, $id)
    {
        $region = Region::where('id', $id)
            ->first();

        $request->name == null ? $name = $region->name : $name = $request->name;

        Region::where('id', $id)
            ->update([
                'name' => $name,
            ]);

        $data = Region::where('id', $id)
            ->first();

        return response()->json(
            [
                'meta' => [
                    'code' => 200,
                    'status' => 'success',
                    'message' => 'Region Success Update!',
                ],
                'data' => [
                    'shop' => $data
                ],
            ]
        );
    }

    public function deleteRegion($id)
    {
        Region::where('id', $id)
            ->first()
            ->delete();

        return response()->json(
            [
                'meta' => [
                    'code' => 200,
                    'status' => 'success',
                    'message' => 'Region Success Deleted!',
                ],
                'data' => [
                    'message' => 'Region Success Deleted!'
                ],
            ]
        );
    }
}
