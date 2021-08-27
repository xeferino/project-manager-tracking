<?php

namespace App\Http\Controllers;

use DataTables;
use App\Models\Process;
use Illuminate\Http\Request;
use App\Http\Requests\FormProcessCreateRequest;
use App\Http\Requests\FormProcessEditRequest;
use Illuminate\Support\Facades\DB;

class ProcessController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $process = DB::table('processes')->select('id', 'name', 'annexed', 'active')->get();
            return Datatables::of($process)
                    ->addIndexColumn()
                    ->addColumn('action', function($process){
                           $btn = '';
                        $btn .= '<a href="'.route('processes.edit',['process' => $process->id]).'" data-toggle="tooltip" data-placement="right" title="Editar"  data-id="'.$process->id.'" id="edit_'.$process->id.'" class="btn btn-primary btn-xs mr-1 editProcess">
                                        <i class="ti-pencil"></i>
                                </a>';
                        /* $btn .= '<a href="javascript:void(0)" data-toggle="tooltip" data-placement="right" title="Detalles"  data-id="'.$Process->id.'" id="det_'.$Process->id.'" class="btn btn-info btn-xs  mr-1 detailProcess">
                                    <i class="ti-search"></i>
                                </a>'; */
                        $btn .= '<a href="javascript:void(0)" data-toggle="tooltip" data-placement="right" title="Eliminar"  data-url="'.route('processes.destroy',['process' => $process->id]).'" class="btn btn-danger btn-xs deleteProcess">
                                        <i class="ti-trash"></i>
                                </a>';
                        return $btn;
                    })
                    ->addColumn('active', function($process){
                        $btn = '';
                        if($process->active==1){
                            $btn .= '<span class="badge badge-success">Activo</span>';
                        }else{
                            $btn .= '<span class="badge badge-danger">Inactivo</span>';
                        }
                        return $btn;
                    })
                    ->rawColumns(['action','active'])
                    ->make(true);
        }
        return view('panel.processes.index', ['title' => 'Procesos', 'processes' => Process::all()]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('panel.processes.create', ['title' => 'Procesos - Crear']);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(FormProcessCreateRequest $request)
    {
        $process                   = new Process();
        $process->name             = $request->name;
        $process->annexed          = $request->annexed;
        $process->active           = $request->active;
        $saved = $process->save();
        if($saved)
            return response()->json(['success' => true, 'message' => 'Proceso registrado exitosamente.'], 200);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        return view('panel.processes.edit', ['title' => 'Procesos - Editar', 'process' => Process::find($id)]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(FormProcessEditRequest $request, Process $Process)
    {
        $process                   = Process::find($Process->id);
        $process->name             = $request->name;
        $process->annexed          = $request->annexed;
        $process->active           = $request->active==1 ? 1 : 0;
        $saved = $process->save();
        if($saved)
            return response()->json(['success' => true, 'message' => 'Proceso actualizado exitosamente.'], 200);
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
            $process = Process::findOrFail($id);
            $delete = $process->delete();
            if ($delete) {
                return response()->json(['success' => true, 'message' => 'Proceso eliminado exitosamente.'], 200);
            } else {
                return response()->json(['error' => true, 'message' => 'El Proceso no se elimino correctamente. Intente mas tarde.'], 403);
            }
        }
        abort(404);
    }
}
