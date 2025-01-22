@if ($isConsolidated=="Y")
<style>
     .page-break {
        page-break-after: always;
    }
</style>
@endif
<div class="container page-break" style="margin-left:10px;margin-top:20px;">
    @php
        //var_dump(request()->all,$isPDF)
    @endphp
    <div class="row" style="height:120px;     background-repeat: no-repeat; background-size: 100% 100%;background-image: url('{{getenv('APP_URL')}}/assets/images/report-header.png') ">
        @if ($isPDF == "Y")
        {{-- <img src="{{getenv('APP_URL')}}/assets/images/report-header.png" alt="" width="90%"> --}}
        <br><br><span style="margin-top:95px; margin-left:120px;font-size:35px; font-weight:bolder;  color:#151B54">Student Information Report 23/24</span>
        @else
        {{-- <img src="{{getenv('APP_URL')}}/assets/images/report-header.png" alt="" width="90%"> --}}
        <span style="margin-top:15px; margin-left:120px;font-size:65px; font-weight:bolder;  color:#151B54">Student Information Report 23/24</span>
        @endif
    </div>
    <div class="row">
        <h2>Student Info</h2>
    </div>
    <div class="row">
        <table class="table" style="border: 1px solid black;">
            <thead>
                <tr>
                    <th style="border: 1px solid black;">
                        Student Last Name
                    </th>
                    <th style="border: 1px solid black;">
                        Student First Name
                    </th>
                    <th style="border: 1px solid black;">
                        SSID
                    </th>
                    <th style="border: 1px solid black;">
                        Grade
                    </th>
                    <th style="border: 1px solid black;">
                        SIS
                    </th>
                    <th style="border: 1px solid black;">
                        Program
                    </th>
                    <th style="border: 1px solid black;">
                        Teacher Name
                    </th>
                </tr>
            </thead>
            <tbody>
                {{-- last name --}}
                <td style="border: 1px solid black;">
                    @if (getenv("APP_ENV") == "PROD" || getenv("IS_TESTING")=="Y")
                        {{decrypt($consolidate->column_b)  ?? ''}}
                    @else
                        {{($consolidate->column_b)  ?? ''}}
                    @endif

                </td>
                {{-- first name --}}
                <td style="border: 1px solid black;">
                    @if (getenv("APP_ENV") == "PROD" || getenv("IS_TESTING")=="Y")
                        {{decrypt($consolidate->column_c)  ?? ''}}
                    @else
                        {{($consolidate->column_c)  ?? ''}}
                    @endif
                </td>
                {{-- SSID --}}
                <td style="border: 1px solid black;">
                    {{($consolidate->column_a)  ?? ''  ?? ''}}
                </td>
                {{-- Grade --}}
                <td style="border: 1px solid black;">
                    {{$consolidate->column_d  ?? ''}}
                </td>
                {{-- SIS --}}
                <td style="border: 1px solid black;">
                    {{$consolidate->column_e  ?? ''}}
                </td>
                {{-- Program --}}
                <td style="border: 1px solid black;">
                    {{$student_list[0]->column_e  ?? ''}}
                </td>
                {{-- Teacher name --}}
                <td style="border: 1px solid black;">
                    {{$consolidate->column_g  ?? ''}}
                </td>
            </tbody>
        </table>
        <div style="margin-top:10px;margin-bottom:10px;">
            <div>
                <strong>QUALIFYING SUBJECT</strong>
            </div>
            <div>
                {{$math_lists[0]->column_f  ?? ''}}
            </div>
            <div>
                {{$student_list[0]->column_f  ?? ''}}
            </div>
        </div>
        <div style="margin-top:10px;margin-bottom:10px;">
            <div>
                <span style="background-color: #ffffff; padding:2px">
                    <strong>INTERVENTION PROGRAM RECOMMENDED</strong>
                </span>
            </div>
            <div>
                <table>
                    <tr>
                        <td style="width: 20%">
                            <span style="color: #8FBC8F; padding:2px">
                                <strong>Math:</strong>
                            </span>
                        </td>
                        <td style="width: 80%">
                            {{ $math_lists[0]->column_j  ?? ''}}
                        </td>
                    </tr>
                    <tr>
                        <td style="width: 20%">
                            <span style="color: #8FBC8F; padding:2px">
                                <strong>Reading:</strong>
                            </span>
                        </td>
                        <td style="width: 80%">
                            {{ $student_list[0]->column_j  ?? ''}}
                        </td>
                    </tr>
                </table>
            </div>
        </div>

        <div style="margin-top:10px;margin-bottom:10px;">
            <div>
                <span style="background-color: #ffffff; padding:2px">
                    <strong>INTERVENTION PROGRAM SELECTED </strong>
                </span>
            </div>
            <div>
                <table>
                    <tr>
                        <td style="width: 20%">
                            <span style="color: #8FBC8F; padding:2px">
                                <strong>Math:</strong>
                            </span>
                        </td>
                        <td style="width: 80%">
                            {{ $math_lists[0]->column_o  ?? ''}}
                        </td>
                    </tr>
                    <tr>
                        <td style="width: 20%">
                            <span style="color: #8FBC8F; padding:2px">
                                <strong>Reading:</strong>
                            </span>
                        </td>
                        <td style="width: 80%">
                            {{ $student_list[0]->column_o  ?? ''}}
                        </td>
                    </tr>
                </table>

            </div>
        </div>
        <div style="margin-top:10px;margin-bottom:10px;">
            <div>
                <span style="background-color: #ffffff; padding:2px">
                    <strong>INTERVENTION CLASS INFO (if applicable) </strong>
                </span>
            </div>
            <div>
                <table>
                    <tr>
                        <td style="width: 20%">
                            <span style="color: #8FBC8F; padding:2px">
                                <strong>Math:</strong>
                            </span>
                        </td>
                        <td style="width: 80%">
                            {{ $math_lists[0]->column_z  ?? ''}}
                        </td>
                    </tr>
                    <tr>
                        <td style="width: 20%">
                            <span style="color: #8FBC8F; padding:2px">
                                <strong>Reading:</strong>
                            </span>
                        </td>
                        <td style="width: 80%">
                            {{ $student_list[0]->column_z  ?? ''}}
                        </td>
                    </tr>
                </table>
            </div>
        </div>
        <div style="margin-top:10px;margin-bottom:10px;">
            <div>
                <span style="background-color: #ffffff; padding:2px">
                    <strong>INTERVENTION CLASS ATTENDANCE (if applicable) </strong>
                </span>
            </div>
            <div>
                <table>
                    <tr>
                        <td style="width: 20%">
                            <span style="color: #8FBC8F; padding:2px">
                                <strong>Math:</strong>
                            </span>
                        </td>
                        <td style="width: 80%">
                            {{ $attendance_maths[0]->column_h ?? ''}}
                        </td>
                    </tr>
                    <tr>
                        <td style="width: 20%">
                            <span style="color: #8FBC8F; padding:2px">
                                <strong>Reading:</strong>
                            </span>
                        </td>
                        <td style="width: 80%">
                            {{ $attendance_elas[0]->column_h ?? ''}}
                        </td>
                    </tr>
                </table>
            </div>
        </div>
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
                                {{$i_ready_math_minutes[0]->column_o  ?? ''}}
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <span style="color:  #8FBC8F; padding:2px">
                                    <strong>Iready Reading Minutes: </strong>
                                </span>
                            </td>
                            <td>
                                {{ $i_ready_reading_minutes[0]->column_o ?? ''}}
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
                                {{ $v_math_minutes[0]->column_h ?? '' }}
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
