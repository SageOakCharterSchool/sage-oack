@extends('layouts.admin')

@section('content')

    <div class="row">
        <div class="col-md-12">
            @if (session('message'))
                <h2 class="alert alert-success"  >{{ session('message') }},</h2>
            @endif
            <div class="card">
                <div class="card-header">
                    <h4>Edit User
                        <a  class="btn btn-warning btn-sm float-end"  href="{{ url('admin/user') }}">Back</a>
                    </h4>
                </div>
                <div class="card-body">
                    <form action="{{ url('admin/user/' . $user->id ) }}" method="post" enctype="multipart/form-data" >
                        @csrf
                        @method('PUT')
                        <div class="col-md-12 mb-3">
                            <label for="" class="form-label">Name</label>
                            <input readonly  type="text" value="{{ old('name', $user->name) }}" class="form-control" id="name" name="name" placeholder="Enter User Name">
                            @error('name')
                                <small class="text-danger">{{$message}}</small>
                            @enderror
                        </div>
                        <div class="col-md-12 mb-3">
                            <label for="" class="form-label">Email</label>
                            <input readonly  type="email" value="{{ old('email', $user->email) }}" class="form-control" id="email" name="email" placeholder="Enter User Email">
                            @error('email')
                                <small class="text-danger">{{$message}}</small>
                            @enderror
                        </div>
                        <div class="col-md-12 mb-3">
                            <label for="" class="form-label">User Type</label>
                            <div class="">
                                <input class="form-check-input" type="radio" value='0' name="role_as" id="role_as0" {{ ($user->role_as == 0) ? 'checked' : '' }} >
                                <label class="form-check-label" for="role_as0">
                                    Student
                                </label>
                            </div>
                            <div class="mt-1">
                                <input class="form-check-input" type="radio" value='2' name="role_as" id="role_as2" {{ ($user->role_as == 2) ? 'checked' : '' }}>
                                <label class="form-check-label" for="role_as2">
                                    Teacher
                                </label>
                            </div>
                            <div class="mt-1">
                                <input class="form-check-input" type="radio" value='4' name="role_as" id="role_as4" {{ ($user->role_as == 4) ? 'checked' : '' }}>
                                <label class="form-check-label" for="role_as4">
                                    Specialist
                                </label>
                            </div>
                            <div class="mt-1">
                                <input class="form-check-input" type="radio" value='3' name="role_as" id="role_as2" {{ ($user->role_as == 3) ? 'checked' : '' }}>
                                <label class="form-check-label" for="role_as2">
                                    Manager
                                </label>
                            </div>
                            <div class="mt-1">
                                <input class="form-check-input" type="radio" value='1' name="role_as" id="role_as2" {{ ($user->role_as == 1) ? 'checked' : '' }}>
                                <label class="form-check-label" for="role_as2">
                                    Admin
                                </label>
                            </div>
                        </div>
                        <div class="col-md-12 mb-3">
                            <label for="" class="form-label">Status</label>
                            <div class="">
                                <input class="form-check-input" type="radio" value='1' name="status" id="status1" {{ ($user->status == 1) ? 'checked' : '' }} >
                                <label class="form-check-label" for="status1">
                                    Active
                                </label>
                            </div>
                            <div class="mt-1">
                                <input class="form-check-input" type="radio" value='0' name="status" id="status2" {{ ($user->status == 0) ? 'checked' : '' }}>
                                <label class="form-check-label" for="status2">
                                    Inactive
                                </label>
                            </div>
                        </div>

                        <div class="col-12">
                            <button class="btn btn-primary float-end" type="submit">Save</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

