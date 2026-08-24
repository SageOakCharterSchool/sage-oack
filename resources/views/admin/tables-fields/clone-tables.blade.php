@extends('layouts.admin')

@section('content')

    <div class="row">
        <div class="col-md-12">
            @include("layouts.includes.admin._messages")
            @include("admin.tables-fields._clone-tables")
            @include("admin.tables-fields._reset-tables")
            @include("admin.tables-fields._reset-consolidate")
            @if (Auth::user()->id == 1)
            @include("admin.tables-fields._reset-error-table")
            @endif

        </div>
    </div>
@endsection
@push('script')
    <script>
        $(".protectMe").click(function() {
            alert("here");
            $.blockUI({ message: "<h1>Please Wait!</h1>" });
        });
    </script>
@endpush

