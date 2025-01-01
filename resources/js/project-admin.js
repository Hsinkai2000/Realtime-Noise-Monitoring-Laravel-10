var baseUri = `${window.location.protocol}//${window.location.hostname}`;
if (window.location.port) {
    baseUri += `:${window.location.port}`;
}
var tabledata = null;
var userList = [];
var inputprojectId = null;
var modalType = "";
var inputUserId = null;
var tab = "rental";

function settable(tabledata, project_type) {
    document.getElementById("table_pages").innerHTML = "";

    // If a table already exists, destroy it
    if (window.table) {
        window.table.destroy();
    }
    if (project_type == "rental") {
        var table = new Tabulator("#example-table", {
            pagination: "local",
            layout: "fitColumns",
            data: tabledata,
            placeholder: "Not authorised",
            paginationSize: 20,
            paginationCounter: "rows",
            paginationElement: document.getElementById("table_pages"),
            selectable: 1,
            columns: [
                {
                    formatter: "rowSelection",
                    titleFormatter: "rowSelection",
                    hozAlign: "center",
                    headerSort: false,
                    frozen: true,
                    width: 30,
                },
                {
                    title: "PJO Number",
                    field: "job_number",
                    headerFilter: "input",
                    minWidth: 100,
                    frozen: true,
                },
                {
                    title: "Client Name",
                    field: "client_name",
                    minWidth: 100,
                    headerFilter: "input",
                },
                {
                    title: "Jobsite Location",
                    field: "jobsite_location",
                    minWidth: 100,
                    headerFilter: "input",
                },
                {
                    title: "Project Description",
                    field: "project_description",
                    minWidth: window.innerWidth * 0.25,
                    headerSort: false,
                },
                {
                    title: "BCA Reference Number",
                    field: "bca_reference_number",
                    minWidth: 100,
                    headerSort: false,
                },
                {
                    title: "SMS Contacts (Number of alerts)",
                    field: "sms_count",
                    minWidth: 100,
                    headerSort: false,
                },
                // {
                //     title: "Status (Ongoing/Completed)",
                //     field: "status",
                //     editor: "list",
                //     editorParams: {
                //         values: ["", "Ongoing", "Completed"],
                //         clearable: true,
                //     },
                //     minWidth: 100,
                //     headerFilter: true,
                //     headerFilterParams: {
                //         values: {
                //             "": "select...",
                //             Ongoing: "Ongoing",
                //             Completed: "Completed",
                //         },
                //         clearable: true,
                //     },
                // },
                {
                    title: "Created At",
                    field: "created_at",
                    minWidth: 100,
                },
            ],
        });
    } else {
        var table = new Tabulator("#example-table", {
            pagination: "local",
            data: tabledata,
            layout: "fitColumns",
            placeholder: "Not authorised",
            paginationElement: document.getElementById("table_pages"),
            paginationSize: 20,
            paginationCounter: "rows",
            dataTree: true,
            dataTreeStartExpanded: true,
            selectable: 1,
            columns: [
                {
                    formatter: "rowSelection",
                    titleFormatter: "rowSelection",
                    hozAlign: "center",
                    headerSort: false,
                    frozen: true,
                    width: 30,
                },
                {
                    title: "Name",
                    field: "name",
                    headerFilter: "input",
                    minWidth: 100,
                    frozen: true,
                    responsive: 0,
                },
                {
                    title: "Jobsite Location",
                    field: "jobsite_location",
                    minWidth: 100,
                    headerFilter: "input",
                },
                {
                    title: "Project Description",
                    field: "project_description",
                    minWidth: window.innerWidth * 0.3,
                    headerSort: false,
                },
                {
                    title: "BCA Reference Number",
                    field: "bca_reference_number",
                    headerSort: false,
                    minWidth: 100,
                },
                {
                    title: "Created At",
                    field: "created_at",
                    minWidth: 100,
                },
            ],
        });
    }
    table.on("rowClick", function (e, row) {
        window.location.href = "/project/" + row.getIndex();
    });
    table.on("rowSelectionChanged", function (data, rows) {
        table_row_changed(data);
    });
    window.table = table;
}

function table_row_changed(data) {
    const editButton = document.getElementById("editButton");
    const deleteButton = document.getElementById("deleteButton");

    if (data && data.length > 0) {
        editButton.disabled = false;
        deleteButton.disabled = false;
        inputprojectId = data[0].id;
    } else {
        editButton.disabled = true;
        deleteButton.disabled = true;
    }
}

function changeTab(event, project_type) {
    // Toggle active class on tabs
    document.querySelectorAll(".nav-link").forEach((tab) => {
        tab.classList.remove("active");
    });

    event.currentTarget.classList.add("active");

    fetch_data(project_type);
}

