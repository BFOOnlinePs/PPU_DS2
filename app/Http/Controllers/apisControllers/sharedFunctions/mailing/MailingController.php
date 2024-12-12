<?php

namespace App\Http\Controllers\apisControllers\sharedFunctions\mailing;

use App\Helpers\ConversationMessageHelper;
use App\Http\Controllers\Controller;
use App\Mail\UserNotification;
use App\Models\ConversationsModel;
use App\Models\MessageModel;
use App\Models\Registration;
use App\Models\SystemSetting;
use App\Models\User;
use App\Models\UsersConversationsModel;
use App\Services\MessageService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;

class MailingController extends Controller
{
    protected $messageService;


    public function __construct(MessageService $messageService)
    {
        $this->messageService = $messageService;
    }

    // when send first message (to create the conversation)
    public function createNewMailWithMessage(Request $request)
    {
        try {
            DB::beginTransaction();
            $validator = Validator::make($request->all(), [
                'user_ids' => 'required|json', // json
                'message' => 'required',
                'message_file' => 'nullable',
                'conversation_name' => 'nullable',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'status' => false,
                    'message' => $validator->errors()->first()
                ], 422);
            }

            $conversation = new ConversationsModel();
            $conversation->c_name = $request->input('conversation_name');
            $conversation->added_by = Auth::user()->u_id;

            if ($conversation->save()) {

                // $user_ids = [(string) Auth::user()->u_id, (string) $request->input('user_ids')];
                // $user_ids_json = json_encode($user_ids);

                $user_conversation = new UsersConversationsModel();
                $user_conversation->uc_conversation_id = $conversation->c_id;
                $user_conversation->uc_user_id = $request->input('user_ids');
                $user_conversation->save();

                $conversation_id =  $conversation->c_id;
                $message_text =  $request->input('message');
                $file = $request->hasFile('message_file') ? $request->file('message_file') : null;

                $message = $this->messageService->createMessage($conversation_id, $message_text, $file);

                if ($message != null) {
                    $current_user = Auth::user();

                    $this->messageService->markMessageAsSeen(
                        $conversation_id,
                        $message->m_id,
                        $current_user->u_id
                    );

                    // return this data with (same as getUserMailing)
                    $mail = UsersConversationsModel::where('uc_conversation_id', $conversation->c_id)
                        ->where('uc_user_id', '!=', $current_user->u_id)
                        ->with('conversation.addedByUser:u_id,name')
                        ->with('lastMessage')
                        ->orderBy('created_at', 'desc')
                        ->first();

                    if ($mail) {
                        $user_ids = json_decode($mail->uc_user_id, true);
                        if (is_array($user_ids)) {
                            $user_ids = array_filter($user_ids, fn($id) => $id != $current_user->u_id);
                        }
                        $users = User::whereIn('u_id', $user_ids)
                            ->select('u_id', 'name')
                            ->get();
                        $mail->users = $users;

                        // for seen
                        $last_message = ConversationMessageHelper::lastMessage($mail->uc_conversation_id);
                        $is_seen = false;
                        if ($last_message) {
                            $is_seen = ConversationMessageHelper::isMessageSeen($last_message->m_id, $current_user->u_id);
                        }
                        $mail->is_seen = $is_seen;
                    }
                    DB::commit();
                    return response()->json([
                        'status' => true,
                        'mail' => $mail,
                        'message' => trans('messages.message_sent'),
                    ]);
                } else {
                    DB::rollBack();
                    return response()->json([
                        'status' => false,
                        'message' => trans('messages.message_not_sent'),
                    ]);
                }
            }
            DB::rollBack();
            return response()->json([
                'status' => false,
                'message' => trans('messages.message_error'),
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('aseel Error creating new mail with message', ['error' => $e->getMessage()]);
            return response()->json([
                'status' => false,
                'message' => trans('messages.message_error'),
            ], 500);
        }
    }

    // reply
    public function addNewMessage(Request $request)
    {
        try {
            DB::beginTransaction();

            $validator = Validator::make($request->all(), [
                'conversations_id' => 'required|exists:conversations,c_id',
                'message' => 'required',
                'message_file' => 'nullable',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'status' => false,
                    'message' => $validator->errors()->first()
                ], 422);
            }

            $conversation_id =  $request->input('conversations_id');
            $message_text =  $request->input('message');
            $file = $request->hasFile('message_file') ? $request->file('message_file') : null;

            $message = $this->messageService->createMessage($conversation_id, $message_text, $file);

            if ($message != null) {
                $current_user_id = auth()->user()->u_id;

                $is_message_marked_as_seen = $this->messageService->markMessageAsSeen(
                    $conversation_id,
                    $message->m_id,
                    $current_user_id
                );

                $message = MessageModel::where('m_id', $message->m_id)
                    ->with([
                        'sender' => function ($query) {
                            $query->select('u_id', 'name', 'u_image', 'u_role_id')
                                ->with('company:c_manager_id,c_name,c_english_name');
                        }
                    ])
                    ->first();

                DB::commit();

                if ($is_message_marked_as_seen) {
                    return response()->json([
                        'status' => true,
                        'message' => trans('messages.message_sent_successfully'),
                        'new_message' => $message,
                    ]);
                }
            }

            DB::rollBack();
            return response()->json([
                'status' => false,
                'message' => trans('messages.message_not_sent_error'),
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error sending message', ['error' => $e->getMessage()]);
            return response()->json([
                'status' => false,
                'message' => $e->getMessage(),
            ]);
        }
    }

    // get conversations of the user
    public function getUserMailing()
    {
        $current_user = Auth::user();
        $conversation_ids_list = UsersConversationsModel::whereJsonContains('uc_user_id', (string) Auth::user()->u_id)
            ->pluck('uc_conversation_id');

        $mails = UsersConversationsModel::whereIn('uc_conversation_id', $conversation_ids_list)
            ->where('uc_user_id', '!=', $current_user->u_id)
            ->with('conversation.addedByUser:u_id,name')
            ->with('lastMessage')
            ->orderBy(
                MessageModel::select('created_at')
                    ->whereColumn('m_conversation_id', 'users_conversations.uc_conversation_id')
                    ->latest()
                    ->take(1),
                'desc'
            )
            ->paginate(14);

        // to get users and isSeen
        $mails->each(function ($mail) use ($current_user) {
            $user_ids = json_decode($mail->uc_user_id, true);

            // remove the conversation creator id
            $user_ids = array_filter($user_ids, fn($id) => $id != $mail->conversation->added_by ?? 0);

            $users = User::whereIn('u_id', $user_ids)
                ->select('u_id', 'name')
                ->get();
            $mail->users = $users;

            // for seen
            $last_message = ConversationMessageHelper::lastMessage($mail->uc_conversation_id);
            $is_seen = false;
            if ($last_message) {
                $is_seen = ConversationMessageHelper::isMessageSeen($last_message->m_id, $current_user->u_id);
            }
            $mail->is_seen = $is_seen;
        });

        return response()->json([
            'status' => true,
            'pagination' => [
                'current_page' => $mails->currentPage(),
                'last_page' => $mails->lastPage(),
                'per_page' => $mails->perPage(),
                'total_items' => $mails->total(),
            ],
            'mails' => $mails->items()
        ]);
    }

    public function getConversationMessages(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'conversation_id' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => $validator->errors()->first()
            ], 422);
        }

