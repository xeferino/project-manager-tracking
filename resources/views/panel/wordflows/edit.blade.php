@extends('layouts.app', ['title' => $title ?? 'Flujos - Editar'])

@section('page-header')
    <div class="page-wrapper">
        <!-- Page-header start -->
        <div class="page-header card">
            <div class="row align-items-end">
                <div class="col-lg-8">
                    <div class="page-header-title">
                        <i class="ti-plus bg-c-blue"></i>
                        <div class="d-inline">
                            <h4>Crear flujo</h4>
                            <span>Agregue la informacion solicita en el formulario de Edicion.</span>
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
                        <li class="breadcrumb-item"><a href="{{ route('wordflows.index') }}">Flujos</a>
                        </li>
                        <li class="breadcrumb-item"><a href="{{ route('wordflows.edit', ['wordflow' => $wordflow->id]) }}">Editar</a>
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
                <h5>Fomulario de actualizacion</h5>
                <div class="card-header-right">
                    <i class="icofont icofont-spinner-alt-5"></i>
                </div>
                <div class="card-header-right">
                    <i class="icofont icofont-spinner-alt-5"></i>
                </div>
            </div>
            <div class="card-block">
                <form method="POST" action="" name="form-wordflow-create-steps" id="form-wordflow-create-steps">
                    @csrf
                    <h4 class="sub-title">Pasos del flujo
                        <div class="label-main">
                            <label class="label label-inverse" style="cursor: pointer !important;">Pasos del {{ $wordflow->name }}</label>
                        </div>
                    </h4>
                    <div class="form-group row">
                        <label class="col-sm-2 col-form-label">Departamento</label>
                        <div class="col-sm-8">
                            <select name="departments" id="departments" class="form-control">
                                <option value="0">.::Seleccione::.</option>
                                @foreach ( $departments as $department )
                                    <option value="{{ $department->id }}">{{ $department->name }}</option>
                                @endforeach
                            </select>
                            <div class="col-form-label has-danger-departments"></div>
                        </div>
                        <div class="col-sm-2">
                            <button type="submit" class="btn btn-primary float-right" id="btn-step"><i class="ti-plus"></i></button>
                        </div>
                        <div class="col-sm-12">
                            <br>
                            <div class="add-input-content"></div>
                        </div>
                    </div>
                    <div class="alert alert-danger  has-danger-step" role="alert" style="dispaly:none;"></div>
                </form>

                <h4 class="sub-title">Informacion requerida</h4>
                <form method="POST" action="{{ route('wordflows.store') }}" name="form-wordflow-create" id="form-wordflow-create">
                    <input type="hidden" name="steps" id="steps">
                    @csrf
                    <div class="form-group row">
                        <div class="col-sm-6">
                            <label class="col-form-label">Nombre</label>
                            <input type="text" name="name" id="name" class="form-control">
                            <div class="col-form-label has-danger-name"></div>
                        </div>
                        <div class="col-sm-6">
                            <label class="col-form-label">Proceso</label>
                            <select name="process" id="process" class="form-control">
                                <option value="">.::Seleccione::.</option>
                                @foreach ( $processes as $process )
                                    <option value="{{ $process->id }}">{{ $process->name }}</option>
                                @endforeach
                            </select>
                            <div class="col-form-label has-danger-process"></div>
                        </div>
                        <div class="col-sm-12">
                            <label class="col-form-label">Descripcion</label>
                            <textarea name="description" id="description" cols="10" rows="5" class="form-control"></textarea>
                            <div class="col-form-label has-danger-description"></div>
                        </div>
                    </div>
                    <button type="submit" class="btn btn-primary float-right btn-wordflow">Registrar</button>
                </form>
            </div>
        </div>
        <!-- Basic Form Inputs card end -->
@endsection

