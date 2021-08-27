@extends('layouts.app', ['title' => $title ?? 'Procesos'])

@section('page-header')
    <div class="page-wrapper">
        <!-- Page-header start -->
        <div class="page-header card">
            <div class="row align-items-end">
                <div class="col-lg-8">
                    <div class="page-header-title">
                        <i class="ti-plus bg-c-blue"></i>
                        <div class="d-inline">
                            <h4>Crear proceso</h4>
                            <span>Agregue la informacion solicita en el formulario de registro.</span>
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
                        <li class="breadcrumb-item"><a href="{{ route('processes.index') }}">Procesos</a>
                        </li>
                        <li class="breadcrumb-item"><a href="{{ route('processes.create') }}">Crear</a>
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
                <h5>Fomulario de registro</h5>
                <div class="card-header-right">
                    <i class="icofont icofont-spinner-alt-5"></i>
                </div>
                <div class="card-header-right">
                    <i class="icofont icofont-spinner-alt-5"></i>
                </div>
            </div>
            <div class="card-block">
                <h4 class="sub-title">Informacion requerida</h4>
                <form method="POST" action="{{ route('processes.store') }}" name="form-process-create" id="form-process-create">
                    @csrf
                    <div class="form-group row">
                        <div class="col-sm-6">
                            <label class="col-form-label">Nombre</label>
                            <input type="text" name="name" id="name" class="form-control">
                            <div class="col-form-label has-danger-name"></div>
                        </div>
                        <div class="col-sm-6">
                            <label class="col-form-label">Anexo</label>
                            <input type="text" name="annexed" id="annexed" class="form-control">
                            <div class="col-form-label has-danger-annexed"></div>
                        </div>
                        <div class="col-sm-6">
                            <label class="col-form-label">Estatus</label>
                            <select name="active" id="active" class="form-control">
                                <option value="">.::Seleccione::.</option>
                                <option value="1">Activo</option>
                                <option value="0">Inactivo</option>
                            </select>
                            <div class="col-form-label has-danger-active"></div>
                        </div>
                    </div>
                    <button type="submit" class="btn btn-primary float-right btn-process">Registrar</button>
                </form>
            </div>
        </div>
        <!-- Basic Form Inputs card end -->
@endsection

@section('script-content')
<script>
    $(function () {
        $("#form-process-create").submit(function( event ) {
            event.preventDefault();
            $('.btn-process').prop("disabled", true).text('Enviando...');
            axios.post('{{ route('processes.store') }}', $(this).serialize(), {
            }).then(response => {
                if(response.data.success){
                    notify(response.data.message, 'success', '3000', 'top', 'right');
                    $('#form-process-create').trigger("reset");
                    $('.btn-process').prop("disabled", false).text('Registrar');
                    $('div.col-form-label').text('');
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

                            if (error.response.data.errors.annexed) {
                                $('.has-danger-annexed').text('' + error.response.data.errors.annexed + '').css("color", "red");
                            }else{
                                $('.has-danger-annexed').text('');
                            }

                            if (error.response.data.errors.active) {
                                $('.has-danger-active').text('' + error.response.data.errors.active + '').css("color", "red");
                            }else{
                                $('.has-danger-active').text('');
                            }
                        }else{
                            notify('Error, Intente nuevamente mas tarde.', 'danger', '5000', 'top', 'right');
                        }
                    }else{
                        notify('Error, Intente nuevamente mas tarde.', 'danger', '5000', 'top', 'right');
                    }
                    $('.btn-process').prop("disabled", false).text('Registrar');
            });
        });
    });
</script>
@endsection
