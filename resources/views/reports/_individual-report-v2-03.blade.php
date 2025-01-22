    <div class="row">
        {{-- Second Group --}}
        <table class="table" style="border: 1px solid black;border-collapse:collapse;margin-top:5px;width: 100%" cellpadding="5" cellspacing="5">
            <tbody>
                <tr>
                    <th colspan="6"  style="border: 1px solid black;text-align: center; ">
                        iReady Scores
                    </th>
                </tr>
                <tr>
                    <th style="border: 1px solid black;text-align: center; ">

                    </th>
                    <th style="border: 1px solid black;text-align: center; ">
                        Fall
                    </th>
                    <th style="border: 1px solid black;text-align: center; ">
                        Mid Year
                    </th>
                    <th style="border: 1px solid black;text-align: center; ">
                        Mid Year Growth
                    </th>
                    <th style="border: 1px solid black;text-align: center; ">
                        End of Year
                    </th>
                    <th style="border: 1px solid black;text-align: center; ">
                        End of Year Growth
                    </th>
                </tr>
                <tr>
                    <th style="border: 1px solid black;text-align: center; ">
                        Math Points
                    </th>
                    <td style="border: 1px solid black;text-align: center; ">
                        {{ $i_ready_math_boys[0]->column_ac  ?? '' }}
                    </td>
                    <td style="border: 1px solid black;text-align: center; ">
                        {{ $i_ready_math_mid_years[0]->column_ac  ?? '' }}
                    </td>
                    <td style="border: 1px solid black;text-align: center; ">
                        {{ $consolidate->column_ae  ?? '' }}
                    </td>
                    <td style="border: 1px solid black;text-align: center; ">
                        {{ $i_ready_math_eoy_s[0]->column_ac  ?? '' }}
                    </td>
                    <td style="border: 1px solid black;text-align: center; ">
                        {{ $consolidate->column_ai  ?? '' }}
                    </td>
                </tr>
                <tr>
                    <th style="border: 1px solid black;text-align: center; ">
                        Math Placement
                    </th>
                    <td style="border: 1px solid black;text-align: center; ">
                        {{ $i_ready_math_boys[0]->column_ae  ?? '' }}
                    </td>
                    <td style="border: 1px solid black;text-align: center; ">
                        {{ $i_ready_math_mid_years[0]->column_ae  ?? '' }}
                    </td>
                    <td style="border: 1px solid black;text-align: center; ">
                        --
                    </td>
                    <td style="border: 1px solid black;text-align: center; ">
                        {{ $i_ready_math_eoy_s[0]->column_ae  ?? '' }}
                    </td>
                    <td style="border: 1px solid black;text-align: center; ">
                        --
                    </td>
                </tr>
                <tr>
                    <th style="border: 1px solid black;text-align: center; ">
                        Math Level
                    </th>
                    <td style="border: 1px solid black;text-align: center; ">
                        {{ $i_ready_math_boys[0]->column_ad  ?? '' }}
                    </td>
                    <td style="border: 1px solid black;text-align: center; ">
                        {{ $i_ready_math_mid_years[0]->column_ad  ?? '' }}
                    </td>
                    <td style="border: 1px solid black;text-align: center; ">
                        {{ $consolidate->column_af  ?? '' }}
                    </td>
                    <td style="border: 1px solid black;text-align: center; ">
                        {{ $i_ready_math_eoy_s[0]->column_ad  ?? '' }}
                    </td>
                    <td style="border: 1px solid black;text-align: center; ">
                        {{ $consolidate->column_aj  ?? '' }}
                    </td>
                </tr>
                <tr>
                    <th style="border: 1px solid black;text-align: center; ">
                        Reading Points
                    </th>
                    <td style="border: 1px solid black;text-align: center; ">
                        {{ $i_ready_reading_boy_s[0]->column_ac  ?? '' }}
                    </td>
                    <td style="border: 1px solid black;text-align: center; ">
                        {{ $i_ready_reading_mid_years[0]->column_ac  ?? '' }}
                    </td>
                    <td style="border: 1px solid black;text-align: center; ">
                        {{ $consolidate->column_ag  ?? '' }}
                    </td>
                    <td style="border: 1px solid black;text-align: center; ">
                        {{ $i_ready_reading_eoy_s[0]->column_ac  ?? '' }}
                    </td>
                    <td style="border: 1px solid black;text-align: center; ">
                        {{ $consolidate->column_ak ?? '' }}
                    </td>
                </tr>
                <tr>
                    <th style="border: 1px solid black;text-align: center; ">
                        Reading Placement
                    </th>
                    <td style="border: 1px solid black;text-align: center; ">
                        {{ $i_ready_reading_boy_s[0]->column_ae  ?? '' }}
                    </td>
                    <td style="border: 1px solid black;text-align: center; ">
                        {{ $i_ready_reading_mid_years[0]->column_ae  ?? '' }}
                    </td>
                    <td style="border: 1px solid black;text-align: center; ">
                        --
                    </td>
                    <td style="border: 1px solid black;text-align: center; ">
                        {{ $i_ready_reading_eoy_s[0]->column_ae  ?? '' }}
                    </td>
                    <td style="border: 1px solid black;text-align: center; ">
                        --
                    </td>
                </tr>
                <tr>
                    <th style="border: 1px solid black;text-align: center; ">
                        Reading Level
                    </th>
                    <td style="border: 1px solid black;text-align: center; ">
                        {{ $i_ready_reading_boy_s[0]->column_ad  ?? '' }}
                    </td>
                    <td style="border: 1px solid black;text-align: center; ">
                        {{ $i_ready_reading_mid_years[0]->column_ad  ?? '' }}
                    </td>
                    <td style="border: 1px solid black;text-align: center; ">
                        {{ $consolidate->column_ah  ?? '' }}
                    </td>
                    <td style="border: 1px solid black;text-align: center; ">
                        {{ $i_ready_reading_eoy_s[0]->column_ad  ?? '' }}
                    </td>
                    <td style="border: 1px solid black;text-align: center; ">
                        {{ $consolidate->column_al  ?? ''  ?? ''}}
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
