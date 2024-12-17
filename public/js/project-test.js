const curruserList = document.getElementById("curruserList");
const addUserBtn = document.getElementById("addUserBtn");
const usernameField = document.getElementById("username");
const passwordField = document.getElementById("password");
var projectModal = document.getElementById("projectModal");

var baseUri = `${window.location.protocol}//${window.location.hostname}`;
if (window.location.port) {
    baseUri += `:${window.location.port}`;
}
var tab = "rental";
var inputprojectId = window.project.id;
isSwitchingModal = false;

projectModal.addEventListener("hidden.bs.modal", function (event) {
    if (!isSwitchingModal) {
        populateUsers();
        var form = document.getElementById("projectForm");
        form.reset();
        console.log("form resetted");

        var errorMessagesDiv = document.getElementById("error-messages");
        if (errorMessagesDiv) {
            errorMessagesDiv.innerHTML = "";
        }
    }
});

function manage_measurement_point_columns() {
    if (window.admin) {
        return [
            {
                formatter: "responsiveCollapse",
                width: 30,
                minWidth: 30,
                hozAlign: "center",
                resizable: false,
                headerSort: false,
            },
            {
                formatter: "rowSelection",
                titleFormatter: "rowSelection",
                hozAlign: "center",
                headerSort: false,
                frozen: true,
                width: 30,
            },
            {
                title: "Point Name",
                field: "point_name",
                minWidth: 100,
                headerFilter: "input",
                frozen: true,
            },
            {
                title: "Point Location",
                field: "device_location",
                headerSort: false,
                headerFilter: "input",
                minWidth: 100,
            },
            {
                title: "Concentrator Serial",
                field: "device_id",
                headerFilter: "input",
                minWidth: 100,
            },
            {
                title: "Concentrator Battery Voltage",
                field: "battery_voltage",
                headerSort: false,
                headerFilter: "input",
                minWidth: 100,
            },
            {
                title: "Concentrator CSQ",
                field: "concentrator_csq",
                headerSort: false,
                headerFilter: "input",
                minWidth: 100,
            },
            {
                title: "Last Concentrator Communication",
                field: "last_communication_packet_sent",
                headerSort: false,
                headerFilter: "input",
                minWidth: 100,
            },
            {
                title: "Noise Serial",
                field: "serial_number",
                minWidth: 100,
                headerFilter: "input",
            },
            {
                title: "Data Status",
                field: "data_status",
                headerSort: false,
                headerFilter: "input",
                minWidth: 100,
                formatter: "tickCross",
            },
        ];
    } else {
        return [
            {
                formatter: "responsiveCollapse",
                width: 30,
                minWidth: 30,
                hozAlign: "center",
                resizable: false,
                headerSort: false,
            },
            {
                formatter: "rowSelection",
                titleFormatter: "rowSelection",
                hozAlign: "center",
                headerSort: false,
                frozen: true,
                width: 30,
            },
            {
                title: "Point Name",
                field: "point_name",
                minWidth: 100,
                headerFilter: "input",
                frozen: true,
            },
            {
                title: "Point Location",
                field: "device_location",
                headerSort: false,
                headerFilter: "input",
                minWidth: 100,
            },
            {
                title: "Noise Serial",
                field: "serial_number",
                minWidth: 100,
                headerFilter: "input",
            },
            {
                title: "Data Status",
                field: "data_status",
                headerSort: false,
                headerFilter: "input",
                minWidth: 100,
                formatter: "tickCross",
            },
        ];
    }
}

function fetchUsers() {}

function set_contact_table() {
    var contactTable = new Tabulator("#contacts_table", {
        layout: "fitColumns",
        data: window.contacts,
        placeholder: "No linked Contacts",
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
                formatter: "rowSelection",
                titleFormatter: "rowSelection",
                hozAlign: "center",
                headerSort: false,
                frozen: true,
                width: 30,
            },
            {
                title: "Name",
                field: "contact_person_name",
                headerSort: false,
                minWidth: 100,
            },
            {
                title: "Designation",
                field: "designation",
                headerSort: false,
                minWidth: 100,
            },
            {
                title: "Email",
                field: "email",
                headerSort: false,
                minWidth: 100,
            },
            {
                title: "SMS",
                field: "phone_number",
                headerSort: false,
                minWidth: 100,
            },
        ],
    });
    contactTable.on("rowSelectionChanged", function (data, rows) {
        contactTableRowChanged(data);
    });
}

