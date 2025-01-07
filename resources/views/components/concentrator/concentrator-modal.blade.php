<div class="modal fade shadow" id="concentratorModal" tabindex="-1">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header bg-dark">
                <h3 class="modal-title text-light" id="concentratorLabel">Concentrator</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                        aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id='concentrator_form'>
                    @csrf
                    <div>
                        <div class="mb-3 row">
                            <label for="serial_number"
                                class="col-md-3 col-sm-12 text-align-center col-form-label">Device Id</label>
                            <div class="col-sm-8 align-content-center">
                                <input type="text" class="form-control" id="inputdevice_id" name="device_id"
                                    minlength="16" maxlength="16" required>
                            </div>
                        </div>

                        <div class="mb-3 row">
                            <label for="concentrator_label" class="col-md-3 col-sm-12 text-align-center col-form-label">
                                Label</label>
                            <div class="col-sm-8 align-content-center">
                                <input type="text" class="form-control" id="inputLabel" name="concentrator_label"
                                    required>
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
                        <button type='submit' onclick="handle_concentrator_submit(event)"
                            class="btn btn-primary text-white">Submit</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
