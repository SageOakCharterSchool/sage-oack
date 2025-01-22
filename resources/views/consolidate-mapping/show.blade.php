@extends('layouts.admin')

@section('template_title')
    {{ $consolidateMapping->name ?? __('Show') . " " . __('Consolidate Mapping') }}
@endsection

@section('content')
    <section class="content container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header" style="display: flex; justify-content: space-between; align-items: center;">
                        <div class="float-left">
                            <span class="card-title">{{ __('Show') }} Consolidate Mapping</span>
                        </div>
                        <div class="float-right">
                            <a class="btn btn-primary btn-sm" href="{{ route('consolidate-mappings.index') }}"> {{ __('Back') }}</a>
                        </div>
                    </div>

                    <div class="card-body bg-white">

                                <div class="form-group mb-2 mb20">
                                    <strong>Cycle Id:</strong>
                                    {{ $consolidateMapping->cycle_id }}
                                </div>
                                <div class="form-group mb-2 mb20">
                                    <strong>Column Name:</strong>
                                    {{ $consolidateMapping->column_name }}
                                </div>
                                <div class="form-group mb-2 mb20">
                                    <strong>Column Description:</strong>
                                    {{ $consolidateMapping->column_description }}
                                </div>
                                <div class="form-group mb-2 mb20">
                                    <strong>Table Source:</strong>
                                    {{ $consolidateMapping->table_source }}
                                </div>
                                <div class="form-group mb-2 mb20">
                                    <strong>Field Source:</strong>
                                    {{ $consolidateMapping->field_source }}
                                </div>
                                <div class="form-group mb-2 mb20">
                                    <strong>Is Formulated:</strong>
                                    {{ $consolidateMapping->is_formulated }}
                                </div>
                                <div class="form-group mb-2 mb20">
                                    <strong>Formula:</strong>
                                    {{ $consolidateMapping->formula }}
                                </div>
                                <div class="form-group mb-2 mb20">
                                    <strong>Created By:</strong>
                                    {{ $consolidateMapping->created_by }}
                                </div>

                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
