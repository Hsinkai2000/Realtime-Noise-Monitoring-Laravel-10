const curruserList = document.getElementById("curruserList");
var userList = [];
const addUserBtn = document.getElementById("addUserBtn");
const usernameField = document.getElementById("username");
const passwordField = document.getElementById("password");
var projectModal = document.getElementById("projectModal");
var contactModal = document.getElementById("contactModal");
var measurementPointModal = document.getElementById("measurementPointModal");
var deleteConfirmationModal = document.getElementById(
    "deleteConfirmationModal"
);
var deleteType = "";
var contactType = "";
var baseUri = `${window.location.protocol}//${window.location.hostname}`;
if (window.location.port) {
    baseUri += `:${window.location.port}`;
}
var inputprojectId = window.project.id;
var concentrator_list_table;
var noiseMeter_list_table;
isSwitchingModal = false;

const valueMap = {
    Residential: {
        mon_sat_7am_7pm_leq5min: 90.0,
        mon_sat_7pm_10pm_leq5min: 70.0,
        mon_sat_10pm_12am_leq5min: 55.0,
        mon_sat_12am_7am_leq5min: 55.0,
        sun_ph_7am_7pm_leq5min: 75.0,
        sun_ph_7pm_10pm_leq5min: 65.0,
        sun_ph_10pm_12am_leq5min: 55.0,
        sun_ph_12am_7am_leq5min: 55.0,
        mon_sat_7am_7pm_leq12hr: 75.0,
        mon_sat_7pm_10pm_leq12hr: 65.0,
        mon_sat_10pm_12am_leq12hr: 55.0,
        mon_sat_12am_7am_leq12hr: 55.0,
        sun_ph_7am_7pm_leq12hr: 75.0,
        sun_ph_7pm_10pm_leq12hr: 140.0,
        sun_ph_10pm_12am_leq12hr: 140.0,
        sun_ph_12am_7am_leq12hr: 140.0,
    },
    "Hospital/Schools": {
        mon_sat_7am_7pm_leq5min: 75.0,
        mon_sat_7pm_10pm_leq5min: 55.0,
        mon_sat_10pm_12am_leq5min: 55.0,
        mon_sat_12am_7am_leq5min: 55.0,
        sun_ph_7am_7pm_leq5min: 75.0,
        sun_ph_7pm_10pm_leq5min: 55.0,
        sun_ph_10pm_12am_leq5min: 55.0,
        sun_ph_12am_7am_leq5min: 55.0,
        mon_sat_7am_7pm_leq12hr: 60.0,
        mon_sat_7pm_10pm_leq12hr: 50.0,
        mon_sat_10pm_12am_leq12hr: 50.0,
        mon_sat_12am_7am_leq12hr: 50.0,
        sun_ph_7am_7pm_leq12hr: 60.0,
        sun_ph_7pm_10pm_leq12hr: 50.0,
        sun_ph_10pm_12am_leq12hr: 50.0,
        sun_ph_12am_7am_leq12hr: 50.0,
    },
    Others: {
        mon_sat_7am_7pm_leq5min: 90.0,
        mon_sat_7pm_10pm_leq5min: 70.0,
        mon_sat_10pm_12am_leq5min: 70.0,
        mon_sat_12am_7am_leq5min: 70.0,
        sun_ph_7am_7pm_leq5min: 90.0,
        sun_ph_7pm_10pm_leq5min: 70.0,
        sun_ph_10pm_12am_leq5min: 70.0,
        sun_ph_12am_7am_leq5min: 70.0,
        mon_sat_7am_7pm_leq12hr: 75.0,
        mon_sat_7pm_10pm_leq12hr: 65.0,
        mon_sat_10pm_12am_leq12hr: 65.0,
        mon_sat_12am_7am_leq12hr: 65.0,
        sun_ph_7am_7pm_leq12hr: 75.0,
        sun_ph_7pm_10pm_leq12hr: 65.0,
        sun_ph_10pm_12am_leq12hr: 65.0,
        sun_ph_12am_7am_leq12hr: 65.0,
    },
};

