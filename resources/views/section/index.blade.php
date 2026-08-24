@extends('layouts.admin')

@section('template_title')
    Section
@endsection

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-12">
                <div class="card">
                    <div class="card-header">
                        <div style="display: flex; justify-content: space-between; align-items: center;">

                            <span id="card_title">
                                {{ __('Section') }}
                            </span>

                             <div class="float-right">
                                <a href="{{ route('sections.create') }}" class="btn btn-primary btn-sm float-right"  data-placement="left">
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
                            <table class="table table-striped ">
                                <thead class="thead">
                                    <tr>
                                        <th>No</th>

										<th style="width: 60%">Section</th>
										<th>Color Code</th>
										<th>Color</th>


                                        <th style="width: 10%"></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($sections as $section)
                                        <tr>
                                            <td>{{ ++$i }}</td>

											<td>{{ $section->section }}</td>
											<td>{{ $section->color }}</td>
											<td style="background-color: {{ $section->color }}"><span style="color: {{$section->font_color}}">Name Here</span></td>

                                            <td>
                                                <form action="{{ route('sections.destroy',$section->id) }}" method="POST">
                                                    <a class="btn btn-sm btn-success" href="{{ route('sections.edit',$section->id) }}"><i class="fa fa-fw fa-edit"></i> {{ __('Edit') }}</a>
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-danger btn-sm"><i class="fa fa-fw fa-trash"></i> {{ __('Delete') }}</button>
                                                </form>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                {!! $sections->links() !!}
            </div>
        </div>
    </div>
@endsection
