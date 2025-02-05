var baseUri = `${window.location.protocol}//${window.location.hostname}`;

if (window.location.port) {
    baseUri += `:${window.location.port}`;
}
var concentratorModal = document.getElementById("concentratorModal");

concentratorModal.addEventListener("hidden.bs.modal", function (event) {
    var form = document.getElementById("concentrator_form");
    form.reset();
    console.log("form resetted");

    var errorMessagesDiv = document.getElementById("error_message");
    if (errorMessagesDiv) {
        errorMessagesDiv.innerHTML = "";
    }
});

function set_tables(data) {
    var concentrator_table = new Tabulator("#concentrator_table", {
        layout: "fitColumns",
        data: data,
        placeholder: "No linked Contacts",
        pagination: "local",
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
                title: "Device Id",
                field: "device_id",
                headerFilter: "input",
                minWidth: 100,
            },
            {
                title: "Label",
                field: "concentrator_label",
                headerFilter: "input",
                minWidth: 100,
            },
            {
                title: "CSQ",
                field: "concentrator_csq",
                headerFilter: "input",
                minWidth: 100,
            },
            {
                title: "HP Number",
                field: "concentrator_hp",
                headerFilter: "input",
                minWidth: 100,
            },
            {
                title: "Battery Voltage",
                field: "battery_voltage",
                headerFilter: "input",
                minWidth: 100,
            },
            {
                title: "Last Communication Packet Sent",
                field: "last_communication_packet_sent",
                headerFilter: "date",
                minWidth: 100,
            },

            {
                title: "PJO Number",
                field: "measurement_point.project.job_number",
                headerFilter: "input",
                minWidth: 100,
            },
            {
                title: "Client Name",
                field: "measurement_point.project.client_name",
                headerFilter: "input",
                minWidth: 100,
            },
            {
                title: "Jobsite Location",
                field: "measurement_point.project.jobsite_location",
                headerFilter: "input",
                minWidth: 100,
            },
            {
                title: "Point Name",
                field: "measurement_point.point_name",
                headerFilter: "input",
                minWidth: 100,
            },
            {
                title: "Last Assigned IP Address",
                field: "last_assigned_ip_address",
                headerFilter: "input",
                minWidth: 100,
            },
            {
                title: "Remarks",
                field: "remarks",
                minWidth: 100,
            },
        ],
    });
    concentrator_table.on("rowSelectionChanged", function (data, rows) {
        window.concentrator = data[0];
        table_row_changed(window.concentrator);
    });
}

function table_row_changed(data) {
    if (data) {
        document.getElementById("editButton").disabled = false;
        document.getElementById("deleteButton").disabled = false;
    } else {
        document.getElementById("editButton").disabled = true;
        document.getElementById("deleteButton").disabled = true;
    }
}

function fill_data() {
    var inputdevice_id = document.getElementById("inputdevice_id");
    var inputLabel = document.getElementById("inputLabel");
    var inputRemarks = document.getElementById("inputRemarks");

    if (window.modalType == "update") {
        inputdevice_id.value = window.concentrator.device_id;
        inputLabel.value = window.concentrator.concentrator_label;
        inputRemarks.value = window.concentrator.remarks;
    } else if (window.modalType == "create") {
        inputdevice_id.value = null;
        inputLabel.value = null;
        inputRemarks.value = null;
    }
}

function handle_update() {
    var csrfToken = document
        .querySelector('meta[name="csrf-token"]')
        .getAttribute("content");
    var form = document.getElementById("concentrator_form");

    var formData = new FormData(form);

    var formDataJson = {};
    formData.forEach((value, key) => {
        formDataJson[key] = value;
    });

    fetch(`${baseUri}/concentrators/${window.concentrator.id}`, {
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
            if (response.status == 422) {
                response.json().then((errorData) => {
                    display_errors("error_message", errorData.errors);
                });
            } else {
                response.json().then((json) => {
                    resetTable(json);
                    closeModal("concentratorModal");
                });
            }
        })
        .catch((error) => {
            alert("There was an error while processing");
        });
    return false;
}

function handle_create() {
    const form = document.getElementById("concentrator_form");
    const csrfToken = document.querySelector('input[name="_token"]').value;
    const formData = new FormData(form);
    fetch(`${baseUri}/concentrators/`, {
        method: "POST",
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
                    display_errors("error_message", errorData.errors);
                });
            } else {
                response.json().then((json) => {
                    resetTable(json);
                    closeModal("concentratorModal");
                });
            }
        })
        .catch((error) => {
            alert("There was an error while processing");
        });
    return false;
}

function handle_concentrator_submit(event) {
    event.preventDefault();
    if (window.modalType == "update") {
        handle_update();
    } else if (window.modalType == "create") {
        handle_create();
    }
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
        fetch(`${baseUri}/concentrators/${window.concentrator.id}`, {
            method: "DELETE",
            headers: {
                "X-CSRF-TOKEN": csrfToken,
                Accept: "application/json",
                "X-Requested-With": "XMLHttpRequest",
            },
        })
            .then((response) => {
                if (!response.ok) {
                    console.log("Error:", response);
                    throw new Error("Network response was not ok");
                }
                return response.json();
            })
            .then((data) => {
                resetTable(data);
                closeModal("deleteConfirmationModal");
            })
            .catch((error) => {
                console.error("Error:", error);
            });
    } else {
        document.getElementById("error-messages-delete").hidden = false;
        return false;
    }
}

function resetTable(json) {
    window.concentrators = json.concentrators;
    set_tables(window.concentrators);
}

function openModal(modalName, type) {
    var modal = new bootstrap.Modal(document.getElementById(modalName));
    modal.toggle();

    if (type == "create") {
        window.modalType = "create";
    } else if (type == "update") {
        window.modalType = "update";
    }
    fill_data();
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

set_tables(window.concentrators);
window.set_tables = set_tables;
window.openModal = openModal;
window.openSecondModal = openSecondModal;
window.handleDelete = handleDelete;
window.handle_concentrator_submit = handle_concentrator_submit;