projectModal.addEventListener("hidden.bs.modal", function (event) {
    if (!isSwitchingModal) {
        populateUsers();
        var form = document.getElementById("projectForm");
        form.reset();
        var errorMessagesDiv = document.getElementById("error-messages");
        if (errorMessagesDiv) {
            errorMessagesDiv.innerHTML = "";
        }
    }
});

contactModal.addEventListener("hidden.bs.modal", function (event) {
    var form = document.getElementById("contact_form");
    form.reset();
    console.log("form resetted");

    var errorMessagesDiv = document.getElementById("error-messagesjs");
    if (errorMessagesDiv) {
        errorMessagesDiv.innerHTML = "";
    }
});

measurementPointModal.addEventListener("hidden.bs.modal", function (event) {
    if (!isSwitchingModal) {
        var form = document.getElementById("measurement_point_form");
        form.reset();
        console.log("form resetted");

        var errorMessagesDiv = document.getElementById("error_messagemp");
        if (errorMessagesDiv) {
            errorMessagesDiv.innerHTML = "";
        }
        populate_soundLimits();
    }
});

function toggle_soundLimits() {
    var soundlimit = document.getElementById("advanced_sound_limits");
    soundlimit.hidden
        ? (soundlimit.hidden = false)
        : (soundlimit.hidden = true);
}

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
                field: "concentrator.device_id",
                headerFilter: "input",
                minWidth: 100,
            },
            {
                title: "Concentrator Battery Voltage",
                field: "concentrator.battery_voltage",
                headerSort: false,
                headerFilter: "input",
                minWidth: 100,
            },
            {
                title: "Concentrator CSQ",
                field: "concentrator.concentrator_csq",
                headerSort: false,
                headerFilter: "input",
                minWidth: 100,
                formatter: function (cell) {
                    const value = cell.getValue();
                    if (value) {
                        if (value <= 70) {
                            return `<strong style="color: green">-${value}dBm</strong>`;
                        } else if (value <= 85) {
                            return `<strong style="color: #FFC300">-${value}dBm</strong>`;
                        } else if (value <= 100) {
                            return `<strong style="color: orange">-${value}dBm</strong>`;
                        } else {
                            return `<strong style="color: red">-${value}dBm</strong>`;
                        }
                    }
                    return "-";
                },
            },
            {
                title: "Last Concentrator Communication",
                field: "concentrator.last_communication_packet_sent",
                headerSort: false,
                headerFilter: "input",
                minWidth: 100,
            },
            {
                title: "Noise Serial",
                field: "noise_meter.serial_number",
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
                field: "noise_meter.serial_number",
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

function check_contact_max() {
    if (window.contacts.length >= window.project.sms_count) {
        document.getElementById("createContactButton").disabled = true;
        document.getElementById("contact_counter").style.color = "#cc2e0e";
    } else {
        document.getElementById("createContactButton").disabled = false;
        document.getElementById("contact_counter").style.color = "#2b1710";
    }
}

function set_contact_table() {
    document.getElementById("contact_counter").textContent =
        window.contacts.length + " / " + window.project.sms_count;
    check_contact_max();
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
}

function update_users(projectId, csrfToken) {
    console.log("userList");
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
    }).then((response) => {
        console.log("responded");
        if (response.status == 422) {
            response.json().then((json) => {
                console.log("User not updated");
            });
        } else {
            response.json().then((json) => {
                console.log("responded ok");
                window.location.reload();
                return true;
            });
        }
    });
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
                display_errors("error-messages", json.errors);
            });
        } else {
            response.json().then((json) => {
                update_users(inputprojectId, csrfToken);
                return true;
            });
        }
    });
}

function openSecondModalUser(initialModal, newModal, li) {
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
        endUserNameDiv.hidden = true;
    } else {
        endUserNameDiv.hidden = false;
    }
}

function display_errors(element, errors) {
    error_messages = document.getElementById(element);
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
            openSecondModalUser("projectModal", "deleteModal", li);
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
                console.log("pressed");
                openSecondModalUser("projectModal", "deleteModal", li);
            });

            li.appendChild(removeBtn);
            curruserList.appendChild(li);

            console.log("userList");
            console.log(userList);
            // Clear input fields
            usernameField.value = "";
            passwordField.value = "";
        } else {
            alert("username is already taken");
            return;
        }
    });
}

