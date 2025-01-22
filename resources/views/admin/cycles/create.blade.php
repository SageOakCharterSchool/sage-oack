@extends('layouts.admin')

@section('content')

    <div class="row">
        <div class="col-md-12">
            @if (session('message'))
                <h2 class="alert alert-success"  >{{ session('message') }},</h2>
            @endif
            <div class="card">
                <div class="card-header">
                    <h4>Add Cycles
                        <a  class="btn btn-warning btn-sm float-end"  href="{{ url('admin/cycle') }}">Back</a>
                    </h4>
                </div>
                <div class="card-body">
                    <form action="{{ url('admin/cycle') }}" method="post" enctype="multipart/form-data" >
                        @csrf
                        <div class="row">

                            <div class="col-md-6 mb-3">
                                <label for="" class="form-label">Start Date</label>
                                <input type="date" value="{{(!is_null(old('date_from')) ? old('date_from', date('Y-m-d',strtotime(request()->input('date_from')))) : date('Y-m-d'))}}" class="form-control" id="date_from" name="date_from" placeholder="Enter Cycle start Date">
                                @error('date_from')
                                    <small class="text-danger">{{$message}}</small>
                                @enderror
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="" class="form-label">End Date</label>
                                <input type="date" value="{{(!is_null(old('date_to')) ? old('date_to', date('Y-m-d',strtotime(request()->input('date_to')))) : date('Y-m-d'))}}" class="form-control" id="date_to" name="date_to" placeholder="Enter Cycle end Date">
                                @error('date_to')
                                    <small class="text-danger">{{$message}}</small>
                                @enderror
                            </div>
                        </div>
                        <div class="row">

                            <div class="col-md-12 mb-3">
                                <label for="" class="form-label">Cycle Name</label>
                                <input type="text" class="form-control" id="cycle_name" name="cycle_name" placeholder="Enter Cycle name">
                                @error('cycle_name')
                                    <small class="text-danger">{{$message}}</small>
                                @enderror
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

