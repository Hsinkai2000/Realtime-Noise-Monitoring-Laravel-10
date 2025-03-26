<p>Dear {{ $data['client_name'] }}</p>

<br>

<p>Your Noise Device: {{ $data['point_name'] }} </p>
<p>Serial Number: {{ $data['serial_number'] }}</p>
<p>Located at: {{ $data['device_location'] }}</p>
<br>
<p>Noise Dose percentage value from 7am to 7am at {{ now()->setTimezone('Asia/Singapore')->format('Y-m-d') }}
    12:00PM is
    {{ number_format($data['dose_perc'], 2) }}%.</p>
<p>Today's noise limits from 7am to 7pm are {{ $data['leq5_7am_7pm'] }}dB LAeq5min, and
    {{ $data['leq12_7am_7pm'] }}dB
    LAeq12h.</p>
<br>
@if ($data['dose_perc'] < 100)
    <p>Please be reminded to keep the Laeq 5min below {{ $data['leq5max'] }}dB until 7pm.</p>
@elseif ($data['dose_perc'] >= 100)
    <p>Please be reminded that Noise Dose percentage should be kept below 100%.</p>
@endif
<br>

<p>Noise Dose percentage is the noise exposure expressed as a percentage of the LAeq12h Noise Limit from 7am to 7pm.
    Noise Dose percentage is accumulated over time and will reach 100% when the noise exposure exceeds the
    limit.
</p>

<br>

<p>Regards,</p>
<p>Geoscan Pte Ltd</p>

<br>

<p>This is an automatic email, please do not reply.</p>
