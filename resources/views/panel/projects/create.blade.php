@extends('layouts.app', ['title' => $title ?? 'Proyectos - Crear'])

@section('page-header')
    <div class="page-wrapper">
        <!-- Page-header start -->
        <div class="page-header card">
            <div class="row align-items-end">
                <div class="col-lg-8">
                    <div class="page-header-title">
                        <i class="ti-plus bg-c-blue"></i>
                        <div class="d-inline">
                            <h4>Crear Proyecto</h4>
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
                        <li class="breadcrumb-item"><a href="{{ route('projects.index') }}">Proyectos</a>
                        </li>
                        <li class="breadcrumb-item"><a href="{{ route('projects.create') }}">Crear</a>
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
                <form method="POST" action="{{ route('projects.store') }}" name="form-project-create" id="form-project-create" enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" name="action" id="action">
                    <input type="hidden" name="annexes" id="annexes">
                    <input type="hidden" name="department" id="department">
                    <div class="form-group row">
                        <div class="col-sm-6">
                            <label class="col-form-label">Nombre</label>
                            <input type="text" name="name" id="name" class="form-control">
                            <div class="col-form-label has-danger-name"></div>
                        </div>
                        <div class="col-sm-6">
                            <label class="col-form-label">Proceso</label>
                            <select name="process" id="process" class="form-control process">
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
                    <div class="add-input-content"></div>
                    <div class="alert alert-danger  has-danger-annexed" role="alert"></div>
                    <div class="form-group row">
                        <div class="col-sm-6">
                            <button type="button" class="btn btn-warning btn-project">Crear proyecto</button>
                        </div>
                        <div class="col-sm-6">
                            <button type="button" class="btn btn-success float-right btn-project-send" disabled>Enviar a departamento</button>
                        </div>
                    </div>


                </form>
            </div>
        </div>
        <!-- Basic Form Inputs card end -->
@endsection

