@extends('layouts.admin')

@section('template_title')
    Equivalence
@endsection

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-12">
                <div class="card">
                    <div class="card-header">
                        <div style="display: flex; justify-content: space-between; align-items: center;">

                            <span id="card_title">
                                {{ __('Equivalence') }}
                            </span>

                             <div class="float-right">
                                <a href="{{ route('equivalences.create') }}" class="btn btn-primary btn-sm float-right"  data-placement="left">
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

										<th>Equivalence</th>
										<th>Value</th>
										<th>Color</th>

                                        <th></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($equivalences as $equivalence)
                                        <tr>
                                            <td>{{ ++$i }}</td>

											<td>{{ $equivalence->equivalence }}</td>
											<td>{{ $equivalence->value }}</td>
											<td>
                                                <div class="text-center" style="height: 40px; width:80px; background: {{$equivalence->color}}">
                                                    <br>{{ $equivalence->color }}</td>
                                                </div>

                                            <td>
                                                <form action="{{ route('equivalences.destroy',$equivalence->id) }}" method="POST">
                                                    <a class="btn btn-sm btn-primary " href="{{ route('equivalences.show',$equivalence->id) }}"><i class="fa fa-fw fa-eye"></i> {{ __('Show') }}</a>
                                                    <a class="btn btn-sm btn-success" href="{{ route('equivalences.edit',$equivalence->id) }}"><i class="fa fa-fw fa-edit"></i> {{ __('Edit') }}</a>
                                                    @csrf
                                                </form>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                {!! $equivalences->links() !!}
            </div>
        </div>
    </div>
@endsection
