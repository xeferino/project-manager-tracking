<?php

namespace App\Http\Controllers;

use DataTables;
use App\Models\Process;
use App\Models\Wordflow;
use App\Models\Department;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Events\ProjectDepartmentProcessed;
use App\Http\Requests\FormWordflowCreateRequest;

class WordflowController extends Controller
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
            $wordflow = DB::table('wordflows')->select('id', 'name', 'description', 'process_id', 'steps')->get();
            return Datatables::of($wordflow)
                    ->addIndexColumn()
                    ->addColumn('action', function($wordflow){
                           $btn = '';
                        /* $btn .= '<a href="'.route('wordflows.edit',['wordflow' => $wordflow->id]).'" data-toggle="tooltip" data-placement="right" title="Editar"  data-id="'.$wordflow->id.'" id="edit_'.$wordflow->id.'" class="btn btn-primary btn-xs mr-1 editWordflow">
                                        <i class="ti-pencil"></i>
                                </a>'; */
                        $btn .= '<a href="" data-toggle="tooltip" data-placement="right" title="Editar"  data-id="'.$wordflow->id.'" id="edit_'.$wordflow->id.'" class="btn btn-primary btn-xs mr-1 editWordflow">
                                        <i class="ti-pencil"></i>
                                </a>';
                        return $btn;
                    })
                    ->addColumn('process', function($wordflow){

                        return $btn = '<div class="label-main">
                                            <label class="label label-inverse">'.Process::find($wordflow->process_id)->name.'</label>
                                        </div>';
                    })
                    ->addColumn('steps', function($wordflow){

                        $btn = '';
                        $i=1;
                        foreach (json_decode($wordflow->steps, true) as $data) {
                            $btn .= 'Paso #'.$i++.': ' .Department::find($data['step'])->name.'<br>';
                        }
                        return $btn;
                    })
                    ->rawColumns(['action','process','steps'])
                    ->make(true);
        }
        return view('panel.wordflows.index', ['title' => 'Flujos', '']);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('panel.wordflows.create', ['title' => 'Flujos - Crear', 'processes' => Process::where('active', 1)->get(), 'departments' => Department::where('active', 1)->get()]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function step()
    {
        if(\Request::wantsJson()){
            return response()->json(['success' => true, 'message' => 'Paso caragdo exitosamente.', 'departments' => Department::where('active', 1)->get()], 200);
        }
        abort(404);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(FormWordflowCreateRequest $request)
    {
        $wordflow                   = new Wordflow();
        $wordflow->name             = $request->name;
        $wordflow->description      = $request->description;
        $wordflow->process_id       = $request->process;
        $wordflow->steps            = $request->steps;
        $saved = $wordflow->save();
        if($saved)
            return response()->json(['success' => true, 'message' => 'flujo registrado exitosamente.'], 200);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $paso = Wordflow::find($id);
        foreach (json_decode($paso->steps, true) as $step) {
            print_r($step['step']);
        }
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
