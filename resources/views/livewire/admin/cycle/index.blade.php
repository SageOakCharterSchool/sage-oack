<div>
    <!-- Modal -->
    <div wire:ignore.self class="modal fade" id="deleteRecordModal" tabindex="-1" aria-labelledby="deleteRecordModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="exampleModalLabel">Cycle Delete</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form  wire:submit.prevent="destroyCycle">
                    <div class="modal-body">
                        <h6>Are you sure you want to delete this Cycle...?</h6>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-danger">Yes Delete</button>
                    </div>
                </form>
            </div>
        </div>
    </div>


    <div class="row">
        <div class="col-md-12">
            @if (session('message'))
                <h2 class="alert alert-success"  >{{ session('message') }},</h2>
            @endif
            @if (session('error-message'))
                <h2 class="alert alert-warning"  >{{ session('error-message') }},</h2>
            @endif
            <div class="card">
                <div class="card-header">
                    <h4>Cycles
                        <a  class="btn btn-warning btn-sm float-end"  href="{{ url('admin/cycle/create') }}">Add Cycle</a>
                    </h4>
                </div>
                <div class="card-body">
                    <table class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Date From</th>
                                <th>Date To</th>
                                <th>Cycle Name</th>
                                <th>Options</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($cycles as $cycle)
                            <tr>
                                <th>{{ $cycle->id}}</th>
                                <th>{{ $cycle->date_from}}</th>
                                <th>{{ $cycle->date_to}}</th>
                                <th>{{ $cycle->cycle_name}}</th>
                                <th>
                                    <a class="btn btn-primary btn-sm" href="{{ url('/admin/cycle/' . $cycle->id . '/edit') }}">Edit</a>
                                    <a class="btn btn-danger  btn-sm" wire:click="deleteCycle({{$cycle->id}})" href="#" data-bs-toggle="modal" data-bs-target="#deleteRecordModal">Delete</a>
                                </th>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                    <div class="mt-2 d-flex align-items-center justify-content-center">
                        {{ $cycles->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('script')
<script>
    window.addEventListener('close-modal', event => {
        $("#deleteRecordModal").modal('hide');
    });
</script>
@endpush
