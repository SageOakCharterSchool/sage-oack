<!-- Modal -->
<div wire:ignore.self class="modal fade" id="user-add-modal" data-bs-backdrop="static" data-bs-keyboard="false"
    tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">

    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="exampleModalLabel">Create User</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" wire:click="closeModal"
                    aria-label="Close"></button>
            </div>
            <form wire:submit.prevent="saveUser">
                <div class="modal-body">
                    <div class="mb-3">

                        <div class="mb-3">
                            <label for="exampleFormControlInput1" class="form-label">Name</label>
                            <input type="text" wire:model.defer="name" class="form-control" id="room_name"
                                placeholder="Full Name">
                            @error('name')
                                <span class="error text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <label for="exampleFormControlInput1" class="form-label">Email</label>
                            <input type="email" wire:model.defer="email" class="form-control" id="room_name"
                                placeholder="Email Address">
                            @error('email')
                                <span class="error text-danger">{{ $message }}</span>
                            @enderror
                        </div>

                    </div>
                    <div class="col-md-12 mb-3">
                        <label for="" class="form-label">User Type</label>
                        <div class="">
                            <input class="form-check-input" wire:model.defer="role_as" type="radio" value='3'
                                name="role_as" id="role_as0">
                            <label class="form-check-label" for="role_as0">
                                Managers
                            </label>
                        </div>
                        <div class="mt-1">
                            <input class="form-check-input" wire:model.defer="role_as" type="radio" value='2'
                                name="role_as" id="role_as2" checked>
                            <label class="form-check-label" for="role_as2">
                                Teacher
                            </label>
                        </div>
                        <div class="mt-1">
                            <input class="form-check-input" wire:model.defer="role_as" type="radio" value='4'
                                name="role_as" id="role_as4" checked>
                            <label class="form-check-label" for="role_as4">
                                Specialist
                            </label>
                        </div>
                        @error('role_as')
                            <span class="error text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" wire:click="closeModal"
                        data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Save changes</button>
                </div>
            </form>
        </div>
    </div>
</div>
