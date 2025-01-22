<div>
    <div class="row">
        <div class="col-md-12">
            @if (session('message'))
                <h2 class="alert alert-success">{{ session('message') }},</h2>
            @endif
            <div class="card">
                <div class="card-header">
                    <h4>
                        Users Enabled to Impersonate

                    </h4>
                </div>
                <div class="card-body">

                    <table class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Name</th>
                                <th>Status</th>
                                <th>Created</th>
                                <th>Options</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($this->impersonatedUsersList as $user)
                                <tr>
                                    <th>{{ $user->id }}</th>
                                    <th>{{ $user->name }}</th>
                                    <th>{{ $user->status == 0 ? 'Inactive' : 'Active' }}</th>
                                    <th>{{ $user->created_at }}</th>
                                    <th>
                                        @if ($user->status == 1)
                                            <a class="btn btn-danger float-end"
                                                wire:click="disableImpersonation({{ $user->user_id }})">Disable
                                                Impersonation</a>
                                        @else
                                            <a class="btn btn-success float-end"
                                                wire:click="enableImpersonation({{ $user->user_id }})">Enable
                                                Impersonation</a>
                                        @endif
                                    </th>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="3">
                                        No Record Found
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                    {{-- <div class="mt-2 d-flex align-items-center justify-content-center">
                        {{ $this->users->links() }}
                    </div> --}}
                </div>
            </div>
        </div>
    </div>
</div>
@push('script')
    <script>

        window.addEventListener('swal:enable-impersonation', event => {
            swal({
                    title: event.detail.message,
                    text: event.detail.text,
                    icon: event.detail.type,
                })

        });
        window.addEventListener('swal:disable-impersonation', event => {
            swal({
                title: event.detail.message,
                text: event.detail.text,
                icon: event.detail.type,
            });
        });
    </script>
@endpush
