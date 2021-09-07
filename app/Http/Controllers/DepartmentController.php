<?php

namespace App\Http\Controllers;

use DataTables;
use App\Models\Department;
use Illuminate\Http\Request;
use App\Http\Requests\FormDepartmentCreateRequest;
use App\Http\Requests\FormDepartmentEditRequest;
use Illuminate\Support\Facades\DB;

class DepartmentController extends Controller
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
            $department = DB::table('departments')->select('id', 'name', 'active')->get();
            return Datatables::of($department)
                    ->addIndexColumn()
                    ->addColumn('action', function($department){
                           $btn = '';
                        $btn .= '<a href="'.route('departments.edit',['department' => $department->id]).'" data-toggle="tooltip" data-placement="right" title="Editar"  data-id="'.$department->id.'" id="edit_'.$department->id.'" class="btn btn-primary btn-xs mr-1 editDepartment">
                                        <i class="ti-pencil"></i>
                                </a>';
                        /* $btn .= '<a href="javascript:void(0)" data-toggle="tooltip" data-placement="right" title="Detalles"  data-id="'.$department->id.'" id="det_'.$department->id.'" class="btn btn-info btn-xs  mr-1 detailDepartment">
                                    <i class="ti-search"></i>
                                </a>'; */
                        $btn .= '<a href="javascript:void(0)" data-toggle="tooltip" data-placement="right" title="Eliminar"  data-url="'.route('departments.destroy',['department' => $department->id]).'" class="btn btn-danger btn-xs deleteDepartment">
                                        <i class="ti-trash"></i>
                                </a>';
                        return $btn;
                    })
                    ->addColumn('active', function($department){
                        $btn = '';
                        if($department->active==1){
                            $btn .= '<span class="badge badge-success">Activo</span>';
                        }else{
                            $btn .= '<span class="badge badge-danger">Inactivo</span>';
                        }
                        return $btn;
                    })
                    ->rawColumns(['action','active'])
                    ->make(true);
        }
        return view('panel.departments.index', ['title' => 'Departamentos']);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('panel.departments.create', ['title' => 'Departamentos - Crear']);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(FormDepartmentCreateRequest $request)
    {
        $department                   = new Department();
        $department->name             = $request->name;
        $department->active           = $request->active;
        $saved = $department->save();
        if($saved)
            return response()->json(['success' => true, 'message' => 'Departamento registrado exitosamente.'], 200);
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
        return view('panel.departments.edit', ['title' => 'Departamentos - Editar', 'department' => Department::find($id)]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(FormDepartmentEditRequest $request, Department $department)
    {
        $department                   = Department::find($department->id);
        $department->name             = $request->name;
        $department->active           = $request->active==1 ? 1 : 0;
        $saved = $department->save();
        if($saved)
            return response()->json(['success' => true, 'message' => 'Departamento actualizado exitosamente.'], 200);
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
            $department = Department::findOrFail($id);
            $delete = $department->delete();
            if ($delete) {
                return response()->json(['success' => true, 'message' => 'Departamento eliminado exitosamente.'], 200);
            } else {
                return response()->json(['error' => true, 'message' => 'El Departamento no se elimino correctamente. Intente mas tarde.'], 403);
            }
        }
        abort(404);
    }
}
