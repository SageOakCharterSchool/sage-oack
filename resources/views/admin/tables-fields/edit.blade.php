@extends('layouts.admin')

@section('content')

    <div class="row">
        <div class="col-md-12">
            @include("layouts.includes.admin._messages")
            <div class="card">
                <div class="card-header">
                    <h4>Edit Table
                        <a  class="btn btn-primary btn-sm float-end"  href="{{ url('/admin/table-def') }}">Back</a>
                    </h4>
                </div>
                <div class="card-body">
                    <form action="{{ url('admin/table-def/' . $table->id ) }}" method="post" enctype="multipart/form-data" >
                        @csrf
                        @method('PUT')
                        <div class="row">


                            <div class="col-md-6 mb-3">
                                <label for="" class="form-label">Table Alias</label>
                                <input type="text" value="{{ (  !is_null(request()->input('table_alias')) ? request()->input('table_alias'): ( old('table_alias',$table->table_alias  ) ) ) }}" class="form-control" id="table_alias" name="table_alias" placeholder="table name">
                                @error('table_alias')
                                    <small class="text-danger">{{$message}}</small>
                                @enderror
                            </div>
                        </div>
                        <div class="col-12">
                            <button class="btn btn-primary float-end" type="submit">Update</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

