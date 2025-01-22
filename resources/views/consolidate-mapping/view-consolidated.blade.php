@extends('layouts.admin')

@section('template_title')
    View Consolidate Result
@endsection

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-12">
                @include('layouts.includes.admin._messages')
                <div class="card">
                    <div class="card-header">
                        <div style="display: flex; justify-content: space-between; align-items: center;">

                            <span id="card_title">
                                {{ __('Consolidate Results')  }}
                            </span>

                            <div class="float-right">
                                <a class="btn btn-primary btn-sm" href="{{ route('consolidate-mappings.index') }}">
                                    {{ __('Back') }}</a>
                            </div>
                        </div>

                    </div>
                    @if ($overrideCycle)
                    <div class="alert alert-info m-3" role="alert">
                        @foreach ($cycles as $cycle)
                            @if ($cycle->id == $overrideCycle)
                                Overriding current cycle with {{ $cycle->cycle_name }}
                            @endif
                        @endforeach
                    </div>
                    @endif
                    <div class="card-body bg-white">
                        <div class="table-responsive">

                            @if (Auth::user()->isAdmin())
                                <button class="btn btn-primary dropdown-toggle float-end m-2" type="button"
                                    data-bs-toggle="dropdown" aria-expanded="false">Consolidated Report</button>
                                <ul class="dropdown-menu">
                                    <li><a class="dropdown-item" href="/admin/consolidate-view">Current
                                            Cycle</a></li>
                                    @foreach ($cycles as $cycle)
                                        <li><a class="dropdown-item"
                                                href="/admin/consolidate-view/{{ $cycle->id }}">{{ $cycle->cycle_name }}</a>
                                        </li>
                                    @endforeach
                                </ul>
                                <button class="btn btn-warning dropdown-toggle float-end m-2" type="button"
                                    data-bs-toggle="dropdown" aria-expanded="false">Export CSV</button>
                                <ul class="dropdown-menu">
                                    <li><a class="dropdown-item" href="/admin/consolidate-view-csv">Current
                                            Cycle</a></li>
                                    @foreach ($cycles as $cycle)
                                        <li><a class="dropdown-item"
                                                href="/admin/consolidate-view-csv/{{ $cycle->id }}">{{ $cycle->cycle_name }}</a>
                                        </li>
                                    @endforeach
                                </ul>
                            @else
                                <a href="" type="button" class="btn btn-warning float-end m-2">Export CSV</a>
                            @endif
                            <form action="{{ route('consolidate-search') }}" method="POST">
                                @csrf
                                <div class="input-group mb-3">

                                    <input type="search" class="form-control" placeholder="Find user here" name="search"
                                        value="{{ request('search') }}">
                                    <button class="btn btn-outline-secondary" type="submit"
                                        id="button-addon2">Search</button>
                                </div>

                            </form>
                            <div class="div1">

                                <table class="table table-striped table-hover">
                                    <thead class="thead">
                                        <tr>
                                            <th >Options</th>
                                            @foreach ($consolidatedFields as $field)
                                                <th style="">{{ $field[1] }}</th>
                                            @endforeach
                                            <th >Options</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($rows as $row)
                                            <tr>
                                                <td>
                                                    <a class="btn btn-sm btn-warning "
                                                        href="/admin/view-report/{{ $row->student_id }}/{{$row->cycle_id}}"><i
                                                            class="fa fa-fw fa-eye"></i> {{ __('View Report') }}</a>
                                                </td>
                                                @foreach ($consolidatedFields as $field)
                                                    <td>{{ $row->{$field[0]} }}</td>
                                                @endforeach
                                                <td>
                                                    <a class="btn btn-sm btn-warning "
                                                        href="/admin/view-report/{{ $row->student_id }}/{{$row->cycle_id}}"><i
                                                            class="fa fa-fw fa-eye"></i> {{ __('View Report') }}</a>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
                {{ $rows->withQueryString()->links('pagination::simple-bootstrap-5') }}
            </div>
        </div>
    </div>
@endsection

@push('script')
    <script></script>
@endpush
