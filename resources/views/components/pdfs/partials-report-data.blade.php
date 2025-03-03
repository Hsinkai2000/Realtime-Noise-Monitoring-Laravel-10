@php
    $date = $date->format('d-m-Y');
@endphp
<div>
    <table class="table-bordered" style="height:50vh">
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
                    {{ \Carbon\Carbon::parse($date)->format('D d M Y') }}
                </td>
                <th>7am</th>
                @for ($index = 0; $index < 12; $index++)
                    <td>
                        @php
                            $slotDate = new DateTime($date . ' 07:' . $index * 5 . ':00');
                        @endphp
                        <x-report-individual-data-component :measurementPoint="$measurementPoint" :slotDate="$slotDate" />
                    </td>
                @endfor
                <td>
                    <x-report-individual-data-component :measurementPoint="$measurementPoint" :slotDate="$slotDate" type='1hLeq' />
                </td>
                <td rowspan="12">
                    <x-report-individual-data-component :measurementPoint="$measurementPoint" :slotDate="$slotDate" type='12hLeq' />
                </td>
                <td rowspan="12">
                    <x-report-individual-data-component :measurementPoint="$measurementPoint" :slotDate="$slotDate" type='dose' />
                </td>
                <td rowspan="12"><x-report-individual-data-component :measurementPoint="$measurementPoint" :slotDate="$slotDate"
                        type='max' />
                </td>
            </tr>
            <tr>
                <th>8am</th>
                @for ($index = 0; $index < 12; $index++)
                    <td>
                        @php
                            $slotDate = new DateTime($date . ' 08:' . $index * 5 . ':00');
                        @endphp
                        <x-report-individual-data-component :measurementPoint="$measurementPoint" :slotDate="$slotDate" />
                    </td>
                @endfor
                <td><x-report-individual-data-component :measurementPoint="$measurementPoint" :slotDate="$slotDate" type='1hLeq' /></td>
            </tr>
            <tr>
                <th>9am</th>
                @for ($index = 0; $index < 12; $index++)
                    <td>
                        @php
                            $slotDate = new DateTime($date . ' 09:' . $index * 5 . ':00');
                        @endphp
                        <x-report-individual-data-component :measurementPoint="$measurementPoint" :slotDate="$slotDate" />
                    </td>
                @endfor
                <td><x-report-individual-data-component :measurementPoint="$measurementPoint" :slotDate="$slotDate" type='1hLeq' /></td>
            </tr>
            <tr>
                <th>10am</th>
                @for ($index = 0; $index < 12; $index++)
                    <td>
                        @php
                            $slotDate = new DateTime($date . ' 10:' . $index * 5 . ':00');
                        @endphp
                        <x-report-individual-data-component :measurementPoint="$measurementPoint" :slotDate="$slotDate" />
                    </td>
                @endfor
                <td><x-report-individual-data-component :measurementPoint="$measurementPoint" :slotDate="$slotDate" type='1hLeq' /></td>
            </tr>
            <tr>
                <th>11am</th>
                @for ($index = 0; $index < 12; $index++)
                    <td>
                        @php
                            $slotDate = new DateTime($date . ' 11:' . $index * 5 . ':00');
                        @endphp
                        <x-report-individual-data-component :measurementPoint="$measurementPoint" :slotDate="$slotDate" />
                    </td>
                @endfor
                <td><x-report-individual-data-component :measurementPoint="$measurementPoint" :slotDate="$slotDate" type='1hLeq' /></td>
            </tr>
            <tr>
                <th>12pm</th>
                @for ($index = 0; $index < 12; $index++)
                    <td>
                        @php
                            $slotDate = new DateTime($date . ' 12:' . $index * 5 . ':00');
                        @endphp
                        <x-report-individual-data-component :measurementPoint="$measurementPoint" :slotDate="$slotDate" />
                    </td>
                @endfor
                <td><x-report-individual-data-component :measurementPoint="$measurementPoint" :slotDate="$slotDate" type='1hLeq' /></td>
            </tr>
            <tr>
                <th>1pm</th>
                @for ($index = 0; $index < 12; $index++)
                    <td>
                        @php
                            $slotDate = new DateTime($date . ' 13:' . $index * 5 . ':00');
                        @endphp
                        <x-report-individual-data-component :measurementPoint="$measurementPoint" :slotDate="$slotDate" />
                    </td>
                @endfor
                <td><x-report-individual-data-component :measurementPoint="$measurementPoint" :slotDate="$slotDate" type='1hLeq' /></td>
            </tr>
            <tr>
                <th>2pm</th>
                @for ($index = 0; $index < 12; $index++)
                    <td>
                        @php
                            $slotDate = new DateTime($date . ' 14:' . $index * 5 . ':00');
                        @endphp
                        <x-report-individual-data-component :measurementPoint="$measurementPoint" :slotDate="$slotDate" />
                    </td>
                @endfor
                <td><x-report-individual-data-component :measurementPoint="$measurementPoint" :slotDate="$slotDate" type='1hLeq' /></td>
            </tr>
            <tr>
                <th>3pm</th>
                @for ($index = 0; $index < 12; $index++)
                    <td>
                        @php
                            $slotDate = new DateTime($date . ' 15:' . $index * 5 . ':00');
                        @endphp
                        <x-report-individual-data-component :measurementPoint="$measurementPoint" :slotDate="$slotDate" />
                    </td>
                @endfor
                <td><x-report-individual-data-component :measurementPoint="$measurementPoint" :slotDate="$slotDate" type='1hLeq' /></td>
            </tr>
            <tr>
                <th>4pm</th>
                @for ($index = 0; $index < 12; $index++)
                    <td>
                        @php
                            $slotDate = new DateTime($date . ' 16:' . $index * 5 . ':00');
                        @endphp
                        <x-report-individual-data-component :measurementPoint="$measurementPoint" :slotDate="$slotDate" />
                    </td>
                @endfor
                <td><x-report-individual-data-component :measurementPoint="$measurementPoint" :slotDate="$slotDate" type='1hLeq' /></td>
            </tr>
            <tr>
                <th>5pm</th>
                @for ($index = 0; $index < 12; $index++)
                    <td>
                        @php
                            $slotDate = new DateTime($date . ' 17:' . $index * 5 . ':00');
                        @endphp
                        <x-report-individual-data-component :measurementPoint="$measurementPoint" :slotDate="$slotDate" />
                    </td>
                @endfor
                <td><x-report-individual-data-component :measurementPoint="$measurementPoint" :slotDate="$slotDate" type='1hLeq' /></td>
            </tr>
            <tr>
                <th>6pm</th>
                @for ($index = 0; $index < 12; $index++)
                    <td>
                        @php
                            $slotDate = new DateTime($date . ' 18:' . $index * 5 . ':00');
                        @endphp
                        <x-report-individual-data-component :measurementPoint="$measurementPoint" :slotDate="$slotDate" />
                    </td>
                @endfor
                <td><x-report-individual-data-component :measurementPoint="$measurementPoint" :slotDate="$slotDate" type='1hLeq' /></td>
            </tr>
            <tr>
                <th>7pm</th>
                @for ($index = 0; $index < 12; $index++)
                    <td>
                        @php
                            $slotDate = new DateTime($date . ' 19:' . $index * 5 . ':00');
                        @endphp
                        <x-report-individual-data-component :measurementPoint="$measurementPoint" :slotDate="$slotDate" />
                    </td>
                @endfor
                <td>
                    <x-report-individual-data-component :measurementPoint="$measurementPoint" :slotDate="$slotDate" type='1hLeq' />
                </td>

                <td rowspan="12">
                    <x-report-individual-data-component :measurementPoint="$measurementPoint" :slotDate="$slotDate" type='12hLeq' />
                </td>
                <td><x-report-individual-data-component :measurementPoint="$measurementPoint" :slotDate="$slotDate" type='dose' /></td>
                <td><x-report-individual-data-component :measurementPoint="$measurementPoint" :slotDate="$slotDate" type='max' /></td>
            </tr>
            <tr>
                <th>8pm</th>
                @for ($index = 0; $index < 12; $index++)
                    <td>
                        @php
                            $slotDate = new DateTime($date . ' 20:' . $index * 5 . ':00');
                        @endphp
                        <x-report-individual-data-component :measurementPoint="$measurementPoint" :slotDate="$slotDate" />
                    </td>
                @endfor
                <td><x-report-individual-data-component :measurementPoint="$measurementPoint" :slotDate="$slotDate" type='1hLeq' /></td>
                <td><x-report-individual-data-component :measurementPoint="$measurementPoint" :slotDate="$slotDate" type='dose' /></td>
                <td><x-report-individual-data-component :measurementPoint="$measurementPoint" :slotDate="$slotDate" type='max' /></td>
            </tr>
            <tr>
                <th>9pm</th>
                @for ($index = 0; $index < 12; $index++)
                    <td>
                        @php
                            $slotDate = new DateTime($date . ' 21:' . $index * 5 . ':00');
                        @endphp
                        <x-report-individual-data-component :measurementPoint="$measurementPoint" :slotDate="$slotDate" />
                    </td>
                @endfor
                <td><x-report-individual-data-component :measurementPoint="$measurementPoint" :slotDate="$slotDate" type='1hLeq' /></td>
                <td><x-report-individual-data-component :measurementPoint="$measurementPoint" :slotDate="$slotDate" type='dose' /></td>
                <td><x-report-individual-data-component :measurementPoint="$measurementPoint" :slotDate="$slotDate" type='max' /></td>
            </tr>
            <tr>
                <th>10pm</th>
                @for ($index = 0; $index < 12; $index++)
                    <td>
                        @php
                            $slotDate = new DateTime($date . ' 22:' . $index * 5 . ':00');
                        @endphp
                        <x-report-individual-data-component :measurementPoint="$measurementPoint" :slotDate="$slotDate" />
                    </td>
                @endfor
                <td><x-report-individual-data-component :measurementPoint="$measurementPoint" :slotDate="$slotDate" type='1hLeq' /></td>
                <td><x-report-individual-data-component :measurementPoint="$measurementPoint" :slotDate="$slotDate" type='dose' /></td>
                <td><x-report-individual-data-component :measurementPoint="$measurementPoint" :slotDate="$slotDate" type='max' /></td>
            </tr>
            <tr>
                <th>11pm</th>
                @for ($index = 0; $index < 12; $index++)
                    <td>
                        @php
                            $slotDate = new DateTime($date . ' 23:' . $index * 5 . ':00');
                        @endphp
                        <x-report-individual-data-component :measurementPoint="$measurementPoint" :slotDate="$slotDate" />
                    </td>
                @endfor
                <td><x-report-individual-data-component :measurementPoint="$measurementPoint" :slotDate="$slotDate" type='1hLeq' /></td>
                <td><x-report-individual-data-component :measurementPoint="$measurementPoint" :slotDate="$slotDate" type='dose' /></td>
                <td><x-report-individual-data-component :measurementPoint="$measurementPoint" :slotDate="$slotDate" type='max' /></td>
            </tr>
            @php
                $date = \Carbon\Carbon::parse($date)->addDay()->format('d-m-Y');
            @endphp
            <tr>
                <td rowspan="7">
                    {{ \Carbon\Carbon::parse($date)->format('D d M Y') }}</td>
                <th>12am</th>
                @for ($index = 0; $index < 12; $index++)
                    <td>
                        @php
                            $slotDate = new DateTime($date . ' 00:' . $index * 5 . ':00');
                        @endphp
                        <x-report-individual-data-component :measurementPoint="$measurementPoint" :slotDate="$slotDate" />
                    </td>
                @endfor
                <td><x-report-individual-data-component :measurementPoint="$measurementPoint" :slotDate="$slotDate" type='1hLeq' /></td>
                <td><x-report-individual-data-component :measurementPoint="$measurementPoint" :slotDate="$slotDate" type='dose' /></td>
                <td><x-report-individual-data-component :measurementPoint="$measurementPoint" :slotDate="$slotDate" type='max' /></td>
            </tr>
            <tr>
                <th>1am</th>
                @for ($index = 0; $index < 12; $index++)
                    <td>
                        @php
                            $slotDate = new DateTime($date . ' 01:' . $index * 5 . ':00');
                        @endphp
                        <x-report-individual-data-component :measurementPoint="$measurementPoint" :slotDate="$slotDate" />
                    </td>
                @endfor
                <td><x-report-individual-data-component :measurementPoint="$measurementPoint" :slotDate="$slotDate" type='1hLeq' /></td>
                <td><x-report-individual-data-component :measurementPoint="$measurementPoint" :slotDate="$slotDate" type='dose' /></td>
                <td><x-report-individual-data-component :measurementPoint="$measurementPoint" :slotDate="$slotDate" type='max' /></td>
            </tr>
            <tr>
                <th>2am</th>
                @for ($index = 0; $index < 12; $index++)
                    <td>
                        @php
                            $slotDate = new DateTime($date . ' 02:' . $index * 5 . ':00');
                        @endphp
                        <x-report-individual-data-component :measurementPoint="$measurementPoint" :slotDate="$slotDate" />
                    </td>
                @endfor
                <td><x-report-individual-data-component :measurementPoint="$measurementPoint" :slotDate="$slotDate" type='1hLeq' /></td>
                <td><x-report-individual-data-component :measurementPoint="$measurementPoint" :slotDate="$slotDate" type='dose' /></td>
                <td><x-report-individual-data-component :measurementPoint="$measurementPoint" :slotDate="$slotDate" type='max' /></td>
            </tr>
            <tr>
                <th>3am</th>
                @for ($index = 0; $index < 12; $index++)
                    <td>
                        @php
                            $slotDate = new DateTime($date . ' 03:' . $index * 5 . ':00');
                        @endphp
                        <x-report-individual-data-component :measurementPoint="$measurementPoint" :slotDate="$slotDate" />
                    </td>
                @endfor
                <td><x-report-individual-data-component :measurementPoint="$measurementPoint" :slotDate="$slotDate" type='1hLeq' /></td>
                <td><x-report-individual-data-component :measurementPoint="$measurementPoint" :slotDate="$slotDate" type='dose' /></td>
                <td><x-report-individual-data-component :measurementPoint="$measurementPoint" :slotDate="$slotDate" type='max' /></td>
            </tr>
            <tr>
                <th>4am</th>
                @for ($index = 0; $index < 12; $index++)
                    <td>
                        @php
                            $slotDate = new DateTime($date . ' 04:' . $index * 5 . ':00');
                        @endphp
                        <x-report-individual-data-component :measurementPoint="$measurementPoint" :slotDate="$slotDate" />
                    </td>
                @endfor
                <td><x-report-individual-data-component :measurementPoint="$measurementPoint" :slotDate="$slotDate" type='1hLeq' /></td>
                <td><x-report-individual-data-component :measurementPoint="$measurementPoint" :slotDate="$slotDate" type='dose' /></td>
                <td><x-report-individual-data-component :measurementPoint="$measurementPoint" :slotDate="$slotDate" type='max' /></td>
            </tr>
            <tr>
                <th>5am</th>
                @for ($index = 0; $index < 12; $index++)
                    <td>
                        @php
                            $slotDate = new DateTime($date . ' 05:' . $index * 5 . ':00');
                        @endphp
                        <x-report-individual-data-component :measurementPoint="$measurementPoint" :slotDate="$slotDate" />
                    </td>
                @endfor
                <td><x-report-individual-data-component :measurementPoint="$measurementPoint" :slotDate="$slotDate" type='1hLeq' /></td>
                <td><x-report-individual-data-component :measurementPoint="$measurementPoint" :slotDate="$slotDate" type='dose' /></td>
                <td><x-report-individual-data-component :measurementPoint="$measurementPoint" :slotDate="$slotDate" type='max' /></td>
            </tr>
            <tr>
                <th>6am</th>
                @for ($index = 0; $index < 12; $index++)
                    <td>
                        @php
                            $slotDate = new DateTime($date . ' 06:' . $index * 5 . ':00');
                        @endphp
                        <x-report-individual-data-component :measurementPoint="$measurementPoint" :slotDate="$slotDate" />
                    </td>
                @endfor
                <td><x-report-individual-data-component :measurementPoint="$measurementPoint" :slotDate="$slotDate" type='1hLeq' /></td>
                <td><x-report-individual-data-component :measurementPoint="$measurementPoint" :slotDate="$slotDate" type='dose' /></td>
                <td><x-report-individual-data-component :measurementPoint="$measurementPoint" :slotDate="$slotDate" type='max' /></td>
            </tr>
        </tbody>
    </table>
</div>
<footer></footer>
