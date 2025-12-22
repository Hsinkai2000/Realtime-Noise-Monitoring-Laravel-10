<div class="modal fade" id="deleteModal" tabindex="-1">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header bg-dark">
                <h3 class="modal-title text-light" id="projectDeleteLabel">Are you sure you want to remove this
                    {{ $type }}?
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                        aria-label="Close"></button>
            </div>

            <div class="modal-body">
                <form id="deleteUserForm">
                    <!-- Cancel button to dismiss the modal -->
                    <button class="btn btn-primary bg-white text-primary" type="button"
                        data-bs-dismiss="modal">Cancel</button>

                    <!-- Submit button to delete the project -->
                    <button id="deleteConfirmButton" type="button" class="btn btn-primary text-white">Remove</button>
                </form>
            </div>
        </div>
    </div>
</div>
