const curruserList = document.getElementById("curruserList");
const addUserBtn = document.getElementById("addUserBtn");
const usernameField = document.getElementById("username");
const passwordField = document.getElementById("password");
var projectModal = document.getElementById("projectModal");
var userList = [];
var baseUri = `${window.location.protocol}//${window.location.hostname}`;
if (window.location.port) {
    baseUri += `:${window.location.port}`;
}
var tab = "rental";
isSwitchingModal = false;

projectModal.addEventListener("hidden.bs.modal", function (event) {
    if (!isSwitchingModal) {
        userList = [];
        curruserList.innerHTML = "";
        var form = document.getElementById("projectForm");
        form.reset();
        console.log("form resetted");

        var errorMessagesDiv = document.getElementById("error-messages");
        if (errorMessagesDiv) {
            errorMessagesDiv.innerHTML = "";
        }
    }
});

function settable(tabledata) {
    if (window.table) {
        window.table.destroy();
    }
    if (tab == "rental") {
        console.log("in rental");
        var table = new Tabulator("#example-table", {
            pagination: "local",
            layout: "fitColumns",
            data: tabledata,
            placeholder: "Not authorised",
            paginationSize: 20,
            paginationCounter: "rows",
            selectable: 1,
            responsiveLayout: "collapse",
            columns: [
                {
                    formatter: "responsiveCollapse",
                    width: 30,
                    minWidth: 30,
                    hozAlign: "center",
                    resizable: false,
                    headerSort: false,
                },
                {
                    title: "PJO Number",
                    field: "job_number",
                    headerFilter: "input",
                    minWidth: 150,
                    frozen: true,
                },
                {
                    title: "Client Name",
                    field: "client_name",
                    minWidth: 150,
                    headerFilter: "input",
                },
                {
                    title: "Jobsite Location",
                    field: "jobsite_location",
                    minWidth: 150,
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
                    minWidth: 150,
                    headerSort: false,
                },
                {
                    title: "SMS Contacts (Number of alerts)",
                    field: "sms_count",
                    minWidth: 150,
                    headerSort: false,
                },
            ],
        });
    } else {
        var table = new Tabulator("#example-table", {
            pagination: "local",
            data: tabledata,
            layout: "fitColumns",
            placeholder: "Not authorised",
            paginationSize: 20,
            paginationCounter: "rows",
            dataTree: true,
            dataTreeStartExpanded: true,
            selectable: 1,
            responsiveLayout: "collapse",
            columns: [
                {
                    formatter: "responsiveCollapse",
                    width: 30,
                    minWidth: 30,
                    hozAlign: "center",
                    resizable: false,
                    headerSort: false,
                },
                {
                    title: "Name",
                    field: "name",
                    headerFilter: "input",
                    minWidth: 150,
                    frozen: true,
                    responsive: 0,
                },
                {
                    title: "Jobsite Location",
                    field: "jobsite_location",
                    minWidth: 150,
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
                    minWidth: 150,
                },
            ],
            rowFormatter: function (row) {
                // Apply special formatting for parent rows
                if (row.getTreeChildren().length) {
                    row.getElement().style.pointerEvents = "none";
                }
            },
        });
    }
    table.on("rowClick", function (e, row) {
        window.location.href = "/project/" + row.getIndex();
    });
    window.table = table;
}

function changeTab(event, project_type) {
    document.querySelectorAll(".nav-link").forEach((tab) => {
        tab.classList.remove("active");
    });

    event.currentTarget.classList.add("active");
    switch (project_type) {
        case "rental":
            tab = "rental";
            settable(window.rental_projects);
            break;

        default:
            tab = "sales";
            settable(window.sales_projects);
            break;
    }
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

function resetTable(json) {
    window.rental_projects = json.rental_projects;
    window.sales_projects = json.sales_projects;
    switch (tab) {
        case "rental":
            console.log("here");
            settable(window.rental_projects);
            break;

        default:
            settable(window.sales_projects);
            break;
    }
}

function create_users(projectId, csrfToken) {
    fetch(`${baseUri}/user/`, {
        method: "POST",
        headers: {
            "X-CSRF-TOKEN": csrfToken,
            Accept: "application/json",
            "X-Requested-With": "XMLHttpRequest",
        },
        body: JSON.stringify({
            project_id: projectId,
            users: userList,
        }),
    });
}

function submit_project() {
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
    }).then((response) => {
        console.log("responded");
        if (response.status == 422) {
            response.json().then((json) => {
                display_errors(json.errors);
            });
        } else {
            response.json().then((json) => {
                create_users(json.project_id, csrfToken);
                window.location.reload();
            });
        }
    });
}

function display_errors(errors) {
    error_messages = document.getElementById("error-messages");
    error_messages.innerHTML = "";
    // Loop through errors and display them
    for (const [field, messages] of Object.entries(errors)) {
        const li = document.createElement("li");
        li.className = "alert alert-danger";
        li.textContent = `${field} : ${messages}`;
        error_messages.appendChild(li);
    }
}

function openSecondModal(initialModal, newModal, li) {
    var firstModalEl = document.getElementById(initialModal);
    var firstModal = bootstrap.Modal.getInstance(firstModalEl);
    isSwitchingModal = true;
    firstModal.hide();

    firstModalEl.addEventListener(
        "hidden.bs.modal",
        function () {
            var secondModal = new bootstrap.Modal(
                document.getElementById(newModal)
            );

            secondModal.show();

            document
                .getElementById("deleteConfirmButton")
                .addEventListener("click", function () {
                    li.remove();
                    console.log(li.textContent);
                    const username = li.textContent
                        .replace("Remove", "")
                        .trim();
                    userList = userList.filter(
                        (user) => user.username !== username
                    );
                    secondModal.hide();
                    console.log(userList);
                });

            document.getElementById(newModal).addEventListener(
                "hidden.bs.modal",
                function () {
                    isSwitchingModal = false;
                    firstModal.show();
                },
                { once: true }
            );
        },
        { once: true }
    );
}

function addUserClicked() {
    const username = usernameField.value.trim();
    const password = passwordField.value.trim();
    if (!username || !password) {
        alert("Both username and password are required.");
        return;
    }

    fetch(`${baseUri}/user/${username}`, {
        method: "GET",
    }).then((response) => {
        if (response.status == 200) {
            userList.push({
                username: username,
                password: password,
            });

            // Create a new list item for the user
            const li = document.createElement("li");
            li.className =
                "list-group-item d-flex justify-content-between align-items-center";
            li.textContent = username;

            // Create a remove button
            const removeBtn = document.createElement("button");
            removeBtn.className = "btn btn-danger btn-sm";
            removeBtn.textContent = "Remove";

            // Add click event to remove the user
            removeBtn.addEventListener("click", (e) => {
                e.preventDefault();
                console.log("pressed");
                openSecondModal("projectModal", "deleteModal", li);
            });

            li.appendChild(removeBtn);
            curruserList.appendChild(li);

            // Clear input fields
            usernameField.value = "";
            passwordField.value = "";
        } else {
            alert("username is already taken");
            return;
        }
    });
}

window.toggleEndUserName = toggleEndUserName;
window.openSecondModal = openSecondModal;
window.submit_project = submit_project;
window.addUserClicked = addUserClicked;

settable(window.rental_projects);
toggleEndUserName();
