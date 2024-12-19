@props(['project' => null])
<div class="modal fade shadow" id="contactModal" tabindex="-1" aria-labelledby="contactModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="contactModalLabel">Contact</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="contact_form">
                    @csrf
                    <div class="mb-3 row">
                        <label for="name" class="col-md-3 col-sm-12 text-align-center col-form-label">Name</label>
                        <div class="col-sm-8 align-content-center">
                            <input type="text" class="form-control" id="inputName" name='contact_person_name'
                                required>
                        </div>
                    </div>

                    <div class="mb-3 row">
                        <label for="designation"
                            class="col-md-3 col-sm-12 text-align-center col-form-label">Designation</label>
                        <div class="col-sm-8 align-content-center">
                            <input type="text" class="form-control" id="inputDesignation" name='designation'
                                required>
                        </div>
                    </div>

                    <div class="mb-3 row">
                        <label for="email" class="col-md-3 col-sm-12 text-align-center col-form-label">Email
                            (separate emails with ;)</label>
                        <div class="col-sm-8 align-content-center">
                            <input type="textfield" class="form-control" id="inputEmail" name='email' required>
                        </div>
                    </div>

                    <div class="mb-3 row">
                        <label for="phone_number" class="col-md-3 col-sm-12 text-align-center col-form-label">Phone
                            Number</label>
                        <div class="col-sm-8 align-content-center">
                            <input type="number" class="form-control" id="inputPhoneNumber" name='phone_number'
                                required>
                        </div>
                    </div>
                    <input hidden id="inputContactProjectID" name='project_id' value="{{ $project->id }}" />

                    <div class="modal-footer">
                        <button class="btn btn-primary bg-white text-primary" data-bs-dismiss="modal">Discard</button>
                        <button class="btn btn-primary text-white" type="button"
                            onclick="handleContactSubmit()">Submit</button>
                    </div>

                    <ul id="error-messagesjs" class="mt-2">

                    </ul>
                </form>
            </div>
        </div>
    </div>
</div>
