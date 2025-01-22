@extends('layouts.admin')

@section('content')

<style>
    .filepond--credits {
        display: none;
    }
</style>

<div class="container">
    <ul class="nav nav-tabs mb-3">
        <li class="nav-item protectMe">
          <a class="nav-link active" aria-current="page" href="{{ url('/admin/upload') }}">Step 1 - Upload Files</a>
        </li>
        <li class="nav-item protectMe">
          <a class="nav-link" href="{{ url('/admin/process-files') }}">Step 2 - Process Files</a>
        </li>
        <li class="nav-item protectMe">
          <a class="nav-link" href="{{ url('/admin/consolidate') }}">Step 3 - Consolidate</a>
        </li>
        <li class="nav-item protectMe">
          <a class="nav-link  protectMe" href="{{ url('/admin/view-consolidated') }}">Step 4 - View Reports</a>
        </li>
    </ul>
    <div class="row">
        @foreach (config('constants.tables') as $table)
            <div class="col-6 ">
                <div class=" card m-2  border border-success">
                    <div class="card-header">
                        Tab: {{$table}}
                    </div>
                    <div class="card-body">
                        <div class="text-center">
                            <form action="upload/{{$table}}" method="post" enctype="multipart/form-data">
                                @csrf
                                <input type="file" class="filepond" name="data_file" id="data_file_{{$table}}">
                                <input type="hidden" name="my_table_name" id="my_table_name_{{$table}}" value="{{$table}}">
                            </form>
                        </div>
                    </div>
                    <div class="card-footer">
                        <div class="btn-group float-end" role="group" aria-label="File-info">
                            <a type="button" class="btn btn-outline-warning btn-sm " href="/admin/export-file-info/{{$table}}">Export Info CSV</a>

                            <button type="button" class="btn btn-outline-info btn-sm " onclick="window.livewire.emit('showFileUploadInfo','{{$table}}')">File Info</button>
                            <button type="button" class="btn btn-outline-warning btn-sm "  onclick="window.livewire.emit('showLastUploadInfo','{{$table}}')">Last Upload Info</button>
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
    @livewire('admin.show-uploads-info')
@endsection
@section('custom_script')
<script>
    const inputElements = document.querySelectorAll('input.filepond');
    // loop over input elements
    Array.from(inputElements).forEach(inputElement => {
        // create a FilePond instance at the input element location
            FilePond.create(inputElement,{
                server: {
                    url: '/admin/uploadfile',
                    headers: {
                        'X-CSRF-TOKEN':'{{csrf_token()}}',
                        'tableName': $(inputElement).prop('id'),
                        'fileName': 'my_table_name_'+ $(inputElement).prop('id')
                    },
                    revert: null,
                    labelTapToUndo: 'tap to close',
                },
                allowFileTypeValidation:true,
                acceptedFileTypes:["text/csv"],
                maxFileSize:"4MB",
                labelIdle:'Drag & Drop your Data File or <span class="filepond--label-action">Browse</span>',
                labelFileProcessingError: (message) => {
                    console.log(message);
                    //console.log(error.message);
                    return "The file had a problem, please check a valid file";
                }
            })
        });
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

