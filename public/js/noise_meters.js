var baseUri = `${window.location.protocol}//${window.location.hostname}`;
var selectedNoiseMeter = [];
if (window.location.port) {
    baseUri += `:${window.location.port}`;
}

var noiseMeterModal = document.getElementById("noiseMeterModal");

noiseMeterModal.addEventListener("hidden.bs.modal", function (event) {
    var form = document.getElementById("noise_meter_form");
    form.reset();
    console.log("form resetted");

    var errorMessagesDiv = document.getElementById("error_message");
    if (errorMessagesDiv) {
        errorMessagesDiv.innerHTML = "";
    }
});

function set_tables(data) {
    var noise_meter_table = new Tabulator("#noise_meter_table", {
        pagination: "local",
        layout: "fitColumns",
        data: data,
        placeholder: "No linked Contacts",
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
                formatter: "rowSelection",
                titleFormatter: "rowSelection",
                hozAlign: "center",
                headerSort: false,
                frozen: true,
                width: 30,
            },
            {
                title: "Serial Number",
                field: "serial_number",
                headerFilter: "input",
                minWidth: 100,
            },
            {
                title: "Label",
                field: "noise_meter_label",
                headerFilter: "input",
                minWidth: 100,
            },
            {
                title: "Brand",
                field: "brand",
                headerFilter: "input",
                minWidth: 100,
            },
            {
                title: "Last Calibration Date",
                field: "last_calibration_date",
                headerFilter: "date",
                minWidth: 100,
            },
            {
                title: "Remarks",
                field: "remarks",
                minWidth: 100,
            },
        ],
    });
    noise_meter_table.on("rowSelectionChanged", function (data, rows) {
        window.noiseMeter = data[0];
        table_row_changed(window.noiseMeter);
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
    var serial = document.getElementById("inputSerialNumber");
    var label = document.getElementById("inputLabel");
    var last_calibration_date = document.getElementById(
        "inputLastCalibrationDate"
    );
    var remarks = document.getElementById("inputRemarks");
    var brand = document.getElementById("inputBrand");
    document.getElementById("error_message").innerHTML = "";

    if (window.modalType == "update") {
        console.log("here");
        console.log(window.noiseMeter);
        serial.value = window.noiseMeter.serial_number;
        label.value = window.noiseMeter.noise_meter_label;
        last_calibration_date.value = window.noiseMeter.last_calibration_date;
        remarks.value = window.noiseMeter.remarks;
        brand.value = window.noiseMeter.brand;
    }
    if (window.modalType == "create") {
        serial.value = null;
        label.value = null;
        label.value = null;
        last_calibration_date.value = null;
        remarks.value = null;
        brand.value = null;
    }
}

function handle_update() {
    var csrfToken = document
        .querySelector('meta[name="csrf-token"]')
        .getAttribute("content");
    var form = document.getElementById("noise_meter_form");

    var formData = new FormData(form);

    var formDataJson = {};
    formData.forEach((value, key) => {
        formDataJson[key] = value;
    });

    fetch(`${baseUri}/noise_meters/${window.noiseMeter.id}`, {
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
                window.location.reload();
            }
        })
        .catch((error) => {
            alert("There was an error while processing");
        });
}

function handle_create() {
    const form = document.getElementById("noise_meter_form");
    const csrfToken = document.querySelector('input[name="_token"]').value;
    const formData = new FormData(form);

    fetch(`${baseUri}/noise_meters/`, {
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
                window.location.reload();
            }
        })
        .catch((error) => {
            alert("There was an error while processing");
        });
}

function handle_noise_meter_submit(event) {
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
        fetch(`${baseUri}/noise_meters/${window.noiseMeter.id} `, {
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
                window.location.reload();
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
    window.noise_meters = json.noise_meters;
    set_tables(window.noise_meters);
}

function openModal(modalName, type) {
    var modal = new bootstrap.Modal(document.getElementById(modalName));
    modal.toggle();

    if (modalName == "deleteConfirmationModal") {
        console.log(window.noiseMeter.noise_meter_label);
        document.getElementById("deleteType").innerHTML =
            window.noiseMeter.noise_meter_label;
    }

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

set_tables(window.noiseMeters);
window.set_tables = set_tables;
window.openModal = openModal;
window.openSecondModal = openSecondModal;
window.handle_noise_meter_submit = handle_noise_meter_submit;
window.handleDelete = handleDelete;
