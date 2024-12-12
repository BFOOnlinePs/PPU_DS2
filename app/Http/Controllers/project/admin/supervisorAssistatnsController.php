<?php

namespace App\Http\Controllers\project\admin;

use App\Http\Controllers\Controller;
use App\Models\SupervisorAssistant;
use App\Models\User;
use Illuminate\Http\Request;

class supervisorAssistatnsController extends Controller
{
    public function create(Request $request)
    {
        $supervisor_assistants = new SupervisorAssistant;
        $supervisor_assistants->sa_assistant_id = $request->assistant_id;
        $supervisor_assistants->sa_supervisor_id = $request->supervisor_id;
        if($supervisor_assistants->save()) {
            $supervisor_assistants = SupervisorAssistant::where('sa_assistant_id' , $request->assistant_id)
                                                        ->pluck('sa_supervisor_id')
                                                        ->toArray();
            $supervisors = User::where('u_role_id' , 3)
                                ->whereNotIn('u_id' , $supervisor_assistants)
                                ->get();
            $supervisors_assistant = SupervisorAssistant::where('sa_assistant_id' , $request->assistant_id)
                                                        ->get();
            $html = view('project.admin.users.includes.assistantList' , ['supervisors_assistant' => $supervisors_assistant])->render();
            return response()->json(['supervisors' => $supervisors , 'html' => $html]);
        }
    }
    public function show_select_for_supervisor(Request $request)
    {
        $supervisor_assistants = SupervisorAssistant::where('sa_assistant_id' , $request->assistant_id)
                                                    ->pluck('sa_supervisor_id')
                                                    ->toArray();
        $supervisors = User::where('u_role_id' , 3)
                            ->whereNotIn('u_id' , $supervisor_assistants)
                            ->get();
        return response()->json(['supervisors' => $supervisors]);
    }
    public function deleteSupervisor(Request $request)
    {
        $assistant = SupervisorAssistant::where('sa_id' , $request->sa_id)
                                            ->first();
        $delete = SupervisorAssistant::where('sa_id' , $request->sa_id)
                                    ->delete();
        if($delete > 0) {
            $supervisor_assistants = SupervisorAssistant::where('sa_assistant_id' , $assistant->sa_assistant_id)
                                                        ->pluck('sa_supervisor_id')
                                                        ->toArray();
            $supervisors = User::where('u_role_id' , 3)
                                ->whereNotIn('u_id' , $supervisor_assistants)
                                ->get();
            $supervisors_assistant = SupervisorAssistant::where('sa_assistant_id' , $assistant->sa_assistant_id)
                                                        ->get();
            $html = view('project.admin.users.includes.assistantList' , ['supervisors_assistant' => $supervisors_assistant])->render();
            return response()->json(['supervisors' => $supervisors , 'html' => $html]);
        }
    }
}
