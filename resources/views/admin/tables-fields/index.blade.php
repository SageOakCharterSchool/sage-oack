@extends('layouts.admin')

@section('content')
    @include('admin.tables-fields._modal_upload_file')
    <div class="row">
        <div class="col-md-12">
            @if (session('message'))
                <h2 class="alert alert-success">{{ session('message') }},</h2>
            @endif
            @if (session('error-message'))
                <h2 class="alert alert-warning">{{ session('error-message') }},</h2>
            @endif
            <div class="card">
                <div class="card-header">
                    <h4>Tables
                        {{-- <a class="btn btn-warning btn-sm float-end" href="{{ url('admin/table-def/create') }}">Add Table</a> --}}
                    </h4>
                </div>
                <div class="card-body">
                    <table class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Table Name</th>
                                <th>Last Upload</th>
                                <th>Rows</th>
                                <th>Options</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($tables as $table)
                                @php
                                    $tableInfo = App\Models\UploadFilesLog::getLastUploadInfo(
                                        $table->cycle_id,
                                        $table->id,
                                    );
                                @endphp
                                <tr>

                                    <th>{{ $table->id }}</th>
                                    <th>{{ $table->table_alias }}
                                        @if ($table->process_status == 1)
                                            <span style="color:green;margin-left:20px;">
                                                1-Completed
                                            </span>
                                        @elseif ($table->process_status == 3)
                                            <span style="color:yellow;margin-left:20px;">
                                                3-Uploading Records
                                            </span>
                                        @elseif ($table->process_status == 2)
                                            <span style="color:red;margin-left:20px;">
                                                2-In Process
                                            </span>
                                        @elseif ($table->process_status == 4)
                                            <span style="color:blue;margin-left:20px;">
                                                4 - Submitted
                                            </span>
                                        @endif
                                    </th>
                                    <th>{{ $tableInfo->created_at ?? '' }}</th>
                                    <th>{{ $tableInfo->total_records ?? '' }}</th>
                                    <th>
                                        {{-- @if ($table->is_system == 0)
                                            <a class="btn btn-primary btn-sm"
                                                href="{{ url('/admin/table-def/' . $table->id . '/edit') }}">Edit</a>
                                        @endif --}}
                                        <a class="btn btn-warning btn-sm"
                                            href="{{ url('/admin/field-def/' . $table->id . '/fields') }}">Fields</a>
                                        @if ($table->process_status <= 1)
                                            @if ($table->allow_upload == 1)
                                                <button class="btn btn-success btn-sm uploadTableRecordsButton"
                                                    onclick="uploadTableRecordsButton({{ $table->id }},'{{ $table->table_name }}','{{ $table->table_alias }}')">Upload</button>
                                            @else
                                                <a class="btn btn-info btn-sm "
                                                    href="/admin/table-def/build-formulas/{{ $table->id }}">Formulas</a>
                                            @endif
                                        @endif
                                    </th>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>

                </div>
            </div>
        </div>
    </div>
    </div>
