<div class="modal fade shadow" id="projectModal" tabindex="-1" aria-labelledby="projectcreateLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="projectcreateLabel">Create Project</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id='projectForm' method="POST">
                    @csrf
                    <div>
                        <div class="mb-3 row">
                            <label for="job_number" class="col-md-3 col-sm-12 text-align-center col-form-label">Job
                                Number</label>
                            <div class="col-sm-8 align-content-center">
                                <input type="text" class="form-control" id="inputJobNumber" name="job_number">
                            </div>
                        </div>

                        <div class="mb-3 row">
                            <label for="client_name" class="col-md-3 col-sm-12 text-align-center col-form-label">Client
                                name
                            </label>
                            <div class="col-sm-8 align-content-center">
                                <input type="text" class="form-control" id="inputClientName" name="client_name">
                            </div>
                        </div>

                        <div class="mb-3 row">
                            <label for="project_description"
                                class="col-md-3 col-sm-12 text-align-center col-form-label">Project
                                Description</label>
                            <div class="col-sm-8 align-content-center">
                                <textarea name='project_description' type="text" class="form-control" id="inputProjectDescription"></textarea>
                            </div>
                        </div>


                        <div class="mb-3 row">
                            <label for="jobsite_location"
                                class="col-md-3 col-sm-12 text-align-center col-form-label">Jobsite
                                Location</label>
                            <div class="col-sm-8 align-content-center">
                                <input type="text" class="form-control" id="inputJobsiteLocation"
                                    name='jobsite_location'>
                            </div>
                        </div>

                        <div class="mb-3 row">
                            <label for="bca_reference_number"
                                class="col-md-3 col-sm-12 text-align-center col-form-label">BCA
                                Reference
                                Number</label>
                            <div class="col-sm-8 align-content-center">
                                <input type="text" class="form-control" id="inputBcaReferenceNumber"
                                    name='bca_reference_number'>
                            </div>
                        </div>

                        <div class="mb-3 row">
                            <label for="sms_count" class="col-md-3 col-sm-12 text-align-center col-form-label">No. of
                                Contacts</label>
                            <div class="col-sm-8 align-content-center">
                                <input type="number" class="form-control" id="inputSmsCount" name='sms_count' value=0
                                    max="20">
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="project_type"
                                class="col-md-3 col-sm-12 text-align-center col-form-label">Project
                                Type</label>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="project_type"
                                    id="projectTypeRental" value="rental" onchange="toggleEndUserName()" checked>
                                <label class="form-check-label" for="project_type">Rental</label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="project_type" id="projectTypeSales"
                                    value="sales" onchange="toggleEndUserName()">
                                <label class="form-check-label" for="project_type">Sales</label>
                            </div>
                        </div>
                        <div class="mb-3 row" id="endUserNameDiv">
                            <label for="end_user_name" class="col-md-3 col-sm-12 text-align-center col-form-label">End
                                User Name</label>
                            <div class="col-sm-8 align-content-center">
                                <input type="text" class="form-control" id="inputEndUserName" name='end_user_name'
                                    value="">
                            </div>
                        </div>

                        <div class="mb-3 row">
                            <label for="user_id"
                                class="col-md-3 col-sm-12 text-align-center col-form-label">User</label>
                            <div class="col-sm-8 align-content-center">

                                <button class="btn btn-primary text-white" type="button"
                                    onclick="openSecondModal('projectModal','userCreateModal','create')">Add
                                    User</button>
                                <button class="btn btn-primary bg-white text-primary" type="button"
                                    onclick="openSecondModal('projectModal','deleteModal')">Remove User</button>

                                <ul id="userselectList">
                                </ul>
                            </div>
                        </div>
                    </div>


                    <div class="modal-footer">
                        <div id="error_message" class="text-danger me-auto"></div>
                        <button type="button" class="btn btn-primary bg-white text-primary"
                            data-bs-dismiss="modal">Discard</button>
                        <button type='button' onclick="submitClicked()"
                            class="btn btn-primary text-white">Submit</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
