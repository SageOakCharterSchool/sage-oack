@extends('layouts.user')
@section('content')
    <h4>My Students</h4>

    <form>
        <div class="input-group mb-3">

            <input type="search" class="form-control" placeholder="Find user here" name="search"
                value="{{ request('search') }}">
            <button class="btn btn-outline-secondary" type="submit" id="button-addon2">Search</button>
        </div>
    </form>
    @if (Auth::user()->isAdmin())
        <button class="btn btn-primary btn-sm float-end p-2" onclick="javascript:createStudentForm()">Create
            Students</button>
    @endif
    <table class="table ">
        <tr>
            <td style="width:10%">
                Student Id
            </td>
            <td>
                Student Name
            </td>
            <td>
                Student Email
            </td>
            <td>
                Grade
            </td>
            <td>
                Student Password
            </td>
            <td>
                Student DOB
            </td>
            <td>
                @if (Auth::user()->isAdmin())
                    Options
                @endif
            </td>

        </tr>
        @if (Auth::user()->isTeacher())
            @include('reports._list-my-students-teacher')
        @elseif (Auth::user()->isAdmin())
            @include('reports._list-my-students-teacher')
        @elseif (Auth::user()->isSpecialist())
            @include('reports._list-my-students-specialist')
        @endif
    </table>
    {{ $myStudents->links('pagination::simple-bootstrap-5') }}
    @include('reports._change_student_password_modal')
    @include('reports._create_students_modal')
    @include('reports._reassign_students_modal')
    @include('reports._assign_students_to_specialist_modal')
@endsection


