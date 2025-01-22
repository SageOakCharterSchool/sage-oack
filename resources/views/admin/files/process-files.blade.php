@extends('layouts.admin')

@section('content')

<div class="container">
    @if (session('no_error_message'))
            <h2 class="alert alert-success"  >{!! session('no_error_message') !!}</h2>
    @endif
    @if (session('error_message'))
            <h2 class="alert alert-danger"  >{!! session('error_message') !!}</h2>
    @endif

    <div class="row">
        @if ($studentListhasRecords)
            <div class="float-end">
                <form action="start-process-all-file" method="post" >
                    @csrf
                    <div class="form-check float-end">
                        <input class="form-check-input" type="checkbox" value="1" id="confirmProcessAllFiles" name="confirmProcessAllFiles">
                        <label class="form-check-label" >
                        Check to confirm process all files
                        </label>
                    </div>
                    <div class="clearfix"></div>
                    <div class="">
                        <button type="submit" class="btn btn-danger btn-sm protectMe float-end">Process All Files</button>
                    </div>
                    <input type="hidden" name="my_table_name" id="my_table_name" value="all-tables">
                </form>
            </div>
        @endif
        <ul class="nav nav-tabs mb-3">
            <li class="nav-item protectMe">
              <a class="nav-link " aria-current="page" href="{{ url('/admin/upload') }}">Step 1 - Upload Files</a>
            </li>
            <li class="nav-item protectMe">
              <a class="nav-link active" href="{{ url('/admin/process-files') }}">Step 2 - Process Files</a>
            </li>
            <li class="nav-item protectMe">
              <a class="nav-link" href="{{ url('/admin/consolidate') }}">Step 3 - Consolidate</a>
            </li>
            <li class="nav-item protectMe">
              <a class="nav-link  protectMe" href="{{ url('/admin/view-consolidated') }}">Step 4 - View Reports</a>
            </li>
        </ul>
        @foreach (config('constants.tables') as $table)
            @if (($table == 'student_accounts' && !$studentListhasRecords) || ($studentListhasRecords))

                    <div class="col-6 ">
                        <div class=" card m-2 border border-warning" style="height: 200px; ">
                            <div class="card-header">
                                Tab: {{$table}}
                            </div>
                            <div class="card-body">
                                @if(Session::has('errorMessage_' . $table))
                                    <div class="alert alert-danger" style="max-height: 50px; overflow-y: scroll;">
                                        <p>The following errors were found processing Tab <u><b>{{$table}}</b></u></p>
                                        @foreach (Session::get('errorMessage_' . $table) as $error)
                                            <p>
                                                <small>{{ $error }}</small>
                                            </p>
                                        @endforeach
                                    </div>
                                @endif
                                @if(Session::has('successMessage_' . $table))
                                    <div class="alert alert-success" style="max-height: 50px; overflow-y: scroll;">
                                        <p>The following was the result processing Tab <u><b>{{$table}}</b></u></p>
                                        @foreach (Session::get('successMessage_' . $table) as $error)
                                            <p>
                                                <small>{{ $error }}</small>
                                            </p>
                                        @endforeach
                                    </div>
                                @endif
                                <form action="start-process-file" method="post" >
                                    @csrf
                                    <div class="text-center">
                                        @if (App\Models\FileUploads::checkForFileToUpload($table))
                                            <p class="card-text">
                                                <span class="badge bg-success">File available to process</span>
                                            </p>
                                        @else
                                            <p class="card-text">
                                                <span class="badge bg-warning">No File available to process</span>
                                            </p>
                                        @endif

                                        @if (App\Models\FileUploads::checkForFileToUpload($table))
                                            <button type="submit" class="btn btn-warning btn-sm protectMe">Process File</button>
                                            <input type="hidden" name="my_table_name" id="my_table_name" value="{{$table}}">
                                        @endif
                                    </div>
                                </form>
                            </div>
                            <div class="card-footer">
                                <div class="btn-group float-end" role="group" aria-label="File-info">
                                    <a type="button" class="btn btn-outline-warning btn-sm " href="/admin/export-file-info/{{$table}}">Export Info CSV</a>
                                    <button type="button" class="btn btn-outline-info btn-sm " onclick="window.livewire.emit('showFileUploadInfo','{{$table}}')">Export Info</button>
                                    <button type="button" class="btn btn-outline-warning btn-sm "  onclick="window.livewire.emit('showLastUploadInfo','{{$table}}')">Last Upload Info</button>
                                </div>
                            </div>
                        </div>
                    </div>

            @endif
        @endforeach
    </div>
    @livewire('admin.show-uploads-info')
</div>
@endsection
@section('custom_script')
<script>

        window.addEventListener('launch-show-info-modal', event => {
            $('#fileUploadInfo1').modal({
                backdrop: 'static',
                keyboard: false
            })
            $('#fileUploadInfo1').modal("show");
        })
        window.addEventListener('closeThisModal1', event => {
            $('#fileUploadInfo1').modal("hide");
        })
        window.addEventListener('launch-show-last_upload-info-modal', event => {
            $('#fileUploadInfo2').modal({
                backdrop: 'static',
                keyboard: false
            })
            $('#fileUploadInfo2').modal("show");
        })
        window.addEventListener('closeThisModal2', event => {
            $('#fileUploadInfo2').modal("hide");
        })
</script>
@endsection

