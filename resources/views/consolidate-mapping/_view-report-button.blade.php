<div class="btn-group" role="group">
    <select id="selectReport_{{ $row->student_id }}" class="form-select form-select-sm" aria-label="Small select example" onchange="javascript:generateReportForStudent('{{ $row->student_id }}','{{$row->cycle_id}}')">
        <option selected>select report</option>
        @foreach ($reportsList as $repId => $reportItem )
            <option value="{{$repId}}">{{$reportItem}}</option>
        @endforeach
      </select>
</div>

