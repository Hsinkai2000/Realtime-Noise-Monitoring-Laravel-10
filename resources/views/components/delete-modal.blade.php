<div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModal" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="projectDeleteLabel">Are you sure you want to delete this {{ $type }}?
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <div class="modal-body">
                <form id="deleteForm">
                    <!-- Cancel button to dismiss the modal -->
                    <button class="btn btn-primary bg-white text-primary" type="button"
                        data-bs-dismiss="modal">Cancel</button>

                    <!-- Submit button to delete the project -->
                    <button onclick="deleteUser(event)" id="deleteButton" type="button"
                        class="btn btn-primary text-white">Delete</button>
                </form>
            </div>
        </div>
    </div>
</div>
