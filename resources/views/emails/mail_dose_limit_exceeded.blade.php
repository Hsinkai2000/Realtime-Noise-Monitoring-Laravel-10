<p>Dear {{ $data['client_name'] }},</p>
<br>
<p>Project: {{ $data['jobsite_location'] }}</p>
<p>Point Name: {{ $data['measurement_point_name'] }}</p>
<p>Dose (%): {{ $data['calculated_dose'] }}% > {{ $data['dose_limit'] }}%</p>
<p>Exceeded Limit at: {{ $data['exceeded_time'] }}</p>
<br>
<p>Regards,</p>
<p>Geoscan</p>