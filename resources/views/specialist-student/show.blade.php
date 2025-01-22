@extends('layouts.admin')

@section('template_title')
    {{ $specialistStudent->name ?? __('Show') . " " . __('Specialist Student') }}
@endsection

@section('content')
    <section class="content container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header" style="display: flex; justify-content: space-between; align-items: center;">
                        <div class="float-left">
                            <span class="card-title">{{ __('Show') }} Specialist Student</span>
                        </div>
                        <div class="float-right">
                            <a class="btn btn-primary btn-sm" href="{{ route('specialist-students.index') }}"> {{ __('Back') }}</a>
                        </div>
                    </div>

                    <div class="card-body bg-white">

                                <div class="form-group mb-2 mb20">
                                    <strong>Cycle Id:</strong>
                                    {{ $specialistStudent->cycle_id }}
                                </div>
                                <div class="form-group mb-2 mb20">
                                    <strong>Created By:</strong>
                                    {{ $specialistStudent->created_by }}
                                </div>
                                <div class="form-group mb-2 mb20">
                                    <strong>Student Id:</strong>
                                    {{ $specialistStudent->student_id }}
                                </div>
                                <div class="form-group mb-2 mb20">
                                    <strong>Specialist Id:</strong>
                                    {{ $specialistStudent->specialist_id }}
                                </div>
                                <div class="form-group mb-2 mb20">
                                    <strong>First Name:</strong>
                                    {{ $specialistStudent->first_name }}
                                </div>
                                <div class="form-group mb-2 mb20">
                                    <strong>Last Name:</strong>
                                    {{ $specialistStudent->last_name }}
                                </div>
                                <div class="form-group mb-2 mb20">
                                    <strong>Email:</strong>
                                    {{ $specialistStudent->email }}
                                </div>
                                <div class="form-group mb-2 mb20">
                                    <strong>Name:</strong>
                                    {{ $specialistStudent->name }}
                                </div>
                                <div class="form-group mb-2 mb20">
                                    <strong>Students List:</strong>
                                    {{ $specialistStudent->students_list }}
                                </div>

                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
