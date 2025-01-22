@extends('layouts.admin')

@section('template_title')
    Formulas
@endsection

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-12">
                <div class="card">
                    <div class="card-header">
                        <div style="display: flex; justify-content: space-between; align-items: center;">

                            <span id="card_title">
                                {{ __('Formulas') }}
                            </span>

                            <div class="float-right">
                                <a href="{{ route('formulas.create') }}" class="btn btn-primary btn-sm float-right"
                                    data-placement="left">
                                    {{ __('Create New') }}
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

                                        <th style="width: 30%">Formula Name</th>
                                        <th>Formula Description</th>

                                        <th style="width: 15%"></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($formulas as $formula)
                                        <tr>
                                            <td>{{ $formula->id }}</td>

                                            <td>{{ $formula->formula_name }}</td>
                                            <td>{{ $formula->formula_description }}</td>

                                            <td>

                                                <form action="{{ route('formulas.destroy', $formula->id) }}" method="POST">
                                                    <a class="btn btn-sm btn-primary "
                                                        href="{{ route('formulas.show', $formula->id) }}"><i
                                                            class="fa fa-fw fa-eye"></i> {{ __('Show') }}</a>
                                                    <a class="btn btn-sm btn-success"
                                                        href="{{ route('formulas.edit', $formula->id) }}"><i
                                                            class="fa fa-fw fa-edit"></i> {{ __('Edit') }}</a>
                                                    @csrf
                                                    @method('DELETE')
                                                    @if ($formula->is_system == 0)
                                                        <button type="submit" class="btn btn-danger btn-sm"
                                                            onclick="event.preventDefault(); confirm('Are you sure to delete?') ? this.closest('form').submit() : false;"><i
                                                                class="fa fa-fw fa-trash"></i> {{ __('Delete') }}</button>
                                                    @endif
                                                </form>

                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                {!! $formulas->withQueryString()->links() !!}
            </div>
        </div>
    </div>
@endsection
