@forelse ($myStudents as $row)
    <tr>
        <td>
            {{ $row->student_id }}
        </td>
        <td>
            {{ $row->column_a }} {{ $row->column_b }}
        </td>
        <td>
            {{ $row->column_e }}
        </td>
        <td>
            {{ $row->column_d }}
        </td>
        <td>
            {{ $row->column_f }}
        </td>
        <td>
            {{ $row->column_g }}
        </td>
        <td>
            @if (Auth::user()->isAdmin())
                <div class="btn-group" role="group">
                    <button id="btnGroupDrop1" type="button" class="btn btn-primary dropdown-toggle"
                        data-bs-toggle="dropdown" aria-expanded="false">
                        Options
                    </button>
                    <ul class="dropdown-menu" aria-labelledby="btnGroupDrop1">

                        <li>
                            <a class="dropdown-item"
                                href="javascript:changeUserPassword({{ $row->student_id }},'{{ $row->column_a }} {{ $row->column_b }}','{{ $row->column_f }}')">Change
                                Password</a>
                        </li>
                        <li>
                            <a class="dropdown-item" href="javascript:deleteUserAccount({{ $row->student_id }})">Delete
                                User</a>
                        </li>
                        <li>
                            <a class="dropdown-item"
                                href="javascript:reassignStudents({{ $row->student_id }})">Reassign User</a>
                        </li>
                        <li>
                            <a class="dropdown-item"
                                href="javascript:assignStudentsToSpecialist({{ $row->student_id }})">Assign To
                                Specialist</a>
                        </li>
                    </ul>
                </div>
            @endif

        </td>
    </tr>
@empty
    <td></td>
    <td>No Students</td>
@endforelse
