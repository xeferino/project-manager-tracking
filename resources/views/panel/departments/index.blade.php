@extends('layouts.app', ['title' => $title ?? 'departamentos'])

@section('page-header')
    <div class="page-wrapper">
        <!-- Page-header start -->
        <div class="page-header card">
            <div class="row align-items-end">
                <div class="col-lg-8">
                    <div class="page-header-title">
                        <i class="icofont icofont-table bg-c-blue"></i>
                        <div class="d-inline">
                            <h4>Listado de departamentos</h4>
                            <span>informacion de los departamentos que se encuentran en el sistema.</span>
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
                        <li class="breadcrumb-item"><a href="{{ route('departments.index') }}">departamentos</a>
                        </li>
                        <li class="breadcrumb-item"><a href="{{ route('departments.index') }}">Listado</a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('page-content')
    <!-- Hover table card start -->
    <div class="card">
        <div class="card-header">
            <h5>Tabla de departamentos</h5>
            <div class="card-header-right">
                <a href="{{ route('departments.create') }}" class="btn btn-primary">Nuevo Departamento</a>
            </div>
        </div>
        <div class="card-block table-border-style">
            <div class="table-responsive">
                <table class="table table-hover table-department">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Nombre</th>
                            <th>Estatus</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <!-- Hover table card end -->
@endsection

@section('script-content')
<script>
    $(function () {
        /*DataTables*/
        var table = $('.table-department').DataTable({
            processing: true,
            serverSide: true,
            "language": {
                "decimal":        "",
                "info":           "Mostrando _START_ - _END_ de un total _TOTAL_ departamentos",
                "infoEmpty":      "Mostrando 0 para 0 de 0 departamentos",
                "infoFiltered":   "(Filtrado para un total de _MAX_ departamentos)",
                "infoPostFix":    "",
                "thousands":      ",",
                "lengthMenu":     "Mostrar _MENU_ Registros",
                "loadingRecords": "Cargando...",
                "processing":     "Procesando...",
                "search":         "Buscar:",
                "zeroRecords":    "No hay coicidencias de registros en la busqueda",
                "paginate": {
                    "first":      "Primero",
                    "last":       "Ultimo",
                    "next":       "Siguiente",
                    "previous":   "Anterior"
                },
                "aria": {
                    "sortAscending":  ": activate to sort column ascending",
                    "sortDescending": ": activate to sort column descending"
                }
            },
            ajax: "{{ route('departments.index') }}",
            columns: [
                {data: 'id', name: 'id'},
                {data: 'name', name: 'name'},
                {data: 'active', name: 'active'},
                {data: 'action', name: 'action', orderable: false, searchable: false},
            ]
        });
        /*DataTables*/

         /*alert-Department-delete*/
         $('body').on('click', '.deleteDepartment', function () {
            var url = $(this).data("url");
            swal({
                    title: '¿Desea eliminar el Departamento?',
                    text: "Recuerde que esta acción no tiene revera.",
                    type: 'error',
                    icon : "error",
                    buttons:{
                        cancel: {
                            visible: true,
                            text : 'Cancelar',
                            className: 'btn btn-default'
                        },
                        confirm: {
                            text : 'Eliminar',
                            className : 'btn btn-danger',
                            showLoaderOnConfirm: true,
                        }
                    }
                }).then((confirm) => {
                    if (confirm) {
                        axios.delete(url, {
                                }).then(response => {
                                    if(response.data.success){
                                        notify(response.data.message, 'success', '3000', 'top', 'right');
                                        table.ajax.reload();
                                    }
                                }).catch(error => {
                                    if (error.response) {
                                        if(error.response.status === 403){
                                            notify(error.response.data.message, 'success', '3000', 'top', 'right');
                                        }else{
                                            notify('Error, Intente nuevamente mas tarde.', 'danger', '5000', 'top', 'right');
                                        }
                                    }else{
                                        notify('Error, Intente nuevamente mas tarde.', 'danger', '5000', 'top', 'right');
                                    }
                                });
                    } else {
                        swal.close();
                    }
                });
        });
        /*alert-Department-delete*/
    });
</script>
@endsection
