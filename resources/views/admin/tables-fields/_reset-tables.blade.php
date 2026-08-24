<div class="card mt-2">
    <div class="card-header">
        <h4>Reset Tables
            <a  class="btn btn-warning btn-sm float-end"  href="{{ url('admin/table-def') }}">Back</a>
        </h4>
    </div>
    <div class="card-body">
        <div class="alert alert-info" role="alert">
            This process will remove all the info on
            <ul>
                <li>student_tables</li>
                <li>teacher_students</li>
            </ul>
            <p>
                * run this option only if a wrong mapping happened on either of these tables.<br>
                ** this process may take a few minutes to run
            </p>
          </div>
        <form action="{{ url('admin/table-def/reset-tables-info') }}" method="post" enctype="multipart/form-data" >
            @csrf
            <div class="row">

                <div class="col-md-6 mb-3">
                    <label for="" class="form-label">Confirm reset tables</label>
                    <input type="checkbox" class="form-check-input" name="reset_tables" id="reset_tables" value="1">

                    @error('reset_tables')
                        <small class="text-danger">{{$message}}</small>
                    @enderror
                </div>
            </div>

            <div class="col-12">
                <button class="btn btn-primary float-end" type="submit">Reset Tables</button>
            </div>
        </form>
    </div>
</div>
