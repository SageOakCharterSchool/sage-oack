<!-- Modal -->
<div class="modal fade" data-bs-backdrop="static" id="studentCreateModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
    >
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Create Students</h5>
                <button type="button" class="close" onclick="javascript:closeCreateStudent()" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="mb-3">
                    <label for="teacher_id" class="form-label">Teacher Id</label>
                    <input type="text" class="form-control createStudentValidation" id="teacher_id" placeholder="Teacher Id">
                </div>
                <div class="mb-3">
                    <label for="student_id" class="form-label">Student Id</label>
                    <input type="text" class="form-control createStudentValidation" id="student_id" placeholder="Student Id">
                </div>
                <div class="mb-3">
                    <label for="student_name" class="form-label">Student First Name</label>
                    <input type="text" class="form-control createStudentValidation" id="student_first_name"
                                placeholder="Student First Name">
                </div>
                <div class="mb-3">
                    <label for="student_name" class="form-label">Student Last Name</label>
                    <input type="text" class="form-control createStudentValidation" id="student_last_name"
                                placeholder="Student Last Name">
                </div>
                <div class="mb-3">
                    <label for="student_email" class="form-label">Student Email</label>
                    <input type="email" class="form-control createStudentValidation" id="student_email"
                                placeholder="Student Email">
                </div>
                <div class="mb-3">
                    <label for="student_grade" class="form-label">Student Grade</label>
                    <input type="text" class="form-control createStudentValidation" id="student_grade"
                                placeholder="Student Grade">
                </div>
                <div class="mb-3">
                    <label for="student_password" class="form-label">Student Password</label>
                        <input type="text" class="form-control createStudentValidation" id="student_password" maxlength="8"
                            placeholder="Enter Password">
                </div>
                <div class="mb-3">
                    <label for="student_dob" class="form-label">Student DOB</label>
                    <input type="text" class="form-control createStudentValidation" id="student_dob"
                    placeholder="DOB m/d/yyyy">
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal" onclick="javascript:closeCreateStudent()">Close</button>
                <button type="button" class="btn btn-primary" onclick="javascript:createStudent()">Save changes</button>
            </div>
        </div>
    </div>
</div>
