<!-- Modal -->
<div class="modal fade" data-bs-backdrop="static" id="studentAssignSpecialistsModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
    >
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Reassign Specialist to Students</h5>
                <button type="button" class="close" onclick="javascript:closeAssignStudentsToSpecialist()" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="mb-3">
                    <label for="reassignStudentInfo" class="form-label">Student Info</label>
                    <span id="assignStudentInfo"></span>
                </div>
                <div class="mb-3">
                    <label for="reassignTeaqchersAvailable" class="form-label">Specialist Available</label>
                    <select id="assignSpecialistAvailable" class="form-select" aria-label="Default select example">
                        <option value="0" selected>Select New Specialist</option>
                        @foreach ($specialistAvailable as $row)
                        <option value="{{$row->specialist_id}}">{{ $row->last_name . ' ' . $row->first_name . ' -> ' . $row->specialist_id }}</option>
                        @endforeach
                    </select>
                    <input type="hidden" name="assignSpecialistStudentId" id="assignSpecialistStudentId" value="">
                </div>

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal" onclick="javascript:closeAssignStudentsToSpecialist()">Close</button>
                <button type="button" class="btn btn-primary" onclick="javascript:saveAssignSpecialist()">Save changes</button>
            </div>
        </div>
    </div>
</div>
