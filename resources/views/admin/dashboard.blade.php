@extends('layouts.admin')

@section('content')
    <style>
        .border-success,
        .border-primary,
        .border-warning,
        .border-danger {
            border-width: medium !important;
        }
    </style>
    <div class="row">
        <div class="col-md-10 offset-md-1 grid-margin">
            @if (session('message'))
                <h2 class="alert alert-success">{{ session('message') }},</h2>
            @endif
            <div class="row row-cols-1 row-cols-md-2 g-4">
                @if (Auth::user()->isAdmin())
                    <div class="col">
                        <div class="card border border-primary" style="height:230px">

                            <div class="card-body">
                                <h5 class="card-title">Cycles</h5>
                                <p class="card-text">
                                    Define periods on the system to store data provided
                                </p>
                                <div class="text-center mt-5">
                                    <a href="/admin/cycle" class="btn btn-primary text ">
                                        Cycles
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col">
                        <div class="card border border-success" style="height:230px">

                            <div class="card-body">
                                <h5 class="card-title">Users</h5>
                                <p class="card-text">
                                    Manage User access, allows you to approve/remove registrations.
                                </p>
                                <div class="text-center mt-5">
                                    <a href="/admin/user" class="btn btn-success text ">
                                        Users
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col">
                        <div class="card border border-warning" style="height:230px">

                            <div class="card-body">
                                <h5 class="card-title">Dynamic Uploads</h5>
                                <p class="card-text">Here you can upload files, consolidate and review students information
                                </p>
                                <div class="text-center mt-5">
                                    <a href="/admin/table-def" class="btn btn-warning text ">
                                        Files
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif
                <div class="col">
                    <div class="card">

                        <div class="card-body border border-danger" style="height:230px">
                            <h5 class="card-title">Reports</h5>
                            <p class="card-text">
                                Will hold a reports repository that will show all the information uploaded based on
                                predefined templates.
                            </p>
                            <div class="text-center mt-5">
                                <a href="/admin/consolidate-view" class="btn btn-danger text ">
                                    View all students as csv
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>


        </div>
    </div>
@endsection
