@extends('layouts.app', ['title' => $title ?? 'Proyectos - Bitacora'])

@section('page-header')
    <div class="page-wrapper">
        <!-- Page-header start -->
        <div class="page-header card">
            <div class="row align-items-end">
                <div class="col-lg-8">
                    <div class="page-header-title">
                        <i class="icofont icofont-eye bg-c-blue"></i>
                        <div class="d-inline">
                            <h4>Bitacora {{ $project->name }}</h4>
                            <span>informacion de la biacora del proyecto.</span>
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
                            <th>Observacion</th>
                            <th>Usuario</th>
                            <th>Enviado</th>
                            <th>Recibido</th>
                            <th>Anexos</th>
                            <th>Estatus</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php
                            $i=1;
                        @endphp
                        @foreach ($project->Binnacles as $binnacle)
                            <tr>
                                <td>{{ $i++ }}</td>
                                <td>{{ $binnacle->observation }}</td>
                                <td>{{ $binnacle->User->name. ' ' .$binnacle->User->surname }}</td>
                                <td>{{ $binnacle->Department($binnacle->department_send_id) }}</td>
                                <td>{{ $binnacle->Department($binnacle->department_received_id) }}</td>
                                <td>{{ $binnacle->annexes }}</td>
                                <td>{{ $binnacle->status }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <!-- Hover table card end -->
@endsection
