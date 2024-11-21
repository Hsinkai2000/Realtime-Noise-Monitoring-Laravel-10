User {{ $data['client_name'] }},
Project: {{ $data['jobsite_location'] }}
Noise Meter S/N: {{ $data['serial_number'] }}
Leq{{ $data['leq_type'] }}: {{ $data['leq_value'] }} dB > {{ $data['exceeded_limit'] }} dB
Exceeded Limit at: {{ $data['exceeded_time'] }}
