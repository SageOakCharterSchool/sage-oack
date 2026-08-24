@extends('layouts.admin')

@section('template_title')
    Consolidate Mappings
@endsection

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-12">
                <div class="card">
                    <div class="card-header">
                        <div style="display: flex; justify-content: space-between; align-items: center;">

                            <span id="card_title">
                                {{ __('Consolidate Mappings') }}
                            </span>

                             <div class="float-right">

                                <div class="input-group mb-3 float-right">
                                    <button class="btn btn-outline-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">Options</button>
                                    <ul class="dropdown-menu">
                                        <li>
                                            <a href="{{ route('consolidate-mappings.create') }}" class="dropdown-item"  >
                                                {{ __('Create New') }}
                                              </a>
                                        </li>
                                        <li>
                                            <a href="{{ route('consolidate-view') }}" class="dropdown-item"  >
                                                {{ __('View Consolidated') }}
                                              </a>
                                        </li>
                                        <li><hr class="dropdown-divider"></li>
                                        <li>
                                            <a href="{{ route('sections.index') }}" class="dropdown-item"  >
                                                {{ __('View Sections') }}
                                              </a>
                                        </li>
                                        {{-- <li>
                                            <a href="{{ route('equivalences.index') }}" class="dropdown-item"  >
                                                {{ __('View Equivalences') }}
                                              </a>
                                        </li> --}}
                                        <li>
                                            <a href="{{ route('consolidate-colors.index') }}" class="dropdown-item"  >
                                                {{ __('Consolidate Colors') }}
                                              </a>
                                        </li>



                                    </ul>
                                  </div>

                              </div>
                        </div>
                    </div>
                    @if ($message = Session::get('success'))
                        <div class="alert alert-success m-4">
                            <p>{{ $message }}</p>
                        </div>
                    @endif
                    @if ($message = Session::get('error'))
                        <div class="alert alert-danger m-4">
                            <p>{{ $message }}</p>
                        </div>
                    @endif
                    @if ($message = Session::get('error-message'))
                        <div class="alert alert-danger m-4">
                            <p>{{ $message }}</p>
                        </div>
                    @endif

                    <div class="card-body bg-white">
                        <div class="table-responsive">
                            <table class="table table-striped ">
                                <thead class="thead">
                                    <tr>
                                        <th>No</th>

                                        <th style="width: 5%" >Screen Sort</th>
                                        <th style="width: 5%" >Column Name</th>
                                        <th style="width: 20%">Column Description</th>
                                        <th style="width: 20%">Field Source</th>
                                        <th style="width: 25%">Formula</th>
                                        <th style="">Color</th>
                                        <th style="">Section</th>


                                        <th style="width: 20%"></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($consolidateMappings as $consolidateMapping)
                                        <tr>
                                            <td>{{ ++$i }}</td>

                                                <td >{{ $consolidateMapping->screen_sort }}</td>
                                                <td >{{ $consolidateMapping->column_name }}</td>
                                                <td >{{ $consolidateMapping->column_description }}</td>
                                                <td >{{ \App\Models\ConsolidateMapping::getFieldSource($consolidateMapping->field_source) ?? "" }}</td>
                                                <td ><a href="/admin/formulas/{{$consolidateMapping->formula_id}}">{{ \App\Models\Formula::getFormulaName($consolidateMapping->formula_id)->formula_name ?? "" }}</a> </td>
                                                @php
                                                    $sectionInfo = \App\Models\Section::getSectionInfo($consolidateMapping->section_id);
                                                @endphp
                                                <td style="background-color: {{$sectionInfo->color ?? ''}}">{{$sectionInfo->section ?? ''}}</td>
                                                <td>
                                                    @if (isset($consolidateColorContent[$consolidateMapping->column_name]))
                                                    <div class="btn-group">
                                                        <button type="button" class="btn btn-warning dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
                                                          Colors
                                                        </button>
                                                        <ul class="dropdown-menu">
                                                            @foreach ($consolidateColorContent[$consolidateMapping->column_name] as $value => $consolidateColor)
                                                                <li style="background-color: {{$consolidateColor['background_color']}};"><a class="dropdown-item" href="#" style="color:{{$consolidateColor['color']}}">{{$value}}</a></li>
                                                            @endforeach
                                                        </ul>
                                                      </div>
                                                    @else
                                                        &nbsp;
                                                    @endif
                                                </td>



                                            <td>
                                                <form action="{{ route('consolidate-mappings.destroy', $consolidateMapping->id) }}" method="POST">
                                                    @csrf
                                                    @method('DELETE')
                                                    <div class="input-group mb-3">
                                                        <button class="btn btn-outline-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">Options</button>
                                                        <ul class="dropdown-menu">
                                                          <li><a class="dropdown-item" href="{{ route('consolidate-mappings.edit', $consolidateMapping->id) }}">{{ __('Edit') }}</a></li>
                                                          <li><a class="dropdown-item" href="/admin/sections">{{ __('Sections') }}</a></li>
                                                          <li><a class="dropdown-item" href="/admin/consolidate-colors?column={{$consolidateMapping->column_name }}">{{ __('Colors') }}</a></li>
                                                          <li><hr class="dropdown-divider"></li>
                                                          <li><button class="dropdown-item" type="submit" onclick="event.preventDefault(); confirm('Are you sure to delete?') ? this.closest('form').submit() : false;">Delete</button></li>
                                                        </ul>
                                                      </div>
                                                </form>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                            <div class="text-end p-3 ">
                                <div >
                                    @if ($consolidateGeneration->status == 1)
                                    <span style="color:green;margin:20px;padding-top:20px">Completed on {{$consolidateGeneration->updated_at}}</span>
                                    @elseif ($consolidateGeneration->status == 2)
                                    <span style="color:red;margin:20px;padding-top:20px">Submitted on on {{$consolidateGeneration->updated_at}}</span>
                                    @elseif ($consolidateGeneration->status == 3)
                                    <span style="color:blue;margin:20px;padding-top:20px">In Process since {{$consolidateGeneration->updated_at}}</span>
                                    @endif
                                    <a class="btn btn-success float-end mt-2" href="/admin/submit-consolidated-generation">Generate Consolidate</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                {!! $consolidateMappings->withQueryString()->links() !!}
            </div>
        </div>
    </div>
@endsection
@push('script')
    <script>
    </script>
@endpush
