User {{ $data['client_name'] }},
Location: {{ $data['device_location'] }}
Noise Meter S/N: {{ $data['serial_number'] }}
Leq{{ $data['leq_type'] }}: {{ $data['leq_value'] }} dB > {{ $data['exceeded_limit'] }} dB
Exceeded Limit at: {{ \Carbon\Carbon::parse($data['exceeded_time'])->format('Y-m-d H:i') }}
