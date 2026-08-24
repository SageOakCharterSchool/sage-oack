<div class="card mt-2">
    <div class="card-header">
        <h4>Reset Consolidated
            <a  class="btn btn-warning btn-sm float-end"  href="{{ url('admin/table-def') }}">Back</a>
        </h4>
    </div>
    <div class="card-body">
        <div class="alert alert-primary" role="alert">
            This process will reset the consolidate process in case any crash
            <p>
                * run this option only if a the consolidate process has been running for more than 60 min.<br>
                ** this process may take a few minutes to run
            </p>
          </div>
        <form action="{{ url('admin/table-def/reset-consolidated') }}" method="post" enctype="multipart/form-data" >
            @csrf
            <div class="row">

                <div >
                    @if ($consolidateGeneration->status == 1)
                    <span style="color:green;margin:20px;padding-top:20px">Completed on {{$consolidateGeneration->updated_at}}</span>
                    @elseif ($consolidateGeneration->status == 2)
                    <span style="color:red;margin:20px;padding-top:20px">Submitted on on {{$consolidateGeneration->updated_at}}</span>
                    @elseif ($consolidateGeneration->status == 3)
                    <span style="color:blue;margin:20px;padding-top:20px">In Process since {{$consolidateGeneration->updated_at}}</span>
                    @endif
                </div>
                @if ($consolidateGeneration->status != 1)
                <div class="col-md-6 mb-3">
                    <label for="" class="form-label">Confirm reset consolidated</label>
                    <input type="checkbox" class="form-check-input" name="reset_consolidated" id="reset_consolidated" value="1">

                    @error('reset_consolidated')
                        <small class="text-danger">{{$message}}</small>
                    @enderror
                </div>
                @endif
            </div>
            @if ($consolidateGeneration->status != 1)
            <div class="col-12">
                <button class="btn btn-primary float-end" type="submit">Reset Consolidated</button>
            </div>
            @endif
        </form>
    </div>
</div>
