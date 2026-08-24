@extends('layouts.admin')

@section('template_title')
    {{ $equivalence->name ?? __('Show') . " " . __('Equivalence') }}
@endsection

@section('content')
    <section class="content container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header" style="display: flex; justify-content: space-between; align-items: center;">
                        <div class="float-left">
                            <span class="card-title">{{ __('Show') }} Equivalence</span>
                        </div>
                        <div class="float-right">
                            <a class="btn btn-primary btn-sm" href="{{ route('equivalences.index') }}"> {{ __('Back') }}</a>
                        </div>
                    </div>

                    <div class="card-body bg-white">

                        <div class="form-group mb-2 mb20">
                            <strong>Equivalence:</strong>
                            {{ $equivalence->equivalence }}
                        </div>
                        <div class="form-group mb-2 mb20">
                            <strong>Value:</strong>
                            {{ $equivalence->value }}
                        </div>
                        <div class="form-group mb-2 mb20">
                            <strong>Color:</strong>
                            {{ $equivalence->color }}
                        </div>
                        <div class="form-group mb-2 mb20">
                            <strong>Created By:</strong>
                            {{ $equivalence->created_by }}
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
