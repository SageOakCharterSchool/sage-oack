@if (Auth::user()->role_as == 1)
    @extends('layouts.admin')
@else
    @extends('layouts.user')
@endif

@section('content')

    <h4>No Report Generated</h4>
    <div class="text-center">
        <a href="/admin/view-consolidated" class="btn btn-primary btn-sm text-center">Go Consolidated</a>
    </div>
@endsection
