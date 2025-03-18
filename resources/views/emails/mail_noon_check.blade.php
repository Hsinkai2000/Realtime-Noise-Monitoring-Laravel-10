<p>Dear {{ $data['client_name'] }},</p>
<br>
<p>Your Noise Device Located at: {{ $data['device_location'] }}</p>
<p>Noise Meter Serial Number: {{ $data['serial_number'] }}</p>
<p>Dose daily value this afternoon at {{ now()->setTimezone('Asia/Singapore')->format('Y-m-d') }} 12:00PM is
    {{ number_format($data['dose_perc'], 2) }}%. Please be reminded that dose value
    should be
    kept below 100%.</p>
<br>
<p>Regards,</p>
<p>Geoscan</p>
