@extends('layouts.app', ['title' => $title ?? 'Dashboard'])

@section('page-content')
<div class="row">
    <!-- card1 start -->
    <div class="col-md-6 col-xl-3">
        <div class="card widget-card-1">
            <div class="card-block-small">
                <i class="icofont icofont-pie-chart bg-c-blue card1-icon"></i>
                <span class="text-c-blue f-w-600">Use space</span>
                <h4>49/50GB</h4>
                <div>
                    <span class="f-left m-t-10 text-muted">
                        <i class="text-c-blue f-16 icofont icofont-warning m-r-10"></i>Get more space
                    </span>
                </div>
            </div>
        </div>
    </div>
    <!-- card1 end -->
    <!-- card1 start -->
    <div class="col-md-6 col-xl-3">
        <div class="card widget-card-1">
            <div class="card-block-small">
                <i class="icofont icofont-ui-home bg-c-pink card1-icon"></i>
                <span class="text-c-pink f-w-600">Revenue</span>
                <h4>$23,589</h4>
                <div>
                    <span class="f-left m-t-10 text-muted">
                        <i class="text-c-pink f-16 icofont icofont-calendar m-r-10"></i>Last 24 hours
                    </span>
                </div>
            </div>
        </div>
    </div>
    <!-- card1 end -->
    <!-- card1 start -->
    <div class="col-md-6 col-xl-3">
        <div class="card widget-card-1">
            <div class="card-block-small">
                <i class="icofont icofont-warning-alt bg-c-green card1-icon"></i>
                <span class="text-c-green f-w-600">Fixed issue</span>
                <h4>45</h4>
                <div>
                    <span class="f-left m-t-10 text-muted">
                        <i class="text-c-green f-16 icofont icofont-tag m-r-10"></i>Tracked via microsoft
                    </span>
                </div>
            </div>
        </div>
    </div>
    <!-- card1 end -->
    <!-- card1 start -->
    <div class="col-md-6 col-xl-3">
        <div class="card widget-card-1">
            <div class="card-block-small">
                <i class="icofont icofont-social-twitter bg-c-yellow card1-icon"></i>
                <span class="text-c-yellow f-w-600">Followers</span>
                <h4>+562</h4>
                <div>
                    <span class="f-left m-t-10 text-muted">
                        <i class="text-c-yellow f-16 icofont icofont-refresh m-r-10"></i>Just update
                    </span>
                </div>
            </div>
        </div>
    </div>
    <!-- card1 end -->
    <!-- Statestics Start -->
    <div class="col-md-12 col-xl-12">
        <div class="card">
            <div class="card-header">
                <h5>Statestics</h5>
                <div class="card-header-left ">
                </div>
                <div class="card-header-right">
                    <ul class="list-unstyled card-option">
                        <li><i class="icofont icofont-simple-left "></i></li>
                        <li><i class="icofont icofont-maximize full-card"></i></li>
                        <li><i class="icofont icofont-minus minimize-card"></i></li>
                        <li><i class="icofont icofont-refresh reload-card"></i></li>
                        <li><i class="icofont icofont-error close-card"></i></li>
                    </ul>
                </div>
            </div>
            <div class="card-block">
                <div id="statestics-chart" style="height:517px;"></div>
            </div>
        </div>
    </div>

    <!-- Email Sent End -->
    <!-- Data widget start -->
    <div class="col-md-12 col-xl-12">
        <div class="card project-task">
            <div class="card-header">
                <div class="card-header-left ">
                    <h5>Time spent : project &amp; task</h5>
                </div>
                <div class="card-header-right">
                    <ul class="list-unstyled card-option">
                        <li><i class="icofont icofont-simple-left "></i></li>
                        <li><i class="icofont icofont-maximize full-card"></i></li>
                        <li><i class="icofont icofont-minus minimize-card"></i></li>
                        <li><i class="icofont icofont-refresh reload-card"></i></li>
                        <li><i class="icofont icofont-error close-card"></i></li>
                    </ul>
                </div>
            </div>
            <div class="card-block p-b-10">
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>Project & Task</th>
                                <th>Time Spents</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>
                                    <div class="task-contain">
                                        <h6 class="bg-c-blue d-inline-block text-center">U</h6>
                                        <p class="d-inline-block m-l-20">UI Design</p>
                                    </div>
                                </td>
                                <td>
                                    <p class="d-inline-block m-r-20">4 : 36</p>
                                    <div class="progress d-inline-block">
                                        <div class="progress-bar bg-c-blue" role="progressbar" aria-valuemin="0" aria-valuemax="100" style="width:80%">
                                        </div>
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <div class="task-contain">
                                        <h6 class="bg-c-pink d-inline-block text-center">R</h6>
                                        <p class="d-inline-block m-l-20">Redesign Android App</p>
                                    </div>
                                </td>
                                <td>
                                    <p class="d-inline-block m-r-20">4 : 36</p>
                                    <div class="progress d-inline-block">
                                        <div class="progress-bar bg-c-pink" role="progressbar" aria-valuemin="0" aria-valuemax="100" style="width:60%">
                                        </div>
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <div class="task-contain">
                                        <h6 class="bg-c-yellow d-inline-block text-center">L</h6>
                                        <p class="d-inline-block m-l-20">Logo Design</p>
                                    </div>
                                </td>
                                <td>
                                    <p class="d-inline-block m-r-20">4 : 36</p>
                                    <div class="progress d-inline-block">
                                        <div class="progress-bar bg-c-yellow" role="progressbar" aria-valuemin="0" aria-valuemax="100" style="width:50%">
                                        </div>
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <div class="task-contain">
                                        <h6 class="bg-c-green d-inline-block text-center">A</h6>
                                        <p class="d-inline-block m-l-20">Appestia landing Page</p>
                                    </div>
                                </td>
                                <td>
                                    <p class="d-inline-block m-r-20">4 : 36</p>
                                    <div class="progress d-inline-block">
                                        <div class="progress-bar bg-c-green" role="progressbar" aria-valuemin="0" aria-valuemax="100" style="width:50%">
                                        </div>
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <div class="task-contain">
                                        <h6 class="bg-c-blue d-inline-block text-center">L</h6>
                                        <p class="d-inline-block m-l-20">Logo Design</p>
                                    </div>
                                </td>
                                <td>
                                    <p class="d-inline-block m-r-20">4 : 36</p>
                                    <div class="progress d-inline-block">
                                        <div class="progress-bar bg-c-blue" role="progressbar" aria-valuemin="0" aria-valuemax="100" style="width:50%">
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
