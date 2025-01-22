<!-- Modal -->
<div class="modal fade" id="deleteTableField" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <form action="{{ url('/admin/field-def/delete')}}" method="POST">
            @csrf
            @method('delete')

            <div class="modal-header">
            <h1 class="modal-title fs-5" id="exampleModalLabel">Confirm Delete Table Field</h1>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <input type="hidden" name="fieldId" id="fieldId" value="">
                <input type="hidden" name="tableId" id="tableId" value="">
            Are you sure to delete this field <span id="columnName"></span>  from Table {{$table->table_name}}
            </div>
            <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            <button type="submit" class="btn btn-danger">Confirm</button>
            </div>
        </form>
      </div>
    </div>
  </div>
<div>
