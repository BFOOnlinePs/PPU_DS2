<?php

namespace App\Http\Controllers\project\training_supervisor;

use App\Http\Controllers\Controller;
use App\Models\ConversationMessagesSeenModel;
use App\Models\ConversationsModel;
use App\Models\MessageModel;
use App\Models\User;
use App\Models\UsersConversationsModel;
use GuzzleHttp\Psr7\Message;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ConversationController extends Controller
{
    public function index()
    {
        $data = ConversationsModel::whereIn('c_id', function ($query) {
            $query->select('m_conversation_id')->from('messages')->where('m_sender_id', auth()->user()->u_id)->orWhereIn('m_id', function ($query2) {
                $query2->select('uc_conversation_id')->from('users_conversations')->where('uc_user_id', auth()->user()->u_id);
            });
        })->get();
        $users = User::query();
        if (auth()->user()->u_role_id == 2) {
            $users->where(function ($query) {
                $query->whereIn('u_id', function ($query) {
                    $query->select('supervisor_id')
                        ->from('registration')
                        ->where('r_student_id', auth()->user()->u_id);
                });
            })->orWhereIn('u_id', function ($query) {
                $query->select('c_manager_id')
                    ->from('companies')
                    ->whereIn('c_id', function ($query2) {
                        $query2->select('sc_company_id')
                            ->from('students_companies')
                            ->where('sc_student_id', auth()->user()->u_id);
                    });
            });
        }
        if (auth()->user()->u_role_id == 10) {
            $users->whereIn('u_id', function ($query) {
                $query->select('r_student_id')->from('registration')->where('supervisor_id', auth()->user()->u_id);
            });
        }
        if (auth()->user()->u_role_id == 6) {
            $users->whereIn('u_id', function ($query) {
                $query->select('sc_student_id')->from('students_companies')->whereIn('sc_company_id', function ($query2) {
                    $query2->select('c_id')->from('companies')->where('c_manager_id', auth()->user()->u_id);
                });
            });
        }
        $users = $users->get();
        return view('project.training_supervisor.conversation.index', ['data' => $data, 'users' => $users]);
    }

    public function details($id)
    {
        $data = MessageModel::where('m_conversation_id', $id)->with(['sender'])->get();
        $receive = UsersConversationsModel::where('uc_conversation_id', $id)->with('receive')->first();
        return view('project.training_supervisor.conversation.details', ['data' => $data, 'id' => $id, 'receive' => $receive]);
    }

    public function add()
    {
        $users = User::query();
        if (auth()->user()->u_role_id == 2) {
            $users->where(function ($query) {
                $query->whereIn('u_id', function ($query) {
                    $query->select('supervisor_id')
                        ->from('registration')
                        ->where('r_student_id', auth()->user()->u_id);
                })
                    ->orWhereIn('u_id', function ($query) {
                        $query->select('c_manager_id')
                            ->from('companies')
                            ->whereIn('c_id', function ($query2) {
                                $query2->select('sc_company_id')
                                    ->from('students_companies')
                                    ->where('sc_student_id', auth()->user()->u_id);
                            });
                    });
            })->orWhere('u_role_id', 8);
            // $users->wherein('u_id',function($query){
            //     $query->select('supervisor_id')->from('registration')->where('r_student_id',auth()->user()->u_id);
            // });
            // $users->whereIn('u_id',function($query){
            //     $query->select('c_manager_id')->from('companies')->whereIn('c_id' , function($query2){
            //         $query2->select('sc_company_id')->from('students_companies')->where('sc_student_id',auth()->user()->u_id);
            //     });
            // });

            // $users->where('u_role_id',8);
        }
        if (auth()->user()->u_role_id == 10) {
            // $users->whereIn('u_id', function ($query) {
            //     $query->select('c_manager_id')
            //         ->from('companies')
            //         ->whereIn('c_id', function ($query) {
            //             $query->select('sc_company_id')
            //                 ->from('students_companies')
            //                 ->whereIn('sc_registration_id', function ($query) {
            //                     $query->select('r_id')
            //                         ->from('registration')
            //                         ->where('supervisor_id', 510);
            //                 });
            //         });
            // });
            $users->whereIn('u_id', function ($query) {
                $query->select('r_student_id')->from('registration')->where('supervisor_id', auth()->user()->u_id);
            })
                ->orWhere('u_role_id', 8);
        }
        if (auth()->user()->u_role_id == 6) {
            $users->whereIn('u_id', function ($query) {
                $query->select('sc_student_id')->from('students_companies')->whereIn('sc_company_id', function ($query2) {
                    $query2->select('c_id')->from('companies')->where('c_manager_id', auth()->user()->u_id);
                });
            })
                ->orWhere('u_role_id', 8);
        }
        $users = $users->get();
        return view('project.training_supervisor.conversation.add', ['users' => $users]);
    }

    public function create(Request $request)
    {
        // $conversations = new ConversationsModel();
        // $conversations->c_name = $request->c_name;
        // if ($conversations->save()){
        //     $data = new UsersConversationsModel();
        //     $data->uc_conversation_id = $conversations->c_id;
        //     $data->uc_user_id = $request->uc_user_id;
        //     if ($data->save()){
        //         $messages = new MessageModel();
        //         $messages->m_conversation_id = $conversations->c_id;
        //         $messages->m_sender_id = auth()->user()->u_id;
        //         $messages->m_message_text = $request->m_message_text;
        //         $messages->m_status = 1;
        //         $messages->save();
        //         return redirect()->route('training_supervisor.conversation.details',['id'=>$conversations->c_id])->with(['success' => 'تمت الاضافة بنجاح']);
        //     }
        // }
        $data = new ConversationsModel();
        $data->c_name = $request->c_name;
        $data->added_by = auth()->user()->u_id;
        if ($data->save()) {
            $data2 = new UsersConversationsModel();
            $data2->uc_conversation_id = $data->c_id;
            $data2->uc_user_id = json_encode(
                array_map(
                    'strval',
                    array_merge([auth()->user()->u_id], $request->users_ids)
                )
            );
            $data2->save();
            $messages = new MessageModel();
            $messages->m_conversation_id = $data->c_id;
            $messages->m_sender_id = auth()->user()->u_id;
            $messages->m_message_text = $request->c_message;
            $messages->m_status = 1;
            $messages->save();
            return redirect()->route('training_supervisor.conversation.index', ['id' => $data->c_id])->with(['success' => 'تمت الاضافة بنجاح']);
        }
    }

    public function create_message(Request $request)
    {
        $data = new MessageModel();
        $data->m_conversation_id = $request->m_conversation_id;
        $data->m_sender_id = auth()->user()->u_id;
        $data->m_message_text = $request->m_message_text;
        $data->m_status = 1;
        if ($data->save()) {
            return redirect()->route('training_supervisor.conversation.details', ['id' => $request->m_conversation_id])->with(['success' => 'تم اضافة الرسالة بنجاح']);
        }
    }

    public function list_conversations(Request $request)
    {
        $data = ConversationsModel::query();
        $data->whereIn('c_id', function ($query) {
            $query->select('uc_conversation_id')
                ->from('users_conversations')
                ->whereJsonContains('uc_user_id', "" . auth()->user()->u_id . ""); // Fixed method name
        });
        if ($request->filled('c_name')) {
            $data->where('c_name', 'like', '%' . $request->c_name . '%');
        }

        $data = $data->with('user','participants')->orderBy('c_id', 'desc')->get();
        return response()->json([
            'success' => true,
            'view' => view('project.training_supervisor.conversation.ajax.list_conversation', ['data' => $data])->render(),
        ]);
    }

    public function list_message_ajax(Request $request)
    {
        $data = MessageModel::where('m_conversation_id', $request->c_id)->with('sender')->get();
        $conversations = ConversationsModel::where('c_id', $request->c_id)->first();
        $conversation_messages_seen = ConversationMessagesSeenModel::where('cms_conversation_id', $request->c_id)->where('cms_receiver_id',auth()->user()->u_id)->first();
        if(empty($conversation_messages_seen)){
            $conversation_messages_seen = new ConversationMessagesSeenModel();
            $conversation_messages_seen->cms_conversation_id = $request->c_id;
            $conversation_messages_seen->cms_receiver_id = auth()->user()->u_id;
            $conversation_messages_seen->cms_message_id = MessageModel::where('m_conversation_id', $request->c_id)->max('m_id');
            $conversation_messages_seen->save();
        }
        else{
            $conversation_messages_seen->cms_message_id = MessageModel::where('m_conversation_id', $request->c_id)->max('m_id');
            $conversation_messages_seen->save();
        }
        return response()->json([
            'success' => 'true',
            'view' => view('project.training_supervisor.conversation.ajax.list_message_ajax', ['data' => $data, 'conversations' => $conversations])->render(),
        ]);
    }

    public function add_message_ajax(Request $request)
    {

        $data = new MessageModel();
        $data->m_conversation_id = $request->m_conversation_id;
        $data->m_sender_id = auth()->user()->u_id;
        $data->m_message_text = $request->m_message_text;
        $data->m_status = 1;
        if ($request->hasFile('image')) {
            // Store the file in the public disk
            $filePath = $request->file('image')->store('uploads/messages', 'public');

            // Extract only the file name
            $fileName = basename($filePath);

            // Save the file name in the database
            $data->m_message_file = $fileName;
        }
        if ($data->save()) {
            return response()->json([
                'success' => 'true',
            ]);
        }
    }
}
