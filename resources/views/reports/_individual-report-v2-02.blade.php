    <div class="row">
        {{-- Second Group --}}
        <table class="table" style="border: 1px solid black;border-collapse:collapse;margin-top:5px;width: 100%" >
            <tbody>
                <tr>
                    <th colspan="6"  style="border: 1px solid black;text-align: center; ">
                        Intervention Qualification & Information
                    </th>
                </tr>
                <tr>
                    <th style="border: 1px solid black;text-align: center; ">
                        Subject
                    </th>
                    <th style="border: 1px solid black;text-align: center; ">
                        Recommendation
                    </th>
                    <th style="border: 1px solid black;text-align: center; ">
                        Selection
                    </th>
                    <th style="border: 1px solid black;text-align: center; ">
                        Class Info
                    </th>
                    <th style="border: 1px solid black;text-align: center; ">
                        Class Attendance
                    </th>
                    <th style="border: 1px solid black;text-align: center; ">
                        Independent Practice<br>Minutes
                    </th>
                </tr>
                <tr>
                    <td style="border: 1px solid black;text-align: center; ">
                        {{$math_lists[0]->column_f  ?? ''}}
                    </td>
                    <td style="border: 1px solid black;text-align: center; ">
                        {{ $math_lists[0]->column_j  ?? ''}}
                    </td>
                    <td style="border: 1px solid black;text-align: center; ">
                        {{ $math_lists[0]->column_o  ?? ''}}
                    </td>
                    <td style="border: 1px solid black;text-align: center; ">
                        {{ $math_lists[0]->column_z  ?? ''}}
                    </td>
                    <td style="border: 1px solid black;text-align: center; ">
                        {{ $attendance_maths[0]->column_h ?? ''}}
                    </td>
                    <td style="border: 1px solid black;text-align: center; ">
                        <table class="noBorder">
                            <tr>
                                <td style="border: 1px solid black;text-align: center; padding: 10px">
                                    iReady
                                </td>
                                <td style="border: 1px solid black;text-align: center; padding: 10px">
                                    {{$i_ready_math_minutes[0]->column_cn  ?? ''}}
                                </td>
                            </tr>
                            <tr>
                                <td style="border: 1px solid black;text-align: center; padding: 10px">
                                    Math Class
                                </td>
                                <td style="border: 1px solid black;text-align: center; padding: 10px">
                                    {{ $v_math_minutes[0]->column_h ?? '' }}
                                </td>
                            </tr>
                        </table>

                    </td>
                </tr>
                <tr>
                    <td style="border: 1px solid black;text-align: center; ">
                        {{$student_list[0]->column_f  ?? ''}}
                    </td>
                    <td style="border: 1px solid black;text-align: center; ">
                        {{ $student_list[0]->column_j  ?? ''}}
                    </td>
                    <td style="border: 1px solid black;text-align: center; ">
                        {{ $student_list[0]->column_o  ?? ''}}
                    </td>
                    <td style="border: 1px solid black;text-align: center; ">
                        {{ $student_list[0]->column_z  ?? ''}}
                    </td>
                    <td style="border: 1px solid black;text-align: center; ">
                        {{ $attendance_elas[0]->column_h ?? ''}}
                    </td>
                    <td style="border: 1px solid black;text-align: center; ">
                        <table class="noBorder">
                            <tr>
                                <td style="border: 1px solid black;text-align: center; padding: 10px">
                                    iReady
                                </td>
                                <td style="border: 1px solid black;text-align: center; padding: 10px">
                                    {{ $i_ready_reading_minutes[0]->column_cn ?? ''}}
                                </td>
                            </tr>
                            <tr>
                                <td style="border: 1px solid black;text-align: center; padding: 10px">
                                    Reading Class
                                </td>
                                <td style="border: 1px solid black;text-align: center; padding: 10px">
                                    {{ $read180_minutes[0]->column_h  ?? '' }}
                                </td>
                            </tr>
                        </table>
                    </td>
                </tr>
                <tr>
                    <td colspan="6">
                        Tutor com Sessions: &nbsp;&nbsp;{{isset($tutor[0])?$tutor[0]['column_j']:''}}
                    </td>
                </tr>
                <tr>
                    <td colspan="6">
                        Class Notes:<br>
                        <div>
                            {{ $math_lists[0]->column_aa ?? '' }}
                        </div>
                        <div>
                            {{ $student->column_aa ?? '' }}
                        </div>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