function fetch_contact_data(type) {
    var inputName = document.getElementById("inputName");
    var inputDesignation = document.getElementById("inputDesignation");
    var inputEmail = document.getElementById("inputEmail");
    var inputPhoneNumber = document.getElementById("inputPhoneNumber");
    var inputContactProjectID = document.getElementById(
        "inputContactProjectID"
    );
    var form = document.getElementById("contact_form");
    inputContactProjectID.value = inputprojectId;

    form.reset();
    if (type == "update") {
        inputName.value = window.selectedContact.contact_person_name;
        inputDesignation.value = window.selectedContact.designation;
        inputEmail.value = window.selectedContact.email;
        inputPhoneNumber.value = window.selectedContact.phone_number;
    }
}

function handleCreateContact() {
    var csrfToken = document
        .querySelector('meta[name="csrf-token"]')
        .getAttribute("content");
    var form = document.getElementById("contact_form");

    var formData = new FormData(form);

    var formDataJson = {};
    formData.forEach((value, key) => {
        formDataJson[key] = value;
    });

    fetch(`${baseUri}/contacts/`, {
        method: "POST",
        headers: {
            "X-CSRF-TOKEN": csrfToken,
            "Content-Type": "application/json",
            Accept: "application/json",
            "X-Requested-With": "XMLHttpRequest",
        },
        body: JSON.stringify(formDataJson),
    }).then((response) => {
        if (response.status == 422) {
            response.json().then((json) => {
                display_errors("error-messagesjs", json.errors);
            });
        } else {
            window.location.reload();
        }
    });
    return false;
}

function handleUpdateContact() {
    var csrfToken = document
        .querySelector('meta[name="csrf-token"]')
        .getAttribute("content");
    var form = document.getElementById("contact_form");

    var formData = new FormData(form);

    var formDataJson = {};
    formData.forEach((value, key) => {
        formDataJson[key] = value;
    });

    fetch(`${baseUri}/contacts/${window.selectedContactid}`, {
        method: "PATCH",
        headers: {
            "X-CSRF-TOKEN": csrfToken,
            "Content-Type": "application/json",
            Accept: "application/json",
            "X-Requested-With": "XMLHttpRequest",
        },
        body: JSON.stringify(formDataJson),
    }).then((response) => {
        if (response.status == 422) {
            response.json().then((json) => {
                display_errors("error-messagesjs", json.errors);
            });
        } else {
            window.location.reload();
        }
    });
    return false;
}

function handleContactSubmit() {
    contactType == "create" ? handleCreateContact() : handleUpdateContact();
}

function openModal(modalName, type = null) {
    if (modalName === "deleteConfirmationModal") {
        if (type === "contact") {
            document.getElementById("deleteType").innerHTML =
                window.selectedContact["contact_person_name"];
            deleteType = "contact";
        } else {
            document.getElementById("deleteType").innerHTML = type;
            deleteType = "project";
        }
    } else if (modalName == "measurementPointModal") {
        concentrator_list_table.deselectRow();
        noiseMeter_list_table.deselectRow();
        if (window.concentrator) {
            concentrator_list_table.selectRow(window.concentrator.id);
        }
        if (window.noise_meter) {
            noiseMeter_list_table.selectRow(window.noise_meter.id);
        }
    } else {
        contactType = type;
        fetch_contact_data(contactType);
    }

    var modal = new bootstrap.Modal(document.getElementById(modalName));
    modal.toggle();
}

function handleDelete(e) {
    e.preventDefault();
    var input = document.getElementById("inputDeleteConfirmation").value;

    if (input !== "DELETE") {
        document.getElementById("error-messages-delete").hidden = false;
        return false;
    }

    var csrfToken = document
        .querySelector('meta[name="csrf-token"]')
        .getAttribute("content");
    var url =
        deleteType == "contact"
            ? `${baseUri}/contacts/${window.selectedContactid}`
            : `${baseUri}/project/${inputprojectId}`;

    fetch(url, {
        method: "DELETE",
        headers: {
            "X-CSRF-TOKEN": csrfToken,
            "Content-Type": "application/json",
            Accept: "application/json",
            "X-Requested-With": "XMLHttpRequest",
        },
    }).then((response) => {
        if (response.status === 422) {
            console.log(response);
        } else {
            if (deleteType === "contact") {
                window.location.reload();
            } else {
                window.location.href = `/project/admin/`;
            }
        }
    });

    return true;
}

