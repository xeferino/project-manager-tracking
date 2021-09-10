@extends('layouts.app', ['title' => $title ?? 'Proyectos - Anexos'])

@section('page-header')
    <div class="page-wrapper">
        <!-- Page-header start -->
        <div class="page-header card">
            <div class="row align-items-end">
                <div class="col-lg-8">
                    <div class="page-header-title">
                        <i class="icofont icofont-eye bg-c-blue"></i>
                        <div class="d-inline">
                            <h4>Anexos {{ $project->name }}</h4>
                            <span>informacion de los anexos ({{ $project->Annexes->count() }}) del proyecto.</span>
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
                        <li class="breadcrumb-item"><a href="{{ route('projects.index') }}">Listado</a>
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
            <h5>Informacion</h5>
        </div>
        <div class="card-block table-border-style">
            <div class="table-responsive">
                <table class="table table-hover table-user">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Nombre Original</th>
                            <th>Nombre</th>
                            <th>Tama&ntilde;o</th>
                            <th>Tipo</th>
                            <th>Ubicacion</th>
                            <th>Observacion</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php
                            $i=1;
                        @endphp
                        @foreach ($project->Annexes as $annexed)
                            <tr>
                                <td>{{ $i++ }}</td>
                                <td>{{ $annexed->file_name_original }}</td>
                                <td>{{ $annexed->file_name }}</td>
                                <td>{{ $annexed->file_size }}</td>
                                <td>{{ $annexed->file_type }}</td>
                                <td>{{ $annexed->file_path }}</td>
                                <td>{{ $annexed->observation }}</td>
                                <td>
                                    <a href="{{ route('projects.download.annexed', ['project' => $annexed->id]) }}" data-toggle="tooltip" data-placement="right" title="Descargar" class="btn btn-warning btn-xs mr-1">
                                        <i class="ti-import"></i>
                                    </a>
                                    <a href="#" data-toggle="tooltip" data-placement="right" title="Ver" data-id="{{ $annexed->id }}" class="btn btn-secondary btn-xs mr-1 viewAnnexed">
                                        <i class="ti-eye"></i>
                                    </a>
                                </td>
                            </tr>
                            <div class="modal fade modal-icon modal-annexed_{{ $annexed->id }}" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                                <div class="modal-dialog" role="document">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h4 class="modal-title" id="myModalLabel">{{ $annexed->file_name }}</h4>
                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                <span aria-hidden="true">&times;</span>
                                            </button>
                                        </div>
                                        <div class="modal-body">
                                            <div class="row">
                                                <div class="col-md-12 text-center">
                                                    <div class="icon-list-demo">
                                                        <img src="{{ asset('assets/images/auth/logo-dark.png') }}" alt="logo.png">
                                                    </div>
                                                </div>
                                                <div class="col-md-12 col-sm-12 text-center">
                                                    <iframe
                                                        src="{{ asset($annexed->file_path.$annexed->file_name) }}"
                                                        class="relative flex items-start"
                                                        width="400"
                                                        width="400"
                                                        frameborder="0">
                                                    </iframe>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                        </div>

                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <!-- Hover table card end -->
@endsection

@section('script-content')
<script>
    $(document).ready(function() {
        $('body').on('click', '.viewAnnexed', function () {
            var id = $(this).data('id');
            $('.modal-annexed_'+id).modal('show');
        });
    });
</script>
@endsection
