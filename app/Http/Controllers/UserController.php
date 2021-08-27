<?php

namespace App\Http\Controllers;

use DataTables;
use App\Models\User;
use App\Models\Department;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Http\Requests\FormUserEditRequest;
use App\Http\Requests\FormUserCreateRequest;


class UserController extends Controller
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
            $user = DB::table('users')->select('id', DB::raw('CONCAT(name," ", surname) AS fullname'), 'role', 'email', 'active', 'department_id')->get();
            return Datatables::of($user)
                    ->addIndexColumn()
                    ->addColumn('action', function($user){
                           $btn = '';
                        $btn .= '<a href="'.route('users.edit',['user' => $user->id]).'" data-toggle="tooltip" data-placement="right" title="Editar"  data-id="'.$user->id.'" id="edit_'.$user->id.'" class="btn btn-primary btn-xs mr-1 editUser">
                                        <i class="ti-pencil"></i>
                                </a>';
                        /* $btn .= '<a href="javascript:void(0)" data-toggle="tooltip" data-placement="right" title="Detalles"  data-id="'.$user->id.'" id="det_'.$user->id.'" class="btn btn-info btn-xs  mr-1 detailUser">
                                    <i class="ti-search"></i>
                                </a>'; */
                        $btn .= '<a href="javascript:void(0)" data-toggle="tooltip" data-placement="right" title="Eliminar"  data-url="'.route('users.destroy',['user' => $user->id]).'" class="btn btn-danger btn-xs deleteUser">
                                        <i class="ti-trash"></i>
                                </a>';
                        return $btn;
                    })
                    ->addColumn('active', function($user){
                        $btn = '';
                        if($user->active==1){
                            $btn .= '<span class="badge badge-success">Activo</span>';
                        }else{
                            $btn .= '<span class="badge badge-danger">Inactivo</span>';
                        }
                        return $btn;
                    })
                    ->addColumn('department', function($user){
                        return Department::find($user->department_id)->name ?? 'NULL';
                    })
                    ->rawColumns(['action','active', 'department'])
                    ->make(true);
        }
        return view('panel.users.index', ['title' => 'Usuarios', 'users' => User::all()]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('panel.users.create', ['title' => 'Usuarios - Crear', 'departments' => Department::all()]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(FormUserCreateRequest $request)
    {
        $user                   = new User();
        $user->name             = $request->name;
        $user->surname          = $request->surname;
        $user->email            = $request->email;
        $user->role             = $request->role;
        $user->department_id    = $request->department;
        $user->active           = $request->active;
        $user->password         = Hash::make($request->password);
        $saved = $user->save();
        if($saved)
            return response()->json(['success' => true, 'message' => 'Usuario registrado exitosamente.'], 200);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $id = Auth::user()->id;
        return view('panel.users.show', ['title' => 'Usuarios - Perfil', 'user' => User::find($id)]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        return view('panel.users.edit', ['title' => 'Usuarios - Editar', 'departments' => Department::all(), 'user' => User::find($id)]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(FormUserEditRequest $request, User $User)
    {
        $user                   = User::find($User->id);
        $user->name             = $request->name;
        $user->surname          = $request->surname;
        $user->email            = $request->email;
        $user->role             = $request->role;
        $user->department_id    = $request->department;
        $user->active           = $request->active==1 ? 1 : 0;
        if($request->password){
            $user->password     = Hash::make($request->password);
        }
        $saved = $user->save();
        if($saved)
            return response()->json(['success' => true, 'message' => 'Usuario actualizado exitosamente.'], 200);
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
            $user = User::findOrFail($id);
            $delete = $user->delete();
            if ($delete) {
                return response()->json(['success' => true, 'message' => 'Usuario eliminado exitosamente.'], 200);
            } else {
                return response()->json(['error' => true, 'message' => 'El usuario no se elimino correctamente. Intente mas tarde.'], 403);
            }
        }
        abort(404);
    }
}