function populate_soundLimits(event, reset_defaults = false) {
    if (event && event.preventDefault) {
        event.preventDefault();
    }

    var selectedCategory = document.getElementById("selectCategory").value;

    var inputmonsat7am7pmleq5 = document.getElementById(
        "inputmonsat7am7pmleq5"
    );
    var inputmonsat7pm10pmleq5 = document.getElementById(
        "inputmonsat7pm10pmleq5"
    );
    var inputmonsat10pm12amleq5 = document.getElementById(
        "inputmonsat10pm12amleq5"
    );
    var inputmonsat12am7amleq5 = document.getElementById(
        "inputmonsat12am7amleq5"
    );

    var inputmonsat7am7pmleq12 = document.getElementById(
        "inputmonsat7am7pmleq12"
    );
    var inputmonsat7pm10pmleq12 = document.getElementById(
        "inputmonsat7pm10pmleq12"
    );
    var inputmonsat10pm12amleq12 = document.getElementById(
        "inputmonsat10pm12amleq12"
    );
    var inputmonsat12am7amleq12 = document.getElementById(
        "inputmonsat12am7amleq12"
    );

    var inputsunph7am7pmleq5 = document.getElementById("inputsunph7am7pmleq5");
    var inputsunph7pm10pmleq5 = document.getElementById(
        "inputsunph7pm10pmleq5"
    );
    var inputsunph10pm12amleq5 = document.getElementById(
        "inputsunph10pm12amleq5"
    );
    var inputsunph12am7amleq5 = document.getElementById(
        "inputsunph12am7amleq5"
    );

    var inputsunph7am7pmleq12 = document.getElementById(
        "inputsunph7am7pmleq12"
    );
    var inputsunph7pm10pmleq12 = document.getElementById(
        "inputsunph7pm10pmleq12"
    );
    var inputsunph10pm12amleq12 = document.getElementById(
        "inputsunph10pm12amleq12"
    );
    var inputsunph12am7amleq12 = document.getElementById(
        "inputsunph12am7amleq12"
    );
    inputmonsat7am7pmleq5.value =
        valueMap[selectedCategory].mon_sat_7am_7pm_leq5min;
    inputmonsat7pm10pmleq5.value =
        valueMap[selectedCategory].mon_sat_7pm_10pm_leq5min;
    inputmonsat10pm12amleq5.value =
        valueMap[selectedCategory].mon_sat_10pm_12am_leq5min;
    inputmonsat12am7amleq5.value =
        valueMap[selectedCategory].mon_sat_12am_7am_leq5min;

    inputmonsat7am7pmleq12.value =
        valueMap[selectedCategory].mon_sat_7am_7pm_leq12hr;
    inputmonsat7pm10pmleq12.value =
        valueMap[selectedCategory].mon_sat_7pm_10pm_leq12hr;
    inputmonsat10pm12amleq12.value =
        valueMap[selectedCategory].mon_sat_10pm_12am_leq12hr;
    inputmonsat12am7amleq12.value =
        valueMap[selectedCategory].mon_sat_12am_7am_leq12hr;

    inputsunph7am7pmleq5.value =
        valueMap[selectedCategory].sun_ph_7am_7pm_leq5min;
    inputsunph7pm10pmleq5.value =
        valueMap[selectedCategory].sun_ph_7pm_10pm_leq5min;
    inputsunph10pm12amleq5.value =
        valueMap[selectedCategory].sun_ph_10pm_12am_leq5min;
    inputsunph12am7amleq5.value =
        valueMap[selectedCategory].sun_ph_12am_7am_leq5min;

    inputsunph7am7pmleq12.value =
        valueMap[selectedCategory].sun_ph_7am_7pm_leq12hr;
    inputsunph7pm10pmleq12.value =
        valueMap[selectedCategory].sun_ph_7pm_10pm_leq12hr;
    inputsunph10pm12amleq12.value =
        valueMap[selectedCategory].sun_ph_10pm_12am_leq12hr;
    inputsunph12am7amleq12.value =
        valueMap[selectedCategory].sun_ph_12am_7am_leq12hr;
}

