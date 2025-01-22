<div class="row">
    {{-- Second Group --}}
    <table class="table" style="border: 1px solid black;border-collapse:collapse;margin-top:5px;width:100%" cellpadding="5" cellspacing="5">
        <tbody>
            <tr style="width:100%">
                {{-- col 1 --}}
                <td style="vertical-align: top;">
                    <table class="table " style="border: 1px solid black;border-collapse:collapse;margin-top:5px;width:100%" cellpadding="5" cellspacing="5">
                        <th colspan="2" style="text-align: center;">
                            CAASPP Score
                        </th>
                        <tr>
                            <td>
                                Math
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
                                Reading
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
                </td>
                {{-- col 2 --}}
                <td style="vertical-align: top">
                    <table class="table " style="border: 1px solid black;border-collapse:collapse;margin-top:5px;width:100%" cellpadding="5" cellspacing="5">
                        <th colspan="2" style="text-align: center;">
                            ELL Status
                        </th>
                        <tr>
                            <td>
                                ELL
                            </td>
                            <td>
                                {{ (isset($elstudents[0])) ? 'Y' : '' }}
                            </td>
                        </tr>
                        <tr>
                            <td>
                                ELPAC Level
                            </td>
                            <td>
                                {{ $elstudents[0]->column_ad ?? '' }}
                            </td>
                        </tr>
                        <tr>
                            <td>
                                Brainpop EL Sessions
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
                </td>
                {{-- col 3 --}}
                <td style="vertical-align: top;">
                    <table class="table " style="border: 1px solid black;border-collapse:collapse;margin-top:5px;width:100%" cellpadding="5" cellspacing="5">
                        <th colspan="2" style="text-align: center;">
                            SST/TREP
                        </th>
                        <tr>
                            <td>
                                Date
                            </td>
                            <td>
                                Type
                            </td>
                        </tr>
                        <tr>
                            <td>
                                {{ $sst_reports[0]->column_d ?? '' }}&nbsp;
                            </td>
                            <td>
                                {{ $sst_reports[0]->column_c ?? '' }}&nbsp;
                            </td>
                        </tr>

                    </table>
                </td>

                {{-- col 4 --}}
                <td colspan="3" style="vertical-align: top;">
                    <table class="table " style="border: 1px solid black;border-collapse:collapse;margin-top:5px;width:100%" cellpadding="5" cellspacing="5">
                        <tr>
                            <th colspan="2" style="text-align: center;width:10%">
                                easyCBM Fall
                            </th>
                        </tr>
                        <tr >
                            <td style="width:20%">
                                Math Risk
                            </td>
                            <td style="width:80%">
                                {{ $easy_cbm_falls[0]->column_at  ?? ''  }}
                            </td>
                        </tr>
                        <tr >
                            <td style="width:20%">
                                Reading Risk
                            </td>
                            <td style="width:80%">
                                {{ $easy_cbm_falls[0]->column_as  ?? ''  }}
                            </td>
                        </tr>
                        {{-- <tr>
                            <td style="vertical-align: top;">
                                Progress Monitoring
                            </td>
                            <td>
                                    @if (isset($easy_cbm_progmons) && $easy_cbm_progmons)
                                        @foreach ($easy_cbm_progmons as $easy_cbm_progmon)
                                            <span style="padding-bottom: 4px">
                                                <strong>Measure Type: </strong> {{ $easy_cbm_progmon->column_s  ?? ''}}
                                            </span>
                                            <br>
                                            <span style="padding-bottom: 4px">
                                                <strong>Score Percentile: </strong> {{ $easy_cbm_progmon->column_w  ?? ''}}
                                            </span>
                                            <br>
                                        @endforeach
                                    @endif
                                </table>
                            </td>
                        </tr> --}}
                    </table>
                </td>
            </tr>

        </tbody>
    </table>
</div>
