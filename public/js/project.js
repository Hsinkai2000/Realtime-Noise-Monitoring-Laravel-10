var baseUri = `${window.location.protocol}//${window.location.hostname}`;
if (window.location.port) {
    baseUri += `:${window.location.port}`;
}
var inputprojectId = null;
var userList = [];
var modalType = "";
var inputUserId = null;
var inputMeasurementPointId = null;
var inputMeasurementPoint = null;
var noise_meter_data = [];
var concentrator_data = [];

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

function toggle_soundLimits() {
    var soundlimit = document.getElementById("advanced_sound_limits");
    soundlimit.hidden
        ? (soundlimit.hidden = false)
        : (soundlimit.hidden = true);
}

function populate_soundLimits(event, reset_defaults = false) {
    if (event && event.preventDefault) {
        event.preventDefault();
    }

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
    if (modalType == "create" || reset_defaults) {
        var category = document.getElementById("selectCategory").value;
        inputmonsat7am7pmleq5.value =
            valueMap[category].mon_sat_7am_7pm_leq5min;
        inputmonsat7pm10pmleq5.value =
            valueMap[category].mon_sat_7pm_10pm_leq5min;
        inputmonsat10pm12amleq5.value =
            valueMap[category].mon_sat_10pm_12am_leq5min;
        inputmonsat12am7amleq5.value =
            valueMap[category].mon_sat_12am_7am_leq5min;

        inputmonsat7am7pmleq12.value =
            valueMap[category].mon_sat_7am_7pm_leq12hr;
        inputmonsat7pm10pmleq12.value =
            valueMap[category].mon_sat_7pm_10pm_leq12hr;
        inputmonsat10pm12amleq12.value =
            valueMap[category].mon_sat_10pm_12am_leq12hr;
        inputmonsat12am7amleq12.value =
            valueMap[category].mon_sat_12am_7am_leq12hr;

        inputsunph7am7pmleq5.value = valueMap[category].sun_ph_7am_7pm_leq5min;
        inputsunph7pm10pmleq5.value =
            valueMap[category].sun_ph_7pm_10pm_leq5min;
        inputsunph10pm12amleq5.value =
            valueMap[category].sun_ph_10pm_12am_leq5min;
        inputsunph12am7amleq5.value =
            valueMap[category].sun_ph_12am_7am_leq5min;

        inputsunph7am7pmleq12.value = valueMap[category].sun_ph_7am_7pm_leq12hr;
        inputsunph7pm10pmleq12.value =
            valueMap[category].sun_ph_7pm_10pm_leq12hr;
        inputsunph10pm12amleq12.value =
            valueMap[category].sun_ph_10pm_12am_leq12hr;
        inputsunph12am7amleq12.value =
            valueMap[category].sun_ph_12am_7am_leq12hr;
    } else if (modalType == "update") {
        inputmonsat7am7pmleq5.value =
            inputMeasurementPoint.soundLimit.mon_sat_7am_7pm_leq5min;
        inputmonsat7pm10pmleq5.value =
            inputMeasurementPoint.soundLimit.mon_sat_7pm_10pm_leq5min;
        inputmonsat10pm12amleq5.value =
            inputMeasurementPoint.soundLimit.mon_sat_10pm_12am_leq5min;
        inputmonsat12am7amleq5.value =
            inputMeasurementPoint.soundLimit.mon_sat_12am_7am_leq5min;

        inputmonsat7am7pmleq12.value =
            inputMeasurementPoint.soundLimit.mon_sat_7am_7pm_leq12hr;
        inputmonsat7pm10pmleq12.value =
            inputMeasurementPoint.soundLimit.mon_sat_7pm_10pm_leq12hr;
        inputmonsat10pm12amleq12.value =
            inputMeasurementPoint.soundLimit.mon_sat_10pm_12am_leq12hr;
        inputmonsat12am7amleq12.value =
            inputMeasurementPoint.soundLimit.mon_sat_12am_7am_leq12hr;

        inputsunph7am7pmleq5.value =
            inputMeasurementPoint.soundLimit.sun_ph_7am_7pm_leq5min;
        inputsunph7pm10pmleq5.value =
            inputMeasurementPoint.soundLimit.sun_ph_7pm_10pm_leq5min;
        inputsunph10pm12amleq5.value =
            inputMeasurementPoint.soundLimit.sun_ph_10pm_12am_leq5min;
        inputsunph12am7amleq5.value =
            inputMeasurementPoint.soundLimit.sun_ph_12am_7am_leq5min;

        inputsunph7am7pmleq12.value =
            inputMeasurementPoint.soundLimit.sun_ph_7am_7pm_leq12hr;
        inputsunph7pm10pmleq12.value =
            inputMeasurementPoint.soundLimit.sun_ph_7pm_10pm_leq12hr;
        inputsunph10pm12amleq12.value =
            inputMeasurementPoint.soundLimit.sun_ph_10pm_12am_leq12hr;
        inputsunph12am7amleq12.value =
            inputMeasurementPoint.soundLimit.sun_ph_12am_7am_leq12hr;
    }
}