function contactTableRowChanged(data) {
    if (data && data.length > 0) {
        document.getElementById("editContactButton").disabled = false;
        document.getElementById("deleteContactButton").disabled = false;
        window.selectedContactid = data[0].id;
        window.selectedContact = data[0];
    } else {
        document.getElementById("editContactButton").disabled = true;
        document.getElementById("deleteContactButton").disabled = true;
    }
}

function set_measurement_point_table() {
    document.getElementById("measurement_point_pages").innerHTML = "";
    var measurementPointTable = new Tabulator("#measurement_point_table", {
        ajaxURL: `${baseUri}/measurement_points/${inputprojectId}`,
        layout: "fitColumns",
        placeholder: "No Linked Measurement Points",
        paginationSize: 8,
        pagination: "local",
        paginationCounter: "rows",
        selectable: 1,
        responsiveLayout: "collapse",
        columns: manage_measurement_point_columns(),
    });
    measurementPointTable.on("rowClick", function (e, row) {
        window.location.href = "/measurement_point/" + row.getIndex();
    });
    measurementPointTable.on("rowSelectionChanged", function (data, rows) {
        table_row_changed(data);
    });
}

function table_row_changed(data) {
    if (data && data.length > 0) {
        document.getElementById("editButton").disabled = false;
        document.getElementById("deleteButton").disabled = false;
        inputMeasurementPointId = data[0].id;
        inputMeasurementPoint = data[0];
    } else {
        document.getElementById("editButton").disabled = true;
        document.getElementById("deleteButton").disabled = true;
    }
}

function update_users(projectId, csrfToken) {
    console.log(userList);
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
    // });
}

function submit_project() {
    console.log("submitting project");
    const form = document.getElementById("projectForm");
    const csrfToken = document.querySelector('input[name="_token"]').value;

    // Convert FormData to JSON
    const formData = new FormData(form);
    const jsonData = {};
    formData.forEach((value, key) => {
        jsonData[key] = value;
    });

    console.log("JSON Data:", jsonData);

    fetch(`${baseUri}/project/${inputprojectId}`, {
        method: "PATCH",
        headers: {
            "X-CSRF-TOKEN": csrfToken,
            Accept: "application/json",
            "Content-Type": "application/json",
            "X-Requested-With": "XMLHttpRequest",
        },
        body: JSON.stringify(jsonData),
    }).then((response) => {
        console.log("responded");
        if (response.status == 422) {
            response.json().then((json) => {
                display_errors(json.errors);
            });
        } else {
            response.json().then((json) => {
                update_users(inputprojectId, csrfToken);
                window.location.reload();
            });
        }
    });
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

function toggleEndUserName() {
    var rentalRadio = document.getElementById("projectTypeRental");
    var endUserNameDiv = document.getElementById("endUserNameDiv");
    if (rentalRadio.checked) {
        endUserNameDiv.style.display = "none";
    } else {
        endUserNameDiv.style.display = "flex";
    }
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

function populateUsers() {
    userList = [];
    curruserList.innerHTML = "";
    var users = window.project.user;
    users.forEach((user) => {
        userList.push({
            username: user.username,
            password: user.password,
        });
    });

    userList.forEach((user) => {
        // Create a new list item for the user
        const li = document.createElement("li");
        li.className =
            "list-group-item d-flex justify-content-between align-items-center";
        li.textContent = user.username;

        // Create a remove button
        const removeBtn = document.createElement("button");
        removeBtn.className = "btn btn-danger btn-sm";
        removeBtn.textContent = "Remove";

        // Add click event to remove the user
        removeBtn.addEventListener("click", (e) => {
            e.preventDefault();
            openSecondModal("projectModal", "deleteModal", li);
        });

        li.appendChild(removeBtn);
        curruserList.appendChild(li);
    });
}

function addUserClicked() {
    console.log("clicked");
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

window.addUserClicked = addUserClicked;
window.toggleEndUserName = toggleEndUserName;
window.submit_project = submit_project;

set_contact_table();
set_measurement_point_table();
toggleEndUserName();
populateUsers();
