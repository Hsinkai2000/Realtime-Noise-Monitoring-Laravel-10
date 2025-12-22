<p>Dear {{ $data['client_name'] }},</p>
<br>
<p>Location: {{ $data['device_location'] }}</p>
<p>Noise Meter S/N: {{ $data['serial_number'] }}</p>
<p>Leq{{ $data['leq_type'] }}: {{ $data['leq_value'] }} dB > {{ $data['exceeded_limit'] }} dB</p>
<p>Exceeded Limit at: {{ \Carbon\Carbon::parse($data['exceeded_time'])->format('Y-m-d H:i') }}</p>
<br>

<p>Regards,</p>
<p>Geoscan Pte Ltd</p>

<br>

<p>This is an automatic email, please do not reply.</p>