function set_device_tables() {
    if (document.getElementById("concentrator_list_table")) {
        concentrator_list_table = new Tabulator("#concentrator_list_table", {
            layout: "fitColumns",
            ajaxURL: `${baseUri}/concentrators`,
            placeholder: "No concentrators",
            pagination: "local",
            paginationSize: 5,
            paginationCounter: "rows",
            selectable: 1,
            ajaxResponse: function (url, params, response) {
                // Add an empty row for unlinking at the top
                response.unshift({
                    id: null,
                    device_id: "Unlink",
                    concentrator_label: "",
                    isAvailable: "true",
                });
                return response;
            },
            columns: [
                {
                    title: "Device ID",
                    field: "device_id",
                    minWidth: 120,
                    width: 120,
                    headerFilter: "input",
                },
                {
                    title: "Label",
                    field: "concentrator_label",
                    headerFilter: "input",
                    minWidth: 150,
                },
                {
                    title: "Unused",
                    field: "isAvailable",
                    formatter: "tickCross",
                    minWidth: 80,
                    width: 80,
                    headerFilter: "tickCross",
                    headerFilterParams: { tristate: true },
                },
            ],
        });

        concentrator_list_table.on(
            "rowSelectionChanged",
            function (data, rows) {
                if (data && data.length > 0) {
                    document.getElementById("concentratorId").value =
                        data[0].id;
                } else {
                    document.getElementById("concentratorId").value = null;
                }
            }
        );

        concentrator_list_table.on("tableBuilt", function () {
            setTimeout(function () {
                const firstRow = concentrator_list_table.getRows()[0];
                if (firstRow) {
                    firstRow.select();
                }
            }, 200);
        });
    }

    if (document.getElementById("noiseMeter_list_table")) {
        noiseMeter_list_table = new Tabulator("#noiseMeter_list_table", {
            layout: "fitColumns",
            ajaxURL: `${baseUri}/noise_meters`,
            placeholder: "No noise meters",
            pagination: "local",
            paginationSize: 5,
            paginationCounter: "rows",
            selectable: 1,
            ajaxResponse: function (url, params, response) {
                // Add an empty row for unlinking at the top
                response.unshift({
                    id: null,
                    serial_number: "Unlink",
                    noise_meter_label: "",
                    isAvailable: "true",
                });
                return response;
            },
            columns: [
                {
                    title: "Serial No",
                    field: "serial_number",
                    minWidth: 120,
                    width: 120,
                    headerFilter: "input",
                },
                {
                    title: "Label",
                    field: "noise_meter_label",
                    headerFilter: "input",
                    minWidth: 150,
                },
                {
                    title: "Unused",
                    field: "isAvailable",
                    formatter: "tickCross",
                    minWidth: 80,
                    width: 80,
                    headerFilter: "tickCross",
                    headerFilterParams: { tristate: true },
                },
            ],
        });
        noiseMeter_list_table.on("rowSelectionChanged", function (data, rows) {
            if (data && data.length > 0) {
                document.getElementById("noiseMeterId").value = data[0].id;
            } else {
                document.getElementById("noiseMeterId").value = null;
            }
        });

        noiseMeter_list_table.on("tableBuilt", function () {
            setTimeout(function () {
                noiseMeter_list_table.selectRow(0);
            }, 200);
        });
    }
}

function create_empty_option(select, text) {
    var defaultOption = document.createElement("option");
    defaultOption.textContent = text;
    defaultOption.selected = true;
    defaultOption.value = "";
    select.appendChild(defaultOption);
}

