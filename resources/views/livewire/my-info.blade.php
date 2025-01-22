<div>
    <div class="card">
        <div class="card-header">
          Update My Info
        </div>
        <div class="card-body">
            <form wire:submit.prevent="saveMyInfo">
                <div class="form-group">
                  <label for="exampleInputEmail1">Email address</label>
                  <input type="email" readonly wire:model.defer="email"  class="form-control" id="exampleInputEmail1" aria-describedby="emailHelp" placeholder="Enter email">
                  <small id="emailHelp" class="form-text text-muted"></small>
                </div>
                <div class="form-group">
                  <label for="exampleInputPassword1">Name</label>
                  <input type="text" wire:model.defer="name"  class="form-control" >
                    @error('name') <span class="text-danger">{{$message}}</span> @enderror
                </div>

              </form>
        </div>
        <div class="card-footer">
            <button type="submit" wire:click="saveMyInfo" class="btn btn-primary float-end">Update My Info</a>
        </div>
    </div>
</div>
