<?php

namespace App\Http\Controllers\apisControllers\sharedFunctions\documents;

use App\Http\Controllers\Controller;
use App\Models\MeAttachmentModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class DocumentsController extends Controller
{
    public function addNewDocumentAttachment(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'parent_attachment_id' => 'required|int', // parent id (-1 if it is the parent)
            'description' => 'nullable',
            'notes' => 'nullable',
            'file' => 'required|file|mimes:jpg,jpeg,png,svg,pdf,doc,docx,xls,xlsx,ppt,pptx,odt,ods,odp,csv,xlsx',

        ], [
            'parent_attachment_id.required' => trans('messages.parent_attachment_id_required'),
            'parent_attachment_id.int' => trans('messages.parent_attachment_id_int'),
            'file.required' => trans('messages.file_required'),
            'file.file' => trans('messages.file_file'),
            'file.mimes' => trans('messages.file_mimes'),
        ]);


        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => $validator->errors()->first(),
            ]);
        }

        $document = new MeAttachmentModel();
        $document->mea_user_id = auth()->user()->u_id;
        $document->mea_description = $request->input('description');
        $document->mea_notes = $request->input('notes');

        if ($request->hasFile('file')) {
            $file = $request->file('file');
            $extension = $file->getClientOriginalExtension();
            $fileName = time() . '_' . uniqid() . '.' . $extension;
            $folderPath = 'files';
            $request->file('file')->storeAs($folderPath, $fileName, 'public');

            $document->mea_file = $fileName;
        }

        $parent_id = $request->input('parent_attachment_id');

        if ($parent_id == -1) {
            // parent
            $document->mea_attachment_id = $parent_id; // -1
        } else {
            $document->mea_attachment_id = $parent_id;

            $parent_document = MeAttachmentModel::where('mea_id', $parent_id)->first();

            if ($parent_document->mea_attachment_id != -1) {
                $parent_parent = MeAttachmentModel::where('mea_id', $parent_document->mea_attachment_id)->first();
                // delete the file
                // if (Storage::exists($folderPath . '/' . $parent_parent->mea_file)) {
                Storage::disk('public')->delete($folderPath . '/' . $parent_parent->mea_file);
                // return $folderPath . '/' . $parent_parent->mea_file;
                // }

                // delete parent parent and its file
                // $parent_parent->delete();


                // make the new one the parent
                $parent_document->mea_attachment_id = -1;
                $parent_document->save();
            }
        }




        if ($document->save()) {
            return response()->json([
                'status' => true,
                'message' => trans('messages.document_added'),
            ]);
        } else {
            return response()->json([
                'status' => false,
                'message' => trans('messages.document_not_added'),
            ]);
        }
    }

    public function getDocumentsAttachments()
    {
        // Fetch all parent records
        $parents = MeAttachmentModel::where('mea_attachment_id', -1)
            ->with('attachmentOwner:u_id,name')
            ->orderBy('created_at', 'desc')
            ->paginate(6);

        $documentsTree = [];

        foreach ($parents as $parent) {
            $child = MeAttachmentModel::where('mea_attachment_id', $parent->mea_id)
                ->with('attachmentOwner:u_id,name')
                ->first();

            // Only add the child if it exists
            if ($child !== null) {
                $documentsTree[] = [$parent, $child];
            } else {
                $documentsTree[] = [$parent];
            }
        }

        return response()->json([
            'status' => true,
            'pagination' => [
                'current_page' => $parents->currentPage(),
                'last_page' => $parents->lastPage(),
                'per_page' => $parents->perPage(),
                'total_items' => $parents->total(),
            ],
            'documents' => $documentsTree,
        ]);
    }
}
