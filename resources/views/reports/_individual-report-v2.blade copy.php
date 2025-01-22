@if ($isConsolidated=="Y")
<style>
     .page-break {
        page-break-after: always;
    }
</style>
@endif
<div class="container page-break" style="margin-left:10px;margin-top:20px;">

    <div class="row text-center">
        <h2>Student Information Report</h2>
    </div>
    <div class="row">
        <table class="table" style="border: 1px solid black;">
            <tbody>
                <tr>
                    <th style="border: 1px solid black;">
                        {{-- last name --}} {{-- first name --}}
                        Student Name:  <span>{{($consolidate->column_b)  ?? ''}} {{($consolidate->column_c)  ?? ''}}</span>
                    </th>
                    <th style="border: 1px solid black;">
                        {{-- SSID --}}
                        SSID: {{($consolidate->column_a)  ?? ''  ?? ''}}
                    </th>
                    <th style="border: 1px solid black;">
                        {{-- Grade --}}
                        Grade: {{$consolidate->column_d  ?? ''}}
                    </th>
                    <th style="border: 1px solid black;">
                        {{-- SIS --}}
                        SIS: {{$consolidate->column_e  ?? ''}}
                    </th>
                    <th style="border: 1px solid black;">
                        {{-- Teacher --}}
                        Teacher: {{$consolidate->column_g  ?? ''}}
                    </th>
                    <th style="border: 1px solid black;">
                        {{-- Program --}}
                        Program: {{$student_list[0]->column_e  ?? ''}}
                    </th>
                </tr>
            </tbody>
        </table>
        <br><br>
        {{-- Second Group --}}
        <table class="table" style="border: 1px solid black;margin-top:20px">
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
                        <table>
                            <tr>
                                <td style="border: 1px solid black;text-align: center; padding: 10px">
                                    iReady
                                </td>
                                <td style="border: 1px solid black;text-align: center; padding: 10px">
                                    {{$i_ready_math_minutes[0]->column_o  ?? ''}}
                                </td>
                            </tr>
                            <tr>
                                <td style="border: 1px solid black;text-align: center; padding: 10px">
                                    V Math
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
                        <table>
                            <tr>
                                <td style="border: 1px solid black;text-align: center; padding: 10px">
                                    iReady
                                </td>
                                <td style="border: 1px solid black;text-align: center; padding: 10px">
                                    {{ $i_ready_reading_minutes[0]->column_o ?? ''}}
                                </td>
                            </tr>
                            <tr>
                                <td style="border: 1px solid black;text-align: center; padding: 10px">
                                    Read 180
                                </td>
                                <td style="border: 1px solid black;text-align: center; padding: 10px">
                                    {{ $i_ready_math_boys[0]->column_ac  ?? '' }}
                                </td>
                            </tr>
                        </table>
                    </td>
                </tr>
                <tr>
                    <td colspan="6">
                        Tutor com Sessions:
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








        <div style="margin-top:10px;margin-bottom:10px;">
            <div>
                <span style="background-color: #ffffff; padding:2px">
                    <strong>INDEPENDENT PRACTICE MINUTES COMPLETED (if applicable)</strong>
                </span>
            </div>
            <div style="margin-left:20px;">
                <div>
                    <table>
                        <tr>
                            <td>
                                <span style="color:  #8FBC8F; padding:2px">
                                    <strong>Iready Math Minutes: </strong>
                                </span>
                            </td>
                            <td>

                            </td>
                        </tr>
                        <tr>
                            <td>
                                <span style="color:  #8FBC8F; padding:2px">
                                    <strong>Iready Reading Minutes: </strong>
                                </span>
                            </td>
                            <td>

                            </td>
                        </tr>
                        <tr>
                            <td>
                                <span style="color:  #8FBC8F; padding:2px">
                                    <strong>Freckle Math Minutes: </strong>
                                </span>
                            </td>
                            <td>
                                {{ $freckle_minutes[0]->column_i ?? ''}}
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <span style="color:  #8FBC8F; padding:2px">
                                    <strong>Freckle Reading Minutes: </strong>
                                </span>
                            </td>
                            <td>
                                {{ $freckle_minutes[0]->column_j ?? ''}}
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <span style="color:  #8FBC8F; padding:2px">
                                    <strong>Read 180 Minutes: </strong>
                                </span>
                            </td>
                            <td>
                                {{ $read180_minutes[0]->column_h ?? '' }}
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <span style="color:  #8FBC8F; padding:2px">
                                    <strong>Vmath Minutes: </strong>
                                </span>
                            </td>
                            <td>

                            </td>
                        </tr>
                        <tr>
                            <td>
                                <span style="color:  #8FBC8F; padding:2px">
                                    <strong>Math 180 Minutes: </strong>
                                </span>
                            </td>
                            <td>
                                {{ $math180_minutes[0]->column_h  ?? ''}}
                            </td>
                        </tr>
                    </table>

                </div>

            </div>
        </div>

        <div style="margin-top:10px;margin-bottom:10px;">
            <div>
                <span style="background-color: #ffffff; padding:2px">
                    <strong>IREADY SCORES</strong>
                </span>
            </div>
            <div>
                <span style="color: #8FBC8F; padding:2px">
                    <strong>FALL iReady Diagnostic</strong>
                </span>
            </div>
            <div style="margin-top:10px;margin-left:20px;">
                <div>
                    <table>
                        <tr>
                            <td>
                                <span style="background-color: #ffffff; padding:2px">
                                    <strong>Math points: </strong>
                                </span>
                            </td>
                            <td>
                                {{ $i_ready_math_boys[0]->column_ac  ?? '' }}
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <span style="background-color: #ffffff; padding:2px">
                                    <strong>Math relative placement: </strong>
                                </span>
                            </td>
                            <td>
                                {{ $i_ready_math_boys[0]->column_ae  ?? '' }}
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <span style="background-color: #ffffff; padding:2px">
                                    <strong>Math Level: </strong>
                                </span>
                            </td>
                            <td>
                                {{ $i_ready_math_boys[0]->column_ad  ?? '' }}
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <span style="background-color: #ffffff; padding:2px">
                                    <strong>Reading points: </strong>
                                </span>
                            </td>
                            <td>
                                {{ $i_ready_reading_boy_s[0]->column_ac  ?? '' }}
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <span style="background-color: #ffffff; padding:2px">
                                    <strong>Reading relative placement: </strong>
                                </span>
                            </td>
                            <td>
                                {{ $i_ready_reading_boy_s[0]->column_ae  ?? '' }}
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <span style="background-color: #ffffff; padding:2px">
                                    <strong>Reading Level: </strong>
                                </span>
                            </td>
                            <td>
                                {{ $i_ready_reading_boy_s[0]->column_ad  ?? '' }}
                            </td>
                        </tr>
                    </table>
                </div>
            </div>
            <div style="margin-top:20px;">
                <span style="background-color: #ffffff; padding:2px">
                    <strong>MID YEAR iReady Diagnostic</strong>
                </span>
            </div>
            <div style="margin-top:10px;margin-left:20px;">
                <div>
                    <table>
                        <tr>
                            <td>
                                <span style="background-color: #ffffff; padding:2px">
                                    <strong>Math points: </strong>
                                </span>
                            </td>
                            <td>
                                {{ $i_ready_math_mid_years[0]->column_ac  ?? '' }}
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <span style="background-color: #ffffff; padding:2px">
                                    <strong>Math relative placement: </strong>
                                </span>
                            </td>
                            <td>
                                {{ $i_ready_math_mid_years[0]->column_ae  ?? '' }}
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <span style="background-color: #ffffff; padding:2px">
                                    <strong>Math Level: </strong>
                                </span>
                            </td>
                            <td>
                                {{ $i_ready_math_mid_years[0]->column_ad  ?? '' }}
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <span style="background-color: #ffffff; padding:2px">
                                    <strong>Reading points: </strong>
                                </span>
                            </td>
                            <td>
                                {{ $i_ready_reading_mid_years[0]->column_ac  ?? '' }}
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <span style="background-color: #ffffff; padding:2px">
                                    <strong>Reading relative placement: </strong>
                                </span>
                            </td>
                            <td>
                                {{ $i_ready_reading_mid_years[0]->column_ae  ?? '' }}
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <span style="background-color: #ffffff; padding:2px">
                                    <strong>Reading Level: </strong>
                                </span>
                            </td>
                            <td>
                                {{ $i_ready_reading_mid_years[0]->column_ad  ?? '' }}
                            </td>
                        </tr>
                    </table>
                </div>

            </div>


            {{-- GROWTH MID YEAR  --}}
            <div style="margin-top:10px;">
                <span style="margin-top:10px;background-color: #ffffff; padding:2px">
                    <strong>GROWTH MID YEAR </strong>
                </span>
            </div>
            <div style="margin-top:10px;margin-left:20px;">
                <div>
                    <table>
                        <tr>
                            <td>
                                <span style="background-color: #ffffff; padding:2px">
                                    <strong>Math growth in points: </strong>
                                </span>
                            </td>
                            <td>
                                {{ $consolidate->column_ae  ?? '' }}
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <span style="background-color: #ffffff; padding:2px">
                                    <strong>Math growth levels: </strong>
                                </span>
                            </td>
                            <td>
                                {{ $consolidate->column_af  ?? '' }}

                            </td>
                        </tr>
                        <tr>
                            <td>
                                <span style="background-color: #ffffff; padding:2px">
                                    <strong>Reading growth in points: </strong>
                                </span>
                            </td>
                            <td>
                                {{ $consolidate->column_ag  ?? '' }}
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <span style="background-color: #ffffff; padding:2px">
                                    <strong>Reading growth levels: </strong>
                                </span>
                            </td>
                            <td>
                                {{ $consolidate->column_ah  ?? '' }}

                            </td>
                        </tr>
                    </table>
                </div>
            </div>
            {{-- END OF YEAR iReady Post Test section --}}
            <div style="margin-top:10px;">
                <span style="margin-top:10px;background-color: #ffffff; padding:2px">
                    <strong>END OF YEAR iReady Post Test </strong>
                </span>
            </div>
            <div style="margin-top:10px;margin-left:20px;">
                <div>
                    <table>
                        <tr>
                            <td>
                                <span style="background-color: #ffffff; padding:2px">
                                    <strong>Math points: </strong>
                                </span>
                            </td>
                            <td>
                                {{ $i_ready_math_eoy_s[0]->column_ac  ?? '' }}
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <span style="background-color: #ffffff; padding:2px">
                                    <strong>Math relative placement: </strong>
                                </span>
                            </td>
                            <td>
                                {{ $i_ready_math_eoy_s[0]->column_ae  ?? '' }}
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <span style="background-color: #ffffff; padding:2px">
                                    <strong>Math Level </strong>
                                </span>
                            </td>
                            <td>
                                {{ $i_ready_math_eoy_s[0]->column_ad  ?? '' }}
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <span style="background-color: #ffffff; padding:2px">
                                    <strong>Reading points: </strong>
                                </span>
                            </td>
                            <td>
                                {{ $i_ready_reading_eoy_s[0]->column_ac  ?? '' }}
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <span style="background-color: #ffffff; padding:2px">
                                    <strong>Reading relative placement: </strong>
                                </span>
                            </td>
                            <td>
                                {{ $i_ready_reading_eoy_s[0]->column_ae  ?? '' }}
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <span style="background-color: #ffffff; padding:2px">
                                    <strong>Reading level: </strong>
                                </span>
                            </td>
                            <td>
                                {{ $i_ready_reading_eoy_s[0]->column_ad  ?? '' }}
                            </td>
                        </tr>
                    </table>
                </div>
            </div>
            {{-- GROWTH END OF YEAR --}}
            <div style="margin-top:10px;">
                <span style="background-color: #ffffff; padding:2px">
                    <strong>GROWTH END OF YEAR </strong>
                </span>
            </div>
            <div style="margin-top:10px;margin-left:20px;">
                <div>
                    <table>

                        <tr>
                            <td>
                                <span style="background-color: #ffffff; padding:2px">
                                    <strong>Math growth in points: </strong>
                                </span>
                            </td>
                            <td>
                                {{ $consolidate->column_ai  ?? '' }}
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <span style="background-color: #ffffff; padding:2px">
                                    <strong>Math growth levels: </strong>
                                </span>
                            </td>
                            <td>
                                {{ $consolidate->column_aj  ?? '' }}
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <span style="background-color: #ffffff; padding:2px">
                                    <strong>Reading growth in points: </strong>
                                </span>
                            </td>
                            <td>
                                {{ $consolidate->column_ak ?? '' }}
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <span style="background-color: #ffffff; padding:2px">
                                    <strong>Reading growth levels: </strong>
                                </span>
                            </td>
                            <td>
                                {{ $consolidate->column_al  ?? ''  ?? ''}}
                            </td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>
        {{-- CAASPP --}}
        <div style="margin-top:10px;">
            <span style="background-color: #ffffff; padding:2px">
                <strong>CAASPP SCORES </strong>
            </span>
        </div>
        <div style="margin-top:10px;margin-left:20px;">
            <div>
                <table>

                    <tr>
                        <td>
                            <span style="background-color: #ffffff; padding:2px">
                                <strong>Math: </strong>
                            </span>
                        </td>
                        <td>
                            @if ($caaspps && isset($caaspps[0]) && ($caaspps[0]->column_a == "02" || $caaspps[0]->column_a == "2"))
                                {{ $caaspps[0]->column_ev  ?? ''  ?? ''}}
                            @elseif ($caaspps && isset($caaspps[1]) && ($caaspps[1]->column_a == "02" || $caaspps[1]->column_a == "2"))
                                {{ $caaspps[1]->column_ev  ?? ''  ?? ''}}
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <span style="background-color: #ffffff; padding:2px">
                                <strong>Reading: </strong>
                            </span>
                        </td>
                        <td>
                            @if ($caaspps && isset($caaspps[0]) && ($caaspps[0]->column_a == "01" || $caaspps[0]->column_a == "1"))
                                {{ $caaspps[0]->column_ev  ?? ''  ?? ''}}
                            @elseif ($caaspps && isset($caaspps[1]) && ($caaspps[1]->column_a == "01" || $caaspps[1]->column_a == "1"))
                                {{ $caaspps[1]->column_ev  ?? ''  ?? ''}}
                            @endif
                        </td>
                    </tr>
                </table>
            </div>
        </div>
        {{-- Easycbm section --}}
        <div style="margin-top:10px;margin-bottom:10px;">
            <div>
                <span style="background-color: #ffffff; padding:2px">
                    <strong>EASYCBM SCORES</strong>
                </span>
            </div>
            <div>
                <span style="color: #8FBC8F; padding:2px">
                    <strong>BENCHMARK MATH </strong>
                </span>
            </div>
            <div style="margin-top:10px;margin-left:20px;">
                <div>
                    <table>
                        <tr>
                            <td>
                                <span style="background-color: #ffffff; padding:2px">
                                    <strong>Proficient Math Percentile: </strong>
                                </span>
                            </td>
                            <td>
                                {{ $easy_cbm_falls[0]->column_aw  ?? ''  }}
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <span style="background-color: #ffffff; padding:2px">
                                    <strong>Overall risk Math:  </strong>
                                </span>
                            </td>
                            <td>
                                {{ $easy_cbm_falls[0]->column_ba  ?? ''  }}
                            </td>
                        </tr>
                    </table>
                </div>
            </div>
            <div style="margin-top: 10px">
                <span style="color: #8FBC8F; padding:2px">
                    <strong>BENCHMARK READING  </strong>
                </span>
            </div>
            <div style="margin-top:10px;margin-left:20px;">
                <div>
                    <table>

                        <tr>
                            <td>
                                <span style="background-color: #ffffff; padding:2px">
                                    <strong>Fluency Percentile: </strong>
                                </span>
                            </td>
                            <td>
                                {{ $easy_cbm_falls[0]->column_ag  ?? ''  }}
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <span style="background-color: #ffffff; padding:2px">
                                    <strong>Vocabulary Percentile: </strong>
                                </span>
                            </td>
                            <td>
                                {{ $easy_cbm_falls[0]->column_am  ?? ''  }}
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <span style="background-color: #ffffff; padding:2px">
                                    <strong>Proficient Reading: </strong>
                                </span>
                            </td>
                            <td>
                                {{ $easy_cbm_falls[0]->column_ac ?? ''  }}
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <span style="background-color: #ffffff; padding:2px">
                                    <strong>Letter Naming: </strong>
                                </span>
                            </td>
                            <td>
                                {{ $easy_cbm_falls[0]->column_w ?? ''  }}
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <span style="background-color: #ffffff; padding:2px">
                                    <strong>Letter Sounds: </strong>
                                </span>
                            </td>
                            <td>
                                {{ $easy_cbm_falls[0]->column_z ?? ''  }}
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <span style="background-color: #ffffff; padding:2px">
                                    <strong>Word Accuracy: </strong>
                                </span>
                            </td>
                            <td>
                                {{ $easy_cbm_falls[0]->column_ap ?? ''  }}
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <span style="background-color: #ffffff; padding:2px">
                                    <strong>Phoneme Segmenting Accuracy: </strong>
                                </span>
                            </td>
                            <td>
                                {{ $easy_cbm_falls[0]->column_aj  ?? ''  }}
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <span style="background-color: #ffffff; padding:2px">
                                    <strong>Overall Risk Reading: </strong>
                                </span>
                            </td>
                            <td>
                                {{ $easy_cbm_falls[0]->column_az  ?? ''  }}
                            </td>
                        </tr>
                    </table>
                </div>
            </div>
            <div style="margin-top:20px;">
                <span style="color: #8FBC8F; padding:2px">
                    <strong>PROGRESS MONITORING</strong>
                </span>
            </div>
            <div style="margin-top:10px;margin-left:20px;">
                <div>
                    <table>
                        @if (isset($easy_cbm_progmons) && $easy_cbm_progmons)
                            @foreach ($easy_cbm_progmons as $easy_cbm_progmon)
                                <tr>
                                    <td>
                                        <span style="background-color: #ffffff; padding:2px">
                                            <strong>Measure Type: </strong>
                                        </span>
                                    </td>
                                    <td>
                                        {{ $easy_cbm_progmon->column_s  ?? ''}}
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <span style="background-color: #ffffff; padding:2px">
                                            <strong>Score Percentile: </strong>
                                        </span>
                                    </td>
                                    <td>
                                        {{ $easy_cbm_progmon->column_w  ?? ''}}
                                    </td>
                                </tr>
                            @endforeach
                        @endif
                    </table>
                </div>

            </div>

        </div>


        {{-- Star scores section --}}
        <div style="margin-top:10px;margin-bottom:10px;">
            <div>
                <span style="background-color: #ffffff; padding:2px">
                    <strong>STAR SCORES</strong>
                </span>
            </div>
            <div>
                <span style="color: #8FBC8F; padding:2px">
                    <strong>FALL STAR Assessment</strong>
                </span>
            </div>
            <div style="margin-top:10px;margin-left:20px;">
                <div>
                    <table>
                        <tr>
                            <td>
                                <span style="background-color: #ffffff; padding:2px">
                                    <strong>Math: </strong>
                                </span>
                            </td>
                            <td>
                                {{-- {{ $star_fall_maths[0]->column_h  ?? ''}} --}}
                                {{ $star_fall_maths[0]->column_i  ?? ''}}
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <span style="background-color: #ffffff; padding:2px">
                                    <strong>Reading:  </strong>
                                </span>
                            </td>
                            <td>
                                {{-- {{ $star_fall_readings[0]->column_h  ?? ''}} --}}
                                {{ $star_fall_readings[0]->column_i  ?? ''}}
                            </td>
                        </tr>
                    </table>
                </div>
            </div>
            {{--  --}}
            <div style="margin-top:20px;">
                <span style="color: #8FBC8F; padding:2px">
                    <strong>ENGLISH LANGUAGE LEARNER STATUS:</strong>
                </span>
            </div>
            <div style="margin-top:10px;margin-left:20px;">
                <div>
                    <table>

                        <tr>
                            <td>
                                <span style="background-color: #ffffff; padding:2px">
                                    <strong>ELL: </strong>
                                </span>
                            </td>
                            <td>
                                {{ (isset($elstudents[0])) ? 'Y' : '' }}
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <span style="background-color: #ffffff; padding:2px">
                                    <strong>ELPAC Level: </strong>
                                </span>
                            </td>
                            <td>
                                {{ $elstudents[0]->column_ad ?? '' }}
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <span style="background-color: #ffffff; padding:2px">
                                    <strong>Brainpop: </strong>
                                </span>
                            </td>
                            <td>
                                @if ($brainpops)
                                    @foreach ($brainpops  as $brainpop )
                                        @if ($brainpop->column_j)
                                            {{ $brainpop->column_j ?? '' }}
                                        @endif
                                    @endforeach
                                @endif
                            </td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>
    </div>
    {{-- STUDENT SUCCESS TEAM HISTORY--}}
    <div style="margin-top:10px;margin-bottom:10px;">
        <div>
            <span style="background-color: #ffffff; padding:2px">
                <strong>STUDENT SUCCESS TEAM HISTORY</strong>
            </span>
        </div>
        <div>
            Type: {{ $sst_reports[0]->column_c ?? '' }}
        </div>
        <div>
            Date: {{ $sst_reports[0]->column_d ?? '' }}
        </div>
    </div>
    {{-- Intervention Notes section --}}
    <div style="margin-top:10px;margin-bottom:10px;">
        <div>
            <span style="background-color: #ffffff; padding:2px">
                <strong>INTERVENTION NOTES</strong>
            </span>
        </div>
        <div>
            {{ $math_lists[0]->column_aa ?? '' }}
        </div>
        <div>
            {{ $student->column_aa ?? '' }}
        </div>
    </div>



</div>
