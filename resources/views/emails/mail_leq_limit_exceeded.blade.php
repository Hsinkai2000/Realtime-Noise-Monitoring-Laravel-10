<p>Dear {{ $data['client_name'] }},</p>
<br>
<p>Project: {{ $data['jobsite_location'] }}</p>
<p>Noise Meter S/N: {{ $data['serial_number'] }}</p>
<p>Leq{{ $data['leq_type'] }}: {{ $data['leq_value'] }} dB > {{ $data['exceeded_limit'] }} dB</p>
<p>Exceeded Limit at: {{ $data['exceeded_time'] }}</p>
<br>
<p>Regards,</p>
<p>Geoscan</p>