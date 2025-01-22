@extends('layouts.admin')

@section('content')
@include('admin.fields-definition._modal_delete_field')


<div>
    <div class="row">
        <div class="col-md-12">
            @if (session('message'))
                <h2 class="alert alert-success"  >{{ session('message') }},</h2>
            @endif
            @if (session('error-message'))
                <h2 class="alert alert-warning"  >{{ session('error-message') }},</h2>
            @endif
            <div class="card">
                <div class="card-header">
                    <h4>Fields on Table {{$table->table_name}}
                        <a  class="btn btn-warning btn-sm float-end"  href="{{ url('admin/table-def') }}">Back</a>
                    </h4>
                </div>
                <div class="card-body">
                    <h4>Fields on Table {{$table->table_name}}
                        {{-- <a  class="btn btn-success btn-sm float-end"  href="{{ url('admin/field-def/' . $table->id . '/create') }}">Add Field</a> --}}
                    </h4>
                    <table class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Column</th>
                                <th>Heading</th>
                                <th>Stud Id</th>
                                {{-- <th>Options</th> --}}
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($tableFields as $field)
                            <tr>
                                <th>{{ $field->id}}</th>
                                <th>{{ $field->column}}</th>
                                <th>{{ $field->column_title}}</th>
                                <th>{{ ($field->is_student_id == 1) ? "Yes":"No"}}</th>
                                {{-- <th>
                                    <a class="btn btn-primary btn-sm" href="{{ url('/admin/field-def/' . $table->id . '/' . $field->id . '/edit') }}">Edit</a>
                                    <button class="btn btn-danger btn-sm deleteTableFieldButton" onclick="deleteTableField({{$table->id}},{{$field->id}},'{{$field->column}}')">Delete</button>
                                </th> --}}
                            </tr>
                            @endforeach
                        </tbody>
                    </table>

                </div>
            </div>
        </div>
    </div>
</div>

@endsection

@push('script')
    <script>
        function deleteTableField(tableId, fieldId,column) {
            $("#columnName").html(column);
            $("#fieldId").val(fieldId);
            $("#tableId").val(tableId);
            $("#deleteTableField").modal('show');
        }
    </script>
@endpush

