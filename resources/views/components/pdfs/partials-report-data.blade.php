@php
    $dateFormatted = $date->format('d-m-Y');
    $dateKey = $date->format('Y-m-d');
@endphp
<div class="h-50">
    <table class="table-bordered h-100">
        <thead>
            <tr>
                <td rowspan="2" class="w-10">Day</td>
                <td rowspan="2" class="w-10">Time</td>
                <td colspan="12" class="w-45">Leq 5 mins</td>



                <td rowspan="2" class="w-10">Leq 1hr</td>
                <td rowspan="2" class="w-10">Leq 12hr</td>
                <td rowspan="2" class="w-10">Curr Dose (%)</td>
                <td rowspan="2" class="w-10">Leq5 Max (dB)</td>
            </tr>
            <tr>
                <td class='time_col'>00</td>
                <td class='time_col'>05</td>
                <td class='time_col'>10</td>
                <td class='time_col'>15</td>
                <td class='time_col'>20</td>
                <td class='time_col'>25</td>
                <td class='time_col'>30</td>
                <td class='time_col'>35</td>
                <td class='time_col'>40</td>
                <td class='time_col'>45</td>
                <td class='time_col'>50</td>
                <td class='time_col'>55</td>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td rowspan="17">
                    {{ \Carbon\Carbon::parse($dateFormatted)->format('D d M Y') }}
                </td>
                <th>7am</th>
                @for ($index = 0; $index < 12; $index++)
                    <td>
                        @php
                            $slotDate = new DateTime($dateFormatted . ' 07:' . $index * 5 . ':00');
                        @endphp
                        <x-report-individual-data-component :measurementPoint="$measurementPoint" :slotDate="$slotDate" :preparedData="$preparedData" />
                    </td>
                @endfor
                <td>
                    <x-report-individual-data-component :measurementPoint="$measurementPoint" :slotDate="$slotDate" type='1hLeq' :preparedData="$preparedData" />
                </td>
                <td rowspan="12">
                    <x-report-individual-data-component :measurementPoint="$measurementPoint" :slotDate="$slotDate" type='12hLeq' :preparedData="$preparedData" />
                </td>
                <td rowspan="12">
                    <x-report-individual-data-component :measurementPoint="$measurementPoint" :slotDate="$slotDate" type='dose' :preparedData="$preparedData" />
                </td>
                <td rowspan="12"><x-report-individual-data-component :measurementPoint="$measurementPoint" :slotDate="$slotDate"
                        type='max' :preparedData="$preparedData" />
                </td>
            </tr>
            <tr>
                <th>8am</th>
                @for ($index = 0; $index < 12; $index++)
                    <td>
                        @php
                            $slotDate = new DateTime($dateFormatted . ' 08:' . $index * 5 . ':00');
                        @endphp
                        <x-report-individual-data-component :measurementPoint="$measurementPoint" :slotDate="$slotDate" :preparedData="$preparedData" />
                    </td>
                @endfor
                <td><x-report-individual-data-component :measurementPoint="$measurementPoint" :slotDate="$slotDate" type='1hLeq' :preparedData="$preparedData" /></td>
            </tr>
            <tr>
                <th>9am</th>
                @for ($index = 0; $index < 12; $index++)
                    <td>
                        @php
                            $slotDate = new DateTime($dateFormatted . ' 09:' . $index * 5 . ':00');
                        @endphp
                        <x-report-individual-data-component :measurementPoint="$measurementPoint" :slotDate="$slotDate" :preparedData="$preparedData" />
                    </td>
                @endfor
                <td><x-report-individual-data-component :measurementPoint="$measurementPoint" :slotDate="$slotDate" type='1hLeq' :preparedData="$preparedData" /></td>
            </tr>
            <tr>
                <th>10am</th>
                @for ($index = 0; $index < 12; $index++)
                    <td>
                        @php
                            $slotDate = new DateTime($dateFormatted . ' 10:' . $index * 5 . ':00');
                        @endphp
                        <x-report-individual-data-component :measurementPoint="$measurementPoint" :slotDate="$slotDate" :preparedData="$preparedData" />
                    </td>
                @endfor
                <td><x-report-individual-data-component :measurementPoint="$measurementPoint" :slotDate="$slotDate" type='1hLeq' :preparedData="$preparedData" /></td>
            </tr>
            <tr>
                <th>11am</th>
                @for ($index = 0; $index < 12; $index++)
                    <td>
                        @php
                            $slotDate = new DateTime($dateFormatted . ' 11:' . $index * 5 . ':00');
                        @endphp
                        <x-report-individual-data-component :measurementPoint="$measurementPoint" :slotDate="$slotDate" :preparedData="$preparedData" />
                    </td>
                @endfor
                <td><x-report-individual-data-component :measurementPoint="$measurementPoint" :slotDate="$slotDate" type='1hLeq' :preparedData="$preparedData" /></td>
            </tr>
            <tr>
                <th>12pm</th>
                @for ($index = 0; $index < 12; $index++)
                    <td>
                        @php
                            $slotDate = new DateTime($dateFormatted . ' 12:' . $index * 5 . ':00');
                        @endphp
                        <x-report-individual-data-component :measurementPoint="$measurementPoint" :slotDate="$slotDate" :preparedData="$preparedData" />
                    </td>
                @endfor
                <td><x-report-individual-data-component :measurementPoint="$measurementPoint" :slotDate="$slotDate" type='1hLeq' :preparedData="$preparedData" /></td>
            </tr>
            <tr>
                <th>1pm</th>
                @for ($index = 0; $index < 12; $index++)
                    <td>
                        @php
                            $slotDate = new DateTime($dateFormatted . ' 13:' . $index * 5 . ':00');
                        @endphp
                        <x-report-individual-data-component :measurementPoint="$measurementPoint" :slotDate="$slotDate" :preparedData="$preparedData" />
                    </td>
                @endfor
                <td><x-report-individual-data-component :measurementPoint="$measurementPoint" :slotDate="$slotDate" type='1hLeq' :preparedData="$preparedData" /></td>
            </tr>
            <tr>
                <th>2pm</th>
                @for ($index = 0; $index < 12; $index++)
                    <td>
                        @php
                            $slotDate = new DateTime($dateFormatted . ' 14:' . $index * 5 . ':00');
                        @endphp
                        <x-report-individual-data-component :measurementPoint="$measurementPoint" :slotDate="$slotDate" :preparedData="$preparedData" />
                    </td>
                @endfor
                <td><x-report-individual-data-component :measurementPoint="$measurementPoint" :slotDate="$slotDate" type='1hLeq' :preparedData="$preparedData" /></td>
            </tr>
            <tr>
                <th>3pm</th>
                @for ($index = 0; $index < 12; $index++)
                    <td>
                        @php
                            $slotDate = new DateTime($dateFormatted . ' 15:' . $index * 5 . ':00');
                        @endphp
                        <x-report-individual-data-component :measurementPoint="$measurementPoint" :slotDate="$slotDate" :preparedData="$preparedData" />
                    </td>
                @endfor
                <td><x-report-individual-data-component :measurementPoint="$measurementPoint" :slotDate="$slotDate" type='1hLeq' :preparedData="$preparedData" /></td>
            </tr>
            <tr>
                <th>4pm</th>
                @for ($index = 0; $index < 12; $index++)
                    <td>
                        @php
                            $slotDate = new DateTime($dateFormatted . ' 16:' . $index * 5 . ':00');
                        @endphp
                        <x-report-individual-data-component :measurementPoint="$measurementPoint" :slotDate="$slotDate" :preparedData="$preparedData" />
                    </td>
                @endfor
                <td><x-report-individual-data-component :measurementPoint="$measurementPoint" :slotDate="$slotDate" type='1hLeq' :preparedData="$preparedData" /></td>
            </tr>
            <tr>
                <th>5pm</th>
                @for ($index = 0; $index < 12; $index++)
                    <td>
                        @php
                            $slotDate = new DateTime($dateFormatted . ' 17:' . $index * 5 . ':00');
                        @endphp
                        <x-report-individual-data-component :measurementPoint="$measurementPoint" :slotDate="$slotDate" :preparedData="$preparedData" />
                    </td>
                @endfor
                <td><x-report-individual-data-component :measurementPoint="$measurementPoint" :slotDate="$slotDate" type='1hLeq' :preparedData="$preparedData" /></td>
            </tr>
            <tr>
                <th>6pm</th>
                @for ($index = 0; $index < 12; $index++)
                    <td>
                        @php
                            $slotDate = new DateTime($dateFormatted . ' 18:' . $index * 5 . ':00');
                        @endphp
                        <x-report-individual-data-component :measurementPoint="$measurementPoint" :slotDate="$slotDate" :preparedData="$preparedData" />
                    </td>
                @endfor
                <td><x-report-individual-data-component :measurementPoint="$measurementPoint" :slotDate="$slotDate" type='1hLeq' :preparedData="$preparedData" /></td>
            </tr>
            <tr>
                <th>7pm</th>
                @for ($index = 0; $index < 12; $index++)
                    <td>
                        @php
                            $slotDate = new DateTime($dateFormatted . ' 19:' . $index * 5 . ':00');
                        @endphp
                        <x-report-individual-data-component :measurementPoint="$measurementPoint" :slotDate="$slotDate" :preparedData="$preparedData" />
                    </td>
                @endfor
                <td>
                    <x-report-individual-data-component :measurementPoint="$measurementPoint" :slotDate="$slotDate" type='1hLeq' :preparedData="$preparedData" />
                </td>

                <td rowspan="12">
                    <x-report-individual-data-component :measurementPoint="$measurementPoint" :slotDate="$slotDate" type='12hLeq' :preparedData="$preparedData" />
                </td>
                <td><x-report-individual-data-component :measurementPoint="$measurementPoint" :slotDate="$slotDate" type='dose' :preparedData="$preparedData" /></td>
                <td><x-report-individual-data-component :measurementPoint="$measurementPoint" :slotDate="$slotDate" type='max' :preparedData="$preparedData" /></td>
            </tr>
            <tr>
                <th>8pm</th>
                @for ($index = 0; $index < 12; $index++)
                    <td>
                        @php
                            $slotDate = new DateTime($dateFormatted . ' 20:' . $index * 5 . ':00');
                        @endphp
                        <x-report-individual-data-component :measurementPoint="$measurementPoint" :slotDate="$slotDate" :preparedData="$preparedData" />
                    </td>
                @endfor
                <td><x-report-individual-data-component :measurementPoint="$measurementPoint" :slotDate="$slotDate" type='1hLeq' :preparedData="$preparedData" /></td>
                <td><x-report-individual-data-component :measurementPoint="$measurementPoint" :slotDate="$slotDate" type='dose' :preparedData="$preparedData" /></td>
                <td><x-report-individual-data-component :measurementPoint="$measurementPoint" :slotDate="$slotDate" type='max' :preparedData="$preparedData" /></td>
            </tr>
            <tr>
                <th>9pm</th>
                @for ($index = 0; $index < 12; $index++)
                    <td>
                        @php
                            $slotDate = new DateTime($dateFormatted . ' 21:' . $index * 5 . ':00');
                        @endphp
                        <x-report-individual-data-component :measurementPoint="$measurementPoint" :slotDate="$slotDate" :preparedData="$preparedData" />
                    </td>
                @endfor
                <td><x-report-individual-data-component :measurementPoint="$measurementPoint" :slotDate="$slotDate" type='1hLeq' :preparedData="$preparedData" /></td>
                <td><x-report-individual-data-component :measurementPoint="$measurementPoint" :slotDate="$slotDate" type='dose' :preparedData="$preparedData" /></td>
                <td><x-report-individual-data-component :measurementPoint="$measurementPoint" :slotDate="$slotDate" type='max' :preparedData="$preparedData" /></td>
            </tr>
            <tr>
                <th>10pm</th>
                @for ($index = 0; $index < 12; $index++)
                    <td>
                        @php
                            $slotDate = new DateTime($dateFormatted . ' 22:' . $index * 5 . ':00');
                        @endphp
                        <x-report-individual-data-component :measurementPoint="$measurementPoint" :slotDate="$slotDate" :preparedData="$preparedData" />
                    </td>
                @endfor
                <td><x-report-individual-data-component :measurementPoint="$measurementPoint" :slotDate="$slotDate" type='1hLeq' :preparedData="$preparedData" /></td>
                <td><x-report-individual-data-component :measurementPoint="$measurementPoint" :slotDate="$slotDate" type='dose' :preparedData="$preparedData" /></td>
                <td><x-report-individual-data-component :measurementPoint="$measurementPoint" :slotDate="$slotDate" type='max' :preparedData="$preparedData" /></td>
            </tr>
            <tr>
                <th>11pm</th>
                @for ($index = 0; $index < 12; $index++)
                    <td>
                        @php
                            $slotDate = new DateTime($dateFormatted . ' 23:' . $index * 5 . ':00');
                        @endphp
                        <x-report-individual-data-component :measurementPoint="$measurementPoint" :slotDate="$slotDate" :preparedData="$preparedData" />
                    </td>
                @endfor
                <td><x-report-individual-data-component :measurementPoint="$measurementPoint" :slotDate="$slotDate" type='1hLeq' :preparedData="$preparedData" /></td>
                <td><x-report-individual-data-component :measurementPoint="$measurementPoint" :slotDate="$slotDate" type='dose' :preparedData="$preparedData" /></td>
                <td><x-report-individual-data-component :measurementPoint="$measurementPoint" :slotDate="$slotDate" type='max' :preparedData="$preparedData" /></td>
            </tr>
            @php
                $dateFormatted = \Carbon\Carbon::parse($dateFormatted)->addDay()->format('d-m-Y');
                $dateKey = \Carbon\Carbon::parse($dateFormatted)->format('Y-m-d');
                $dayPreparedData = $preparedData[$dateKey] ?? null;
            @endphp
            <tr>
                <td rowspan="7">
                    {{ \Carbon\Carbon::parse($dateFormatted)->format('D d M Y') }}</td>
                <th>12am</th>
                @for ($index = 0; $index < 12; $index++)
                    <td>
                        @php
                            $slotDate = new DateTime($dateFormatted . ' 00:' . $index * 5 . ':00');
                        @endphp
                        <x-report-individual-data-component :measurementPoint="$measurementPoint" :slotDate="$slotDate" :preparedData="$preparedData" />
                    </td>
                @endfor
                <td><x-report-individual-data-component :measurementPoint="$measurementPoint" :slotDate="$slotDate" type='1hLeq' :preparedData="$preparedData" /></td>
                <td><x-report-individual-data-component :measurementPoint="$measurementPoint" :slotDate="$slotDate" type='dose' :preparedData="$preparedData" /></td>
                <td><x-report-individual-data-component :measurementPoint="$measurementPoint" :slotDate="$slotDate" type='max' :preparedData="$preparedData" /></td>
            </tr>
            <tr>
                <th>1am</th>
                @for ($index = 0; $index < 12; $index++)
                    <td>
                        @php
                            $slotDate = new DateTime($dateFormatted . ' 01:' . $index * 5 . ':00');
                        @endphp
                        <x-report-individual-data-component :measurementPoint="$measurementPoint" :slotDate="$slotDate" :preparedData="$preparedData" />
                    </td>
                @endfor
                <td><x-report-individual-data-component :measurementPoint="$measurementPoint" :slotDate="$slotDate" type='1hLeq' :preparedData="$preparedData" /></td>
                <td><x-report-individual-data-component :measurementPoint="$measurementPoint" :slotDate="$slotDate" type='dose' :preparedData="$preparedData" /></td>
                <td><x-report-individual-data-component :measurementPoint="$measurementPoint" :slotDate="$slotDate" type='max' :preparedData="$preparedData" /></td>
            </tr>
            <tr>
                <th>2am</th>
                @for ($index = 0; $index < 12; $index++)
                    <td>
                        @php
                            $slotDate = new DateTime($dateFormatted . ' 02:' . $index * 5 . ':00');
                        @endphp
                        <x-report-individual-data-component :measurementPoint="$measurementPoint" :slotDate="$slotDate" :preparedData="$preparedData" />
                    </td>
                @endfor
                <td><x-report-individual-data-component :measurementPoint="$measurementPoint" :slotDate="$slotDate" type='1hLeq' :preparedData="$preparedData" /></td>
                <td><x-report-individual-data-component :measurementPoint="$measurementPoint" :slotDate="$slotDate" type='dose' :preparedData="$preparedData" /></td>
                <td><x-report-individual-data-component :measurementPoint="$measurementPoint" :slotDate="$slotDate" type='max' :preparedData="$preparedData" /></td>
            </tr>
            <tr>
                <th>3am</th>
                @for ($index = 0; $index < 12; $index++)
                    <td>
                        @php
                            $slotDate = new DateTime($dateFormatted . ' 03:' . $index * 5 . ':00');
                        @endphp
                        <x-report-individual-data-component :measurementPoint="$measurementPoint" :slotDate="$slotDate" :preparedData="$preparedData" />
                    </td>
                @endfor
                <td><x-report-individual-data-component :measurementPoint="$measurementPoint" :slotDate="$slotDate" type='1hLeq' :preparedData="$preparedData" /></td>
                <td><x-report-individual-data-component :measurementPoint="$measurementPoint" :slotDate="$slotDate" type='dose' :preparedData="$preparedData" /></td>
                <td><x-report-individual-data-component :measurementPoint="$measurementPoint" :slotDate="$slotDate" type='max' :preparedData="$preparedData" /></td>
            </tr>
            <tr>
                <th>4am</th>
                @for ($index = 0; $index < 12; $index++)
                    <td>
                        @php
                            $slotDate = new DateTime($dateFormatted . ' 04:' . $index * 5 . ':00');
                        @endphp
                        <x-report-individual-data-component :measurementPoint="$measurementPoint" :slotDate="$slotDate" :preparedData="$preparedData" />
                    </td>
                @endfor
                <td><x-report-individual-data-component :measurementPoint="$measurementPoint" :slotDate="$slotDate" type='1hLeq' :preparedData="$preparedData" /></td>
                <td><x-report-individual-data-component :measurementPoint="$measurementPoint" :slotDate="$slotDate" type='dose' :preparedData="$preparedData" /></td>
                <td><x-report-individual-data-component :measurementPoint="$measurementPoint" :slotDate="$slotDate" type='max' :preparedData="$preparedData" /></td>
            </tr>
            <tr>
                <th>5am</th>
                @for ($index = 0; $index < 12; $index++)
                    <td>
                        @php
                            $slotDate = new DateTime($dateFormatted . ' 05:' . $index * 5 . ':00');
                        @endphp
                        <x-report-individual-data-component :measurementPoint="$measurementPoint" :slotDate="$slotDate" :preparedData="$preparedData" />
                    </td>
                @endfor
                <td><x-report-individual-data-component :measurementPoint="$measurementPoint" :slotDate="$slotDate" type='1hLeq' :preparedData="$preparedData" /></td>
                <td><x-report-individual-data-component :measurementPoint="$measurementPoint" :slotDate="$slotDate" type='dose' :preparedData="$preparedData" /></td>
                <td><x-report-individual-data-component :measurementPoint="$measurementPoint" :slotDate="$slotDate" type='max' :preparedData="$preparedData" /></td>
            </tr>
            <tr>
                <th>6am</th>
                @for ($index = 0; $index < 12; $index++)
                    <td>
                        @php
                            $slotDate = new DateTime($dateFormatted . ' 06:' . $index * 5 . ':00');
                        @endphp
                        <x-report-individual-data-component :measurementPoint="$measurementPoint" :slotDate="$slotDate" :preparedData="$preparedData" />
                    </td>
                @endfor
                <td><x-report-individual-data-component :measurementPoint="$measurementPoint" :slotDate="$slotDate" type='1hLeq' :preparedData="$preparedData" /></td>
                <td><x-report-individual-data-component :measurementPoint="$measurementPoint" :slotDate="$slotDate" type='dose' :preparedData="$preparedData" /></td>
                <td><x-report-individual-data-component :measurementPoint="$measurementPoint" :slotDate="$slotDate" type='max' :preparedData="$preparedData" /></td>
            </tr>
        </tbody>
    </table>
</div>
<footer></footer>
