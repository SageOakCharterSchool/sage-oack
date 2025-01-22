<div>
        <div class="input-group mb-3">
            <input type="text" class="form-control" placeholder="Name/Email/Id"
                wire:model.defer="keyWord">
            <div class="input-group-append">
                <button wire:click="render" class="btn btn-outline-secondary" type="button">Search</button>
            </div>
        </div>
        <div class="row">

            @if (count($studentList) > 0)
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
                                <th scope="col">Teacher Id</th>
                                <th scope="col">Teacher Info</th>
                                <th scope="col">Last</th>
                                <th scope="col">First</th>
                            </tr>
                            </thead>
                            <tbody>
                                @foreach ($studentList as $studenListRow)
                                    <tr>
                                        <th scope="row">{{$studenListRow->student_id}}</th>
                                        <td>{{$studenListRow->teacher_id}}</td>
                                        <td>{{App\Models\TeacherStudent::getUserInfoFromId($studenListRow->teacher_id)}}</td>
                                        <td>{{$studenListRow->column_a}}</td>
                                        <td>{{$studenListRow->column_b}}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            @endif
            @if (count($mathList) > 0)
                <div class="col-12 col-sm-12 col-md-6 col-lg-6 mb-2">
                    <div class="card">
                        <div class="card-header">
                            <b>
                                Findings in Math-list for this student
                            </b>
                        </div>
                        <div class="card-body">
                            <table class="table">
                                <thead>
                                <tr>
                                    <th scope="col">#</th>
                                    <th scope="col">Teacher Id</th>
                                    <th scope="col">Teacher Info</th>
                                    <th scope="col">Last</th>
                                    <th scope="col">First</th>
                                </tr>
                                </thead>
                                <tbody>
                                    @foreach ($mathList as $mathListRow)
                                        <tr>
                                            <th scope="row">{{$mathListRow->student_id}}</th>
                                            <td>{{$mathListRow->teacher_id}}</td>
                                            <td>{{App\Models\TeacherStudent::getUserInfoFromId($mathListRow->teacher_id)}}</td>
                                            <td>{{$mathListRow->column_a}}</td>
                                            <td>{{$mathListRow->column_b}}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            @endif
        </div>
</div>
