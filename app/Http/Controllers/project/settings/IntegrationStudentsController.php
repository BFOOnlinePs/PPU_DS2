<?php

namespace App\Http\Controllers\project\settings;

use App\Http\Controllers\Controller;
use App\Imports\StudentImport;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class IntegrationStudentsController extends Controller
{
    public function index()
    {
        return view('project.admin.settings.integration_students.index');
    }

    public function uploadFileExcel(Request $request)
    {
        if ($request->hasFile('input-file')) {
            $file = $request->file('input-file');
            $extension = $file->getClientOriginalExtension();
            $filename = time() . '.' . $extension;
            $file->storeAs('excel', $filename, 'public'); // Save file temporarily in excel folder

            // Allowed Excel extensions
            $allowedExtensions = ['xlsx', 'xls'];
            if (in_array($extension, $allowedExtensions)) {
                // Read the first row of the Excel file
                $path = public_path('storage/excel/' . $filename);
                $firstRow = Excel::toCollection([], $path)->first()->first();
                return response()->json([
                    'status' => 1,
                    'headers' => $firstRow,
                    'name_file_hidden' => $filename
                ]);
            } else {
                return response()->json([
                    'status' => 0
                ]);
            }
        }
    }

    public function submitForm(Request $request)
    {
        if ($request->hasFile('input-file')) {
            $path = public_path('storage/excel/' . $request->input('name_file_hidden'));
            $decodedMap = explode(',', $request->input('data'));
            $result = [];
            for ($i = 0; $i < count($decodedMap) - 1; $i += 2) {
                $key = $decodedMap[$i];
                $value = $decodedMap[$i + 1];
                $result[$key] = $value;
            }
            $user_object = new StudentImport($result);
            Excel::import($user_object, $path);
            return response()->json([
                'cnt' => $user_object->getCount(),
                'user_object_array' => $user_object->getArrayStudentsNames()
            ]);
        }
    }
    
}
