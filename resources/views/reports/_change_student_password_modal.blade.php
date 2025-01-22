<!-- Modal -->
<div class="modal fade" data-bs-backdrop="static" id="studentChangePasswordModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
    >
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Change Password</h5>
                <button type="button" class="close" onclick="javascript:closeChageStudentPassword()" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <table class="table">
                    <tbody>
                        <tr>
                            <th>Student Id</th>
                            <td><span id="studentIdToChange"></span></td>
                        </tr>
                        <tr>
                            <th>Student Name</th>
                            <td><span id="studentNameToChange"></span></td>
                        </tr>
                        <tr>
                            <th>Student Password</th>
                            <td>
                                <div class="form-group">
                                    <input type="text" class="form-control" id="studentPassword" maxlength="8"
                                        placeholder="Enter Password">
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <th>Password Updated?</th>
                            <td>
                                <div class="form-group">
                                    <input type="checkbox" class="" id="password_changed" value="1">
                                </div>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal" onclick="javascript:closeChageStudentPassword()">Close</button>
                <button type="button" class="btn btn-primary" onclick="javascript:changeStudentPassword()">Save changes</button>
            </div>
        </div>
    </div>
</div>
