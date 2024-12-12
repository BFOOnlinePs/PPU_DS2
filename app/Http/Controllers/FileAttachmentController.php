<?php

namespace App\Http\Controllers;

use App\Models\FileAttachmentModel;
use Illuminate\Http\Request;

class FileAttachmentController extends Controller
{
    public function create(Request $request){
        $data = new FileAttachmentModel();
        $data->table_name = $request->table_name;
        $data->table_name_id = $request->table_name_id;
        $data->note = $request->note;
        $data->added_by = auth()->user()->u_id;
        if ($request->hasFile('file')) {
            $file = $request->file('file');
            $extension = $file->getClientOriginalExtension();
            $filename = time() . '.' . $extension;
            $file->storeAs('files', $filename, 'public');
            $data->file = $filename;
        }
        if ($data->save()){
            return redirect()->back();
        }
    }

    public function file_attachment_list_ajax(Request $request){
        $data = FileAttachmentModel::where('table_name',$request->table_name)->where('table_name_id',$request->table_name_id)->get();
        return response()->json([
            'success' => 'true',
            'view' => view($request->view,['data'=>$data])->render(),
        ]);
    }
}