function fetch_data(project_type) {
    const csrfToken = document
        .querySelector('meta[name="csrf-token"]')
        .getAttribute("content");

    fetch(`${baseUri}/projects`, {
        method: "POST",
        headers: {
            "Content-Type": "application/json; charset=UTF-8",
            "X-CSRF-TOKEN": csrfToken,
        },
        body: JSON.stringify({ project_type }),
    })
        .then((response) => {
            if (!response.ok) {
                throw new Error("User not Authorised");
            }
            return response.json();
        })
        .then((json) => {
            const tabledata = json.projects || [];
            settable(tabledata, project_type);
        })
        .catch((error) => {
            console.error(error);
            settable([], project_type);
        });
}

function create_users(projectId, csrfToken) {
    userList.forEach((user) => {
        user.project_id = projectId;
        fetch(`${baseUri}/user/`, {
            method: "POST",
            headers: {
                "X-CSRF-TOKEN": csrfToken,
                Accept: "application/json",
                "X-Requested-With": "XMLHttpRequest",
            },
            body: JSON.stringify(user),
        }).then((response) => {
            return response.json();
        });
    });
    return true;
}

function handleCreate() {
    const form = document.getElementById("projectForm");
    const csrfToken = document.querySelector('input[name="_token"]').value;
    const formData = new FormData(form);

    fetch(`${baseUri}/project`, {
        method: form.method,
        headers: {
            "X-CSRF-TOKEN": csrfToken,
            Accept: "application/json",
            "X-Requested-With": "XMLHttpRequest",
        },
        body: formData,
    })
        .then((response) => {
            if (response.status == 422) {
                response.json().then((errorData) => {
                    document.getElementById("error_message").innerHTML =
                        errorData["Unprocessable Entity"];
                });
            }
            return response.json();
        })
        .then((json) => {
            create_users(json.project_id, csrfToken);
            fetch_data(tab);
            closeModal("projectModal");
        });
}

function toggleEndUserName() {
    var rentalRadio = document.getElementById("projectTypeRental");
    var endUserNameDiv = document.getElementById("endUserNameDiv");
    if (rentalRadio.checked) {
        endUserNameDiv.style.display = "none";
    } else {
        endUserNameDiv.style.display = "flex";
    }
}

function deleteUser(event) {
    if (event) {
        event.preventDefault();
    }
    var csrfToken = document
        .querySelector('meta[name="csrf-token"]')
        .getAttribute("content");

    fetch(`${baseUri}/users/${inputUserId}`, {
        method: "DELETE",
        headers: {
            "X-CSRF-TOKEN": csrfToken,
            Accept: "application/json",
            "X-Requested-With": "XMLHttpRequest",
        },
    })
        .then((response) => {
            if (!response.ok) {
                throw new Error("Network response was not ok");
            }
            return response.json();
        })
        .then((data) => {
            populateUser("userselectList", inputprojectId);
            // Close the modal
            closeModal("deleteModal");
        })
        .catch((error) => {
            console.error("Error:", error);
        });
}

function handleDelete(event) {
    if (event) {
        event.preventDefault();
    }
    var csrfToken = document
        .querySelector('meta[name="csrf-token"]')
        .getAttribute("content");
    var confirmation = document.getElementById("inputDeleteConfirmation").value;

    if (confirmation == "DELETE") {
        fetch(`${baseUri}/project/${inputprojectId}`, {
            method: "DELETE",
            headers: {
                "X-CSRF-TOKEN": csrfToken,
                Accept: "application/json",
                "X-Requested-With": "XMLHttpRequest",
            },
        })
            .then((response) => {
                if (!response.ok) {
                    throw new Error("Network response was not ok");
                }
                return response.json();
            })
            .then((data) => {
                closeModal("deleteConfirmationModal");
            })
            .catch((error) => {
                console.error("Error:", error);
            });
    } else {
        var error = document.getElementById("deleteConfirmationError");
        error.hidden = false;
    }
}

function fetch_project_data(data) {
    var updatejobNumber = document.getElementById("inputJobNumber");
    var clientName = document.getElementById("inputClientName");
    var projectDescription = document.getElementById("inputProjectDescription");
    var jobsiteLocation = document.getElementById("inputJobsiteLocation");
    var bcaReferenceNumber = document.getElementById("inputBcaReferenceNumber");
    var sms_count = document.getElementById("inputSmsCount");
    var projectTypeRental = document.getElementById("projectTypeRental");
    var projectTypeSales = document.getElementById("projectTypeSales");
    var endUserName = document.getElementById("inputEndUserName");
    var endUserNameDiv = document.getElementById("endUserNameDiv");

    if (data != null) {
        updatejobNumber.value = data.job_number;
        clientName.value = data.client_name;
        projectDescription.value = data.project_description;
        jobsiteLocation.value = data.jobsite_location;
        bcaReferenceNumber.value = data.bca_reference_number;
        sms_count.value = data.sms_count;
        if (data.end_user_name) {
            projectTypeSales.checked = true;
            endUserNameDiv.style.display = "block"; // Show the end user name field
            endUserName.value = data.end_user_name;
        } else {
            projectTypeRental.checked = true;
            endUserNameDiv.style.display = "none"; // Hide the end user name field
        }
        populateUser("userselectList", data.id);
    } else {
        updatejobNumber.value = null;
        clientName.value = null;
        projectDescription.value = null;
        jobsiteLocation.value = null;
        bcaReferenceNumber.value = null;
        sms_count.value = 0;
        populateUser("userselectList");
    }
}

