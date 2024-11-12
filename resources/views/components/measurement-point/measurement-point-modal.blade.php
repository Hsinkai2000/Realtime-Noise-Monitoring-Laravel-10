<div class="modal fade shadow" id="measurementPointModal" tabindex="-1" aria-labelledby="measurementPointLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="measurementPointLabel">Measurement Point</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id='measurement_point_form' method="POST">
                    @csrf
                    <div>
                        <h4>Measurement Point</h4>
                        <div class="mb-3 row">
                            <label for="point_name"
                                class="col-md-3 col-sm-12 text-align-center col-form-label">Measurement Point
                                Name</label>
                            <div class="col-sm-8 align-content-center">
                                <input type="text" class="form-control" id="inputPointName" name="point_name">
                            </div>
                        </div>

                        <div class="mb-3 row">
                            <label for="remarks"
                                class="col-md-3 col-sm-12 text-align-center col-form-label">Remarks</label>
                            <div class="col-sm-8 align-content-center">
                                <input type="text" class="form-control" id="inputRemarks" name="remarks">
                            </div>
                        </div>

                        <div class="mb-3 row">
                            <label for="device_location"
                                class="col-md-3 col-sm-12 text-align-center col-form-label">Device Location</label>
                            <div class="col-sm-8 align-content-center">
                                <input type="text" class="form-control" id="inputDeviceLocation"
                                    name="device_location">
                            </div>
                        </div>
                        </br>
                        <hr>
                        </br>
                        <h4>Link Devices</h4>
                        <div class="mb-3 row">
                            <div class="col-6">
                                <div class="col">
                                    Concentrator
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="col">
                                    Noise Meter
                                </div>
                            </div>
                        </div>

                        <div class="mb-3 row" id="existing_devices" hidden>
                            <div class="col-6">
                                <div class="col">
                                    Existing Device Id:
                                </div>
                                <div class="col">
                                    <span id="existing_device_id"></span>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="col">
                                    Existing Noise Meter
                                </div>
                                <div class="col">
                                    <span id="existing_serial"></span>
                                </div>
                            </div>
                        </div>

                        @if (Auth::user()->isAdmin())
                            <div class="mb-3 row">
                                <div class="col-6">
                                    <div class="col">
                                        New Device Id:
                                    </div>
                                    <div class="col">
                                        <select id="selectConcentrator" name="concentrator_id" style="width: 80%">
                                        </select>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="col">
                                        New Noise Meter
                                    </div>
                                    <div class="col">
                                        <select id="selectNoiseMeter" name="noise_meter_id" style="width: 80%">
                                        </select>
                                    </div>
                                </div>
                            </div>
                        @endif

                        <input hidden name='project_id' value="{{ $project['id'] }}" />


                        </br>
                        <hr>
                        </br>
                        <h4>Sound Limits</h4>
                        <div id='existing_category' class="mb-3 row" hidden>
                            <div class="col-md-6 col-sm-12">
                                Existing Category
                            </div>
                            <div class="col-md-6 col-sm-12">
                                <span id="category"></span>
                            </div>
                        </div>
                        <div class="mb-3 row">
                            <div class="col-md-6 col-sm-12">
                                Category
                            </div>
                            <div class="col-md-6 col-sm-12 ">
                                <select id='selectCategory' name='category' style="width: 100%"
                                    onchange="populate_soundLimits()">
                                    <option value="Residential" selected>Residential</option>
                                    <option value="Hospital/Schools">Hospital/Schools</option>
                                    <option value="Others">Others</option>
                                </select>
                            </div>
                        </div>
                        </br>

                        <span onclick="toggle_soundLimits()" type='button'>Advanced Sound Limit Configuration > </span>
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
                                        <input type="text" class="form-control" id="inputmonsat7am7pmleq5"
                                            name="mon_sat_7am_7pm_leq5min" {{ $isReadOnly }}>
                                    </div>
                                </div>
                                <div class="col-md-3 col-sm-12">
                                    <div class="form-group">
                                        <label>7pm-10pm</label>
                                        <input type="text" class="form-control" id="inputmonsat7pm10pmleq5"
                                            name="mon_sat_7pm_10pm_leq5min" {{ $isReadOnly }}>
                                    </div>
                                </div>
                                <div class="col-md-3 col-sm-12">
                                    <div class="form-group">
                                        <label>10pm-12am</label>
                                        <input type="text" class="form-control" id="inputmonsat10pm12amleq5"
                                            name="mon_sat_10pm_12am_leq5min" {{ $isReadOnly }}>
                                    </div>
                                </div>
                                <div class="col-md-3 col-sm-12">
                                    <div class="form-group">
                                        <label>12am-7am</label>
                                        <input type="text" class="form-control" id="inputmonsat12am7amleq5"
                                            name="mon_sat_12am_7am_leq5min" {{ $isReadOnly }}>
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
                                        <input type="text" class="form-control" id="inputmonsat7am7pmleq12"
                                            name="mon_sat_7am_7pm_leq12hr" {{ $isReadOnly }}>
                                    </div>
                                </div>
                                <div class="col-md-3 col-sm-12">
                                    <div class="form-group">
                                        <label>7pm-10pm</label>
                                        <input type="text" class="form-control" id="inputmonsat7pm10pmleq12"
                                            name="mon_sat_7pm_10pm_leq12hr" {{ $isReadOnly }}>
                                    </div>
                                </div>
                                <div class="col-md-3 col-sm-12">
                                    <div class="form-group">
                                        <label>10pm-12am</label>
                                        <input type="text" class="form-control" id="inputmonsat10pm12amleq12"
                                            name="mon_sat_10pm_12am_leq12hr" {{ $isReadOnly }}>
                                    </div>
                                </div>
                                <div class="col-md-3 col-sm-12">
                                    <div class="form-group">
                                        <label>12am-7am</label>
                                        <input type="text" class="form-control" id="inputmonsat12am7amleq12"
                                            name="mon_sat_12am_7am_leq12hr" {{ $isReadOnly }}>
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
                                        <input type="text" class="form-control" id="inputsunph7am7pmleq5"
                                            name="sun_ph_7am_7pm_leq5min" {{ $isReadOnly }}>
                                    </div>
                                </div>
                                <div class="col-md-3 col-sm-12">
                                    <div class="form-group">
                                        <label>7pm-10pm</label>
                                        <input type="text" class="form-control" id="inputsunph7pm10pmleq5"
                                            name="sun_ph_7pm_10pm_leq5min" {{ $isReadOnly }}>
                                    </div>
                                </div>
                                <div class="col-md-3 col-sm-12">
                                    <div class="form-group">
                                        <label>10pm-12am</label>
                                        <input type="text" class="form-control" id="inputsunph10pm12amleq5"
                                            name="sun_ph_10pm_12am_leq5min" {{ $isReadOnly }}>
                                    </div>
                                </div>
                                <div class="col-md-3 col-sm-12">
                                    <div class="form-group">
                                        <label>12am-7am</label>
                                        <input type="text" class="form-control" id="inputsunph12am7amleq5"
                                            name="sun_ph_12am_7am_leq5min" {{ $isReadOnly }}>
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
                                        <input type="text" class="form-control" id="inputsunph7am7pmleq12"
                                            name="sun_ph_7am_7pm_leq12hr" {{ $isReadOnly }}>
                                    </div>
                                </div>
                                <div class="col-md-3 col-sm-12">
                                    <div class="form-group">
                                        <label>7pm-10pm</label>
                                        <input type="text" class="form-control" id="inputsunph7pm10pmleq12"
                                            name="sun_ph_7pm_10pm_leq12hr" {{ $isReadOnly }}>
                                    </div>
                                </div>
                                <div class="col-md-3 col-sm-12">
                                    <div class="form-group">
                                        <label>10pm-12am</label>
                                        <input type="text" class="form-control" id="inputsunph10pm12amleq12"
                                            name="sun_ph_10pm_12am_leq12hr" {{ $isReadOnly }}>
                                    </div>
                                </div>
                                <div class="col-md-3 col-sm-12">
                                    <div class="form-group">
                                        <label>12am-7am</label>
                                        <input type="text" class="form-control" id="inputsunph12am7amleq12"
                                            name="sun_ph_12am_7am_leq12hr" {{ $isReadOnly }}>
                                    </div>
                                </div>
                            </div>
                        </div>

                        </br>


                        <div class="modal-footer">
                            <div id="error_message" class="text-danger me-auto"></div>
                            <button type="button" class="btn btn-primary bg-white text-primary"
                                data-bs-dismiss="modal">Discard</button>
                            <button type='button' onclick="handle_measurementpoint_submit()"
                                class="btn btn-primary text-white">Submit</button>
                        </div>
                </form>
            </div>
        </div>
    </div>
</div>
