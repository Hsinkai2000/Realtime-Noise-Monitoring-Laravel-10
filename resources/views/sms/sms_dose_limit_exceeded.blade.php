@if ($data['calculated_dose'] < 100)
User {{ $data['client_name'] }},
Location: {{ $data['device_location'] }}
Noise Meter S/N: {{ $data['serial_number'] }}
Dose (%): {{ $data['calculated_dose'] }}% > {{ $data['dose_limit'] }}%
Exceeded Limit at: {{ \Carbon\Carbon::parse($data['exceeded_time'])->format('Y-m-d H:i') }}
@else
User {{ $data['client_name'] }},
Location: {{ $data['device_location'] }}
Noise Meter S/N: {{ $data['serial_number'] }}
Dose (%): {{ $data['calculated_dose'] }}%
Exceeded Limit at: {{ \Carbon\Carbon::parse($data['exceeded_time'])->format('Y-m-d H:i') }}
@endif