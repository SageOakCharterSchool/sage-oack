@extends('layouts.admin')

@section('content')
    <div class="mt-2" style="margin-bottom:150px">
        @include('layouts.includes.admin._messages')
        <div class="card" style="min-height: 10rem">
            <div class="card-header">
                Upload Chrome Tracking Info
            </div>
            <div class="card-body">
                <form action="/admin/process-upload-tracking" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="mb-3">
                        <label for="formFile" class="form-label">Upload your file</label>
                        <input class="form-control" type="file" id="file_upload" name="file_upload">
                    </div>
                    <div class="mb-3">
                        <label for="formFile" class="form-label">CSV Structure:</label>
                        <br>
                        <table class="table table-striped table-bordered table-hover">
                            <br>
                            File should have 5 columns in a CSV format<br><br>
                            <thead>
                                <tr>
                                    <th>Tracking #</th>
                                    <th>Full_Name</th>
                                    <th>Student_id</th>
                                    <th>Grade</th>
                                    <th>Student_email</th>
                                </tr>
                            </thead>
                        </table>
                    </div>

                    <button type="submit" class="btn btn-primary float-end">Upload</button>
                </form>
            </div>
        </div>
    </div>
@endsection