function create_empty_option(select, text) {
    var defaultOption = document.createElement("option");
    defaultOption.textContent = text;
    defaultOption.selected = true;
    defaultOption.disabled = true;
    select.appendChild(defaultOption);
}

function populateConcentrator() {
    console.log("called");
    var selectConcentrator;
    var defaultConcentrator;
    selectConcentrator = document.getElementById("selectConcentrator");
    selectConcentrator.innerHTML = "";
    if (modalType === "update") {
        defaultConcentrator = concentrator_data[0];
        document.getElementById("existing_device_id").textContent =
            defaultConcentrator.device_id
                ? `${defaultConcentrator.device_id} | ${defaultConcentrator.concentrator_label}`
                : "None Linked";
        if (!defaultConcentrator.device_id) {
            create_empty_option(selectConcentrator, "Choose Concentrator...");
        }
    } else {
        create_empty_option(selectConcentrator, "Choose Concentrator...");
    }

    const url = `${baseUri}/concentrators/`;
    fetch(url)
        .then((response) => {
            if (!response.ok) {
                throw new Error(
                    "Network response was not ok " + response.statusText
                );
            }
            return response.json();
        })
        .then((data) => {
            console.log(data);
            data = data.concentrators;

            // Create options from fetched data
            data.forEach((concentrator) => {
                const option = document.createElement("option");
                option.value = concentrator.id;
                option.textContent =
                    concentrator.device_id +
                    " | " +
                    concentrator.concentrator_label;

                if (
                    defaultConcentrator &&
                    concentrator.id == defaultConcentrator.concentrator_id
                ) {
                    option.selected = true;
                }
                selectConcentrator.appendChild(option);
            });
        })
        .catch((error) => {
            console.error("Error fetching data:", error);
        });
}

function populateNoiseMeter() {
    var selectNoiseMeter;
    var defaultNoiseMeter;
    selectNoiseMeter = document.getElementById("selectNoiseMeter");
    selectNoiseMeter.innerHTML = "";
    if (modalType == "update") {
        defaultNoiseMeter = noise_meter_data[0];
        document.getElementById("existing_serial").textContent =
            defaultNoiseMeter.serial_number
                ? `${defaultNoiseMeter.serial_number} | ${defaultNoiseMeter.noise_meter_label}`
                : "None linked";
        if (!defaultNoiseMeter.serial_number) {
            create_empty_option(selectNoiseMeter, "Choose Noise Meter...");
        }
    } else {
        create_empty_option(selectNoiseMeter, "Choose Noise Meter...");
    }

    const url = `${baseUri}/noise_meters`;
    fetch(url)
        .then((response) => {
            if (!response.ok) {
                throw new Error(
                    "Network response was not ok " + response.statusText
                );
            }
            return response.json();
        })
        .then((data) => {
            data = data.noise_meters;

            data.forEach((noise_meter) => {
                const option = document.createElement("option");
                option.value = noise_meter.id;
                option.textContent =
                    noise_meter.serial_number +
                    " | " +
                    noise_meter.noise_meter_label;
                if (
                    defaultNoiseMeter &&
                    noise_meter.id == defaultNoiseMeter.noise_meter_id
                ) {
                    option.selected = true;
                }

                selectNoiseMeter.appendChild(option);
            });
        })
        .catch((error) => {
            console.error("Error fetching data:", error);
        });
}

function populateSelects() {
    console.log("LAKSJD");
    populateConcentrator();
    populateNoiseMeter();
}

