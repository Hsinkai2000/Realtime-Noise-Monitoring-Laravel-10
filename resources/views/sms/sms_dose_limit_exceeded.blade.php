User {{ $data['client_name'] }},
Project: {{ $data['jobsite_location'] }}
Point Name: {{ $data['measurement_point_name'] }}
Dose (%): {{ $data['calculated_dose'] }}% > {{ $data['dose_limit'] }}%
Exceeded Limit at: {{ $data['exceeded_time'] }}