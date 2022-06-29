<?php

namespace App\Http\Controllers\API;

use App\Models\Document;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;

class DocumentController extends Controller
{
    public function getAll()
    {
        $data = Document::get();

        return response()->json(
            [
                'meta' => [
                    'code' => 200,
                    'status' => 'success',
                    'message' => 'Show All Documents',
                ],
                'data' => $data,
            ]
        );
    }

    public function addDocument(Request $request)
    {
        $validate = Validator::make($request->all(), [
            'name' => 'required|max:255',
            'file' => 'required',
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
            $dir = 'documents/';

            if ($request->file('file')) {
                $file = $request->file('file');
                $extension = $file->getClientOriginalExtension();
                $filename = Str::random(40) . '.' . $extension;
                $data['file'] = $url.$dir.$filename;
                $file->move('documents', $filename);
            }

            $data = Document::create([
                'user_id' => auth()->user()->id,
                'name' => $request->name,
                'file' => $data['file'],
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

    public function deleteDocument($id)
    {
        Document::where('id', $id)
            ->first()
            ->delete();

        return response()->json(
            [
                'meta' => [
                    'code' => 200,
                    'status' => 'success',
                    'message' => 'Document Success Deleted!',
                ],
                'data' => [
                    'message' => 'Document Success Deleted!'
                ],
            ]
        );
    }
}