function set_contact_table() {
    var contactTable = new Tabulator("#contacts_table", {
        layout: "fitColumns",
        data: window.contacts,
        placeholder: "No linked Contacts",
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

function fetch_contact_data() {
    var inputName = document.getElementById("inputName");
    var inputDesignation = document.getElementById("inputDesignation");
    var inputEmail = document.getElementById("inputEmail");
    var inputPhoneNumber = document.getElementById("inputPhoneNumber");
    var inputContactProjectID = document.getElementById(
        "inputContactProjectID"
    );

    inputContactProjectID.value = inputprojectId;
    if (modalType == "create") {
        inputName.value = null;
        inputDesignation.value = null;
        inputEmail.value = null;
        inputPhoneNumber.value = null;
    } else if (modalType == "update") {
        inputName.value = window.selectedContact.contact_person_name;
        inputDesignation.value = window.selectedContact.designation;
        inputEmail.value = window.selectedContact.email;
        inputPhoneNumber.value = window.selectedContact.phone_number;
    }
}

function manage_measurement_point_columns() {
    if (window.admin) {
        return [
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

function set_measurement_point_table(measurementPoint_data) {
    document.getElementById("measurement_point_pages").innerHTML = "";
    var measurementPointTable = new Tabulator("#measurement_point_table", {
        layout: "fitColumns",
        data: measurementPoint_data,
        placeholder: "No Linked Measurement Points",
        paginationSize: 20,
        pagination: "local",
        paginationCounter: "rows",
        paginationElement: document.getElementById("measurement_point_pages"),
        selectable: 1,
        columns: manage_measurement_point_columns(),
    });
    measurementPointTable.on("rowClick", function (e, row) {
        window.location.href = "/measurement_point/" + row.getIndex();
    });
    measurementPointTable.on("rowSelectionChanged", function (data, rows) {
        table_row_changed(data);
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

function fetch_measurement_point_data(data = null) {
    noise_meter_data = [];
    concentrator_data = [];
    var pointName = document.getElementById("inputPointName");
    var remarks = document.getElementById("inputRemarks");
    var device_location = document.getElementById("inputDeviceLocation");
    var category = document.getElementById("category");
    document.getElementById("error_message").innerHTML = "";
    if (data) {
        pointName.value = data.point_name;
        remarks.value = data.remarks;
        device_location.value = data.device_location;
        category.innerHTML = data.category;

        concentrator_data.push({
            concentrator_id: data.concentrator_id,
            concentrator_label: data.concentrator_label,
            device_id: data.device_id,
        });

        noise_meter_data.push({
            noise_meter_id: data.noise_meter_id,
            noise_meter_label: data.noise_meter_label,
            serial_number: data.serial_number,
        });

        document.getElementById("existing_devices").hidden = false;
        document.getElementById("existing_category").hidden = false;
        document.getElementById("advanced_sound_limits").hidden = true;
    } else {
        pointName.value = null;
        remarks.value = null;
        device_location.value = null;
        category.innerHTML = null;

        concentrator_data.push({
            concentrator_id: null,
            concentrator_label: null,
            device_id: null,
        });

        noise_meter_data.push({
            noise_meter_id: null,
            noise_meter_label: null,
            serial_number: null,
        });
        document.getElementById("existing_devices").hidden = true;
        document.getElementById("existing_category").hidden = true;
        document.getElementById("advanced_sound_limits").hidden = true;
    }
}

function getProjectId() {
    inputprojectId = document.getElementById("inputprojectId").value;
}

function get_measurement_point_data() {
    fetch(`${baseUri}/measurement_points/${inputprojectId}`, {
        method: "get",
        headers: {
            "Content-type": "application/json; charset=UTF-8",
            "X-CSRF-TOKEN": document
                .querySelector('meta[name="csrf-token"]')
                .getAttribute("content"),
        },
    })
        .then((response) => {
            if (!response.ok) {
                return response.text().then((text) => {
                    throw new Error(text);
                });
            }
            return response.json();
        })
        .then((json) => {
            var measurementPoint_data = json.measurement_point;
            set_measurement_point_table(measurementPoint_data);
        })
        .catch((error) => {
            console.log(error);
        });
}

async function update_sound_limits(formDataJson) {
    var csrfToken = document
        .querySelector('meta[name="csrf-token"]')
        .getAttribute("content");
    return fetch(
        `${baseUri}/soundlimits/${inputMeasurementPoint.soundLimit.id}`,
        {
            method: "PATCH",
            headers: {
                "X-CSRF-TOKEN": csrfToken,
                "Content-Type": "application/json",
                Accept: "application/json",
                "X-Requested-With": "XMLHttpRequest",
            },
            body: JSON.stringify(formDataJson),
        }
    ).then((response) => {
        if (!response.ok) {
            throw new Error(
                "Network response was not ok " + response.statusText
            );
        }
        closeModal("measurementPointModal");
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
            closeModal("measurementPointModal");
        }
    });
}

async function handle_create_measurement_point(confirmation) {
    var csrfToken = document
        .querySelector('meta[name="csrf-token"]')
        .getAttribute("content");
    var form = document.getElementById("measurement_point_form");

    var formData = new FormData(form);

    var formDataJson = {};
    formData.forEach((value, key) => {
        formDataJson[key] = value;
    });

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
            if (response.status == 422) {
                response.json().then((errorData) => {
                    if (
                        errorData["Unprocessable Entity"]["concentrator"] ||
                        errorData["Unprocessable Entity"]["noise_meter"]
                    ) {
                        if (errorData["Unprocessable Entity"]["concentrator"]) {
                            message +=
                                errorData["Unprocessable Entity"][
                                    "concentrator"
                                ]["concentrator_label"] + "\t";
                        }
                        if (errorData["Unprocessable Entity"]["noise_meter"]) {
                            message +=
                                errorData["Unprocessable Entity"][
                                    "noise_meter"
                                ]["noise_meter_label"] + "\t";
                        }
                        document.getElementById("devicesSpan").innerHTML =
                            message;

                        openSecondModal(
                            "measurementPointModal",
                            "confirmationModal"
                        );
                    }
                    document.getElementById("error_message").innerHTML =
                        errorData["Unprocessable Entity"];
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

async function handle_measurement_point_update(confirmation) {
    var csrfToken = document
        .querySelector('meta[name="csrf-token"]')
        .getAttribute("content");
    var form = document.getElementById("measurement_point_form");

    var formData = new FormData(form);

    var formDataJson = {};
    formData.forEach((value, key) => {
        formDataJson[key] = value;
    });

    formDataJson["confirmation"] = confirmation;

    return fetch(`${baseUri}/measurement_points/${inputMeasurementPointId}`, {
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
                    if (
                        errorData["Unprocessable Entity"]["concentrator"] ||
                        errorData["Unprocessable Entity"]["noise_meter"]
                    ) {
                        var message = "";
                        if (errorData["Unprocessable Entity"]["concentrator"]) {
                            message +=
                                errorData["Unprocessable Entity"][
                                    "concentrator"
                                ]["concentrator_label"] + " | ";
                        }
                        if (errorData["Unprocessable Entity"]["noise_meter"]) {
                            message +=
                                errorData["Unprocessable Entity"][
                                    "noise_meter"
                                ]["noise_meter_label"] + " | ";
                        }
                        document.getElementById("devicesSpan").innerHTML =
                            message;
                        openSecondModal(
                            "measurementPointModal",
                            "confirmationModal"
                        );
                    }
                    document.getElementById("error_message").innerHTML =
                        errorData["Unprocessable Entity"];
                });
            } else {
                console.log("in fetch");
                update_sound_limits(formDataJson);
            }
        })
        .catch((error) => {
            console.error("Error:", error);
            alert("There was an error: " + error.message);
        });
}

function handleContactSubmit() {
    modalType == "create" ? handleCreateContact() : handleUpdateContact();
    location.reload();
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
    })
        .then((response) => {
            if (!response.ok) {
                throw new Error(
                    "Network response was not ok " + response.statusText
                );
            } else {
                response.json().then((json) => {
                    resetContactTable(json);
                    closeModal("contactModal");
                });
            }
        })
        .catch((error) => {
            console.error("Error:", error);
            alert("There was an error: " + error.message);
        });
    return false;
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
    })
        .then((response) => {
            if (!response.ok) {
                throw new Error(
                    "Network response was not ok " + response.statusText
                );
            } else {
                response.json().then((json) => {
                    resetContactTable(json);
                    closeModal("contactModal");
                });
            }
        })
        .catch((error) => {
            console.error("Error:", error);
            alert("There was an error: " + error.message);
        });
    return false;
}

async function handleMeasurementPointDelete(csrfToken) {
    return fetch(`${baseUri}/measurement_points/${inputMeasurementPointId}`, {
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
            closeModal("deleteConfirmationModal");
        })
        .catch((error) => {
            console.error("Error:", error);
        });
}

async function handleContactDelete(csrfToken) {
    return fetch(`${baseUri}/contacts/${window.selectedContactid}`, {
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
            resetContactTable(data);
            closeModal("deleteConfirmationModal");
        })
        .catch((error) => {
            console.error("Error:", error);
        });
}

async function handleDelete(event) {
    console.log("in here");
    try {
        if (event) {
            event.preventDefault();
        }
        var csrfToken = document
            .querySelector('meta[name="csrf-token"]')
            .getAttribute("content");

        var confirmation = document.getElementById(
            "inputDeleteConfirmation"
        ).value;
        console.log(confirmation);
        console.log("in here deep");
        if (confirmation == "DELETE") {
            if (window.deleteType == "measurementPoints") {
                await handleMeasurementPointDelete(csrfToken);
            } else if (window.deleteType == "contact") {
                await handleContactDelete(csrfToken);
            }
        } else {
            console.log("here");
            console.log(
                document
                    .getElementById("deleteConfirmationError")
                    .checkVisibility()
            );
            var error = document.getElementById("deleteConfirmationError");
            error.hidden = false;
            console.log(
                document
                    .getElementById("deleteConfirmationError")
                    .checkVisibility()
            );
        }
    } catch (error) {
        console.log(error);
    } finally {
        get_measurement_point_data();
    }
}

async function handle_measurementpoint_submit(confirmation = false) {
    try {
        if (modalType == "update") {
            await handle_measurement_point_update(confirmation);
        } else {
            await handle_create_measurement_point(confirmation);
        }
    } catch (error) {
        console.log(error);
    } finally {
        get_measurement_point_data();
    }
}

function openModal(modalName, type = null) {
    if (modalName == "measurementPointModal") {
        if (type == "create") {
            modalType = "create";
            fetch_measurement_point_data();
            populateSelects();
            populate_soundLimits(null);
        } else if (type == "update") {
            modalType = "update";
            fetch_measurement_point_data(inputMeasurementPoint);
            if (window.admin) {
                console.log(window.admin);
                populateSelects();
            }
            populate_soundLimits(null);
        }
    } else if (modalName == "contactModal") {
        if (type == "create") {
            modalType = "create";
        } else if ((type = "update")) {
            modalType = "update";
        }
        fetch_contact_data();
    } else if (modalName == "deleteConfirmationModal") {
        document.getElementById("deleteConfirmationError").hidden = true;
        type == "contact"
            ? (window.deleteType = "contact")
            : (window.deleteType = "measurementPoints");
    }

    var modal = new bootstrap.Modal(document.getElementById(modalName));
    modal.toggle();
}

function closeModal(modal) {
    // Close the modal
    const modalElement = document.getElementById(modal);
    const modalInstance = bootstrap.Modal.getInstance(modalElement);
    modalInstance.hide();
}

function check_contact_max() {
    if (window.contacts.length >= window.project.sms_count) {
        document.getElementById("createContactButton").disabled = true;
    }
}

async function handleConfirmationSubmit(event) {
    console.log("here");
    try {
        if (event) {
            event.preventDefault();
        }
        var csrfToken = document
            .querySelector('meta[name="csrf-token"]')
            .getAttribute("content");

        var confirmation = document.getElementById(
            "inputContinueConfirmation"
        ).value;
        if (confirmation == "YES") {
            await handle_measurementpoint_submit(true);
            location.reload();
        } else {
            var error = document.getElementById("confirmationError");
            error.hidden = false;
        }
    } catch (error) {
        console.log(error);
    } finally {
        get_measurement_point_data();
    }
}

function openSecondModal(initialModal, newModal) {
    if (newModal == "confirmationModal") {
        document.getElementById("confirmationError").hidden = true;
    }

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
                    firstModal.show();
                },
                { once: true }
            );
        },
        { once: true }
    );
}

function resetContactTable(json) {
    window.contacts = json.contacts;
    set_contact_table();
}

window.handle_measurement_point_update = handle_measurement_point_update;
window.handle_create_measurement_point = handle_create_measurement_point;
window.handleDelete = handleDelete;
window.openModal = openModal;
window.handle_measurementpoint_submit = handle_measurementpoint_submit;
window.populate_soundLimits = populate_soundLimits;
window.toggle_soundLimits = toggle_soundLimits;
window.handleContactSubmit = handleContactSubmit;
window.handleConfirmationSubmit = handleConfirmationSubmit;
getProjectId();
get_measurement_point_data();
set_contact_table();
check_contact_max();