function handleUpdate() {
    var csrfToken = document
        .querySelector('meta[name="csrf-token"]')
        .getAttribute("content");
    var form = document.getElementById("projectForm");

    var formData = new FormData(form);

    var formDataJson = {};
    formData.forEach((value, key) => {
        formDataJson[key] = value;
    });

    fetch(`${baseUri}/project/${inputprojectId}`, {
        method: "PATCH",
        headers: {
            "X-CSRF-TOKEN": csrfToken,
            "Content-Type": "application/json",
            Accept: "application/json",
            "X-Requested-With": "XMLHttpRequest",
        },
        body: JSON.stringify(formDataJson),
    })
        .then((response) => {
            if (!response.ok) {
                throw new Error(
                    "Network response was not ok " + response.statusText
                );
            }
            return response.json();
        })
        .then((json) => {
            create_users(inputprojectId, csrfToken);
            fetch_data(tab);
            closeModal("projectModal");
        })
        .catch((error) => {
            console.error("Error:", error);
            alert("There was an error: " + error.message);
        });

    return false;
}

function submitClicked() {
    if (modalType == "update") {
        handleUpdate();
    } else {
        handleCreate();
    }
}

function handle_create_dummy_user() {
    var inputUsername = document.getElementById("inputUsername").value;
    var inputPassword = document.getElementById("inputPassword").value;
    userList.push({
        username: inputUsername,
        password: inputPassword,
    });
    if (modalType == "update") {
        populateUser("userselectList", inputprojectId);
    } else {
        populateUser("userselectList");
    }

    closeModal("userCreateModal");
}

function populateUser(element, project_id = null) {
    window.userselectList = document.getElementById(element);
    if (project_id) {
        fetch(`${baseUri}/users/${project_id}`)
            .then((response) => {
                if (!response.ok) {
                    throw new Error("Network response was not ok");
                }
                return response.json();
            })
            .then((data) => {
                populateList(data.users);
            })
            .catch((error) => {
                console.error("Error fetching data:", error);
            });
    } else {
        populateList([]);
    }
}

function populateList(data) {
    window.userselectList.innerHTML = "";

    let selectedListItem = null;
    if (userList != []) {
        userList.forEach((user) => {
            data.push(user);
        });
    }

    data.forEach((item) => {
        const listItem = document.createElement("div");
        listItem.textContent = item.username;
        listItem.className = "list-item";

        listItem.addEventListener("click", function () {
            handleSelection(item);

            if (selectedListItem) {
                selectedListItem.classList.remove("selected");
            }

            listItem.classList.add("selected");

            selectedListItem = listItem;
        });

        window.userselectList.appendChild(listItem);
    });
}

function handleSelection(item) {
    inputUserId = item.id;
}

function openModal(modalName, type) {
    if (modalName == "deleteConfirmationModal") {
        document.getElementById("deleteConfirmationError").hidden = true;
    }
    var modal = new bootstrap.Modal(document.getElementById(modalName));
    modal.toggle();
    document.getElementById("error_message").innerHTML = "";
    document.getElementById("inputEndUserName").value = "";
    document.getElementById("projectTypeRental").checked = true;
    toggleEndUserName();

    if (type == "update") {
        userList = [];
        modalType = "update";
        fetch_project_data(window.projects[inputprojectId - 1]);
    } else if (type == "create") {
        userList = [];
        modalType = "create";
        fetch_project_data(null);
    }
}

function openSecondModal(initialModal, newModal) {
    var firstModalEl = document.getElementById(initialModal);
    var firstModal = bootstrap.Modal.getInstance(firstModalEl);

    firstModal.hide();

    firstModalEl.addEventListener(
        "hidden.bs.modal",
        function () {
            var secondModal = new bootstrap.Modal(
                document.getElementById(newModal)
            );

            if (newModal == "userCreateModal") {
                document.getElementById("inputUsername").value = "";
                document.getElementById("inputPassword").value = "";
            }
            secondModal.show();

            document.getElementById(newModal).addEventListener(
                "hidden.bs.modal",
                function () {
                    firstModal.show();
                },
                { once: true }
            );
        },
        { once: true }
    );
}

function closeModal(modal) {
    // Close the modal
    const modalElement = document.getElementById(modal);
    const modalInstance = bootstrap.Modal.getInstance(modalElement);
    modalInstance.hide();
}

window.deleteUser = deleteUser;
window.openModal = openModal;
window.openSecondModal = openSecondModal;
window.populateUser = populateUser;
window.handleDelete = handleDelete;
window.handle_create_dummy_user = handle_create_dummy_user;
window.changeTab = changeTab;
window.fetch_data = fetch_data;
window.toggleEndUserName = toggleEndUserName;
window.submitClicked = submitClicked;

fetch_data("rental");
toggleEndUserName();
