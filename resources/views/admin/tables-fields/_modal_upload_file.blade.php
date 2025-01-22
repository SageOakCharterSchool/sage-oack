<!-- Modal -->
<div class="modal fade" data-bs-backdrop="static" data-bs-keyboard="false" id="UploadTableRecords" tabindex="-1"
    aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">

            <div class="modal-header">
                <h1 class="modal-title fs-5" id="exampleModalLabel">Upload files</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" onclick="closeUploadModal()" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <input type="hidden" name="tableId" id="tableId" value="">
                Upload records for Table <span id="tableName"></span>
                <div class="mb-3">
                    <label for="formFile" class="form-label">Enter File to Upload</label>
                    <input class="form-control" type="file" onchange="changeFileToUpload()"  id="fileToUpload" name="fileToUpload">
                </div>
                <div id="fieldsInfo" style="display: none">


                    <div class="mb-3  ">
                        <label for="formFile" class="form-label">Enter Cell where Student Id is Located</label>
                        <select class="form-control allInCaps" id="student_id_cell_name" name="student_id_cell_name"
                            placeholder="column name: example A,B,C,AD,none">
                            <option value="none">None</option>
                        </select>

                    </div>
                    {{-- teacher students block --}}
                    <div class="mb-3 teacherBlock " style="display: none" id="enter_teacher_id_block">
                        <label for="formFile" class="form-label">Enter Cell where Teacher Id is Located</label>
                        <select class="form-control allInCaps" id="teacher_id_cell_name" name="teacher_id_cell_name"
                            placeholder="column name: example A,B,C,AD,none"></select>
                    </div>
                    <div class="mb-3 teacherBlock" style="display: none" id="enter_teacher_email_block">
                        <label for="formFile" class="form-label">Enter Cell where Teacher Email is Located</label>
                        <select class="form-control allInCaps" id="teacher_email_cell_name"
                            name="teacher_email_cell_name" placeholder="column name: example A,B,C,AD,none"></select>
                    </div>

                    <div class="mb-3 teacherBlock" style="display: none" id="enter_teacher_first_name_block">
                        <label for="formFile" class="form-label">Enter Cell where Teacher First Name is Located</label>
                        <select class="form-control allInCaps" id="teacher_first_name_cell_name" name="teacher_first_name_cell_name"
                            placeholder="column name: example A,B,C,AD,none"></select>
                    </div>
                    <div class="mb-3 teacherBlock" style="display: none" id="enter_teacher_last_name_block">
                        <label for="formFile" class="form-label">Enter Cell where Teacher Last Name is Located</label>
                        <select class="form-control allInCaps" id="teacher_last_name_cell_name" name="teacher_last_name_cell_name"
                            placeholder="column name: example A,B,C,AD,none"></select>
                    </div>
                    {{-- student account block --}}
                    <div class="mb-3 studentBlock" style="display: none" id="enter_first_name_id_block">
                        <label for="formFile" class="form-label">Enter Cell where First Name is Located</label>
                        <select class="form-control allInCaps" id="first_name_id_cell_name"
                            name="first_name_id_cell_name" placeholder="column name: example A,B,C,AD,none"></select>
                    </div>
                    <div class="mb-3 studentBlock" style="display: none" id="enter_last_name_id_block">
                        <label for="formFile" class="form-label">Enter Cell where Last Name is Located</label>
                        <select class="form-control allInCaps" id="last_name_id_cell_name" name="last_name_id_cell_name"
                            placeholder="column name: example A,B,C,AD,none"></select>
                    </div>
                    <div class="mb-3 studentBlock" style="display: none" id="enter_student_email_block">
                        <label for="formFile" class="form-label">Enter Cell where Email is Located</label>
                        <select class="form-control allInCaps" id="email_id_cell_name" name="email_id_cell_name"
                            placeholder="column name: example A,B,C,AD,none"></select>
                    </div>
                    <div class="mb-3 studentBlock" style="display: none" id="enter_dob_id_block">
                        <label for="formFile" class="form-label">Enter Cell where DOB is Located</label>
                        <select class="form-control allInCaps" id="dob_id_cell_name" name="dob_id_cell_name"
                            placeholder="column name: example A,B,C,AD,none"></select>
                    </div>
                    <div class="mb-3 studentBlock" style="display: none" id="enter_password_id_block">
                        <label for="formFile" class="form-label">Enter Cell where Password is Located</label>
                        <select class="form-control allInCaps" id="password_id_cell_name"
                            name="password_id_cell_name" placeholder="column name: example A,B,C,AD,none"></select>
                    </div>
                    <div class="mb-3 studentBlock" style="display: none" id="enter_grade_id_block">
                        <label for="formFile" class="form-label">Enter Cell where Grade is Located</label>
                        <select class="form-control allInCaps" id="grade_id_cell_name"
                            name="grade_id_cell_name" placeholder="column name: example A,B,C,AD,none"></select>
                    </div>
                    <div class="mb-3 studentBlock" style="display: none" id="enter_program_id_block">
                        <label for="formFile" class="form-label">Enter Cell where Program is Located</label>
                        <select class="form-control allInCaps" id="program_id_cell_name"
                            name="program_id_cell_name" placeholder="column name: example A,B,C,AD,none"></select>
                    </div>
                    <div class="mb-3 studentBlock" style="display: none" id="enter_sis_id_block">
                        <label for="formFile" class="form-label">Enter Cell where SIS is Located</label>
                        <select class="form-control allInCaps" id="sis_id_cell_name"
                            name="sis_id_cell_name" placeholder="column name: example A,B,C,AD,none"></select>
                    </div>

                    <div class="mb-3 " style="display: none" id="list_of_fields_0">
                        <label for="formFile" class="form-label">List of Fields for this Table</label>
                        <div class="list_of_fields_1">

                        </div>
                    </div>
                </div>
                <input type="hidden" id="is_teacher_table" name="is_teacher_table" value="">
                <input type="hidden" id="is_student_account_table" name="is_student_account_table" value="">
            </div>
            <div class="modal-footer" id="buttonsToProcess">
                <button type="button" class="btn btn-secondary" onclick="closeUploadModal()" data-bs-dismiss="modal">Close</button>
                <button type="button" onclick="getColumnsToUpload()" class="btn btn-warning">Get Columns
                    Info</button>
                <button type="button" onclick="processUpload()" class="btn btn-danger">Confirm</button>
            </div>
            <div id="waitMessage" style="display: none" class="text-center">
                <div class="alert alert-warning" role="alert">
                    Uploading file, please wait... <img style="margin-left:20px;"
                        src="{{ url('/assets/images/spinner.gif') }}" height="30px" />
                </div>
            </div>
        </div>
    </div>
</div>
<div>
