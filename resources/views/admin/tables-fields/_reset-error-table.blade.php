<div class="card mt-2">
    <div class="card-header">
        <h4>Reset Error Table
            <a  class="btn btn-warning btn-sm float-end"  href="{{ url('admin/table-def') }}">Back</a>
        </h4>
    </div>
    <div class="card-body">
        <div class="alert alert-warning" role="alert">
            This process will restart upload table that has bee errored due any reason

            <p>
                * run this option only if a the upload has been in process for more than 30 min.<br>
                ** this process may take a few minutes to run
            </p>
          </div>
        <form action="{{ url('admin/table-def/reset-error-table') }}" method="post" enctype="multipart/form-data" >
            @csrf
            <div class="row">


                <div class="col-md-6 mb-3">
                    <label for="" class="form-label">Enter Table Id to reset upload</label>
                    <input type="number" class="form-control" name="reset_table_id" id="reset_table_id" value="0">

                    @error('reset_table_id')
                        <small class="text-danger">{{$message}}</small>
                    @enderror
                </div>
                </div>
                <div class="row">
                <div class="col-md-6 mb-3">
                    <label for="" class="form-label">Confirm restart errored table</label>
                    <input type="checkbox" class="form-check-input" name="reset_errored_table" id="reset_errored_table" value="1">

                    @error('reset_errored_table')
                        <small class="text-danger">{{$message}}</small>
                    @enderror
                </div>
                <div class="row">
                <div class="col-md-6 mb-3">
                    <label for="" class="form-label">Confirm remove errored table</label>
                    <input type="checkbox" class="form-check-input" name="remove_errored_table" id="remove_errored_table" value="1">

                    @error('remove_errored_table')
                        <small class="text-danger">{{$message}}</small>
                    @enderror
                </div>

            </div>
            @if ($erroredTable)
            <div class="col-12">
                <button class="btn btn-primary float-end" type="submit">Reset Errored Table</button>
            </div>
            @endif
        </form>
    </div>
</div>
