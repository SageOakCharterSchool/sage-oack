@extends('layouts.admin')

@section('content')

<div class="container">
    <ul class="nav nav-tabs mb-3">
        <li class="nav-item protectMe">
          <a class="nav-link " aria-current="page" href="{{ url('/admin/upload') }}">Step 1 - Upload Files</a>
        </li>
        <li class="nav-item protectMe">
          <a class="nav-link " href="{{ url('/admin/process-files') }}">Step 2 - Process Files</a>
        </li>
        <li class="nav-item protectMe">
          <a class="nav-link active" href="{{ url('/admin/consolidate') }}">Step 3 - Consolidate</a>
        </li>
        <li class="nav-item protectMe">
          <a class="nav-link  protectMe" href="{{ url('/admin/view-consolidated') }}">Step 4 - View Reports</a>
        </li>
    </ul>
        <div class="card border border-primary">
            <div class="card-header">
                Consolidate All Files
            </div>
            <div class="card-body">

                <form action="/admin/consolidate-all-files" method="post" >
                    @csrf
                    @if(Session::has('error-message'))
                        <p class="alert alert-warning">{{ Session::get('error-message') }}</p>
                    @endif
                    @if(Session::has('success-message'))
                        <p class="alert alert-success">{{ Session::get('success-message') }}</p>
                    @endif
                    <div class="row">
                        <h5 class="card-title">Consolidation</h5>
                        <p class="card-text">This process will generate a Sheet-0 tab</p>
                        <p>
                            <label for="inputPassword5" class="form-label">Confirm Consolidation process</label>
                            <input class="form-check-input" type="checkbox" value="1" id="confirmProcess" name="confirmProcess">
                        </p>
                    </div>
                    <div class="text-center">
                        <button class="btn btn-warning protectMe">Process</button>
                    </div>
                </form>
            </div>
        </div>
</div>

@endsection
