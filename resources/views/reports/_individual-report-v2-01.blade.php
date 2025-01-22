    <div class="row">
        <div class="row" style="height:120px;     background-repeat: no-repeat; background-size: 100% 100%;background-image: url('{{getenv('APP_URL')}}/assets/images/report-header.png') ">
            @if ($isPDF == "Y")
            {{-- <img src="{{getenv('APP_URL')}}/assets/images/report-header.png" alt="" width="90%"> --}}
            <br><br><span style="margin-top:95px; margin-left:120px;font-size:2.0rem; font-weight:bolder;  color:#151B54">Student Information Report 24/25</span>
            @else
            {{-- <img src="{{getenv('APP_URL')}}/assets/images/report-header.png" alt="" width="90%"> --}}
            <span style="margin-top:15px; margin-left:120px;font-size:4.5rem; font-weight:bolder;  color:#151B54">Student Information Report 24/25</span>
            @endif
        </div>
    </div>
    <div class="row " style="margin-top:10px;">
        <table class="table" style="border: 1px solid black;border-collapse:collapse;" cellpadding="5" cellspacing="5">
            <tbody>
                <tr>
                    <th style="border: 1px solid black; width:165px;">
                        {{-- last name --}} {{-- first name --}}
                        Student Name:  <br><span>{{($consolidate->column_b)  ?? ''}} {{($consolidate->column_c)  ?? ''}}</span>
                    </th>
                    <th style="border: 1px solid black; width:80px;">
                        {{-- SSID --}}
                        SSID: <br>{{($consolidate->column_a)  ?? ''  ?? ''}}
                    </th>
                    <th style="border: 1px solid black; width:60px">
                        {{-- Grade --}}
                        Grade:<br> {{$consolidate->column_d  ?? ''}}
                    </th>
                    <th style="border: 1px solid black; width:100px">
                        {{-- SIS --}}
                        SIS: <br>{{$consolidate->column_e  ?? ''}}
                    </th>
                    <th style="border: 1px solid black; width:110px">
                        {{-- Teacher --}}
                        Teacher: <br>{{$consolidate->column_g  ?? ''}}
                    </th>
                    <th style="border: 1px solid black; width:110px">
                        {{-- Program --}}
                        @php
                            $programName = str_replace("Independent Study - " , "", $student_accounts[0]->column_h);
                        @endphp
                        Program: <br>{{$programName  ?? ''}}
                    </th>
                </tr>
            </tbody>
        </table>
    </row>
