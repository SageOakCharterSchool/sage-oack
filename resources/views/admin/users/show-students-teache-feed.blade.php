@extends('layouts.admin')

@section('content')

<div class="row">
    <div class="card mb-2">
        <div class="card-body">
            Teacher Id: {{$teacherId}}<br>
            Teacher Name: {{$user->name}}<br>
            Teacher Email: {{$user->email}}<br>
        </div>
      </div>
    <div class="col-12 col-sm-12 col-md-6 col-lg-6 mb-2">
        <div class="card">
            <div class="card-header">
                <b>
                    Students in Class-list for this cycle
                </b>
            </div>
            <div class="card-body">
                <table class="table">
                    <thead>
                      <tr>
                        <th scope="col">#</th>
                        <th scope="col">Last</th>
                        <th scope="col">First</th>
                      </tr>
                    </thead>
                    <tbody>
                        @foreach ($studentListRows as $studenListRow)
                            <tr>
                                <th scope="row">{{$studenListRow->student_id}}</th>
                                <td>{{$studenListRow->column_a}}</td>
                                <td>{{$studenListRow->column_b}}</td>
                            </tr>
                        @endforeach
                    </tbody>
                  </table>
            </div>
        </div>
    </div>
    <div class="col-12 col-sm-12 col-md-6 col-lg-6 mb-2">
        <div class="card">
            <div class="card-header">
                <b>
                    Students in Math-list for this cycle
                </b>
            </div>
            <div class="card-body">
                <table class="table">
                    <thead>
                      <tr>
                        <th scope="col">#</th>
                        <th scope="col">Last</th>
                        <th scope="col">First</th>
                      </tr>
                    </thead>
                    <tbody>
                        @foreach ($mathListRows as $mathListRow)
                            <tr>
                                <th scope="row">{{$mathListRow->student_id}}</th>
                                <td>{{$mathListRow->column_a}}</td>
                                <td>{{$mathListRow->column_b}}</td>
                            </tr>
                        @endforeach
                    </tbody>
                  </table>
            </div>
        </div>
    </div>
</div>
<div class="card mt-2">
    <div class="card-header">
        Check Student Feed
    </div>
    <div class="card-body">
        @livewire('admin.user.check-student-feed')
    </div>
</div>
@endsection

