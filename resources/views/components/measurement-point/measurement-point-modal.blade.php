@props(['measurementPoint' => null])

<div class="modal fade shadow" id="measurementPointModal" tabindex="-1">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header bg-dark">
                <h3 class="modal-title text-light" id="measurementPointLabel">Measurement Point</h3>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                    aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id='measurement_point_form' method="POST">
                    @csrf
                    <div class="mb-3">
                        <h4>Measurement Point Information</h4>
                        <hr>
                        <div class="mb-3 row">
                            <label for="point_name" class="col-md-3 col-sm-12 text-align-center col-form-label">Point
                                Name</label>
                            <div class="col-sm-8 align-content-center">
                                <input type="text" class="form-control" id="inputPointName" name="point_name"
                                    @if ($measurementPoint) value="{{ old('point_name', $measurementPoint ? $measurementPoint->point_name : '') }}" @endif>
                            </div>
                        </div>

                        <div class="mb-3 row">
                            <label for="remarks"
                                class="col-md-3 col-sm-12 text-align-center col-form-label">Remarks</label>
                            <div class="col-sm-8 align-content-center">
                                <input type="text" class="form-control" id="inputRemarks" name="remarks"
                                    @if ($measurementPoint) value="{{ old('remarks', $measurementPoint ? $measurementPoint->remarks : '') }}" @endif>
                            </div>
                        </div>

                        <div class="mb-3 row">
                            <label for="device_location"
                                class="col-md-3 col-sm-12 text-align-center col-form-label">Device Location</label>
                            <div class="col-sm-8 align-content-center">
                                <input type="text" class="form-control" id="inputDeviceLocation"
                                    name="device_location" maxlength="40"
                                    @if ($measurementPoint) value="{{ old('device_location', $measurementPoint ? $measurementPoint->device_location : '') }}" @endif>
                            </div>
                        </div>
                        </br>
                        <h4>Link Devices</h4>
                        <hr>

                        <div class="mb-3 row" id="existing_devices" @if (!$measurementPoint) hidden @endif>
                            <div class="col-6">
                                <div class="col">
                                    Existing Device Id:
                                </div>
                                <div class="col" id="existing_device_id">
                                    @if ($measurementPoint)
                                        {{ $measurementPoint->concentrator ? $measurementPoint->concentrator->device_id . ' | ' . $measurementPoint->concentrator->concentrator_label : 'Not Linked' }}
                                    @endif
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="col">
                                    Existing Noise Meter:
                                </div>
                                <div class="col" id="existing_serial">
                                    @if ($measurementPoint)
                                        {{ $measurementPoint->noiseMeter ? $measurementPoint->noiseMeter->serial_number . ' | ' . $measurementPoint->noiseMeter->noise_meter_label : 'Not Linked' }}
                                    @endif
                                </div>
                            </div>
                        </div>

                        @if (Auth::user()->isAdmin())
                            <div class="mb-3 row">
                                <div class="col">Select Concentrator</div>
                            </div>
                            <div class="mb-2 row">
                                <div class="col" id="concentrator_list_table"></div>
                            </div>

                            <div class="mb-3 row">
                                <div class="col">Select Noise Meter</div>
                            </div>
                            <div class="mb-2 row">
                                <div class="col" id="noiseMeter_list_table"></div>
                            </div>
                            <input type="text" name="concentrator_id" id="concentratorId"
                                value="@if ($measurementPoint) {{ old('concentrator_id', $measurementPoint->concentrator ? $measurementPoint->concentrator->id : null) }} @endif"
                                hidden>
                            <input type="text" name="noise_meter_id" id="noiseMeterId"
                                value="@if ($measurementPoint) {{ old('noise_meter_id', $measurementPoint->noiseMeter ? $measurementPoint->noiseMeter->id : null) }} @endif"
                                hidden>
                        @else
                            <input name="concentrator_id"
                                value="@if ($measurementPoint) {{ $measurementPoint->concentrator ? $measurementPoint->concentrator->id : '' }} @endif"
                                hidden>
                            <input name="noise_meter_id"
                                value="@if ($measurementPoint) {{ $measurementPoint->noiseMeter ? $measurementPoint->noiseMeter->id : '' }} @endif"
                                hidden>
                        @endif

                        <input name='project_id' hidden
                            value="@if (!$measurementPoint) {{ $project['id'] }}@else {{ $measurementPoint->project_id }} @endif" />

                        </br>
                        <h4>Sound Limits</h4>
                        <hr>
                        <div id='existing_category' class="mb-3 row" @if (!$measurementPoint) hidden @endif>
                            <div class="col-md-6 col-sm-12">
                                Existing Category
                            </div>
                            <div class="col-md-6 col-sm-12" id="category">
                                @if ($measurementPoint)
                                    {{ $measurementPoint->soundLimit->category }}
                                @endif
                            </div>
                        </div>
                        <div class="mb-3 row" @if (!Auth::user()->isAdmin()) hidden @endif>
                            <div class="col-md-6 col-sm-12">
                                Category
                            </div>
                            <div class="col-md-6 col-sm-12 ">
                                <select id='selectCategory' name='category' style="width: 100%"
                                    onchange="populate_soundLimits()">
                                    <option value="Residential" @if ($measurementPoint && $measurementPoint->soundLimit->category == 'Residential') selected @endif>
                                        Residential</option>
                                    <option value="Hospital/Schools"
                                        @if ($measurementPoint && $measurementPoint->soundLimit->category == 'Hospital/Schools') selected @endif>Hospital/Schools</option>
                                    <option value="Others" @if ($measurementPoint && $measurementPoint->soundLimit->category == 'Others') selected @endif>Others
                                    </option>
                                </select>
                            </div>
                        </div>
                        </br>

                        <span onclick="toggle_soundLimits()" class="text-primary" type='button'>Advanced Sound Limit
                            Configuration > </span>
                        </br>

                        <div id="advanced_sound_limits" hidden>
                            </br>
                            <div class="row">
                                @if (Auth::user()->isAdmin())
                                    <button class="btn btn-primary bg-white text-primary" type="button"
                                        onclick="populate_soundLimits(event, true)">Reset to
                                        defaults</button>
                                @endif
                            </div>
                            </br>

                            @php
                                $isReadOnly = !Auth::user()->isAdmin() ? 'disabled' : '';
                            @endphp

                            <div class="row">
                                <div class="col-md-6 col-sm-12">
                                    <div class="form-group">
                                        <label>Mon-Sat</label>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6 col-sm-12">
                                    <div class="form-group">
                                        <label>Leq 5 Mins</label>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-3 col-sm-12">
                                    <div class="form-group">
                                        <label>7am-7pm</label>
                                        <input type="number" min="0" max="140" class="form-control"
                                            id="inputmonsat7am7pmleq5" name="mon_sat_7am_7pm_leq5min"
                                            @if ($measurementPoint) value="{{ old('mon_sat_7am_7pm_leq5min', $measurementPoint->soundLimit->mon_sat_7am_7pm_leq5min) }}" @endif
                                            {{ $isReadOnly }}>
                                    </div>
                                </div>
                                <div class="col-md-3 col-sm-12">
                                    <div class="form-group">
                                        <label>7pm-10pm</label>
                                        <input type="number" min="0" max="140" class="form-control"
                                            id="inputmonsat7pm10pmleq5" name="mon_sat_7pm_10pm_leq5min"
                                            @if ($measurementPoint) value="{{ old('mon_sat_7pm_10pm_leq5min', $measurementPoint->soundLimit->mon_sat_7pm_10pm_leq5min) }}" @endif
                                            {{ $isReadOnly }}>
                                    </div>
                                </div>
                                <div class="col-md-3 col-sm-12">
                                    <div class="form-group">
                                        <label>10pm-12am</label>
                                        <input type="number" min="0" max="140" class="form-control"
                                            id="inputmonsat10pm12amleq5" name="mon_sat_10pm_12am_leq5min"
                                            @if ($measurementPoint) value="{{ old('mon_sat_10pm_12am_leq5min', $measurementPoint->soundLimit->mon_sat_10pm_12am_leq5min) }}" @endif
                                            {{ $isReadOnly }}>
                                    </div>
                                </div>
                                <div class="col-md-3 col-sm-12">
                                    <div class="form-group">
                                        <label>12am-7am</label>
                                        <input type="number" min="0" max="140" class="form-control"
                                            id="inputmonsat12am7amleq5" name="mon_sat_12am_7am_leq5min"
                                            @if ($measurementPoint) value="{{ old('mon_sat_12am_7am_leq5min', $measurementPoint->soundLimit->mon_sat_12am_7am_leq5min) }}" @endif
                                            {{ $isReadOnly }}>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6 col-sm-12">
                                    <div class="form-group">
                                        <label>Leq 1/12 hour(s)</label>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-3 col-sm-12">
                                    <div class="form-group">
                                        <label>7am-7pm</label>
                                        <input type="number" min="0" max="140" class="form-control"
                                            id="inputmonsat7am7pmleq12" name="mon_sat_7am_7pm_leq12hr"
                                            @if ($measurementPoint) value="{{ old('mon_sat_7am_7pm_leq12hr', $measurementPoint->soundLimit->mon_sat_7am_7pm_leq12hr) }}" @endif
                                            {{ $isReadOnly }}>
                                    </div>
                                </div>
                                <div class="col-md-3 col-sm-12">
                                    <div class="form-group">
                                        <label>7pm-10pm</label>
                                        <input type="number" min="0" max="140" class="form-control"
                                            id="inputmonsat7pm10pmleq12" name="mon_sat_7pm_10pm_leq12hr"
                                            @if ($measurementPoint) value="{{ old('mon_sat_7pm_10pm_leq12hr', $measurementPoint->soundLimit->mon_sat_7pm_10pm_leq12hr) }}" @endif
                                            {{ $isReadOnly }}>
                                    </div>
                                </div>
                                <div class="col-md-3 col-sm-12">
                                    <div class="form-group">
                                        <label>10pm-12am</label>
                                        <input type="number" min="0" max="140" class="form-control"
                                            id="inputmonsat10pm12amleq12" name="mon_sat_10pm_12am_leq12hr"
                                            @if ($measurementPoint) value="{{ old('mon_sat_10pm_12am_leq12hr', $measurementPoint->soundLimit->mon_sat_10pm_12am_leq12hr) }}" @endif
                                            {{ $isReadOnly }}>
                                    </div>
                                </div>
                                <div class="col-md-3 col-sm-12">
                                    <div class="form-group">
                                        <label>12am-7am</label>
                                        <input type="number" min="0" max="140" class="form-control"
                                            id="inputmonsat12am7amleq12" name="mon_sat_12am_7am_leq12hr"
                                            @if ($measurementPoint) value="{{ old('mon_sat_12am_7am_leq12hr', $measurementPoint->soundLimit->mon_sat_12am_7am_leq12hr) }}" @endif
                                            {{ $isReadOnly }}>
                                    </div>
                                </div>
                            </div>

                            </br>

                            <div class="row">
                                <div class="col-md-6 col-sm-12">
                                    <div class="form-group">
                                        <label>Sun/PH</label>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6 col-sm-12">
                                    <div class="form-group">
                                        <label>Leq 5 Mins</label>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-3 col-sm-12">
                                    <div class="form-group">
                                        <label>7am-7pm</label>
                                        <input type="number" min="0" max="140" class="form-control"
                                            id="inputsunph7am7pmleq5" name="sun_ph_7am_7pm_leq5min"
                                            @if ($measurementPoint) value="{{ old('sun_ph_7am_7pm_leq5min', $measurementPoint->soundLimit->sun_ph_7am_7pm_leq5min) }}" @endif
                                            {{ $isReadOnly }}>
                                    </div>
                                </div>
                                <div class="col-md-3 col-sm-12">
                                    <div class="form-group">
                                        <label>7pm-10pm</label>
                                        <input type="number" min="0" max="140" class="form-control"
                                            id="inputsunph7pm10pmleq5" name="sun_ph_7pm_10pm_leq5min"
                                            @if ($measurementPoint) value="{{ old('sun_ph_7pm_10pm_leq5min', $measurementPoint->soundLimit->sun_ph_7pm_10pm_leq5min) }}" @endif
                                            {{ $isReadOnly }}>
                                    </div>
                                </div>
                                <div class="col-md-3 col-sm-12">
                                    <div class="form-group">
                                        <label>10pm-12am</label>
                                        <input type="number" min="0" max="140" class="form-control"
                                            id="inputsunph10pm12amleq5" name="sun_ph_10pm_12am_leq5min"
                                            @if ($measurementPoint) value="{{ old('sun_ph_10pm_12am_leq5min', $measurementPoint->soundLimit->sun_ph_10pm_12am_leq5min) }}" @endif
                                            {{ $isReadOnly }}>
                                    </div>
                                </div>
                                <div class="col-md-3 col-sm-12">
                                    <div class="form-group">
                                        <label>12am-7am</label>
                                        <input type="number" min="0" max="140" class="form-control"
                                            id="inputsunph12am7amleq5" name="sun_ph_12am_7am_leq5min"
                                            @if ($measurementPoint) value="{{ old('sun_ph_12am_7am_leq5min', $measurementPoint->soundLimit->sun_ph_12am_7am_leq5min) }}" @endif
                                            {{ $isReadOnly }}>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6 col-sm-12">
                                    <div class="form-group">
                                        <label>Leq 1/12 hour(s)</label>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-3 col-sm-12">
                                    <div class="form-group">
                                        <label>7am-7pm</label>
                                        <input type="number" min="0" max="140" class="form-control"
                                            id="inputsunph7am7pmleq12" name="sun_ph_7am_7pm_leq12hr"
                                            @if ($measurementPoint) value="{{ old('sun_ph_7am_7pm_leq12hr', $measurementPoint->soundLimit->sun_ph_7am_7pm_leq12hr) }}" @endif
                                            {{ $isReadOnly }}>
                                    </div>
                                </div>
                                <div class="col-md-3 col-sm-12">
                                    <div class="form-group">
                                        <label>7pm-10pm</label>
                                        <input type="number" min="0" max="140" class="form-control"
                                            id="inputsunph7pm10pmleq12" name="sun_ph_7pm_10pm_leq12hr"
                                            @if ($measurementPoint) value="{{ old('sun_ph_7pm_10pm_leq12hr', $measurementPoint->soundLimit->sun_ph_7pm_10pm_leq12hr) }}" @endif
                                            {{ $isReadOnly }}>
                                    </div>
                                </div>
                                <div class="col-md-3 col-sm-12">
                                    <div class="form-group">
                                        <label>10pm-12am</label>
                                        <input type="number" min="0" max="140" class="form-control"
                                            id="inputsunph10pm12amleq12" name="sun_ph_10pm_12am_leq12hr"
                                            @if ($measurementPoint) value="{{ old('sun_ph_10pm_12am_leq12hr', $measurementPoint->soundLimit->sun_ph_10pm_12am_leq12hr) }}" @endif
                                            {{ $isReadOnly }}>
                                    </div>
                                </div>
                                <div class="col-md-3 col-sm-12">
                                    <div class="form-group">
                                        <label>12am-7am</label>
                                        <input type="number" min="0" max="140" class="form-control"
                                            id="inputsunph12am7amleq12" name="sun_ph_12am_7am_leq12hr"
                                            @if ($measurementPoint) value="{{ old('sun_ph_12am_7am_leq12hr', $measurementPoint->soundLimit->sun_ph_12am_7am_leq12hr) }}" @endif
                                            {{ $isReadOnly }}>
                                    </div>
                                </div>
                            </div>
                        </div>

                        </br>
                        <h4>Alert Configuration</h4>
                        <hr>
                        <div class="mb-3 row" @if (!Auth::user()->isAdmin()) hidden @endif>
                            <div class="col-md-6 col-sm-12 ">
                                Alert Mode
                            </div>
                            <div class="col-md-6 col-sm-12">
                                <select id='selectAlertMode' name='alert_mode' style="width: 100%"
                                    onchange="populate_soundLimits()">
                                    <option value="0" @if ($measurementPoint && $measurementPoint->alert_mode == 0) selected @endif>None
                                    </option>
                                    <option value="1" @if ($measurementPoint && $measurementPoint->alert_mode == 1) selected @endif>Email
                                        Only</option>
                                    <option value="2"
                                        @if ($measurementPoint && $measurementPoint->alert_mode == 2) selected @elseif (!$measurementPoint) selected @endif>
                                        Both
                                        Email and SMS</option>
                                </select>
                            </div>
                        </div>
                        <div class="mb-3 row">
                            <div class="col-md-6 col-sm-12">
                                Days of Alert
                            </div>
                            <div class="col-md-6 col-sm-12">
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="checkbox" value="Sun" id="sun"
                                        name="alert_days[]"
                                        @if ($measurementPoint && in_array('Sun', explode(', ', $measurementPoint->alert_days))) checked @elseif ($measurementPoint) @else checked @endif>
                                    <label class="form-check-label" for="sun">
                                        Sun
                                    </label>
                                </div>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="checkbox" value="Mon" id="mon"
                                        name="alert_days[]"
                                        @if ($measurementPoint && in_array('Mon', explode(', ', $measurementPoint->alert_days))) checked @elseif ($measurementPoint) @else checked @endif>
                                    <label class="form-check-label" for="mon">
                                        Mon
                                    </label>
                                </div>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="checkbox" value="Tue" id="tue"
                                        name="alert_days[]"
                                        @if ($measurementPoint && in_array('Tue', explode(', ', $measurementPoint->alert_days))) checked @elseif ($measurementPoint) @else checked @endif>
                                    <label class="form-check-label" for="tue">
                                        Tue
                                    </label>
                                </div>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="checkbox" value="Wed" id="wed"
                                        name="alert_days[]"
                                        @if ($measurementPoint && in_array('Wed', explode(', ', $measurementPoint->alert_days))) checked @elseif ($measurementPoint) @else checked @endif>
                                    <label class="form-check-label" for="wed">
                                        Wed
                                    </label>
                                </div>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="checkbox" value="Thu" id="thu"
                                        name="alert_days[]"
                                        @if ($measurementPoint && in_array('Thu', explode(', ', $measurementPoint->alert_days))) checked @elseif ($measurementPoint) @else checked @endif>
                                    <label class="form-check-label" for="thu">
                                        Thu
                                    </label>
                                </div>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="checkbox" value="Fri" id="fri"
                                        name="alert_days[]"
                                        @if ($measurementPoint && in_array('Fri', explode(', ', $measurementPoint->alert_days))) checked @elseif ($measurementPoint) @else checked @endif>
                                    <label class="form-check-label" for="fri">
                                        Fri
                                    </label>
                                </div>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="checkbox" value="Sat" id="sat"
                                        name="alert_days[]"
                                        @if ($measurementPoint && in_array('Sat', explode(', ', $measurementPoint->alert_days))) checked @elseif ($measurementPoint) @else checked @endif>
                                    <label class="form-check-label" for="sat">
                                        Sat
                                    </label>
                                </div>
                            </div>
                        </div>
                        <div class="mb-3 row" @if (!Auth::user()->isAdmin()) hidden @endif>
                            <div class="col-md-6 col-sm-12 ">
                                Alert Hours
                            </div>
                            <div class="col-md-6 col-sm-12">
                                <div class="row">
                                    <div class="col">
                                        <label for="alert_start_time">Start Time (24h)</label>
                                        <input type="text" class="form-control"
                                            value="@if ($measurementPoint) {{ $measurementPoint->alert_start_time }} @else 07:00 @endif"
                                            id="alert_start_time" name="alert_start_time">
                                    </div>
                                    <div class="col">
                                        <label for="alert_end_time">End Time (24h)</label>
                                        <input type="text" class="form-control" id="alert_end_time"
                                            name="alert_end_time"
                                            value="@if ($measurementPoint) {{ $measurementPoint->alert_end_time }} @else 22:00 @endif">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                </form>
            </div>

            <div class="modal-footer">
                <div id="error_messagemp" class="text-danger me-auto"></div>
                <button type="button" class="btn btn-primary bg-white text-primary"
                    data-bs-dismiss="modal">Discard</button>
                <button type='button' onclick="handle_measurementpoint_submit()" id="mp_submit_button"
                    class="btn btn-primary text-white">Submit</button>
            </div>
        </div>
    </div>
</div>
<script>
    $("#alert_start_time").clockpicker({
        placement: 'top',
        align: 'left',
        autoclose: true,
        default: '07:00'
    });


    $("#alert_end_time").clockpicker({
        placement: 'top',
        align: 'left',
        autoclose: true,
        default: '22:00'
    });
</script>
