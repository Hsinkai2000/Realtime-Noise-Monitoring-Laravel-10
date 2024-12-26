<div class="modal fade shadow" id="noiseMeterModal" tabindex="-1" aria-labelledby="noiseMeterLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header bg-dark">
                <h3 class="modal-title text-light" id="noiseMeterModal">Noise Meter</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                        aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id='noise_meter_form'>
                    @csrf
                    <div>
                        <h4>Noise Meter</h4>
                        <div class="mb-3 row">
                            <label for="serial_number"
                                class="col-md-3 col-sm-12 text-align-center col-form-label">Serial
                                Number</label>
                            <div class="col-sm-8 align-content-center">
                                <input type="number" class="form-control" id="inputSerialNumber" maxlength= "4"
                                    name="serial_number" required min="0">
                            </div>
                        </div>

                        <div class="mb-3 row">
                            <label for="noise_meter_label" class="col-md-3 col-sm-12 text-align-center col-form-label">
                                Label</label>
                            <div class="col-sm-8 align-content-center">
                                <input type="text" class="form-control" id="inputLabel" name="noise_meter_label"
                                    required>
                            </div>
                        </div>

                        <div class="mb-3 row">
                            <label for="brand" class="col-md-3 col-sm-12 text-align-center col-form-label">
                                Brand</label>
                            <div class="col-sm-8 align-content-center">
                                <input type="text" class="form-control" id="inputBrand" name="brand" required>
                            </div>
                        </div>

                        <div class="mb-3 row">
                            <label for="last_calibration_date"
                                class="col-md-3 col-sm-12 text-align-center col-form-label">
                                Last Calibration Date</label>
                            <div class="col-sm-8 align-content-center">
                                <input type="date" class="form-control" id="inputLastCalibrationDate"
                                    name="last_calibration_date" required>
                            </div>
                        </div>

                        <div class="mb-3 row">
                            <label for="remarks"
                                class="col-md-3 col-sm-12 text-align-center col-form-label">Remarks</label>
                            <div class="col-sm-8 align-content-center">
                                <input type="text" class="form-control" id="inputRemarks" name="remarks" required>
                            </div>
                        </div>
                    </div>

                    <div class="modal-footer">
                        <div id="error_message" class="text-danger me-auto"></div>
                        <button type="button" class="btn btn-primary bg-white text-primary"
                            data-bs-dismiss="modal">Discard</button>
                        <button type='submit' onclick="handle_noise_meter_submit(event)"
                            class="btn btn-primary text-white">Submit</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
