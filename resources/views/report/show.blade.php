@extends('layouts.admin')

@section('template_title')
    {{ $report->name ?? __('Show') . ' ' . __('Report') }}
@endsection

@section('content')
    <section class="content container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header" style="display: flex; justify-content: space-between; align-items: center;">
                        <div class="float-left">
                            <span class="card-title">{{ __('Show') }} Report</span>
                        </div>
                        <div class="float-right">
                            <a class="btn btn-primary btn-sm" href="{{ route('build-reports.index') }}">
                                {{ __('Back') }}</a>
                        </div>
                    </div>

                    <div class="card-body bg-white">


                        <div class="form-group mb-2 mb20">
                            <strong>Report Name:</strong>
                            {{ $report->report_name }}
                        </div>
                        <div class="form-group mb-2 mb20">
                            <strong>Report Description:</strong>
                            {{ $report->report_description }}
                        </div>
                        <div class="form-group mb-2 mb20">
                            <strong>Report:</strong>
                            {!! html_entity_decode($report->report) !!}
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
@push('css')
    <style>
        .page-break {
            page-break-after: always;
        }

        table {
            font-size: 12px;
            /* table-layout: fixed */
        }

        th,
        td {
            border: 1px solid;
        }

        th {
            background: #C8D3CA !important;
        }

        .noborder {
            border: none;
        }

        table.noBorder td {
            border: none !important;
        }

        @page {
            margin-top: 0px;
            margin-bottom: 0px;
        }
    </style>
@endpush
