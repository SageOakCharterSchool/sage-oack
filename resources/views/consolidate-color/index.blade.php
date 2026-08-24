@extends('layouts.admin')

@section('template_title')
    Consolidate Color
@endsection

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-12">
                <div class="card">
                    <div class="card-header">
                        <div style="display: flex; justify-content: space-between; align-items: center;">

                            <span id="card_title">
                                {{ __('Consolidate Color') }}
                            </span>

                             <div class="float-right">
                                <a href="/admin/consolidate-colors/create/{{$columnToFilter ?? null}}" class="btn btn-primary btn-sm float-right"  data-placement="left">
                                  {{ __('Create New') }}
                                </a>
                                <a href="{{ route('consolidate-mappings.index') }}" class="btn btn-info btn-sm float-right"  data-placement="left">
                                    {{ __('Back to Consolidate') }}
                                  </a>
                              </div>
                        </div>
                    </div>
                    @if ($message = Session::get('success'))
                        <div class="alert alert-success m-4">
                            <p>{{ $message }}</p>
                        </div>
                    @endif

                    <div class="card-body bg-white">
                        <div class="table-responsive">
                            <table class="table table-striped table-hover">
                                <thead class="thead">
                                    <tr>
                                        <th>No</th>

										<th>Column</th>
										<th>Field to Color</th>
										<th>Value</th>
										<th>Color</th>

                                        <th></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($masterTableColors as $masterTableColor)
                                        <tr>
                                            <td>{{ $masterTableColor->id }}</td>

											<td>{{ $masterTableColor->column_name }}</td>
											<td>{!! $tmpVariables[$masterTableColor->column_name] ?? "<span style='color:red;background:yellow;'>Missing in consolidate</span>" !!}</td>
											<td>{{ $masterTableColor->value }}</td>
                                            <td>
                                                <div class="text-center" style="height: 40px; width:80px; background-color: {{$masterTableColor->background_color}};color: {{$masterTableColor->color}}">
                                                    <br>{{ $masterTableColor->color }}</td>
                                                </div>
                                            </td>

                                            <td>
                                                <form action="{{ route('consolidate-colors.destroy',$masterTableColor->id) }}" method="POST">
                                                    <a class="btn btn-sm btn-primary " href="{{ route('consolidate-colors.show',$masterTableColor->id) }}"><i class="fa fa-fw fa-eye"></i> {{ __('Show') }}</a>
                                                    <a class="btn btn-sm btn-success" href="{{ route('consolidate-colors.edit',$masterTableColor->id) }}"><i class="fa fa-fw fa-edit"></i> {{ __('Edit') }}</a>
                                                    @csrf
                                                    @method('DELETE')

                                                </form>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                {!! $masterTableColors->links('pagination::simple-bootstrap-5') !!}

            </div>
        </div>
    </div>
@endsection
