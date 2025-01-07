<div class="modal fade" id="confirmationModal" tabindex="-1">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="projectDeleteLabel">The device(s): <span id="devicesSpan"></span> is used in
                    another
                    measurement
                    point. Are
                    you sure you want to continue?
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <div class="modal-body">
                <form id="deleteForm">
                    <label class="mb-4" for="deleteConfirmation">To Continue, Please enter "YES"</label>
                    </br>
                    <input class="mb-4" id="inputContinueConfirmation" type="text">
                    </br>
                    <!-- Cancel button to dismiss the modal -->
                    <button type="button" class="btn btn-primary bg-white text-primary"
                        data-bs-dismiss="modal">Discard</button>

                    <!-- Submit button to delete the project -->
                    <button onclick="handleConfirmationSubmit(event)" id="confirmSubmitButton" type="button"
                        class="btn btn-primary text-white">Continue</button>
                    <p id="confirmationError" class="text-danger" hidden>Confirmation Failed!</p>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
    var confirmationModal = document.getElementById('confirmationModal');
    confirmationModal.addEventListener('hidden.bs.modal', function(event) {
        console.log('hello')
        var form = document.getElementById('deleteForm');
        form.reset();
        var errorMessages = document.getElementById("confirmationError");
        errorMessages.hidden = true;
    });
</script>
