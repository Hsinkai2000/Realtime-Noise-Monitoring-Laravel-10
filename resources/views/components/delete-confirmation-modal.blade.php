<div class="modal fade" id="deleteConfirmationModal" tabindex="-1" aria-labelledby="deleteConfirmationModal"
    aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="projectDeleteLabel">Are you sure you want to delete this {{ $type }}?
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <div class="modal-body">
                <form id="deleteForm">
                    <label class="mb-4" for="deleteConfirmation">To Delete, Please enter "DELETE"</label>
                    </br>
                    <input class="mb-4" id="inputDeleteConfirmation" type="text">
                    </br>
                    <!-- Cancel button to dismiss the modal -->
                    <button type="button" class="btn btn-primary bg-white text-primary"
                        data-bs-dismiss="modal">Cancel</button>

                    <!-- Submit button to delete the project -->
                    <button onclick="handleDelete(event)" id="deleteButton" type="button"
                        class="btn btn-primary text-white">Delete</button>
                    <p id="deleteConfirmationError" class="text-danger" hidden>Delete Confirmation
                        Failed!</p>
                </form>
            </div>
        </div>
    </div>
</div>
