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
                                <a href="{{ route('consolidate-mappings.create') }}" class="btn btn-primary btn-sm float-right"  data-placement="left">
                                  {{ __('Create New') }}
                                </a>
                                <a href="{{ route('consolidate-view') }}" class="btn btn-success btn-sm float-right"  data-placement="left">
                                  {{ __('View Consolidated') }}
                                </a>
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
                            <table class="table table-striped table-hover">
                                <thead class="thead">
                                    <tr>
                                        <th>No</th>

									<th style="width: 5%" >Screen Sort</th>
									<th style="width: 5%" >Column Name</th>
									<th style="width: 20%">Column Description</th>
									<th style="width: 20%">Field Source</th>
									<th style="width: 25%">Formula</th>


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


                                            <td>
                                                <form action="{{ route('consolidate-mappings.destroy', $consolidateMapping->id) }}" method="POST">
                                                    <a class="btn btn-sm btn-primary " href="{{ route('consolidate-mappings.show', $consolidateMapping->id) }}"><i class="fa fa-fw fa-eye"></i> {{ __('Show') }}</a>
                                                    <a class="btn btn-sm btn-success" href="{{ route('consolidate-mappings.edit', $consolidateMapping->id) }}"><i class="fa fa-fw fa-edit"></i> {{ __('Edit') }}</a>
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-danger btn-sm" onclick="event.preventDefault(); confirm('Are you sure to delete?') ? this.closest('form').submit() : false;"><i class="fa fa-fw fa-trash"></i> {{ __('Delete') }}</button>
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
