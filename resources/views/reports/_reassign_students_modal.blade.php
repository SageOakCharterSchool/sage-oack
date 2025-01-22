<!-- Modal -->
<div class="modal fade" data-bs-backdrop="static" id="studentReassginModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
    >
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Reassign Students</h5>
                <button type="button" class="close" onclick="javascript:closeReassignStudents()" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="mb-3">
                    <label for="reassignStudentInfo" class="form-label">Student Info</label>
                    <span id="reassignStudentInfo"></span>
                </div>
                <div class="mb-3">
                    <label for="reassignTeaqchersAvailable" class="form-label">Teachers Available</label>
                    <select id="reassignTeaqchersAvailable" class="form-select" aria-label="Default select example">
                        <option value="0" selected>Select New Teacher</option>
                        @foreach ($teachersAvailable as $row)
                        <option value="{{$row->teacher_id}}">{{ $row->last_name . ' ' . $row->first_name . ' -> ' . $row->teacher_id }}</option>
                        @endforeach
                    </select>
                    <input type="hidden" name="reassignStudentId" id="reassignStudentId" value="">
                </div>

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal" onclick="javascript:closeReassignStudents()">Close</button>
                <button type="button" class="btn btn-primary" onclick="javascript:saveReassignStudent()">Save changes</button>
            </div>
        </div>
    </div>
</div>
