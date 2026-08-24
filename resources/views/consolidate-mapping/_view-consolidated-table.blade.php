<table class="table table-striped table-hover-hold">
    <thead class="thead">
        <tr>
            @if (!$exportFormat)
                <th >Options</th>
            @endif
            @foreach ($consolidatedBasicFields as $k => $field)
                    <th style="">{{ $field[1] }}</th>
            @endforeach
            @foreach ($consolidatedFields as $k => $field)
                @if (!isset($consolidatedBasicFields[$k]))
                    @if (!$sectionId || $sectionId == 0)
                            @if (isset($sections[$field[2]]))
                                <th style="background-color: {{$sections[$field[2]]->color}} !important"><span style="color:{{$sections[$field[2]]->font_color}}">{{ $field[1] }}</span></th>
                            @else
                                <th style="">{{ $field[1] }}</th>
                            @endif
                    @else
                        @if (isset($sections[$field[2]]))
                            @if ($sectionId == $field[2])
                                <th style="background-color: {{$sections[$field[2]]->color}} !important"><span style="color:{{$sections[$field[2]]->font_color}}">{{ $field[1] }}</span></th>
                            @endif
                        @endif
                    @endif

                @endif
            @endforeach
            @if (!$exportFormat)
                @if (!$sectionId || $sectionId == 0)
                    <th >Options</th>
                @endif
            @endif
        </tr>
    </thead>
    <tbody>
        @foreach ($rows as $row)
            <tr>
                @if (!$exportFormat)
                <td style="z-index: 10 !important;">
                    @include("consolidate-mapping._view-report-button")
                </td>
                @endif
                @foreach ($consolidatedBasicFields as $k => $field)
                    <td style="">{{ $row->{$field[0]} }}</td>
                @endforeach
                @foreach ($consolidatedFields as $k => $field)
                    @if (!isset($consolidatedBasicFields[$k]))
                        @if (!$sectionId || $sectionId == 0)
                            @php
                                $backgroundColor = "#ffffff";
                                $color = "#000000";
                                $cleanString = str_replace("\r", "", $row->{$field[0]});
                                if (isset($consolidateColors[$field[0]][$cleanString])) {
                                    $backgroundColor = $consolidateColors[$field[0]][$cleanString]['background_color'];
                                    $color = $consolidateColors[$field[0]][$cleanString]['color'];
                                }
                            @endphp
                            <td style="background:{{ $backgroundColor }} !important">
                                <span style="color:{{ $color }} !important">
                                {{ $row->{$field[0]} }}
                                </span>
                            </td>
                        @else
                            @if (isset($sections[$field[2]]))
                                @if ($sectionId == $field[2])
                                @php
                                    $backgroundColor = "#ffffff";
                                    $color = "#000000";
                                    $cleanString = str_replace("\r", "", $row->{$field[2]});
                                    if (isset($consolidateColors[$field[2]][$cleanString])) {
                                        $backgroundColor = $consolidateColors[$field[2]][$cleanString]['background_color'];
                                        $color = $consolidateColors[$field[2]][$cleanString]['color'];
                                    }
                                @endphp
                                    <td style="background:{{ $backgroundColor }} !important">
                                        <span style="color:{{ $color }} !important">
                                        {{ $row->{$field[0]} }}
                                        </span>
                                    </td>
                                @endif
                            @endif
                        @endif
                    @endif
                @endforeach

            </tr>
        @endforeach
    </tbody>
</table>
