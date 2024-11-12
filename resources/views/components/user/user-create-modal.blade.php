<div class="modal fade shadow" id="userCreateModal" tabindex="-1" aria-labelledby="userCreateModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="userCreateModalLabel">Create User</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form method="POST" id='createUserForm' action="{{ route('user.create') }}">
                    @csrf
                    @method('POST')

                    <div class="mb-3 row">
                        <label for="bca_reference_number"
                            class="col-md-3 col-sm-12 text-align-center col-form-label">Username</label>
                        <div class="col-sm-8 align-content-center">
                            <input type="text" class="form-control" id="inputUsername" name='username' required>
                        </div>
                    </div>


                    <div class="mb-3 row">
                        <label for="bca_reference_number"
                            class="col-md-3 col-sm-12 text-align-center col-form-label">Password</label>
                        <div class="col-sm-8 align-content-center">
                            <input type="text" class="form-control" id="inputPassword" name='password' required>
                        </div>
                    </div>


                    <div class="modal-footer">
                        <button class="btn btn-primary bg-white text-primary" data-bs-dismiss="modal">Discard</button>
                        <button class="btn btn-primary text-white" type="button"
                            onclick="handle_create_dummy_user()">Create
                            User</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
