<div class="modal fade shadow" id="viewPdfModal" tabindex="-1" aria-labelledby="viewPdfModal" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header bg-dark">
                <h5 class="modal-title text-light" id="viewPdfLabel">View Pdf</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                    aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="viewPdfForm">
                    @csrf
                    <div>
                        <div class="row mb-3">
                            <label for='start_date' class="col-md-3 col-sm-12 text-align-center col-form-label">Start
                                Date</label>
                            <div class="col-sm-8 align-content-center">
                                <input type="text" class="form-control" id='start_date'
                                    placeholder="Choose Starting Date" name="start_date"
                                    value="{{ \Carbon\Carbon::now()->toDateString() }}">
                            </div>
                        </div>
                        <div class="row mb-3">
                            <label for='end_date' class="col-md-3 col-sm-12 text-align-center col-form-label">End
                                Date</label>
                            <div class="col-sm-8 align-content-center">
                                <input type="text" class="form-control" id='end_date'
                                    placeholder="Choose Ending Date" name="end_date">
                            </div>
                        </div>
                    </div>
                    <ul id="error-messages-pdf" class="mt-2 text-danger" hidden>
                        <li>End Date must be after start date</li>
                    </ul>
                </form>
            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-primary bg-white text-primary"
                    data-bs-dismiss="modal">Discard</button>
                <button type='button' onclick="openPdf()" class="btn btn-primary text-white">View</button>
            </div>
        </div>
    </div>
</div>
