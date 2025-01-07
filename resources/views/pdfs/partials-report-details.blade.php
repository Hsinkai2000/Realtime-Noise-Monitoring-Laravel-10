@php
    $soundlimit = $measurementPoint->soundLimit;
@endphp
<div>
    <br />
    <br />
    <table class="table-bordered w-100">
        <tr>
            <th scope='row'>PJO Number</th>
            <td scope='row'>{{ $measurementPoint->project->job_number }}</td>
        </tr>
        <tr>
            <th scope='row'>Client</th>
            <td scope='row'>{{ $measurementPoint->project->client_name }}</td>

        </tr>
        <tr>
            <th scope='row'>Location</th>
            <td scope='row'>{{ $measurementPoint->project->jobsite_location }}</td>
        </tr>
        <tr>
            <th scope='row'>Project Description</th>
            <td scope='row'>{{ $measurementPoint->project->project_description }}</td>
        </tr>
        <tr>
            <th scope='row'>BCA Reference Number</th>
            <td scope='row'>{{ $measurementPoint->project->bca_reference_number }}</td>
        </tr>
    </table>
    <hr>
    <br />
    <h3>Measurement Point Details:</h3>
    @if ($measurementPoint->noiseMeter)
        <table class="table-bordered w-100">
            <tr>
                <th>Noise Meter</th>
                <th>Last Calibration Date</th>
                <th>Device Location</th>
                <th>Remarks</th>
            </tr>

            <tr>
                <td>{{ $measurementPoint->noiseMeter->brand . ' S/N ' . $measurementPoint->noiseMeter->serial_number }}
                </td>
                <td>{{ \Carbon\Carbon::parse($measurementPoint->noiseMeter->last_calibration_date)->format('Y-m-d') }}
                </td>
                <td>{{ $measurementPoint->device_location }}</td>
                <td>{{ $measurementPoint->remarks }}</td>
            </tr>
        </table>
    @else
        <table class="table-bordered w-100">
            <tr>
                <th>Noise Meter</th>
            </tr>

            <tr>
                <td> No Noise Meter Connected
            </tr>
        </table>
    @endif

    <hr>
    <br />
    <h3>Sound Limits:</h3>
    @if ($soundlimit->category == 'Residential')
        <table class="table-bordered w-100">
            <tr>
                <th>{{ 'Category: ' . $soundlimit->category }}</th>
                <th>7am-7pm</th>
                <th>7pm-10pm</th>
                <th>10pm-12am</th>
                <th>12am-7am</th>
            </tr>
            <tr>
                <th rowspan="2">Mon-Sat</th>
                <td>{{ $soundlimit->mon_sat_7am_7pm_leq5min }} dBA <br>(Leq 5 mins)</td>
                <td>{{ $soundlimit->mon_sat_7pm_10pm_leq5min }} dBA <br>(Leq 5 mins)</td>
                <td>{{ $soundlimit->mon_sat_10pm_12am_leq5min }} dBA <br>(Leq 5 mins)</td>
                <td>{{ $soundlimit->mon_sat_12am_7am_leq5min }} dBA <br>(Leq 5 mins)</td>
            </tr>
            <tr>
                <td>{{ $soundlimit->mon_sat_7am_7pm_leq12hr }} dBA <br>(Leq 12 hrs)</td>
                <td>{{ $soundlimit->mon_sat_7pm_10pm_leq12hr }} dBA <br>(Leq 1 hr)</td>
                <td>{{ $soundlimit->mon_sat_10pm_12am_leq12hr }} dBA <br>(Leq 1 hr)</td>
                <td>{{ $soundlimit->mon_sat_12am_7am_leq12hr }} dBA <br>(Leq 1 hr)</td>
            </tr>
            <tr>
                <th rowspan="2">Sun/Ph</th>
                <td>{{ $soundlimit->sun_ph_7am_7pm_leq5min }} dBA <br>(Leq 5 mins)</td>
                <td>{{ $soundlimit->sun_ph_7pm_10pm_leq5min }} dBA <br>(Leq 5 mins)</td>
                <td>{{ $soundlimit->sun_ph_10pm_12am_leq5min }} dBA <br>(Leq 5 mins)</td>
                <td>{{ $soundlimit->sun_ph_12am_7am_leq5min }} dBA <br>(Leq 5 mins)</td>
            </tr>
            <tr>
                <td>{{ $soundlimit->sun_ph_7am_7pm_leq12hr < 140 ? $soundlimit->sun_ph_7am_7pm_leq12hr . ' dBA' : '-' }}
                    (Leq 12 hrs)
                </td>
                <td colspan="3">
                    {{ $soundlimit->sun_ph_7pm_10pm_leq12hr < 140 ? $soundlimit->sun_ph_7pm_10pm_leq12hr . ' dBA' : '-' }}
                    <br>(Leq 12 hrs)

                </td>
            </tr>
        </table>
    @else
        <table class="table-bordered w-100">
            <tr>
                <th>{{ 'Category: ' . $soundlimit->category }}</th>
                <th>7am-7pm</th>
                <th>7pm-10pm</th>
                <th>10pm-12am</th>
                <th>12am-7am</th>
            </tr>
            <tr>
                <th rowspan="2">Mon-Sat</th>
                <td>{{ $soundlimit->mon_sat_7am_7pm_leq5min }} dBA <br>(Leq 5 mins)</td>
                <td>{{ $soundlimit->mon_sat_7pm_10pm_leq5min }} dBA <br>(Leq 5 mins)</td>
                <td>{{ $soundlimit->mon_sat_10pm_12am_leq5min }} dBA <br>(Leq 5 mins)</td>
                <td>{{ $soundlimit->mon_sat_12am_7am_leq5min }} dBA <br>(Leq 5 mins)</td>
            </tr>
            <tr>
                <td>{{ $soundlimit->mon_sat_7am_7pm_leq12hr }} dBA <br>(Leq 12 hrs)</td>
                <td colspan="3">{{ $soundlimit->mon_sat_7pm_10pm_leq12hr }} dBA <br>(Leq 12 hrs)</td>
            </tr>
            <tr>
                <th rowspan="2">Sun/Ph</th>
                <td>{{ $soundlimit->sun_ph_7am_7pm_leq5min }} dBA <br>(Leq 5 mins)</td>
                <td>{{ $soundlimit->sun_ph_7pm_10pm_leq5min }} dBA <br>(Leq 5 mins)</td>
                <td>{{ $soundlimit->sun_ph_10pm_12am_leq5min }} dBA <br>(Leq 5 mins)</td>
                <td>{{ $soundlimit->sun_ph_12am_7am_leq5min }} dBA <br>(Leq 5 mins)</td>
            </tr>
            <tr>
                <td>{{ $soundlimit->sun_ph_7am_7pm_leq12hr < 140 ? $soundlimit->sun_ph_7am_7pm_leq12hr . ' dBA' : '-' }}
                    (Leq 12 hrs)
                </td>
                <td colspan="3">
                    {{ $soundlimit->sun_ph_7pm_10pm_leq12hr < 140 ? $soundlimit->sun_ph_7pm_10pm_leq12hr . ' dBA' : '-' }}
                    (Leq 12 hrs)
                </td>
            </tr>
        </table>
    @endif
</div>
