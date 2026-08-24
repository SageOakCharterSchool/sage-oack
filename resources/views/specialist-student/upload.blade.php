@extends('layouts.admin')

@section('template_title')
    Specialist Students
@endsection

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-12">
                <div class="card">
                    <div class="card-header">
                        <div style="display: flex; justify-content: space-between; align-items: center;">

                            <span id="card_title">
                                {{ __('Specialist Students') }}
                            </span>
                            <div class="float-right">
                                <a class="btn btn-primary btn-sm" href="{{ route('specialist-students.index') }}">
                                    {{ __('Back') }}</a>
                            </div>

                        </div>
                    </div>


                    <div class="card-body bg-white">
                        <div>


                        @include('layouts.includes.admin._messages')
                        <form action="{{ route('specialist-students-process-upload-file') }}" method="POST"
                            enctype="multipart/form-data">
                            @csrf
                            <div class="mb-3">
                                <label for="formFile" class="form-label">CSV File</label>
                                <input class="form-control" type="file" id="specialist_file" name="specialist_file">
                            </div>
                            <div class="mb-3 ">
                                <button type="submit" class="btn btn-primary mb-3 float-end">Upload file</button>
                            </div>

                        </form>
                        </div>
                        <div class="clearfix"></div>
                        <div class="table-responsive">
                            <span>Make sure the file has this structure</span><br>
                            <table class="table table-bordered">

                                <tbody>
                                    <tr>
                                        <td>SEIS ID</td>
                                        <td>SSID</td>
                                        <td>Last Name</td>
                                        <td>First Name</td>
                                        <td>Middle Name</td>
                                        <td>Preferred Name</td>
                                        <td>DOB</td>
                                        <td>CaseManager</td>
                                        <td>CaseManagerEmail</td>
                                        <td>Reporting LEA</td>
                                        <td>DSEA</td>
                                        <td>School</td>
                                        <td>Grade</td>
                                        <td>Primany Language</td>
                                        <td>SPED Type</td>
                                        <td>Disability 1</td>
                                        <td>Next Plan Review</td>
                                        <td>Next Reevaluation</td>
                                        <td>Eligibility</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
@endsection
