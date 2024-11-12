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
    <table class="table-bordered w-100">
        <tr>
            <th>Device ID</th>
            <th>Serial No.</th>
            <th>Brand</th>
            <th>Last Calibration Date</th>
            <th>Remarks</th>
            <th>Category</th>
            <th>Device Location</th>
        </tr>

        <tr>
            <td>{{ $measurementPoint->concentrator->device_id }}</td>
            <td>{{ $measurementPoint->noiseMeter->serial_number }}</td>
            <td>{{ $measurementPoint->noiseMeter->brand }}</td>
            <td>{{ $measurementPoint->noiseMeter->last_calibration_date }}</td>
            <td>{{ $measurementPoint->remarks }}</td>
            <td>{{ $measurementPoint->soundLimit->category }}</td>
            <td>{{ $measurementPoint->device_location }}</td>
        </tr>
    </table>

    <hr>
    <br />
    <h3>Sound Limits:</h3>
    <table class="table-bordered w-100">
        <tr>
            <th colspan="2">{{ $soundlimit->category }}</th>
            <th>7am-7pm</th>
            <th>7pm-10pm</th>
            <th>10pm-12am</th>
            <th>12am-7am</th>
        </tr>
        <tr>
            <th rowspan="2"">Mon-Sat</th>
            <th>Leq 5 mins</th>
            <td>{{ $soundlimit->mon_sat_7am_7pm_leq5min }} dB</td>
            <td>{{ $soundlimit->mon_sat_7pm_10pm_leq5min }} dB</td>
            <td>{{ $soundlimit->mon_sat_10pm_12am_leq5min }} dB</td>
            <td>{{ $soundlimit->mon_sat_12am_7am_leq5min }} dB</td>
        </tr>
        <tr>
            <th>Leq 1/12 hour(s)</th>
            <td>{{ $soundlimit->mon_sat_7am_7pm_leq12hr }} dB</td>
            <td>{{ $soundlimit->mon_sat_7pm_10pm_leq12hr }} dB</td>
            <td>{{ $soundlimit->mon_sat_10pm_12am_leq12hr }} dB</td>
            <td>{{ $soundlimit->mon_sat_12am_7am_leq12hr }} dB</td>
        </tr>
        <tr>
            <th rowspan="2"">Sun/Ph</th>
            <th>Leq 5 mins</th>
            <td>{{ $soundlimit->sun_ph_7am_7pm_leq5min }} dB</td>
            <td>{{ $soundlimit->sun_ph_7pm_10pm_leq5min }} dB</td>
            <td>{{ $soundlimit->sun_ph_10pm_12am_leq5min }} dB</td>
            <td>{{ $soundlimit->sun_ph_12am_7am_leq5min }} dB</td>
        </tr>
        <tr>
            <th>Leq 1/12 hour(s)</th>
            <td>{{ $soundlimit->sun_ph_7am_7pm_leq12hr }} dB</td>
            <td>{{ $soundlimit->sun_ph_7pm_10pm_leq12hr }} dB</td>
            <td>{{ $soundlimit->sun_ph_10pm_12am_leq12hr }} dB</td>
            <td>{{ $soundlimit->sun_ph_12am_7am_leq12hr }} dB</td>
        </tr>
    </table>
</div>
