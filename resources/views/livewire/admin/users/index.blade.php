<div>

    @include('livewire._user-add-modal')

    <div class="row">
        <div class="col-md-12">
            @if (session('message'))
                <h2 class="alert alert-success">{{ session('message') }},</h2>
            @endif
            <div class="card">
                <div class="card-header">
                    <h4>
                        Users Management
                        @if (Auth::user()->isAdmin())
                            <button type="button" class="btn btn-primary float-end" data-bs-toggle="modal"
                                data-bs-target="#user-add-modal">
                                Add New User
                            </button>
                        @endif
                    </h4>
                </div>
                <div class="card-body">
                    <div class="input-group mb-3">
                        <input type="text" class="form-control" placeholder="Name/Email/Id"
                            wire:model.defer="keyWord">
                        <div class="input-group-append">
                            <button wire:click="render" class="btn btn-outline-secondary" type="button">Search</button>
                        </div>
                    </div>
                    <table class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Name</th>
                                <th>Email</th>
                                <th>Type</th>
                                <th>Status</th>
                                <th>Verified</th>
                                <th>Created</th>
                                <th>Last Login</th>
                                <th>Options</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($this->users as $user)
                                <tr>
                                    <th>{{ $user->id }}</th>
                                    <th>{{ $user->name }}</th>
                                    <th>{{ $user->email }}</th>
                                    <th>{{ $user->role_as == 0 ? 'Student' : ($user->role_as == 2 ? 'Teacher' : ($user->role_as == 3 ? 'Manager' : ($user->role_as == 4 ? 'Specialist':'Admin'))) }}
                                    </th>
                                    <th>{{ $user->status == 0 ? 'Inactive' : 'Active' }}</th>
                                    <th>{{ $user->email_verified == 0 ? 'No' : 'Yes' }}</th>
                                    <th>{{ $user->created_at }}</th>
                                    <th>{{ $user->last_login }}</th>
                                    <th>

                                        <div class="btn-group" role="group"
                                            aria-label="Button group with nested dropdown">

                                            <div class="btn-group" role="group">
                                                <button id="btnGroupDrop1" type="button"
                                                    class="btn btn-primary dropdown-toggle" data-bs-toggle="dropdown"
                                                    aria-expanded="false">
                                                    Options
                                                </button>
                                                <ul class="dropdown-menu" aria-labelledby="btnGroupDrop1">
                                                    @if (Auth::user()->isAdmin())
                                                        <li><a class="dropdown-item"
                                                                wire:click="alertResendEmail({{ $user->id }})">Resend
                                                                Email</a></li>
                                                    @endif
                                                    @if (Auth::user()->isAdmin())
                                                        @if (!\App\Models\UsersEnabledToImpersonate::checkIfUserHasImpersonatePermissions($user->id))
                                                        <li>
                                                            <a class="dropdown-item"
                                                                wire:click="enableImpersonation({{ $user->id }})">Enable Impersonation</a>
                                                        </li>
                                                        @else
                                                        <li>
                                                            <a class="dropdown-item"
                                                                wire:click="disableImpersonation({{ $user->id }})">Disable Impersonation</a>
                                                        </li>
                                                        @endif
                                                    @endif
                                                    @if (Auth::user()->isAdmin())
                                                        @if ($user->email_verified != 0)
                                                            <li>
                                                                <a class="dropdown-item"
                                                                    href="{{ url('/admin/impersonate/' . $user->id . '') }}">Impersonate
                                                                    User</a>
                                                            </li>
                                                        @endif
                                                    @else
                                                        @if (\App\Models\UsersEnabledToImpersonate::checkIfUserHasImpersonatePermissions(Auth::user()->id))
                                                            @if ($user->email_verified != 0)
                                                                <li>
                                                                    <a class="dropdown-item"
                                                                        href="{{ url('/admin/impersonate/' . $user->id . '') }}">Impersonate
                                                                        User</a>
                                                                </li>
                                                            @endif
                                                        @endif
                                                    @endif

                                                    @if (Auth::user()->isAdmin())
                                                        <li>
                                                            @if ($user->email_verified != 0)
                                                                <a class="dropdown-item"
                                                                    href="{{ url('/admin/user/' . $user->id . '/edit') }}">Edit
                                                                    User</a>
                                                            @endif
                                                        </li>
                                                    @endif
                                                    @if (Auth::user()->isAdmin())
                                                        <li>
                                                            @if ($user->email_verified != 0 and $user->role_as == 2)
                                                                <a class="dropdown-item"
                                                                    href="{{ url('/admin/user/' . $user->id . '/show-students-feed') }}">Show
                                                                    sources</a>
                                                            @endif
                                                        </li>
                                                    @endif
                                                    @if (Auth::user()->isAdmin())
                                                        <li><a class="dropdown-item"
                                                                wire:click="alertConfirm({{ $user->id }})">Delete
                                                                User</a></li>
                                                    @endif
                                                </ul>
                                            </div>
                                        </div>


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
                    <div class="mt-2 d-flex align-items-center justify-content-center">
                        {{ $this->users->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('script')
    <script>
        window.addEventListener('close-add-modal', event => {
            $("#user-add-modal").modal('hide');
        })
        window.addEventListener('close-modal', event => {
            $("#deleteRecordModal").modal('hide');
        });
        window.addEventListener('swal:modal', event => {
            swal({
                title: event.detail.message,
                text: event.detail.text,
                icon: event.detail.type,
            });
        });
        window.addEventListener('swal:resend-email-modal', event => {
            swal({
                title: event.detail.message,
                text: event.detail.text,
                icon: event.detail.type,
            });
        });

        window.addEventListener('swal:confirm', event => {
            swal({
                    title: event.detail.message,
                    text: event.detail.text,
                    icon: event.detail.type,
                    buttons: true,
                    dangerMode: true,
                })
                .then((willDelete) => {
                    if (willDelete) {
                        window.livewire.emit('remove', event.detail.id);
                    }
                });
        });
        window.addEventListener('swal:confirmResendEmail', event => {
            swal({
                    title: event.detail.message,
                    text: event.detail.text,
                    icon: event.detail.type,
                    buttons: true,
                    dangerMode: true,
                })
                .then((willDelete) => {
                    if (willDelete) {
                        window.livewire.emit('resendRegistrationEmail', event.detail.id);
                    }
                });
        });
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
