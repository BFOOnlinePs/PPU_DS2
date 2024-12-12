<?php

namespace App\Http\Controllers\apisControllers\sharedFunctions\announcements;

use App\Http\Controllers\Controller;
use App\Models\AnnouncementModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class AnnouncementsController extends Controller
{
    // active and the user belongs to the target group
    public function getAllActiveAnnouncements(Request $request)
    {
        // return auth()->user()->u_role_id;
        $per_page = $request->input('per_page', 5);
        $announcements = AnnouncementModel::where('a_status', 1)

            ->whereJsonContains('a_target_group', (string)auth()->user()->u_role_id)
            ->orderBy('created_at', 'desc')
            ->paginate($per_page);

        return response()->json([
            'pagination' => [
                'current_page' => $announcements->currentPage(),
                'last_page' => $announcements->lastPage(),
                'per_page' => $announcements->perPage(),
                'total_items' => $announcements->total(),
            ],
            'announcements' => $announcements->items(),
        ]);
    }

    public function getAllAnnouncements()
    {
        $announcements = AnnouncementModel::orderBy('created_at', 'desc')
            ->with('addedBy:u_id,name')
            ->paginate(50);

        return response()->json([
            'pagination' => [
                'current_page' => $announcements->currentPage(),
                'last_page' => $announcements->lastPage(),
                'per_page' => $announcements->perPage(),
                'total_items' => $announcements->total(),
            ],
            'announcements' => $announcements->items(),
        ]);
    }


    public function getUserAnnouncements()
    {
        $announcements = AnnouncementModel::where('a_added_by', auth()->user()->u_id)
            ->orderBy('created_at', 'desc')
            ->paginate(6);

        return response()->json([
            'pagination' => [
                'current_page' => $announcements->currentPage(),
                'last_page' => $announcements->lastPage(),
                'per_page' => $announcements->perPage(),
                'total_items' => $announcements->total(),
            ],
            'announcements' => $announcements->items(),
        ]);
    }

    public function addNewAnnouncement(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required',
            'content' => 'required',
            // 'target_group' => 'required',
            'image' => 'image|mimes:jpg,jpeg,png,svg',
        ], [
            'title.required' => trans('messages.announcement_title_required'),
            'content.required' => trans('messages.announcement_content_required'),
            // 'target_group.required' => trans('messages.announcement_target_group_required'),
            'image.image' => trans('messages.announcement_image_image'),
            'image.mimes' => trans('messages.announcement_image_mimes'),
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'errors' => $validator->errors()->first(),
            ], 400);
        }

        $announcement = new AnnouncementModel();

        $announcement->a_title = $request->input('title');
        $announcement->a_content = $request->input('content');
        $announcement->a_added_by = auth()->user()->u_id;
        $announcement->a_target_group = $request->input('target_group');
        $announcement->a_status = 0; // un active

        if ($request->hasFile('image')) {
            // return $request->file('image');
            $file = $request->file('image');
            $extension = $file->getClientOriginalExtension();
            $fileName = time() . '_' . uniqid() . '.' . $extension;
            $folderPath = 'uploads/announcements';
            $request->file('image')->storeAs($folderPath, $fileName, 'public');

            $announcement->a_image = $fileName;
        }

        if ($announcement->save()) {
            return response()->json([
                'status' => true,
                'message' => trans('messages.announcement_added'),
                'announcement' => $announcement
            ]);
        } else {
            return response()->json([
                'status' => false,
                'message' => trans('messages.announcement_not_added'),
            ], 500);
        }
    }

    public function changeAnnouncementStatus(Request $request, $announcement_id)
    {

        $validator = Validator::make($request->all(), [
            'status' => 'required|in:0,1',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => $validator->errors()->first(),
            ], 400);
        }

        $announcement = AnnouncementModel::where('a_id', $announcement_id)->first();
        // return $announcement;
        if (!$announcement) {
            return response()->json([
                'status' => false,
                'message' => trans('messages.announcement_not_exists'),
            ], 404);
        }

        $announcement->a_status = $request->input('status');

        if ($announcement->save()) {
            return response()->json([
                'status' => true,
                'message' => trans('messages.announcement_status_updated'),
                'announcement' => $announcement
            ], 200);
        }
    }

    public function editAnnouncement(Request $request, $announcement_id)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required',
            'content' => 'required',
            'image' => 'image|mimes:jpg,jpeg,png,svg',
            // 'target_group' => 'required',
            'is_image_deleted' => 'in:true,false'
        ], [
            'title.required' => trans('messages.announcement_title_required'),
            'content.required' => trans('messages.announcement_content_required'),
            'image.image' => trans('messages.announcement_image_image'),
            'image.mimes' => trans('messages.announcement_image_mimes'),
            // 'target_group.required' => trans('messages.announcement_target_group_required'),

            // 'is_image_deleted.boolean' => ''
        ]);


        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => $validator->errors()->first(),
            ], 400);
        }

        $announcement = AnnouncementModel::where('a_id', $announcement_id)->first();

        if (!$announcement) {
            return response()->json([
                'status' => false,
                'message' => trans('messages.announcement_not_exists'),
            ], 404);
        }


        // Update the title, content, and target group
        $announcement->a_title = $request->input('title');
        $announcement->a_content = $request->input('content');
        $announcement->a_target_group = $request->input('target_group');


        if ($request->input('is_image_deleted')) {
            $announcement->a_image = null;
        }

        // image : the new image     (if changed)
        // hasFile('image') = false  (if nothing happened)
        if ($request->hasFile('image')) {
            // return $request->file('image');
            $file = $request->file('image');
            $extension = $file->getClientOriginalExtension();
            $fileName = time() . '_' . uniqid() . '.' . $extension;
            $folderPath = 'uploads/announcements';
            $request->file('image')->storeAs($folderPath, $fileName, 'public');

            $announcement->a_image = $fileName;
        }

        if ($announcement->save()) {
            return response()->json([
                'status' => true,
                'message' => trans('messages.announcement_updated'),
                'announcement' => $announcement
            ], 200);
        }
    }
}
