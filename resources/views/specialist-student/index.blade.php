@extends('layouts.admin')

@section('template_title')
    Specialist Students
@endsection

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-12">
                <div class="card">
                    <div class="card-header">
                        <div style="display: flex; justify-content: space-between; align-items: center;">

                            <span id="card_title">
                                {{ __('Specialist Students') }}
                            </span>

                             <div class="float-right">
                                <a href="{{ route('specialist-students.create') }}" class="btn btn-primary btn-sm float-right"  data-placement="left">
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

									<th >Specialist Id</th>
									<th >First Name</th>
									<th >Last Name</th>
									<th >Email</th>

                                        <th></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($specialistStudents as $specialistStudent)
                                        <tr>
                                            <td>{{ ++$i }}</td>

										<td >{{ $specialistStudent->specialist_id }}</td>
										<td >{{ $specialistStudent->first_name }}</td>
										<td >{{ $specialistStudent->last_name }}</td>
										<td >{{ $specialistStudent->email }}</td>

                                            <td>
                                                <form action="{{ route('specialist-students.destroy', $specialistStudent->id) }}" method="POST">
                                                    <a class="btn btn-sm btn-primary " href="{{ route('specialist-students.show', $specialistStudent->id) }}"><i class="fa fa-fw fa-eye"></i> {{ __('Show') }}</a>
                                                    <a class="btn btn-sm btn-success" href="{{ route('specialist-students.edit', $specialistStudent->id) }}"><i class="fa fa-fw fa-edit"></i> {{ __('Edit') }}</a>
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-danger btn-sm" onclick="event.preventDefault(); confirm('Are you sure to delete?') ? this.closest('form').submit() : false;"><i class="fa fa-fw fa-trash"></i> {{ __('Delete') }}</button>
                                                </form>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                {!! $specialistStudents->withQueryString()->links() !!}
            </div>
        </div>
    </div>
@endsection
