@extends('layouts.admin')

@section('content')
    @if ($isConsolidated == "N")
        <a class="btn btn-primary" href="{{ $url }}">Export to PDF</a>
    @endif
    <a class="btn btn-warning float-end" href="/admin/consolidate-view">Back</a>
    {!! $html !!}
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

