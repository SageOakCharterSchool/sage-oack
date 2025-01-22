@extends('layouts.admin')

@section('content')

    <div class="row">
        <div class="col-md-12">
            @include("layouts.includes.admin._messages")
            <div class="card">
                <div class="card-header">
                    <h4>Create Tables
                        <a  class="btn btn-warning btn-sm float-end"  href="{{ url('admin/table-def') }}">Back</a>
                    </h4>
                </div>
                <div class="card-body">
                    <form action="{{ url('admin/table-def/store') }}" method="post" enctype="multipart/form-data" >
                        @csrf
                        <div class="row">

                            <div class="col-md-6 mb-3">
                                <label for="" class="form-label">Table Name</label>
                                <input type="text" value="{{old('table_name')}}" class="form-control" id="table_name" name="table_name" placeholder="table name">
                                @error('table_name')
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