@section('script-content')
<script>
    $(function () {

        $('body').on('change', '.process', function(e) {
            e.preventDefault();
            if ($('#process').val() == '' || $('#process').val() == null) {
                $('.has-danger-process').text('El proceso es requerido').css("color", "red");
            }else{
                $('.has-danger-process').text('');
                axios.post('{{ route('project.annexed') }}', {
                    id: $('#process').val(),
                }).then(response => {
                    if(response.data.success){
                        notify(response.data.message, 'success', '3000', 'top', 'right');
                        $('.btn-project-send').prop("disabled", false).text(`Enviar al siguiente departamento ${response.data.wordflow.name}`);
                        $('#department').val(response.data.wordflow.id);
                        $('.add-input-content').html(function(){
                            var items = '';
                            items += `<h4 class="sub-title">Axenos del proceso ${response.data.annexes.name}
                                            <div class="label-main">
                                                <label class="label label-inverse add-annexed" style="cursor: pointer !important;">Nuevo Anexo</label>
                                            </div>
                                        </h4>`;
                            /* for(i=1; i<=response.data.annexes.annexed; i++){
                                items += `<div class="form-group row">
                                                <label class="col-sm-2 col-form-label">Anexo #${i}</label>
                                                <div class="col-sm-8">
                                                    <input type="file" class="form-control">
                                                </div>
                                            </div>
                                           <div class="col-sm-2">
                                                <a href="#" class="btn btn-danger btn-xs mr-1 delete">
                                                    <i class="ti-trash"></i>
                                                </a>
                                            </div>`;
                            };
                            items += `<div class="add-input-content-new"></div>`; */
                            return items;
                        });
                    }
                }).catch(error => {
                    if (error.response) {
                        if(error.response.status === 422){
                            notify('Error, Intente nuevamente mas tarde.', 'danger', '5000', 'top', 'right');
                        }else{
                            notify('Error, Intente nuevamente mas tarde.', 'danger', '5000', 'top', 'right');
                        }
                    }else{
                        notify('Error, Intente nuevamente mas tarde.', 'danger', '5000', 'top', 'right');
                    }
                });
            }
        });

        $('body').on('click', '.add-annexed', function(e) {
            var annexes = document.createElement('div');
            annexes.setAttribute('class', 'div-content');
            var class_div = $('.div-content').length+1;
            content = '';
            var content = '';
            content += `<div class="form-group row">
                            <label class="col-sm-2 col-form-label">Anexo # ${class_div}</label>
                            <div class="col-sm-8">
                                <input type="file"name="annexed[]" id="annexed" class="form-control-file">
                            </div>
                            <div class="col-sm-2">
                                <a href="#" class="btn btn-danger btn-xs mr-1 delete" data-annexed="${class_div}">
                                    <i class="ti-trash"></i>
                                </a>
                            </div>
                        </div>`;

            $(annexes).append(content);
            $('.add-input-content').append(annexes);
            $('#annexes').val(class_div);
        });

        $('body').on('click', '.add-input-content  .delete', function(e) {
            e.preventDefault();
            $(this).parents('.div-content').remove();
            $('#annexes').val($(this).data('annexed')-1);
        });

        $('.has-danger-annexed').hide();
        $('.has-danger-departments').hide();
        $('body').on('click', '.btn-project', function(e) {
            //e.preventDefault();
            //alert('aqui');
            const formData = document.getElementById("form-project-create");
            $('#action').val('created');
            //formData.addEventListener("submit", function (event) {
                event.preventDefault();
                let data = new FormData(formData);
                axios({
                    method:'post',
                    url:'{{ route('projects.store') }}',
                    data:data,
                    headers: {
                        'Accept': 'application/json',
                        'Content-Type': 'multipart/form-data'
                    }
                }).then((response)=>{
                    if(response.data.success){
                        notify(response.data.message, 'success', '3000', 'top', 'right');
                        $('#form-project-create').trigger("reset");
                        $('.btn-project').prop("disabled", false).text('Registrar');
                        $('div.col-form-label').text('');
                        setTimeout(function () {location.reload(); }, 3000);
                    }
                }).catch((error) => {
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
                            if (error.response.data.errors.annexed) {
                                $('.has-danger-annexed').text('' + error.response.data.errors.annexed + '').show();
                            }else{
                                $('.has-danger-annexed').text('').hide();
                            }
                        }else{
                            notify('Error, Intente nuevamente mas tarde.', 'danger', '5000', 'top', 'right');
                        }
                    }else{
                        notify('Error, Intente nuevamente mas tarde.', 'danger', '5000', 'top', 'right');
                    }
                    $('.btn-project').prop("disabled", false).text('Registrar');
                });
            //});
        });

        $('body').on('click', '.btn-project-send', function(e) {
            //e.preventDefault();
            //alert('aqui');
            const formData = document.getElementById("form-project-create");
            $('#action').val('send');
            $('#department').val();

            //formData.addEventListener("submit", function (event) {
                event.preventDefault();
                let data = new FormData(formData);
                axios({
                    method:'post',
                    url:'{{ route('projects.store') }}',
                    data:data,
                    headers: {
                        'Accept': 'application/json',
                        'Content-Type': 'multipart/form-data'
                    }
                }).then((response)=>{
                    if(response.data.success){
                        notify(response.data.message, 'success', '3000', 'top', 'right');
                        $('#form-project-create').trigger("reset");
                        $('.btn-project').prop("disabled", false).text('Registrar');
                        $('div.col-form-label').text('');
                        setTimeout(function () {location.reload(); }, 3000);
                    }
                }).catch((error) => {
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
                            if (error.response.data.errors.annexed) {
                                $('.has-danger-annexed').text('' + error.response.data.errors.annexed + '').show();
                            }else{
                                $('.has-danger-annexed').text('').hide();
                            }
                        }else{
                            notify('Error, Intente nuevamente mas tarde.', 'danger', '5000', 'top', 'right');
                        }
                    }else{
                        notify('Error, Intente nuevamente mas tarde.', 'danger', '5000', 'top', 'right');
                    }
                    $('.btn-project').prop("disabled", false).text('Registrar');
                });
            //});
        });
});
</script>
@endsection
