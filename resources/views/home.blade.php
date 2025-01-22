@extends('layouts.user')

@section('content')
<style>
    .border-success, .border-primary, .border-warning, .border-danger {
        border-width: medium !important;
    }

</style>
<div class="row">
    <div class="col-md-10 offset-md-1 grid-margin">
        @if (session('message'))
            <h2 class="alert alert-success"  >{{ session('message') }},</h2>
        @endif

        <div class="row row-cols-1 row-cols-md-2 g-4" >



            <div class="col">
              <div class="card">

                <div class="card-body border border-danger" style="height:230px">
                  <h5 class="card-title">Reports</h5>
                  <p class="card-text">
                    Will hold a reports repository that will show all the information uploaded based on predefined templates.
                  </p>
                  <div class="text-center mt-5">
                    <a href="/admin/consolidate-view"  class="btn btn-danger text " >
                        View all students as csv
                    </a>
                </div>
                </div>
              </div>
            </div>
          </div>


    </div>
</div>

@endsection
