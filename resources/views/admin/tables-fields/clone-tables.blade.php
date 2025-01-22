@extends('layouts.admin')

@section('content')

    <div class="row">
        <div class="col-md-12">
            @include("layouts.includes.admin._messages")
            <div class="card">
                <div class="card-header">
                    <h4>Clone Tables
                        <a  class="btn btn-warning btn-sm float-end"  href="{{ url('admin/table-def') }}">Back</a>
                    </h4>
                </div>
                <div class="card-body">
                    <div class="alert alert-warning" role="alert">
                        This process will clone Tables, Formulas, Consolidated and Reports from one cycle to another
                      </div>
                    <form action="{{ url('admin/table-def/clone-tables-store') }}" method="post" enctype="multipart/form-data" >
                        @csrf
                        <div class="row">

                            <div class="col-md-6 mb-3">
                                <label for="" class="form-label">Cycle From</label>
                                <select class="form-control" id="cycle_from" name="cycle_from" placeholder="cycle from" >
                                    <option value="">Select Cycle</option>
                                    @foreach ($cycles as $cycle)
                                        <option value="{{$cycle->id}}" {{ request()->old('cycle_from') == $cycle->id ? 'selected' : '' }}>{{$cycle->cycle_name}}</option>
                                    @endforeach
                                </select>

                                @error('cycle_from')
                                    <small class="text-danger">{{$message}}</small>
                                @enderror
                            </div>
                        </div>
                        <div class="row">

                            <div class="col-md-6 mb-3">
                                <label for="" class="form-label">Cycle To</label>
                                <select class="form-control" id="cycle_to" name="cycle_to" placeholder="cycle to" >
                                    <option value="">Select Cycle</option>
                                    @foreach ($cycles as $cycle)
                                        <option value="{{$cycle->id}}" {{ request()->old('cycle_to') == $cycle->id ? 'selected' : '' }}>{{$cycle->cycle_name}}</option>
                                    @endforeach
                                </select>
                                @error('cycle_to')
                                    <small class="text-danger">{{$message}}</small>
                                @enderror
                            </div>
                        </div>
                        <div class="col-12">
                            <button class="btn btn-primary float-end" type="submit">Clone</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

