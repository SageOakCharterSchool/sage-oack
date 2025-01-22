@extends('layouts.admin')

@section('content')

    <div class="row">
        <div class="col-md-12">
            @include("layouts.includes.admin._messages")
            <div class="card">
                @if (session('message'))
                    <h2 class="alert alert-success"  >{{ session('message') }},</h2>
                @endif
                @if (session('error-message'))
                    <h2 class="alert alert-warning"  >{{ session('error-message') }},</h2>
                @endif
                <div class="card-header">
                    <h4>Create Field on Table {{$table->table_name}}
                        <a  class="btn btn-warning btn-sm float-end"  href="{{ url('admin/field-def/' . $table->id . '/fields') }}">Back</a>
                    </h4>
                </div>
                <div class="card-body">
                    <form action="{{ url('/admin/field-def/'. $table->id . '/store') }}" method="post" enctype="multipart/form-data" >
                        @csrf
                        <div class="row">

                            <div class="col-md-6 mb-3">
                                <label for="" class="form-label">Column</label>
                                <input type="text" value="{{old('column')}}" class="form-control" id="column" name="column" placeholder="column">
                                @error('column')
                                    <small class="text-danger">{{$message}}</small>
                                @enderror
                            </div>
                        </div>
                        <div class="row">

                            <div class="col-md-6 mb-3">
                                <label for="" class="form-label">Column Heading</label>
                                <input type="text" value="{{old('column_title')}}" class="form-control" id="column_title" name="column_title" placeholder="column heading">
                                @error('column_title')
                                    <small class="text-danger">{{$message}}</small>
                                @enderror
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="" class="form-label">Is Student Id?</label>
                                <div class="form-check2">
                                    <input type="radio" class="form-check-input" id="is_student_id_no" name="is_student_id" value="0"  @if(old('is_student_id') == 0) checked @endif >No
                                    <label class="form-check-label" for="radio1"></label>
                                </div>
                                <div class="form-check2 mt-2">
                                    <input type="radio" class="form-check-input" id="is_student_id_yes" name="is_student_id" value="1" @if(old('is_student_id') == 1) checked @endif >Yes
                                    <label class="form-check-label" for="radio2"></label>
                                </div>
                                @error('is_student_id')
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

