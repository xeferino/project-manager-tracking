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
            $project = DB::table('projects')->select('id', 'name', 'description', 'process_id', 'status', 'updated_at')->get();
            return Datatables::of($project)
                    ->addIndexColumn()
                    ->addColumn('action', function($project){
                           $btn = '';

                        $btn .= '<a href="" data-toggle="tooltip" data-placement="right" title="Editar"  data-id="'.$project->id.'" id="edit_'.$project->id.'" class="btn btn-primary btn-xs mr-1 editProject">
                                    <i class="ti-pencil"></i>
                                </a>';
                        $btn .= '<a href="" data-toggle="tooltip" data-placement="right" title="Bitacora"  data-id="'.$project->id.'" id="binnacle_'.$project->id.'" class="btn btn-info btn-xs mr-1 binnacleProject">
                                    <i class="ti-search"></i>
                                </a>';
                        $btn .= '<a href="" data-toggle="tooltip" data-placement="right" title="Eliminar"  data-id="'.$project->id.'" id=delete_'.$project->id.'" class="btn btn-danger btn-xs mr-1 deleteProject">
                                    <i class="ti-trash"></i>
                                </a>';
                        return $btn;
                    })
                    ->addColumn('department', function($project){

                        return $btn = '<div class="label-main">
                                            <label class="label label-inverse">Department</label>
                                        </div>';
                    })->addColumn('user', function($project){

                        return $btn = 'we';
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
                        }elseif($project->status=='created'){
                            $btn = '<div class="label-main">
                                        <label class="label label-primary">Creado</label>
                                    </div>';
                        }elseif($project->status=='returned'){
                            $btn = '<div class="label-main">
                                        <label class="label label-danger">Devuelto</label>
                                    </div>';
                        }elseif($project->status=='dispatched'){
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
            $project->status       = 'created';
        }elseif($request->action == 'send'){
            $project->status       = 'dispatched';
        }
        $saved = $project->save();

        if($saved){
            $user = Auth::user()->name.' '.Auth::user()->surname;
            $binnacle                  = new Binnacle();
            $binnacle->observation     = 'El proyecto se creo con el nombre ('.$project->name.'), por el usuario '.$user.' perteneciente al departamento de '.Auth::user()->Department->name;
            $binnacle->project_id      = $project->id;
            $binnacle->user_id         = Auth::user()->id;

            $process = Process::find($project->process_id)->name;
            $dir = storage_path('app') . '/documents/';
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
                $binnacle->annexes     = 0;
            }elseif($request->action == 'send'){
                $binnacle->annexes     = $request->annexes;
                if($request->has('annexed')){
                    $i=1;
                    foreach($request->file('annexed') as $doc => $document){
                        $fileName =  'Anexo-'.$i++.'.'.$document->getClientOriginalExtension();
                        $filesize = $document->move($projects, $fileName);
                        AnnexedProject::create([
                            'name'              => $document->getClientOriginalName(),
                            'file_name'         => $fileName,
                            'file_path'         => $projects.'/',
                            'file_path_delete'  => $projects.'/',
                            'file_size'         => $this->get_file_size(filesize($filesize)),
                            'file_type'         => $document->getClientOriginalExtension(),
                            'project_id'        => $project->id,
                        ]);
                    }
                }
                $departmet = Department::find($request->department);
                //event(new ProjectDepartmentProcessed($project, $departmet));
                $project    = Project::find($project->id);
                $departmet  = Department::find($request->department);
                SendMailNotifyProject::dispatch($project, $departmet);
            }
            $saved = $binnacle->save();

            return response()->json(['success' => true, 'message' => 'proyecto creado en el sistema exitosamente.'], 200);
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
            $departmet = Department::find($departmet_id[0]['step']);
            return response()->json(['success' => true, 'message' => 'Procede a cargar los anexos, segun el proceso seleccionado', 'annexes' => $process, 'wordflow' => $departmet], 200);
        }
        abort(404);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        /* $project    = Project::find(31);
        $departmet  = Department::find($id);
        SendMailNotifyProject::dispatch($project, $departmet); */
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
        //
    }
}
