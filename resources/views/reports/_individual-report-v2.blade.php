
<style>
     .page-break {
        page-break-after: always;
    }
    table {
            font-size:12px;
            /* table-layout: fixed */
    }
    th, td {
       border: 1px solid;
    }
    th {
        background: #C8D3CA !important;
    }
    .noborder {
       border: none;
    }
    table.noBorder td
    {
        border: none !important;
    }
    @page {
        margin-top:0px;
        margin-bottom:0px;
    }

</style>
<div class="container page-break" style="margin-left:10px;margin-top:10px;">

    @include('reports._individual-report-v2-01')
    @include('reports._individual-report-v2-02')
    @include('reports._individual-report-v2-03')
    @include('reports._individual-report-v2-04')

</div>
@push('css')
@endpush