@endsection
@push('script')
    <script>

        function convert(n) {
            var result = '';
            do {
                result = (n % 26 + 10).toString(36) + result;
                n = Math.floor(n / 26) - 1;
            } while (n >= 0)
            return result.toUpperCase();
        }

        function getColumnsToUpload() {
            var input = document.getElementById('fileToUpload');
            if (!input) {
                swal({
                    title: "Upload Error!",
                    text: "Um, couldn't find the fileinput element",
                    icon: "error"
                });
            } else if (!input.files) {
                swal({
                    title: "Upload Error!",
                    text: "This browser doesn't seem to support the `files` property of file inputs.",
                    icon: "error"
                });
            } else if (!input.files[0]) {
                swal({
                    title: "Upload Error!",
                    text: "Please select a file before clicking 'Get Columns Info'",
                    icon: "error"
                });
            } else {
                var file = input.files[0];
                if (file) {
                    var alpha = [];
                    for (let i = 0; i < 1000; i++) {
                        alpha.push(convert(i));
                    }

                    var reader = new FileReader();
                    reader.readAsText(file, "UTF-8");
                    reader.onload = function(evt) {
                        const [header, ...lines] = evt.srcElement.result.replace(/\r/g, '').split('\n');
                        const objHeaders = header.split(',')
                        var fieldsToShow = "<ul>";
                        var i = 0;
                        $("#student_id_cell_name").append(new Option('Please select a value',''));
                        $("#teacher_id_cell_name").append(new Option('Please select a value',''));
                        $("#teacher_email_cell_name").append(new Option('Please select a value',''));
                        $("#teacher_first_name_cell_name").append(new Option('Please select a value',''));
                        $("#teacher_last_name_cell_name").append(new Option('Please select a value',''));
                        $("#teacher_student_id_cell_name").append(new Option('Please select a value',''));
                        $("#first_name_id_cell_name").append(new Option('Please select a value',''));
                        $("#last_name_id_cell_name").append(new Option('Please select a value',''));
                        $("#email_id_cell_name").append(new Option('Please select a value',''));
                        $("#dob_id_cell_name").append(new Option('Please select a value',''));
                        $("#password_id_cell_name").append(new Option('Please select a value',''));
                        $("#grade_id_cell_name").append(new Option('Please select a value',''));
                        $("#program_id_cell_name").append(new Option('Please select a value',''));
                        $("#sis_id_cell_name").append(new Option('Please select a value',''));
                        for (field of objHeaders) {
                            $("#student_id_cell_name").append(new Option(field, alpha[i]));
                            $("#teacher_id_cell_name").append(new Option(field, alpha[i]));
                            $("#teacher_email_cell_name").append(new Option(field, alpha[i]));
                            $("#teacher_first_name_cell_name").append(new Option(field, alpha[i]));
                            $("#teacher_last_name_cell_name").append(new Option(field, alpha[i]));
                            $("#teacher_student_id_cell_name").append(new Option(field, alpha[i]));
                            $("#first_name_id_cell_name").append(new Option(field, alpha[i]));
                            $("#last_name_id_cell_name").append(new Option(field, alpha[i]));
                            $("#email_id_cell_name").append(new Option(field, alpha[i]));
                            $("#dob_id_cell_name").append(new Option(field, alpha[i]));
                            $("#password_id_cell_name").append(new Option(field, alpha[i]));
                            $("#program_id_cell_name").append(new Option(field, alpha[i]));
                            $("#grade_id_cell_name").append(new Option(field, alpha[i]));
                            $("#sis_id_cell_name").append(new Option(field, alpha[i]));
                            fieldsToShow += "<li>" + alpha[i++] + '  ->  ' + field + "</li>";
                        }
                        fieldsToShow += "</ul>";
                        $("#fieldsInfo").show();
                        $("#list_of_fields_0").show();
                        $(".list_of_fields_1").html(fieldsToShow);
                        $.ajax({
                            type: 'post',
                            url: '/admin/table-def/get-last-mapping',
                            dataType: 'json',
                            data: {
                                "_token": "{{ csrf_token() }}",
                                "tableId": $("#tableId").val(),
                            },
                            success: function(res) {
                                this.lastMapping = $.parseJSON(res);
                                $.each(this.lastMapping, function(i, val)
                                {
                                    $("#" + i).val(val);
                                });
                            },

                        });


                    }
                }
            }
        }

        function uploadTableRecordsButton(tableId, tableName, tableAlias) {

            $("#tableName").html(tableAlias);
            $("#tableId").val(tableId);
            $("#UploadTableRecords").modal('show');

            if (tableName === 'teacher_students') {
                $("#is_teacher_table").val(1);
                if ($("#fileToUpload").val() !== "") {
                    $(".teacherBlock").show();
                }
            }

            if (tableName === 'student_accounts') {
                $("#is_student_account_table").val(1);
                if ($("#fileToUpload").val() !== "") {
                    $(".studentBlock").show();
                }
            }
        }

        function processUpload() {

            if (!validateFieldsEntered()) {
                return;
            }
            var file_data = $('#fileToUpload').prop('files')[0];
            var form_data = new FormData();
            form_data.append('file_to_upload', file_data);
            form_data.append('table_id', $('#tableId').val());
            form_data.append('table_name', $('#tableName').html());
            form_data.append('student_id_cell_name', $('#student_id_cell_name').val());
            form_data.append('teacher_id_cell_name', $('#teacher_id_cell_name').val());
            form_data.append('teacher_email_cell_name', $('#teacher_email_cell_name').val());
            form_data.append('teacher_first_name_cell_name', $('#teacher_first_name_cell_name').val());
            form_data.append('teacher_last_name_cell_name', $('#teacher_last_name_cell_name').val());
            form_data.append('teacher_student_id_cell_name', $('#teacher_student_id_cell_name').val());
            form_data.append('email_id_cell_name', $('#email_id_cell_name').val());
            form_data.append('first_name_id_cell_name', $('#first_name_id_cell_name').val());
            form_data.append('last_name_id_cell_name', $('#last_name_id_cell_name').val());
            form_data.append('dob_id_cell_name', $('#dob_id_cell_name').val());
            form_data.append('password_id_cell_name', $('#password_id_cell_name').val());
            form_data.append('program_id_cell_name', $('#program_id_cell_name').val());
            form_data.append('grade_id_cell_name', $('#grade_id_cell_name').val());
            form_data.append('sis_id_cell_name', $('#sis_id_cell_name').val());
            form_data.append('is_teacher_table', $('#is_teacher_table').val());
            form_data.append('is_student_account_table', $('#is_student_account_table').val());
            form_data.append('_token', '{{ csrf_token() }}');
            $("#buttonsToProcess").hide();
            $("#waitMessage").show();
            $.ajax({
                type: 'post',
                url: '/admin/table-def/upload',
                enctype: 'multipart/form-data',
                cache: false,
                contentType: false,
                processData: false,
                data: form_data,
                success: function(res) {
                    $('#fileToUpload').val(null);
                    $("#buttonsToProcess").show();
                    $("#waitMessage").hide();
                    $("#UploadTableRecords").modal('hide');
                    swal({
                        title: "Upload Completed!",
                        text: "Upload file was submitted to a queue successfully and may take a few minues.",
                        icon: "success",
                        showCancelButton: false,
                        confirmButtonColor: "#3085d6",
                        cancelButtonColor: "#d33",
                        confirmButtonText: "Ok",
                    }).then((result) => {
                        if (result) {
                            window.location.href = "/admin/table-def"
                        }
                    });
                },
                error: function(request, status, error) {
                    $("#buttonsToProcess").show();
                    $("#waitMessage").hide();
                    var response = JSON.parse(request.responseText);
                    swal({
                        title: "Upload Error!",
                        text: response.message,
                        icon: "error"
                    });
                }
            });
        }

        function validateFieldsEntered() {
            $(".allInCaps").removeClass("is-invalid");
            var field1 = null;
            var field2 = null;
            var field3 = null;
            var field4 = null;
            var field5 = null;
            var field6 = null;
            var field7 = null;
            var tableIdToCheck = $('#tableId').val();

            if (tableIdToCheck === "1") {

                field1 = $("#student_id_cell_name").val();
                field5 = $("#teacher_id_cell_name").val();
                field2 = $("#teacher_email_cell_name").val();
                field3 = $("#teacher_first_name_cell_name").val();
                field4 = $("#teacher_last_name_cell_name").val();

                if (field1 && field2 && field3 && field4 && field5 ) {
                    return true;
                } else {
                    $(".allInCaps").addClass("is-invalid");
                    swal({
                        title: "Upload Error!",
                        text: "Please define all the columns requested",
                        icon: "error"
                    });
                    return false;
                }
            } else if (tableIdToCheck === "2") {
                var field1 = $("#student_id_cell_name").val();
                var field2 = $("#first_name_id_cell_name").val();
                var field3 = $("#last_name_id_cell_name").val();
                var field4 = $("#email_id_cell_name").val();
                var field5 = $("#dob_id_cell_name").val();
                var field6 = $("#password_id_cell_name").val();
                var field7 = $("#program_id_cell_name").val();
                var field8 = $("#grade_id_cell_name").val();
                var field9 = $("#sis_id_cell_name").val();
                if (field1 && field2 && field3 && field4 && field5 && field6 && field7 && field8 && field9) {
                    return true;
                } else {
                    $(".allInCaps").addClass("is-invalid");
                    swal({
                        title: "Upload Error!",
                        text: "Please define all the columns requested",
                        icon: "error"
                    });
                    return false;
                }
            } else {
                var field1 = $("#student_id_cell_name").val();
                if (field1) {
                    return true;
                } else {
                    $(".allInCaps").addClass("is-invalid");
                    swal({
                        title: "Upload Error!",
                        text: "Please define all the columns requested",
                        icon: "error"
                    });
                    return false;
                }
            }
        }

        function closeUploadModal() {

            $("#list_of_fields_0").hide();
            $(".list_of_fields_1").html(null);
            $("#fileToUpload").val(null);
            // $("#tableName").html(null);
            // $("#tableId").val(null);
            $("#is_teacher_table").val(0);
            $("#is_student_account_table").val(0);
            $(".studentBlock").hide();
            $(".teacherBlock").hide();
            removeSelectOptions();

        }

        function removeSelectOptions() {
            $("#student_id_cell_name").empty();
            $("#teacher_id_cell_name").empty();
            $("#teacher_email_cell_name").empty();
            $("#teacher_first_name_cell_name").empty();
            $("#teacher_last_name_cell_name").empty();
            $("#teacher_student_id_cell_name").empty();
            $("#first_name_id_cell_name").empty();
            $("#last_name_id_cell_name").empty();
            $("#email_id_cell_name").empty();
            $("#dob_id_cell_name").empty();
            $("#program_id_cell_name").empty();
            $("#grade_id_cell_name").empty();
            $("#sis_id_cell_name").empty();
            $("#password_id_cell_name").empty();
        }
        function changeFileToUpload() {
            if ($("#is_teacher_table").val() == 1) {
                if ($("#fileToUpload").val() !== "") {
                    $(".teacherBlock").show();
                    removeSelectOptions();
                    getColumnsToUpload()
                }
            } else if ($("#is_student_account_table").val() == 1) {
                if ($("#fileToUpload").val() !== "") {
                    $(".studentBlock").show();
                    removeSelectOptions();
                    getColumnsToUpload()
                }
            } else {
                removeSelectOptions();
                getColumnsToUpload()
            }

        }
    </script>
@endpush
