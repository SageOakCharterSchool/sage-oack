@extends('layouts.admin')

@section('template_title')
    {{ $masterTableColor->name ?? __('Show') . " " . __('Consolidate Color') }}
@endsection

@section('content')
    <section class="content container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header" style="display: flex; justify-content: space-between; align-items: center;">
                        <div class="float-left">
                            <span class="card-title">{{ __('Show') }} Consolidate Color</span>
                        </div>
                        <div class="float-right">
                            <a class="btn btn-primary btn-sm" href="{{ route('consolidate-colors.index') }}"> {{ __('Back') }}</a>
                        </div>
                    </div>

                    <div class="card-body bg-white">

                        <div class="form-group mb-2 mb20">
                            <strong>Cycle Id:</strong>
                            {{ $masterTableColor->cycle_id }}
                        </div>
                        <div class="form-group mb-2 mb20">
                            <strong>Table Name:</strong>
                            {{ $masterTableColor->table_name }}
                        </div>
                        <div class="form-group mb-2 mb20">
                            <strong>Value:</strong>
                            {{ $masterTableColor->value }}
                        </div>
                        <div class="form-group mb-2 mb20">
                            <strong>Color:</strong>
                            {{ $masterTableColor->color }}
                        </div>
                        <div class="form-group mb-2 mb20">
                            <strong>Created By:</strong>
                            {{ $masterTableColor->created_by }}
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