        $messages = MessageModel::where('m_conversation_id', $request->input('conversation_id'))
            // ->with('sender:u_id,name,u_image')
            ->with([
                'sender' => function ($query) {
                    $query->select('u_id', 'name', 'u_image', 'u_role_id')
                        ->with('company:c_manager_id,c_name,c_english_name');
                }
            ])
            ->orderBy('created_at', 'desc')
            ->get();

        return response()->json([
            'status' => true,
            'messages' => $messages
        ]);
    }

    public function getChatableUsers()
    {
        $user = Auth::user();
        $chatable_users = [];

        $current_semester = SystemSetting::first();

        // (depend on year and semester)
        if ($user->u_role_id == 2) { // for student (he can chat his supervisor and managers)
            $managers = User::select('users.u_id', 'users.name')
                ->join('company_branches', 'users.u_id', '=', 'company_branches.b_manager_id')
                ->join('students_companies', 'company_branches.b_id', '=', 'students_companies.sc_branch_id')
                ->join('registration', 'students_companies.sc_registration_id', '=', 'registration.r_id')
                ->where('registration.r_student_id', $user->u_id)
                ->where('registration.r_year', $current_semester->ss_year)
                ->where('registration.r_semester', $current_semester->ss_semester_type)
                ->distinct()
                ->get();

            $supervisors = Registration::join('users', 'registration.supervisor_id', '=', 'users.u_id')
                ->where('registration.r_student_id', $user->u_id)
                ->where('registration.r_year', $current_semester->ss_year)
                ->where('registration.r_semester', $current_semester->ss_semester_type)
                ->select('users.u_id', 'users.name')
                ->distinct()
                ->get();

            // return $supervisors;
            $chatable_users = $managers->merge($supervisors)->unique('u_id');

            // return $chatableUsers;
        } else if ($user->u_role_id == 10) { // trainings supervisor (he can chat his students from registration table)

            $studentIds = Registration::where('supervisor_id', $user->u_id)
                ->where('r_year', $current_semester->ss_year)
                ->where('r_semester', $current_semester->ss_semester_type)
                ->pluck('r_student_id');

            $students = User::whereIn('u_id', $studentIds)
                ->select('u_id', 'name')
                ->get();

            $chatable_users = $students;
        } else if ($user->u_role_id == 6) { // manager (he can chat his trainees)
            $trainees = Registration::join('users', 'registration.r_student_id', '=', 'users.u_id')
                ->join('students_companies', 'registration.r_id', '=', 'students_companies.sc_registration_id')
                ->join('company_branches', 'students_companies.sc_branch_id', '=', 'company_branches.b_id')
                ->where('company_branches.b_manager_id', $user->u_id)
                ->where('registration.r_year', $current_semester->ss_year)
                ->where('registration.r_semester', $current_semester->ss_semester_type)
                ->select('users.u_id', 'users.name')
                ->distinct()
                ->get();

            $chatable_users = $trainees->unique('u_id');
            // return $chatableUsers;
        };

        // add all users that has role id = 8 to the chatable users
        $chatable_users = $chatable_users->merge(User::where('u_role_id', 8)->select('u_id', 'name')->get());

        return response()->json([
            'status' => true,
            'chatable_users' => $chatable_users
        ]);
    }

    public function markMessageAsSeen(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'conversation_id' => 'required|exists:conversations,c_id',
            'message_id' => 'required|exists:messages,m_id',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => $validator->errors()->first()
            ], 422);
        }

        $current_user_id = auth()->user()->u_id;

        $success = $this->messageService->markMessageAsSeen(
            $request->input('conversation_id'),
            $request->input('message_id'),
            $current_user_id
        );

        if (!$success) {
            return response()->json([
                'status' => false,
                'message' => trans('messages.message_not_marked_as_seen')
            ], 422);
        }

        return response()->json([
            'status' => true,
            'message' => trans('messages.message_marked_as_seen')
        ]);
    }


    // public function unseenConversationsCount()
    // {
    //     $current_user_id = auth()->user()->u_id;
    //     $unseen_conversations_count = $this->messageService->unseenConversationsCount($current_user_id);
    //     // unseen notifications count
    //     $unseen_notifications_count = $this->notificationService->unseenNotificationsCount($current_user_id);
    //     return response()->json([
    //         'status' => true,
    //         'unseen_conversations_count' => $unseen_conversations_count
    //     ]);
    // }
}
