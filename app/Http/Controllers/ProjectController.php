<?php

namespace App\Http\Controllers;

use File;
use DataTables;
use App\Models\Process;
use App\Models\Project;
use App\Models\Binnacle;
use App\Models\Wordflow;
use App\Models\Department;
use Illuminate\Http\Request;
use App\Models\AnnexedProject;
use App\Models\ProjectWordFlow;
use Illuminate\Support\Facades\DB;
use App\Jobs\SendMailNotifyProject;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use App\Events\ProjectDepartmentProcessed;
use App\Http\Requests\FormProjectCreateRequest;

class ProjectController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $project = DB::table('projects')
                        ->join('project_word_flows', 'projects.id', '=', 'project_word_flows.project_id')
                        ->where('project_word_flows.wordflow_department_id', Auth::user()->department_id)
                        ->where('project_word_flows.wordflow_department', 'TRUE')
                        ->orwhere('project_word_flows.user_created_id', Auth::user()->id)
                        ->orwhere('project_word_flows.user_accepted_id', Auth::user()->id)
                        ->groupBy('projects.id')
                        ->select('projects.id', 'projects.name', 'projects.description', 'projects.status', 'projects.updated_at')
                        ->get();

            return Datatables::of($project)
                    ->addIndexColumn()
                    ->addColumn('action', function($project){
                           $btn = '';

                        $btn .= '<a href="" data-toggle="tooltip" data-placement="right" title="Revisar"  data-id="'.$project->id.'" id="edit_'.$project->id.'" class="btn btn-warning btn-xs mr-1 editProject">
                                    <i class="ti-eye"></i>
                                </a>';
                        $btn .= '<a href="'.route('projects.show.annexed',['project' => $project->id]).'" data-toggle="tooltip" data-placement="right" title="Anexos"  data-id="'.$project->id.'" id="annexed_'.$project->id.'" class="btn btn-success btn-xs mr-1 annexedProject">
                                    <i class="ti-layers-alt"></i>
                                </a>';
                        $btn .= '<a href="'.route('projects.binnacle',['project' => $project->id]).'" data-toggle="tooltip" data-placement="right" title="Bitacora"  data-id="'.$project->id.'" id="binnacle_'.$project->id.'" class="btn btn-inverse btn-xs mr-1 binnacleProject">
                                    <i class="ti-search"></i>
                                </a>';
                        $btn .= '<a href="#" data-toggle="tooltip" data-placement="right" title="Eliminar"  data-id="'.$project->id.'" id=delete_'.$project->id.'" data-url="'.route('projects.destroy',['project' => $project->id]).'" class="btn btn-danger btn-xs mr-1 deleteProject">
                                    <i class="ti-trash"></i>
                                </a>';

                        return $btn;
                    })
                    ->addColumn('department', function($project){

                        return $btn = '<div class="label-main">
                                            <label class="label label-inverse">'.Auth::user()->department->name.'</label>
                                        </div>';
                    })->addColumn('user', function($project){

                        return $btn = Auth::user()->name.' '.Auth::user()->surname;
                    })
                    ->addColumn('status', function($project){

                        $btn = '';
                        if($project->status=='completed'){
                            $btn = '<div class="label-main">
                                        <label class="label label-success">Completdo</label>
                                    </div>';
                        }elseif($project->status=='pending'){
                            $btn = '<div class="label-main">
                                        <label class="label label-warning">Pendiente</label>
                                    </div>';
                        }elseif($project->status=='CREADO'){
                            $btn = '<div class="label-main">
                                        <label class="label label-primary">Creado</label>
                                    </div>';
                        }elseif($project->status=='returned'){
                            $btn = '<div class="label-main">
                                        <label class="label label-danger">Devuelto</label>
                                    </div>';
                        }elseif($project->status=='ENVIADO'){
                            $btn = '<div class="label-main">
                                        <label class="label label-inverse">Enviado</label>
                                    </div>';
                        }
                        return $btn;
                    })
                    ->rawColumns(['action','department','status'])
                    ->make(true);
        }
        return view('panel.projects.index', ['title' => 'Proyectos']);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('panel.projects.create', ['title' => 'Proyectos - Crear', 'processes' => Process::where('active', 1)->get()]);
    }


    function get_file_size($size)
    {
        $units = array('Bytes', 'KB', 'MB');
        return round($size / pow(1024, ($i = floor(log($size, 1024)))), 2).' '.$units[$i];
    }

    public function downloadAnnexed($id)
    {
        $annexed = AnnexedProject::findOrFail($id);
        //return $annexed;
        return response()->download(public_path($annexed->file_path.$annexed->file_name));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(FormProjectCreateRequest $request)
    {
        $project                   = new Project();
        $project->name             = $request->name;
        $project->description      = $request->description;
        $project->process_id       = $request->process;
        if($request->action == 'created'){
            $project->status       = 'CREADO';
        }elseif($request->action == 'send'){
            $project->status       = 'ENVIADO';
        }
        $saved = $project->save();

        if($saved){
            $process  = Process::find($project->process_id);
            $wordflow = Wordflow::where('process_id', $process->id)->first();

            $user = Auth::user()->name.' '.Auth::user()->surname;
            $binnacle                  = new Binnacle();
            $binnacle->observation     = 'El proyecto se creo con el nombre ('.$project->name.'), por el usuario '.$user.' perteneciente al departamento de '.Auth::user()->Department->name;
            $binnacle->project_id      = $project->id;
            $binnacle->user_id         = Auth::user()->id;

            $process = Process::find($project->process_id)->name;
            $dir = public_path() . '/documents/';
            if (!File::exists($dir)) {
                File::makeDirectory($dir , 0777, true);
            }
            $process = $dir.$process;
            if (!File::exists($process)) {
                File::makeDirectory($process , 0777, true);
            }

            $projects = $process.'/'.$project->name;
            if (!File::exists($projects)) {
                File::makeDirectory($projects , 0777, true);
            }

            if($request->action == 'created'){
                foreach (json_decode($wordflow->steps, true) as $key => $value) {
                    ProjectWordFlow::create([
                        'user_created_id'           =>Auth::user()->id,
                        'user_accepted_id'          => NULL,
                        'step'                      => $key+1,
                        'wordflow_id'               => $wordflow->id,
                        'wordflow_department_id'    => $value['id'],
                        'wordflow_department'       => NULL,
                        'wordflow_status'           => NULL,
                        'project_id'                => $project->id,
                    ]);
                }

                $binnacle->annexes     = $request->annexes ?? $request->annexed_count;
                $binnacle->status      = 'CREADO';
                if($request->has('annexed')){
                    $i=1;
                    foreach($request->file('annexed') as $doc => $document){
                        foreach($request->input('annexed_name') as $annexed => $name){
                            if($doc == $annexed){
                                $fileName =  'Anexo-'.$i++.'.'.$document->getClientOriginalExtension();
                                $filesize = $document->move($projects, $fileName);
                                AnnexedProject::create([
                                    'file_name_original'    => $document->getClientOriginalName(),
                                    'file_name'             => $fileName,
                                    'file_path'             => 'documents/'.Process::find($project->process_id)->name.'/'.$project->name.'/',
                                    'file_path_delete'      => 'documents/'.Process::find($project->process_id)->name.'/'.$project->name.'/',
                                    'file_size'             => $this->get_file_size(filesize($filesize)),
                                    'file_type'             => $document->getClientOriginalExtension(),
                                    'project_id'            => $project->id,
                                    'observation'           => NULL,
                                    'annexed_name'          => $name
                                ]);
                            }
                        }
                    }
                }
            }elseif($request->action == 'send'){
                foreach (json_decode($wordflow->steps, true) as $key => $value) {
                    $wordflow_department = NULL;
                    $wordflow_status     = NULL;
                    $step                = 1;
                    if($value['id'] == $request->department){
                        $wordflow_department   = 'TRUE';
                        $wordflow_status       = 'RECIBIDO';
                    }else{
                        if(($key+1)== 2){
                            $wordflow_department = 'NEXT';
                        }
                    }

                    ProjectWordFlow::create([
                        'user_created_id'           =>Auth::user()->id,
                        'user_accepted_id'          => NULL,
                        'step'                      => $key+1,
                        'wordflow_id'               => $wordflow->id,
                        'wordflow_department_id'    => $value['id'],
                        'wordflow_department'       => $wordflow_department,
                        'wordflow_status'           => $wordflow_status,
                        'project_id'                => $project->id,
                    ]);
                }

                $binnacle->annexes                 = $request->annexes ?? $request->annexed_count;
                $binnacle->status                  = 'ENVIADO';
                $binnacle->department_send_id      = $request->department;
                $binnacle->department_received_id  = $request->department;
                if($request->has('annexed')){
                    $i=1;
                    foreach($request->file('annexed') as $doc => $document){
                        foreach($request->input('annexed_name') as $annexed => $name){
                            if($doc == $annexed){
                                $fileName =  'Anexo-'.$i++.'.'.$document->getClientOriginalExtension();
                                $filesize = $document->move($projects, $fileName);
                                AnnexedProject::create([
                                    'file_name_original'    => $document->getClientOriginalName(),
                                    'file_name'             => $fileName,
                                    'file_path'             => 'documents/'.Process::find($project->process_id)->name.'/'.$project->name.'/',
                                    'file_path_delete'      => 'documents/'.Process::find($project->process_id)->name.'/'.$project->name.'/',
                                    'file_size'             => $this->get_file_size(filesize($filesize)),
                                    'file_type'             => $document->getClientOriginalExtension(),
                                    'project_id'            => $project->id,
                                    'observation'           => NULL,
                                    'annexed_name'          => $name
                                ]);
                            }
                        }
                    }
                }
                $departmet = Department::find($request->department);
                //SendMailNotifyProject::dispatch($project, $departmet);
                event(new ProjectDepartmentProcessed($project, $departmet));
            }
            $saved = $binnacle->save();
            if($request->action == 'created'){
                return response()->json(['success' => true, 'message' => 'proyecto creado en el sistema exitosamente.'], 200);
            }elseif($request->action == 'send'){
                return response()->json(['success' => true, 'message' => 'proyecto creado y enviado al departamento '.$departmet->name.' en el sistema exitosamente.'], 200);
            }
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function showAnnexed(Request $request)
    {
        if(\Request::wantsJson()){
            $process  = Process::find($request->id);
            $wordflow = Wordflow::where('process_id', $process->id)->first();
            $departmet_id = json_decode($wordflow->steps, true);
            $departmet = Department::find($departmet_id[0]['id']);
            return response()->json(['success' => true, 'message' => 'Procede a cargar los anexos, segun el proceso seleccionado', 'process' => $process, 'annexes' => $process->Annexes, 'wordflow' => $departmet], 200);
        }
        abort(404);
    }

     /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function Binnacle($id)
    {
        return view('panel.projects.binnacle', ['title' => 'Proyectos - Bitacora', 'project' => Project::find($id)]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function Annexed($id)
    {
        return view('panel.projects.annexed', ['title' => 'Proyectos - Anexos', 'project' => Project::find($id)]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        /* $project    = Project::find(16);
        $departmet  = Department::find($id);
        event(new ProjectDepartmentProcessed($project, $departmet)); */
        //SendMailNotifyProject::dispatch($project, $departmet);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        if(\Request::wantsJson()){
            $project = Project::findOrFail($id);
            $delete = $project->delete();
            if ($delete) {
                File::deleteDirectory(public_path('documents/'.$project->Process->name.'/'.$project->name));
                return response()->json(['success' => true, 'message' => 'Proyecto eliminado exitosamente.'], 200);
            } else {
                return response()->json(['error' => true, 'message' => 'El Proyecto no se elimino correctamente. Intente mas tarde.'], 403);
            }
        }
        abort(404);
    }
}
