<div>
    <ul class="nav nav-tabs mb-3">
        @if (Auth::user()->isAdmin())
        <li class="nav-item protectMe">
          <a class="nav-link " aria-current="page" href="{{ url('/admin/upload') }}">Step 1 - Upload Files</a>
        </li>
        <li class="nav-item protectMe">
          <a class="nav-link " href="{{ url('/admin/process-files') }}">Step 2 - Process Files</a>
        </li>
        <li class="nav-item protectMe">
          <a class="nav-link " href="{{ url('/admin/consolidate') }}">Step 3 - Consolidate</a>
        </li>
        @endif
        <li class="nav-item protectMe">
          <a class="nav-link  active" href="{{ url('/admin/view-consolidated') }}">Step 4 - View Reports</a>
        </li>
    </ul>
    <div class="row">
        <div class="col-md-12">
            @if (session('message'))
                <h2 class="alert alert-success"  >{{ session('message') }},</h2>
            @endif

            <div class="card">
                <div class="card-header">
                    <h4>Consolidated
                        <a  class="btn btn-warning btn-sm float-end mr-2 ml-2" target="_blank"  href="{{ url('admin/generate-consolidated-report') }}">Generate Report</a>
                        <a  class="btn btn-primary btn-sm float-end ml-2 mr-2" style="margin-right: 5px;" href="{{ url('admin/generate-consolidated-report-csv') }}">Export to CSV</a>
                    </h4>
                </div>


                <div class="card-body" style="overflow: scroll">
                    <div class="input-group mb-3 mt-3">
                        <input type="text" class="form-control" wire:model="search" placeholder="Type Student Id/Teacher Id/Student Name">
                        <button class="btn btn-outline-secondary protectMeShort" type="button" id="button-addon2">Search</button>
                    </div>
                    <table class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                @foreach ($headers as $header)
                                    @if (

                                        $header['header'] == 'created_at' ||
                                        $header['header'] == 'updated_at' ||
                                        $header['header'] == 'student_id' ||
                                        $header['header'] == 'created_by'
                                    )
                                        @php
                                            continue;
                                        @endphp
                                    @endif
                                    <th>
                                        {{$header['header']}}
                                    </th>
                                @endforeach
                                <th>Options</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($rows as $row)

                            <tr>
                                @foreach ($headers as $header)
                                    @if (
                                            $header['header'] == 'created_at' ||
                                            $header['header'] == 'updated_at' ||
                                            $header['header'] == 'student_id' ||
                                            $header['header'] == 'created_by'
                                        )
                                        @php
                                            continue;
                                        @endphp
                                        @endif
                                    <th>
                                        @if ($header['header'] == 'column_b' || $header['header'] == 'column_c')
                                            @if (getenv("APP_ENV") == "PROD" || getenv("IS_TESTING")=="Y")
                                                {{ trim(decrypt($row->{$header['field']})) }}
                                            @else
                                                {{ trim(($row->{$header['field']})) }}
                                            @endif

                                        @else
                                            @if ($header['header']=='Program')
                                                {{\App\Models\Consolidate3::getProgramName($cycle,$row->student_id)}}
                                            @elseif ($header['header']=='CAASPP Math')
                                                {{\App\Models\Consolidate3::getCaasppMath($cycle,$row->student_id)}}
                                            @elseif ($header['header']=='CAASPP Reading')
                                                {{\App\Models\Consolidate3::getCaasppReading($cycle,$row->student_id)}}
                                            @elseif ($header['header']=='Tutor.com Sessions')
                                                {{\App\Models\Consolidate3::getTutorSessions($cycle,$row->student_id)}}
                                            @else
                                                {{ trim($row->{$header['field']}) }}
                                            @endif
                                        @endif
                                        <div class="btn-group" role="group" aria-label="Button group with nested dropdown">
                                            @if ($header['field'] == 'id')
                                                <div class="btn-group" role="group">
                                                    <button type="button" class="btn btn-warning btn-sm dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
                                                        Report
                                                    </button>
                                                    <ul class="dropdown-menu">
                                                        <li><a class="dropdown-item protectMeShort" target="_blank" href="{{ url('/admin/generate-individual-report/' . $row->id ) }}">Report</a></li>
                                                        <li><a class="dropdown-item " wire:click.prevent="showStudentCounts({{$row->id}})" data-bs-toggle="modal" data-bs-target="#showStudentCounts">Show Counts</a></li>
                                                        <li><a class="dropdown-item " target="_blank" href="{{ url('/admin/generate-analysis-report/' . $row->id ) }}">Show Analysis</a></li>
                                                    </ul>
                                                </div>
                                            @endif

                                          </div>

                                    </th>
                                @endforeach
                                <th>
                                    <div class="btn-group" role="group" aria-label="Button group with nested dropdown">

                                        <div class="btn-group" role="group">
                                          <button type="button" class="btn btn-warning btn-sm dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
                                            Report
                                          </button>
                                          <ul class="dropdown-menu">
                                            <li><a class="dropdown-item protectMeShort" target="_blank" href="{{ url('/admin/generate-individual-report/' . $row->id ) }}">Report </a></li>
                                          </ul>
                                        </div>
                                      </div>

                                </th>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                    <div class="mt-2 d-flex align-items-center justify-content-center">
                        {{ $rows->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal -->
    <div wire:ignore.self class="modal fade" id="showStudentCounts" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    @if ($counts)
                    <h5 class="modal-title" id="exampleModalLabel">Student {{$studentId}} count</h5>
                    @else
                    <h5 class="modal-title" id="exampleModalLabel">Loading Student counts</h5>
                    @endif
                    <button type="button" class="btn-close" data-bs-dismiss="modal" wire:click="closeShowCountsModal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    @if ($counts)
                        @foreach ($counts as $k => $count)
                        <h6>
                            <div>
                                {{$k}}:
                                @if ($count == "ok")
                                <span class="text-success">
                                    {{$count}}
                                </span>
                                @else
                                <span class="text-danger">
                                    {{$count}}
                                </span>
                                @endif
                            </div>
                        </h6>
                        @endforeach
                    @else
                        <h6>Loading....</h6>
                    @endif
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" wire:click="closeShowCountsModal"  data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>
</div>
