@extends('layouts.admin')

@section('template_title')
    {{ $formula->name ?? __('Show') . " " . __('Formula') }}
@endsection

@section('content')
    <section class="content container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header" style="display: flex; justify-content: space-between; align-items: center;">
                        <div class="float-left">
                            <span class="card-title">{{ __('Show') }} Formula</span>
                        </div>
                        <div class="float-right">
                            <a class="btn btn-primary btn-sm" href="{{ route('formulas.index') }}"> {{ __('Back') }}</a>
                        </div>
                    </div>

                    <div class="card-body bg-white">

                                <div class="form-group mb-2 mb20">
                                    <strong>Cycle Id:</strong>
                                    {{ $formula->cycle_id }}
                                </div>
                                <div class="form-group mb-2 mb20">
                                    <strong>Formula Name:</strong>
                                    {{ $formula->formula_name }}
                                </div>
                                <div class="form-group mb-2 mb20">
                                    <strong>Formula Description:</strong>
                                    {{ ($formula->formula_description) }}
                                </div>
                                <div class="form-group mb-2 mb20">
                                    <strong>Formula:</strong>
                                    {{ \App\Models\Formula::replaceChars($formula->formula) }}
                                </div>
                                <div class="form-group mb-2 mb20">
                                    <strong>Created By:</strong>
                                    {{ $formula->created_by }}
                                </div>

                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
