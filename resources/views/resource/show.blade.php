@extends('layouts.admin')

@section('template_title')
    {{ $resource->name ?? __('Show') . " " . __('Resource') }}
@endsection

@section('content')
    <section class="content container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header" style="display: flex; justify-content: space-between; align-items: center;">
                        <div class="float-left">
                            <span class="card-title">{{ __('Show') }} Resource</span>
                        </div>
                        <div class="float-right">
                            <a class="btn btn-primary btn-sm" href="{{ route('resources.index') }}"> {{ __('Back') }}</a>
                        </div>
                    </div>

                    <div class="card-body bg-white">

                        <div class="form-group mb-2 mb20">
                            <strong>Resource:</strong>
                            {{ $resource->resource }}
                        </div>
                        <div class="form-group mb-2 mb20">
                            <strong>Resource Url:</strong>
                            {{ $resource->resource_url }}
                        </div>
                        <div class="form-group mb-2 mb20">
                            <strong>Description:</strong>
                            {{ $resource->description }}
                        </div>
                        <div class="form-group mb-2 mb20">
                            <strong>Status:</strong>
                            {{ $resource->status }}
                        </div>
                        <div class="form-group mb-2 mb20">
                            <strong>Created By:</strong>
                            {{ $resource->created_by }}
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