@section('script-content')
<script>
    $(function () {

        const pasos = [];

        $("#form-wordflow-create-steps").submit(function( event ) {
            event.preventDefault();
            department = $("#departments option:selected").text();
            depart = $("#departments").val();
            step = $('#departments').val();
            if(depart == 0){
                $('.has-danger-departments').text('seleccione un departamento para crear el paso').show().css("color", "red");
            }else{
                var valid = 0;
                $.each(pasos, function(key, value) {
                    if(value.department == department){
                        valid = 1;
                    }
                });
                if(valid){
                    $('.has-danger-departments').text('el departamento seleccionado, ya esta igresado en los pasos para la creacion del flujograma para este proceso').show().css("color", "red");
                }else{
                    $('.has-danger-departments').text('').hide();
                    pasos.push({
                        'step' : step,
                        'department' : department
                    });
                    console.log(pasos);
                    //writeSteps(pasos);
                    $('#steps').val(JSON.stringify(pasos));
                }
            }
        });

        $('.has-danger-step').hide();
        $('.has-danger-departments').hide();
        $("#form-wordflow-create").submit(function( event ) {
            event.preventDefault();
            $('.btn-wordflow').prop("disabled", true).text('Enviando...');
            axios.post('{{ route('wordflows.store') }}', $(this).serialize(), {
            }).then(response => {
                if(response.data.success){
                    notify(response.data.message, 'success', '3000', 'top', 'right');
                    $('#form-wordflow-create').trigger("reset");
                    $('.btn-wordflow').prop("disabled", false).text('Registrar');
                    $('div.col-form-label').text('');
                    setTimeout(function () {location.reload(); }, 3000);
                }
            }).catch(error => {
                if (error.response) {
                        if(error.response.status === 422){
                            var err = error.response.data.errors;
                            if (error.response.data.errors.name) {
                                $('.has-danger-name').text('' + error.response.data.errors.name + '').css("color", "red");
                            }else{
                                $('.has-danger-name').text('');
                            }

                            if (error.response.data.errors.process) {
                                $('.has-danger-process').text('' + error.response.data.errors.process + '').css("color", "red");
                            }else{
                                $('.has-danger-process').text('');
                            }

                            if (error.response.data.errors.description) {
                                $('.has-danger-description').text('' + error.response.data.errors.description + '').css("color", "red");
                            }else{
                                $('.has-danger-description').text('');
                            }
                            if (error.response.data.errors.steps) {
                                $('.has-danger-step').text('' + error.response.data.errors.steps + '').show();
                            }else{
                                $('.has-danger-step').text('').hide();
                            }
                        }else{
                            notify('Error, Intente nuevamente mas tarde.', 'danger', '5000', 'top', 'right');
                        }
                    }else{
                        notify('Error, Intente nuevamente mas tarde.', 'danger', '5000', 'top', 'right');
                    }
                    $('.btn-wordflow').prop("disabled", false).text('Registrar');
            });
        });

        $('body').on('click', '.delete', function(e){
            var index = $(this).data('id');
            pasos.splice(index, 1);
            console.log(pasos);
            writeSteps(pasos);
        });
});
    function writeSteps(pasos){
        $('.add-input-content').html(function(){
            var items = '';
            items += `<div class="card-block table-border-style">
                        <div class="table-responsive">
                            <table class="table table-hover table-user">
                                <thead>
                                    <th>Item</th>
                                    <th>Departamento</th>
                                    <th>Aciones</th>
                                </thead>
                                <tbody>`;
                                    var i = 1;
                                    $.each(pasos, function(key, value) {
                            items +=     `<tr>
                                                <td>Paso #${i++}</td>
                                                <td>${value.department}</td>
                                                <td align="center">
                                                    <button type="button" class="btn btn-danger delete" data-id="${key}">
                                                        <i class="ti-trash"></i>
                                                    </button>
                                                </td>
                                            </tr>`;
                                        });
                        items +=`</tbody>
                            </table>
                        </div>
                    </div>`;
            return items;
        });
    }
</script>
@endsection
