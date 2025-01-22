@forelse ($mySpecialistStudents as $row)
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
            &nbsp;
        </td>
    </tr>
@empty
    <td></td>
    <td>No Students</td>
@endforelse
