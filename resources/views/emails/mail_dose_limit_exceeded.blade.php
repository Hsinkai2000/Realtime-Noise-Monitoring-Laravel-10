<p>
    Dear {{ $data['client_name'] }},</p>
<br>
<p>Location: {{ $data['device_location'] }}</p>
<p>Noise Meter S/N: {{ $data['serial_number'] }}</p>
<p>Dose (%): {{ $data['calculated_dose'] }}%@if ($data['calculated_dose'] < 100)
        > {{ $data['dose_limit'] }}%
    @endif
</p>
<p>Exceeded Limit at: {{ \Carbon\Carbon::parse($data['exceeded_time'])->format('Y-m-d H:i') }}</p>
<br>
<p>Regards,</p>
<p>Geoscan</p>