@push('script')
    <script>
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        function changeUserPassword(sId, sName, sPass) {
            $('#studentIdToChange').html(sId);
            $('#studentNameToChange').html(sName);
            $('#studentPassword').val(sPass);
            var myModal = document.getElementById("studentChangePasswordModal");
            var modal = new bootstrap.Modal(myModal);
            modal.show();

        }

        function reassignStudents(sId) {
            var data = {
                studentId: sId,
            };
            $.ajax({
                type: "POST",
                url: '/admin/user/get-students-teacher-info',
                data: data,
                success: function(response) {
                    $("#reassignTeaqchersAvailable").val(0);
                    $("#reassignStudentId").val(sId);
                    var studInfo = "<p>" + 'Student Id: ' + response.studAccount.student_id + "<br>" +
                        'Student Name: ' + response.studAccount.column_a + ' ' + response.studAccount.column_b +
                        '<br>' +
                        'Current Teacher Id: ' + response.currentTeacherAssigned.teacher_id + '<br>' +
                        'Current Teacher: ' + response.currentTeacherAssigned.first_name + ' ' + response
                        .currentTeacherAssigned.last_name + '<br>' +
                        "</p>"
                    $('#reassignStudentInfo').html(studInfo);
                    var myModal = document.getElementById("studentReassginModal");
                    var modal = new bootstrap.Modal(myModal);
                    modal.show();
                },
                error: function(error) {
                    Swal.fire({
                        title: "Error!",
                        text: error.responseJSON.msg,
                        icon: "error"
                    });
                }
            });
        }

        function assignStudentsToSpecialist(sId) {
            var data = {
                studentId: sId,
            };
            $.ajax({
                type: "POST",
                url: '/admin/user/get-students-specialist-info',
                data: data,
                success: function(response) {
                    $("#assignSpecialistAvailable").val(0);
                    $("#assignSpecialistStudentId").val(sId);
                    var studInfo = "<p>" + 'Student Id: ' + response.studAccount.student_id + "<br>" +
                        'Student Name: ' + response.studAccount.column_a + ' ' + response.studAccount.column_b +
                        '<br>';
                    console.log(response.currentSpecialistAssigned);
                    if (response.currentSpecialistAssigned !== undefined) {
                        studInfo +=
                            'Current Specialist Id: ' + response.currentSpecialistAssigned.specialist_id +
                            '<br>' +
                            'Current Specialist: ' + response.currentSpecialistAssigned.first_name + ' ' +
                            response
                            .currentSpecialistAssigned.last_name + '<br>' +
                            "</p>";
                    }
                    $('#assignStudentInfo').html(studInfo);
                    var myModal = document.getElementById("studentAssignSpecialistsModal");
                    var modal = new bootstrap.Modal(myModal);
                    modal.show();
                },
                error: function(error) {
                    Swal.fire({
                        title: "Error!",
                        text: error.responseJSON.msg,
                        icon: "error"
                    });
                }
            });
        }

        function saveReassignStudent() {
            var data = {
                studentId: $('#reassignStudentId').val(),
                newTeacherId: $('#reassignTeaqchersAvailable').val(),
            };
            $.blockUI({
                message: '<h1>Please Wait</h1>',
            });
            $.ajax({
                type: "POST",
                url: '/admin/user/reassign-student-teacher',
                data: data,
                success: function(response) {
                    $.unblockUI();
                    Swal.fire({
                        title: "Reassing Completed!",
                        text: response.msg,
                        icon: "success"
                    });
                },
                error: function(error) {
                    Swal.fire({
                        title: "Error!",
                        text: error.responseJSON.msg,
                        icon: "error"
                    });
                }
            });
            closeReassignStudents();
        }

        function saveAssignSpecialist() {
            var data = {
                studentId: $('#assignSpecialistStudentId').val(),
                newSpecialistId: $('#assignSpecialistAvailable').val(),
            };
            $.blockUI({
                message: '<h1>Please Wait</h1>',
            });
            $.ajax({
                type: "POST",
                url: '/admin/user/reassign-student-specialist',
                data: data,
                success: function(response) {
                    $.unblockUI();
                    Swal.fire({
                        title: "Assigning Completed!",
                        text: response.msg,
                        icon: "success"
                    });
                },
                error: function(error) {
                    Swal.fire({
                        title: "Error!",
                        text: error.responseJSON.msg,
                        icon: "error"
                    });
                }
            });
            closeAssignStudentsToSpecialist();
        }

        function changeStudentPassword() {
            var passChanged = 0;
            if ($("#password_changed").is(':checked')) {
                passChanged = 1;
            }
            var data = {
                studentId: $('#studentIdToChange').html(),
                newPass: $('#studentPassword').val(),
                passChanged: passChanged,
            };
            $.ajax({
                type: "POST",
                url: '/admin/user/reset-student-password',
                data: data,
                success: function(response) {
                    Swal.fire({
                        title: "Sent!",
                        text: response.msg,
                        icon: "success"
                    });
                    closeChageStudentPassword();
                    window.location.href = '/admin/view-students?search=' + data.studentId;
                },
                error: function(error) {
                    Swal.fire({
                        title: "Error!",
                        text: error.responseJSON.msg,
                        icon: "error"
                    });
                }
            });
        }

        function deleteUserAccount(sId) {
            var data = {
                studentId: sId,
            };
            Swal.fire({
                title: "Sure to delete this Student ?",
                icon: "question",
                showCancelButton: true,
                confirmButtonColor: "#3085d6",
                cancelButtonColor: "#d33",
                confirmButtonText: "Yes Delete "
            }).then((result) => {
                if (result.isConfirmed) {
                    $.blockUI({
                        message: '<h1>Please Wait</h1>',
                    });
                    $.ajax({
                        type: "POST",
                        url: '/admin/user/delete-student-account',
                        data: data,
                        success: function(response) {
                            Swal.fire({
                                title: "Deleted!",
                                text: response.msg,
                                icon: "success"
                            });
                            closeChageStudentPassword();
                            window.location.href = '/admin/view-students';
                        },
                        error: function(error) {
                            Swal.fire({
                                title: "Error!",
                                text: error.responseJSON.msg,
                                icon: "error"
                            });
                        }
                    });
                } else if (result.isDenied) {
                    Swal.fire('Account not deleted', '', 'info')
                }
            })
        }

        function closeChageStudentPassword() {
            $("#studentChangePasswordModal").modal('hide');
            $('.modal-backdrop').remove();
        }

        function closeReassignStudents() {
            $("#studentReassginModal").modal('hide');
            $('.modal-backdrop').remove();
        }

        function closeAssignStudentsToSpecialist() {
            $("#studentAssignSpecialistsModal").modal('hide');
            $('.modal-backdrop').remove();
        }

        function closeCreateStudent() {
            $("#studentCreateModal").modal('hide');
            $('.modal-backdrop').remove();
        }

        function createStudentForm() {
            $('#teacher_id').val('');
            $('#student_id').val('');
            $('#student_first_name').val('');
            $('#student_last_name').val('');
            $('#student_email').val('');
            $('#student_grade').val('');
            $('#student_password').val('');
            $('#student_dob').val('');
            var myModal = document.getElementById("studentCreateModal");
            var modal = new bootstrap.Modal(myModal);
            modal.show();
        }

        function createStudent() {
            $(".createStudentValidation").removeClass('is-invalid')
            var data = {
                teacher_id: $('#teacher_id').val(),
                student_id: $('#student_id').val(),
                student_first_name: $('#student_first_name').val(),
                student_last_name: $('#student_last_name').val(),
                student_email: $('#student_email').val(),
                student_grade: $('#student_grade').val(),
                student_password: $('#student_password').val(),
                student_dob: $('#student_dob').val(),
            };
            $.ajax({
                type: "POST",
                url: '/admin/user/create-student-account',
                data: data,
                success: function(response) {
                    $.blockUI({
                        message: '<h1>Please Wait</h1>',
                    });
                    closeCreateStudent();
                    window.location.href = '/admin/view-students?search=' + data.student_id;
                },
                error: function(xhr) {
                    if (xhr.status == 422) {
                        var errors = xhr.responseJSON.errors;
                        var html = '<ul>';
                        $.each(errors, (key, error) => {
                            $("#" + key).addClass('is-invalid')
                            html += `<li>${error}</li>`;
                        });
                        html += '</ul>'
                    }
                    Swal.fire({
                        title: "Error!",
                        html: html,
                        icon: "error"
                    });
                }
            });

        }
    </script>
@endpush
