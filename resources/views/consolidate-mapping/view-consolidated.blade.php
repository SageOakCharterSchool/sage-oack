@extends('layouts.admin-full')

@section('template_title')
    View Consolidate Result
@endsection

@section('content')

        <div class="row">
            <div class="col-sm-12">
                @include('layouts.includes.admin._messages')
                <div class="card-no">
                    <div class="card-header-no">
                        <div style="display: flex; justify-content: space-between; align-items: center;">

                            <span id="card_title">
                                {{ __('Consolidate Results') }}
                            </span>

                            <div class="float-right">
                                <a class="btn btn-primary btn-sm" href="{{ route('home') }}">
                                    {{ __('Back') }}</a>
                            </div>
                        </div>

                    </div>
                    @if ($overrideCycle)
                        <div class="alert alert-info m-3" role="alert">
                            @foreach ($cycles as $cycleRow)
                                @if ($cycleRow->id == $overrideCycle)
                                    Overriding current cycle with {{ $cycleRow->cycle_name }}
                                @endif
                            @endforeach
                        </div>
                    @endif
                    <div class="card-body-no bg-white-no">
                        <div class="table-responsive">

                            <div class="row">
                                <div class="col-4">
                                    <form action="{{ route('consolidate-search') }}" method="POST">
                                        @csrf
                                        <div class="input-group mb-3">

                                            <input type="search" class="form-control" placeholder="Find user here"
                                                name="search" value="{{ request('search') }}">
                                            <button class="btn btn-outline-secondary" type="submit"
                                                id="button-addon2">Search</button>
                                        </div>

                                    </form>
                                </div>
                                <div class="col-8">
                                    @if (Auth::user()->isAdmin())
                                        <button class="btn btn-primary dropdown-toggle float-end m-2" type="button"
                                            data-bs-toggle="dropdown" aria-expanded="false">Consolidated Report</button>
                                        <ul class="dropdown-menu">
                                            <li><a class="dropdown-item"
                                                    href="/admin/consolidate-view/{{ App\Models\Cycle::getCurrentCycle()->id }}/{{ $sectionId ?? 0 }}">Current
                                                    Cycle</a></li>
                                            @foreach ($cycles as $cycleRow)
                                                <li><a class="dropdown-item"
                                                        href="/admin/consolidate-view/{{ $cycleRow->id }}/{{ $sectionId ?? 0 }}">{{ $cycleRow->cycle_name }}</a>
                                                </li>
                                            @endforeach
                                        </ul>
                                        <button class="btn btn-info dropdown-toggle float-end m-2" type="button"
                                            data-bs-toggle="dropdown" aria-expanded="false">Export Excel</button>
                                        <ul class="dropdown-menu">
                                            <li><a class="dropdown-item"
                                                    href="/admin/consolidate-view-excel/{{ App\Models\Cycle::getCurrentCycle()->id }}/{{ $sectionId ?? 0 }}">Current
                                                    Cycle</a></li>
                                            @foreach ($cycles as $cycleRow)
                                                <li><a class="dropdown-item"
                                                        href="/admin/consolidate-view-excel/{{ $cycleRow->id }}/{{ $sectionId ?? 0 }}">{{ $cycleRow->cycle_name }}</a>
                                                </li>
                                            @endforeach
                                        </ul>
                                        <button class="btn btn-warning dropdown-toggle float-end m-2" type="button"
                                            data-bs-toggle="dropdown" aria-expanded="false">Export CSV</button>
                                        <ul class="dropdown-menu">
                                            <li><a class="dropdown-item"
                                                    href="/admin/consolidate-view-csv/{{ App\Models\Cycle::getCurrentCycle()->id }}/{{ $sectionId ?? 0 }}">Current
                                                    Cycle</a></li>
                                            @foreach ($cycles as $cycleRow)
                                                <li><a class="dropdown-item"
                                                        href="/admin/consolidate-view-csv/{{ $cycleRow->id }}/{{ $sectionId ?? 0 }}">{{ $cycleRow->cycle_name }}</a>
                                                </li>
                                            @endforeach
                                        </ul>
                                    @else
                                        <a href="/admin/consolidate-view-excel/{{ App\Models\Cycle::getCurrentCycle()->id }}/{{ $sectionId ?? 0 }}" type="button" class="btn btn-info float-end m-2">Export
                                                    Excel</a>
                                        <a href="/admin/consolidate-view-csv/{{ App\Models\Cycle::getCurrentCycle()->id }}/{{ $sectionId ?? 0 }}" type="button" class="btn btn-warning float-end m-2">Export CSV</a>
                                    @endif
                                    {{-- <a href="/admin/consolidate-view-full" type="button" class="btn btn-outline-secondary float-end m-2">Full Screen</a> --}}
                                </div>
                            </div>
                            <div>
                                <ul class="nav nav-pills m-1 ">
                                    <li class="nav-item">
                                        @if (!$sectionId || $sectionId == 0)
                                            <a class="nav-link active " aria-current="page"
                                                href="/admin/consolidate-view/{{ $cycle->id }}/0">General</a>
                                        @else
                                            <a class="nav-link " aria-current="page"
                                                href="/admin/consolidate-view/{{ $cycle->id }}/0">General</a>
                                        @endif
                                    </li>
                                    @foreach ($sections as $k => $section)
                                        @if ($k == $sectionId)
                                            <li class="nav-item"
                                                style="color: {{ $section->font_color }} !important;background-color: {{ $section->color }};!important">
                                                <a class="nav-link " style="color: {{ $section->font_color }} !important;"
                                                    href="/admin/consolidate-view/{{ $cycle->id }}/{{ $section->id }}">{{ $section->section }}</a>
                                            </li>
                                        @else
                                            <li class="nav-item">
                                                <a class="nav-link"
                                                    href="/admin/consolidate-view/{{ $cycle->id }}/{{ $section->id }}">{{ $section->section }}</a>
                                            </li>
                                        @endif
                                    @endforeach
                                </ul>
                            </div>
                            <div class="div1">
                                @include('consolidate-mapping._view-consolidated-table')
                            </div>
                        </div>
                    </div>
                </div>
                {{ $rows->withQueryString()->links('pagination::simple-bootstrap-5') }}
            </div>
        </div>

@endsection

@push('script')
    <script>
        function generateReportForStudent(studentId, cycleId) {
            var tmpId = "#selectReport_" + studentId;
            reportId = $(tmpId).val();
            console.log(studentId, cycleId, reportId);
            window.location.href = "/admin/view-report/" + reportId + "/" + studentId + "/" + cycleId;
        }
    </script>
@endpush
