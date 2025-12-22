@props(['project' => null])
<div class="modal fade deleteConfirmationModal" id="deleteConfirmationModal" tabindex="-1" data-type="{{ $type }}">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header bg-dark">
                <h3 class="modal-title text-light" id="projectDeleteLabel">Are you sure you want to delete&nbsp;
                </h3>
                <h3 class="modal-title text-light" id="deleteType">
                    {{ $type }}
                </h3>
                <h3 class="modal-title text-light">?</h3>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                    aria-label="Close"></button>
            </div>

            <div class="modal-body">
                <form id="deleteConfirmationForm" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('DELETE')
                    <label class="mb-4" for="deleteConfirmation">To Delete, Please enter "DELETE"</label>
                    </br>
                    <input class="mb-4" name="confirmation" id="inputDeleteConfirmation" type="text"
                        value="">
                    </br>

                    <!-- Cancel button to dismiss the modal -->
                    <button type="button" class="btn btn-primary bg-white text-primary"
                        data-bs-dismiss="modal">Cancel</button>

                    <!-- Submit button to delete the project -->
                    <button id="deleteButton" onclick="handleDelete(event)" type="button"
                        class="btn btn-primary text-white">Delete</button>

                    <ul id="error-messages-delete" class="mt-2 text-danger" hidden>
                        <li>Confirmation code is invalid</li>
                    </ul>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
    var deleteConfirmationModal = document.getElementById('deleteConfirmationModal');
    deleteConfirmationModal.addEventListener('hidden.bs.modal', function(event) {
        var form = document.getElementById('deleteConfirmationForm');
        form.reset();
        var errorMessages = document.getElementById("error-messages-delete");
        errorMessages.hidden = true;
    });
</script>
