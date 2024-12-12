<?php

namespace App\Http\Controllers\project\supervisors;

use App\Http\Controllers\Controller;
use App\Models\SupervisorAssistant;
use App\Models\User;
use Illuminate\Http\Request;

class AssistantController extends Controller
{
    public function index($id)
    {
        $user = User::find($id);
        $supervisorAssistants_id = SupervisorAssistant::where('sa_supervisor_id' , $id)
                                                        ->pluck('sa_assistant_id')
                                                        ->toArray();
        $assistants = User::where('u_role_id' , 4)
                                ->whereNotIn('u_id' , $supervisorAssistants_id)
                                ->get();
        $supervisorAssistants = SupervisorAssistant::where('sa_supervisor_id' , $id)->get();
        return view('project.supervisor.assistants.index' , ['user' => $user , 'assistants' => $assistants , 'supervisorAssistants' => $supervisorAssistants]);
    }
    public function create(Request $request)
    {
        if(empty($request->assistant_id)) {
            $supervisorAssistants = SupervisorAssistant::where('sa_supervisor_id' , $request->supervisor_id)->get();
            $html = view('project.supervisor.assistants.ajax.supervisorAssistantList' , ['supervisorAssistants' => $supervisorAssistants])->render();
            $supervisorAssistants_id = SupervisorAssistant::where('sa_supervisor_id' , $request->supervisor_id)
                        ->pluck('sa_assistant_id')
                        ->toArray();
            $assistants = User::where('u_role_id' , 4)
                        ->whereNotIn('u_id' , $supervisorAssistants_id)
                        ->get();
            return response()->json(['html' => $html , 'assistants' => $assistants]);
        }
        else {
            $assistant = new SupervisorAssistant;
            $assistant->sa_supervisor_id = $request->supervisor_id;
            $assistant->sa_assistant_id = $request->assistant_id;
            if($assistant->save())
            {
                $supervisorAssistants = SupervisorAssistant::where('sa_supervisor_id' , $request->supervisor_id)->get();
                $html = view('project.supervisor.assistants.ajax.supervisorAssistantList' , ['supervisorAssistants' => $supervisorAssistants])->render();
                $supervisorAssistants_id = SupervisorAssistant::where('sa_supervisor_id' , $request->supervisor_id)
                            ->pluck('sa_assistant_id')
                            ->toArray();
                $assistants = User::where('u_role_id' , 4)
                            ->whereNotIn('u_id' , $supervisorAssistants_id)
                            ->get();
                return response()->json(['html' => $html , 'assistants' => $assistants]);
            }
        }
    }
    public function delete(Request $request)
    {
        $deleted = SupervisorAssistant::where('sa_id' , $request->sa_id)->delete();
        if($deleted > 0) {
            $supervisorAssistants = SupervisorAssistant::where('sa_supervisor_id' , $request->supervisor_id)->get();
            $html = view('project.supervisor.assistants.ajax.supervisorAssistantList' , ['supervisorAssistants' => $supervisorAssistants])->render();
            $supervisorAssistants_id = SupervisorAssistant::where('sa_supervisor_id' , $request->supervisor_id)
                        ->pluck('sa_assistant_id')
                        ->toArray();
            $assistants = User::where('u_role_id' , 4)
                        ->whereNotIn('u_id' , $supervisorAssistants_id)
                        ->get();
            return response()->json(['html' => $html , 'assistants' => $assistants]);
        }
    }
}