async function handle_measurementpoint_submit(confirmation = false) {
    var csrfToken = document
        .querySelector('meta[name="csrf-token"]')
        .getAttribute("content");
    var form = document.getElementById("measurement_point_form");

    var formData = new FormData(form);
    var checkedBoxes = document.querySelectorAll(
        'input[name="alert_days[]"]:checked'
    );

    var alertDays = [];
    checkedBoxes.forEach((checkedBox) => {
        alertDays.push(checkedBox.value);
    });
    var formDataJson = {};
    formData.forEach((value, key) => {
        formDataJson[key] = value;
    });

    formDataJson["alert_days"] = alertDays.join(", ");

    formDataJson["confirmation"] = confirmation;

    return fetch(`${baseUri}/measurement_point`, {
        method: "POST",
        headers: {
            "X-CSRF-TOKEN": csrfToken,
            "Content-Type": "application/json",
            Accept: "application/json",
            "X-Requested-With": "XMLHttpRequest",
        },
        body: JSON.stringify(formDataJson),
    })
        .then((response) => {
            console.log("responded");
            console.log(response);
            if (response.status == 422) {
                response.json().then((json) => {
                    let message = "";

                    if (json.errors) {
                        const errorKeys = Object.keys(json.errors);

                        const isDeviceErrorOnly = errorKeys.every((key) =>
                            ["concentrator_id", "noise_meter_id"].includes(key)
                        );

                        if (!isDeviceErrorOnly) {
                            if (json.errors.concentrator_id)
                                delete json.errors.concentrator_id;
                            if (json.errors.noise_meter_id)
                                delete json.errors.noise_meter_id;
                            display_errors("error_messagemp", json.errors);
                        } else {
                            if (json.errors.concentrator_id) {
                                message +=
                                    json.errors.concentrator_id.join(" ") +
                                    "\n";
                            }

                            if (json.errors.noise_meter_id) {
                                message +=
                                    json.errors.noise_meter_id.join(" ") + "\n";
                            }

                            document.getElementById("devicesSpan").innerHTML =
                                message;

                            openSecondModal(
                                "measurementPointModal",
                                "confirmationModal"
                            );
                        }
                    }
                });
            } else {
                response.json().then(async (json) => {
                    formDataJson["measurement_point_id"] =
                        json.measurement_point["id"];
                    return await create_sound_limits(formDataJson);
                });
            }
        })
        .catch((error) => {
            console.error("Error:", error);
            alert("There was an error: " + error.message);
        });
}

async function create_sound_limits(formDataJson) {
    var csrfToken = document
        .querySelector('meta[name="csrf-token"]')
        .getAttribute("content");
    return fetch(`${baseUri}/soundlimits`, {
        method: "POST",
        headers: {
            "X-CSRF-TOKEN": csrfToken,
            "Content-Type": "application/json",
            Accept: "application/json",
            "X-Requested-With": "XMLHttpRequest",
        },
        body: JSON.stringify(formDataJson),
    }).then((response) => {
        if (response.status == 422) {
            response.json().then((errorData) => {
                document.getElementById("error_message").innerHTML =
                    errorData["Unprocessable Entity"];
            });
        } else {
            window.location.reload();
            var form = document.getElementById("measurement_point_form");
            form.reset();
            return true;
        }
    });
}

function openSecondModal(initialModal, newModal) {
    if (newModal == "confirmationModal") {
        document.getElementById("confirmationError").hidden = true;
    }
    isSwitchingModal = true;

    var firstModalEl = document.getElementById(initialModal);
    var firstModal = bootstrap.Modal.getInstance(firstModalEl);

    firstModal.hide();

    firstModalEl.addEventListener(
        "hidden.bs.modal",
        function () {
            var secondModal = new bootstrap.Modal(
                document.getElementById(newModal)
            );

            secondModal.show();

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

async function handleConfirmationSubmit(event) {
    try {
        if (event) {
            event.preventDefault();
        }
        var confirmation = document.getElementById(
            "inputContinueConfirmation"
        ).value;
        if (confirmation == "YES") {
            console.log("yes");
            await handle_measurementpoint_submit(true);
            // location.reload();
        } else {
            console.log("failed");
            var error = document.getElementById("confirmationError");
            error.hidden = false;
        }
    } catch (error) {
        console.log(error);
    }
}

window.addUserClicked = addUserClicked;
window.toggleEndUserName = toggleEndUserName;
window.submit_project = submit_project;
window.openModal = openModal;
window.handleContactSubmit = handleContactSubmit;
window.handleDelete = handleDelete;
window.toggle_soundLimits = toggle_soundLimits;
window.populate_soundLimits = populate_soundLimits;
window.handle_measurementpoint_submit = handle_measurementpoint_submit;

set_device_tables();
set_contact_table();
set_measurement_point_table();
// toggleEndUserName();
populateUsers();
populate_soundLimits();
