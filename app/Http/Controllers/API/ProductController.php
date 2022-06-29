<?php

namespace App\Http\Controllers\API;

use App\Models\Product;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;

class ProductController extends Controller
{
    public function getProduct($id)
    {
        $data = Product::where('distributor_id', $id)->get();

        return response()->json(
            [
                'meta' => [
                    'code' => 200,
                    'status' => 'success',
                    'message' => 'Show Product from Distributor',
                ],
                'data' => $data,
            ]
        );
    }

    public function addProduct(Request $request)
    {
        $validate = Validator::make($request->all(), [
            'distributor_id' => 'required',
            'name' => 'required|max:255',
            'unit' => 'required',
            'price' => 'required',
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
            $data = Product::create([
                'distributor_id' => $request->distributor_id,
                'name' => $request->name,
                'unit' => $request->unit,
                'price' => $request->price,
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

    public function deleteProduct($id)
    {
        Product::where('id', $id)
            ->first()
            ->delete();

        return response()->json(
            [
                'meta' => [
                    'code' => 200,
                    'status' => 'success',
                    'message' => 'Product Success Deleted!',
                ],
                'data' => [
                    'message' => 'Product Success Deleted!'
                ],
            ]
        );
    }
}
