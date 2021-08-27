@extends('layouts.app', ['title' => $title ?? 'Usuarios'])

@section('page-header')
    <div class="page-wrapper">
        <!-- Page-header start -->
        <div class="page-header card">
            <div class="row align-items-end">
                <div class="col-lg-8">
                    <div class="page-header-title">
                        <i class="ti-pencil bg-c-blue"></i>
                        <div class="d-inline">
                            <h4>Perfil de usuario</h4>
                            <span>Actualice la informacion del detalle de su perfil en el sistema.</span>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4">
                    <div class="page-header-breadcrumb">
                    <ul class="breadcrumb-title">
                        <li class="breadcrumb-item">
                            <a href="{{ route('dashboard') }}">
                                <i class="icofont icofont-home"></i>
                            </a>
                        </li>
                        <li class="breadcrumb-item"><a href="{{ route('users.show', ['user' =>  Auth::user()->id]) }}">Perfil</a>
                        </li>
                        <li class="breadcrumb-item"><a href="{{ route('users.show', ['user' =>  Auth::user()->id]) }}">Detalle</a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('page-content')
<div class="row">
    <div class="col-sm-12">
        <!-- Basic Form Inputs card start -->
        <div class="card">
            <div class="card-header">
                <h5>Detalles de perfil</h5>
                <div class="card-header-right">
                    <i class="icofont icofont-spinner-alt-5"></i>
                </div>
                <div class="card-header-right">
                    <i class="icofont icofont-spinner-alt-5"></i>
                </div>
            </div>
            <div class="card-block">
                <h4 class="sub-title">Informacion de perfil</h4>
                <form method="POST" action="{{ route('users.update', ['user' =>  Auth::user()->id]) }}" name="form-user-edit" id="form-user-edit">
                    @csrf
                    @method('PUT')
                    <div class="form-group row">
                        <div class="col-sm-6">
                            <label class="col-form-label">Nombres</label>
                            <input type="text" name="name" id="name" value="{{Auth::user()->name }}" class="form-control">
                            <div class="col-form-label has-danger-name"></div>
                        </div>
                        <div class="col-sm-6">
                            <label class="col-form-label">Apellidos</label>
                            <input type="text" name="surname" id="surname" value="{{Auth::user()->surname }}" class="form-control">
                            <div class="col-form-label has-danger-surname"></div>
                        </div>
                        <div class="col-sm-6">
                            <label class="col-form-label">Email</label>
                            <input type="text" name="email" id="email" value="{{Auth::user()->email }}" class="form-control">
                            <div class="col-form-label has-danger-email"></div>
                        </div>
                        <div class="col-sm-6">
                            <label class="col-form-label">Departamento</label>
                            <select name="department" id="department" class="form-control">
                                <option value="{{ Auth::user()->department->id }}">{{Auth::user()->department->name }}</option>
                            </select>
                            <div class="col-form-label has-danger-department"></div>
                        </div>
                        <div class="col-sm-6">
                            <label class="col-form-label">Role</label>
                            <select name="role" id="role" class="form-control">
                                @if (Auth::user()->role === 'root')
                                    <option value="root">Super Admin</option>
                                @endif
                                @if (Auth::user()->role === 'administer')
                                    <option value="administer">Administrador</option>
                                @endif
                                @if (Auth::user()->role === 'department')
                                    <option value="department">Departamento</option>
                                @endif
                            </select>
                            <div class="col-form-label has-danger-role"></div>
                        </div>
                        <div class="col-sm-6">
                            <label class="col-form-label">Estatus</label>
                            <select name="active" id="active" class="form-control">
                                @if (Auth::user()->active === 1)
                                    <option value="1">Activo</option>
                                @endif
                                @if (Auth::user()->active === 0)
                                    <option value="0">Inactivo</option>
                                @endif
                            </select>
                            <div class="col-form-label has-danger-active"></div>
                        </div>
                        <div class="col-sm-6">
                            <label class="col-form-label">Contrase&ntilde;a</label>
                            <input type="password" name="password" id="password" class="form-control">
                            <div class="col-form-label has-danger-password"></div>
                        </div>
                        <div class="col-sm-6">
                            <label class="col-form-label">Confirmar Contrase&ntilde;a</label>
                            <input type="password" name="cpassword" id="cpassword" class="form-control">
                            <div class="col-form-label has-danger-cpassword"></div>
                        </div>
                    </div>
                    <button type="submit" class="btn btn-primary float-right btn-user">Actualizar</button>
                </form>
            </div>
        </div>
        <!-- Basic Form Inputs card end -->
@endsection

@section('script-content')
<script>
    $(function () {
        $("#form-user-edit").submit(function( event ) {
            event.preventDefault();
            $('.btn-user').prop("disabled", true).text('Enviando...');
            axios.put($("#form-user-edit").attr("action"), $(this).serialize(), {
            }).then(response => {
                if(response.data.success){
                    notify(response.data.message, 'success', '3000', 'top', 'right');
                    $('#form-user-edit').trigger("reset");
                    $('.btn-user').prop("disabled", false).text('Actualizar');
                    $('div.col-form-label').text('');
                    setTimeout(function () {location.reload(); }, 3000);
                }
            }).catch(error => {
                if (error.response) {
                        if(error.response.status === 422){
                            var err = error.response.data.errors;
                            /* $.each(err, function( key, value) {
                                notify(value, 'danger', '5000', 'top', 'right');
                            }); */
                            if (error.response.data.errors.name) {
                                $('.has-danger-name').text('' + error.response.data.errors.name + '').css("color", "red");
                            }else{
                                $('.has-danger-name').text('');
                            }
                            if (error.response.data.errors.surname) {
                                $('.has-danger-surname').text('' + error.response.data.errors.surname + '').css("color", "red");
                            }else{
                                $('.has-danger-surname').text('');
                            }
                            if (error.response.data.errors.email) {
                                $('.has-danger-email').text('' + error.response.data.errors.email + '').css("color", "red");
                            }else{
                                $('.has-danger-email').text('');
                            }
                            if (error.response.data.errors.role) {
                                $('.has-danger-role').text('' + error.response.data.errors.role + '').css("color", "red");
                            }else{
                                $('.has-danger-role').text('');
                            }
                            if (error.response.data.errors.active) {
                                $('.has-danger-active').text('' + error.response.data.errors.active + '').css("color", "red");
                            }else{
                                $('.has-danger-active').text('');
                            }
                            if (error.response.data.errors.department) {
                                $('.has-danger-department').text('' + error.response.data.errors.department + '').css("color", "red");
                            }else{
                                $('.has-danger-department').text('');
                            }
                            if (error.response.data.errors.password) {
                                $('.has-danger-password').text('' + error.response.data.errors.password + '').css("color", "red");
                            }else{
                                $('.has-danger-password').text('');
                            }
                            if (error.response.data.errors.cpassword) {
                                $('.has-danger-cpassword').text('' + error.response.data.errors.cpassword + '').css("color", "red");
                            }else{
                                $('.has-danger-cpassword').text('');
                            }
                        }else{
                            notify('Error, Intente nuevamente mas tarde.', 'danger', '5000', 'top', 'right');
                        }
                    }else{
                        notify('Error, Intente nuevamente mas tarde.', 'danger', '5000', 'top', 'right');
                    }
                    $('.btn-user').prop("disabled", false).text('Actualizar');
            });
        });
    });
</script>
@endsection
