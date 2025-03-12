User {{ $data['client_name'] }},
Location: {{ $data['device_location'] }}
Noise Meter S/N: {{ $data['serial_number'] }}
Dose (%): {{ $data['calculated_dose'] }}%@if ($data['calculated_dose'] < 100)
    > {{ $data['dose_limit'] }}%
@endif

Exceeded Limit at: {{ \Carbon\Carbon::parse($data['exceeded_time'])->format('Y-m-d H:i') }}
