@extends('layouts.admin')

@section('content')
<h3>Consolidated break-down (Sheet0)</h3>
    <table class="table">
        @foreach ($tableColumnInfo as $colInfo)
        @php
            $analysis = JMHelper::JMGetFieldAnalysis($colInfo,$tableValues,$consolidate);
        @endphp
        <tr>
            @if ($colInfo->Comment != "")
                <td style="width: 15%">{{$colInfo->Comment}}</td>
                <td style="width: 15%">{{$colInfo->Field}}</td>
                <td style="widows: 15%">{{ ($analysis[0] ?? "") }}</td>
                <td style="widows: 15%">{{ ($analysis[1] ?? "") }}</td>
                <td style="widows: 15%">{{ ($analysis[2] ?? "") }}</td>
                <td style="widows: 15%">{{ ($analysis[3] ?? "") }}</td>
            @endif
        </tr>
        @endforeach
    </table>
@endsection
