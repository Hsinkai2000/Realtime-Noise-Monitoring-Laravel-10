var baseUri = `${window.location.protocol}//${window.location.hostname}`;
if (window.location.port) {
    baseUri += `:${window.location.port}`;
}
var dpMin, dpMax;
var viewPdfModal = document.getElementById("viewPdfModal");
var measurementPointModal = document.getElementById("measurementPointModal");
var deleteConfirmationModal = document.getElementById(
    "deleteConfirmationModal"
);
var isSwitchingModal = false;
var inputprojectId = window.measurementPointData.project.id;
console.log("porjectid " + inputprojectId);

const localeEn = {
    days: [
        "Sunday",
        "Monday",
        "Tuesday",
        "Wednesday",
        "Thursday",
        "Friday",
        "Saturday",
    ],
    daysShort: ["Sun", "Mon", "Tue", "Wed", "Thu", "Fri", "Sat"],
    daysMin: ["Su", "Mo", "Tu", "We", "Th", "Fr", "Sa"],
    months: [
        "January",
        "February",
        "March",
        "April",
        "May",
        "June",
        "July",
        "August",
        "September",
        "October",
        "November",
        "December",
    ],
    monthsShort: [
        "Jan",
        "Feb",
        "Mar",
        "Apr",
        "May",
        "Jun",
        "Jul",
        "Aug",
        "Sep",
        "Oct",
        "Nov",
        "Dec",
    ],
    today: "Today",
    clear: "Clear",
    dateFormat: "MM/dd/yyyy",
    timeFormat: "hh:mm aa",
    firstDay: 0,
};

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

viewPdfModal.addEventListener("hidden.bs.modal", function (event) {
    document.getElementById("error-messages-pdf").hidden = true;
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
        populateSelects();
        toggle_soundLimits();
    }
});

function populateSelects() {
    if (window.admin) {
        populateConcentrator();
        populateNoiseMeter();
    }
}

function populateConcentrator() {
    console.log("called");
    var selectConcentrator;
    var defaultConcentrator;
    selectConcentrator = document.getElementById("selectConcentrator");
    selectConcentrator.innerHTML = "";
    defaultConcentrator = window.concentrator;

    create_empty_option(selectConcentrator, "Choose Concentrator...");

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
                    concentrator.id == defaultConcentrator.id
                ) {
                    console.log("same");
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

    defaultNoiseMeter = window.noise_meter;

    create_empty_option(selectNoiseMeter, "Choose Noise Meter...");

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
                    noise_meter.id == defaultNoiseMeter.id
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

function create_empty_option(select, text) {
    var defaultOption = document.createElement("option");
    defaultOption.textContent = text;
    defaultOption.selected = true;
    defaultOption.disabled = true;
    select.appendChild(defaultOption);
}

function set_tables() {
    var noise_meter_table = new Tabulator("#noise_meter_table", {
        layout: "fitColumns",
        data: new Array(window.noise_meter),
        placeholder: "No linked noise meter",
        columns: [
            {
                title: "Serial Number",
                field: "serial_number",
                headerSort: false,
                minWidth: 100,
            },
            {
                title: "Label",
                field: "noise_meter_label",
                headerSort: false,
                minWidth: 100,
            },
            {
                title: "Brand",
                field: "brand",
                headerSort: false,
                minWidth: 100,
            },
            {
                title: "Last Calibration Date",
                field: "last_calibration_date",
                headerSort: false,
                minWidth: 100,
            },
            {
                title: "Remarks",
                field: "remarks",
                headerSort: false,
                minWidth: 100,
            },
        ],
    });

    var concentrator_table = new Tabulator("#concentrator_table", {
        layout: "fitColumns",
        data: new Array(window.concentrator),
        placeholder: "No linked concentrator",
        columns: [
            {
                title: "Device ID",
                field: "device_id",
                headerSort: false,
                minWidth: 100,
            },
            {
                title: "Label",
                field: "concentrator_label",
                headerSort: false,
                minWidth: 100,
            },
            {
                title: "CSQ",
                field: "concentrator_csq",
                headerSort: false,
                minWidth: 100,
            },
            {
                title: "Handphone Number",
                field: "concentrator_hp",
                headerSort: false,
                minWidth: 100,
            },
            {
                title: "Battery Voltage",
                field: "battery_voltage",
                headerSort: false,
                minWidth: 100,
            },
            {
                title: "Last Communication Packet Sent",
                field: "last_communication_packet_sent",
                headerSort: false,
                minWidth: 100,
            },
            {
                title: "Remarks",
                field: "remarks",
                headerSort: false,
                minWidth: 100,
            },
        ],
    });
}

function openModal(modalName, type) {
    var modal = new bootstrap.Modal(document.getElementById(modalName));
    modal.toggle();

    if (modalName == "viewPdfModal") {
        initDatePicker();
    }
}

function closeModal(modal) {
    // Close the modal
    const modalElement = document.getElementById(modal);
    const modalInstance = bootstrap.Modal.getInstance(modalElement);
    modalInstance.hide();
    location.reload();
}

function initDatePicker() {
    document.getElementById("start_date").value = formatDate(new Date());
    document.getElementById("end_date").value = formatDate(new Date());
    dpMin = new AirDatepicker("#start_date", {
        autoClose: true,
        dateFormat: "dd-MM-yyyy",
        container: "#viewPdfModal",
        locale: localeEn,
        onSelect({ date }) {
            dpMax.update({
                minDate: date,
            });
            dpMax.show();
        },
    });

    dpMax = new AirDatepicker("#end_date", {
        autoClose: true,
        dateFormat: "dd-MM-yyyy",
        container: "#viewPdfModal",
        locale: localeEn,
        onSelect({ date }) {
            dpMin.update({
                maxDate: date,
            });
        },
    });
}

function formatDate(date) {
    const day = String(date.getDate()).padStart(2, "0");
    const month = String(date.getMonth() + 1).padStart(2, "0"); // Month is 0-indexed
    const year = date.getFullYear();
    return `${day}-${month}-${year}`;
}

async function openPdf() {
    var select_start_date = document.getElementById("start_date").value;
    var select_end_date = document.getElementById("end_date").value;

    if (select_start_date <= select_end_date) {
        const newTab = window.open(
            `${baseUri}/pdf/${new URLSearchParams({
                id: window.measurementPointData.id,
                start_date: select_start_date,
                end_date: select_end_date,
            }).toString()}`,
            "Report"
        );
        newTab.focus();
        closeModal("viewPdfModal");
    } else {
        document.getElementById("error-messages-pdf").hidden = false;
        return false;
    }
}

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
    var url = `${baseUri}/measurement_points/${window.measurementPointData.id}`;

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
            console.log(response.error);
        } else {
            window.location.href = `/project/${inputprojectId}`;
        }
    });

    return true;
}

async function handle_measurementpoint_submit(confirmation = false) {
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

    return fetch(
        `${baseUri}/measurement_points/${window.measurementPointData.id}`,
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
    )
        .then((response) => {
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
                    return await update_sound_limits(formDataJson);
                });
            }
        })
        .catch((error) => {
            console.error("Error:", error);
            alert("There was an error: " + error.message);
        });
}

async function update_sound_limits(formDataJson) {
    var csrfToken = document
        .querySelector('meta[name="csrf-token"]')
        .getAttribute("content");
    return fetch(`${baseUri}/soundlimits/${window.soundLimit.id}`, {
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
            response.json().then((errorData) => {
                document.getElementById("error_message").innerHTML =
                    errorData["Unprocessable Entity"];
            });
        } else {
            window.location.reload();
        }
    });
}

async function handleConfirmationSubmit(event) {
    console.log("here");
    try {
        if (event) {
            event.preventDefault();
        }

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

window.openPdf = openPdf;
window.openModal = openModal;
window.set_tables = set_tables;
window.toggle_soundLimits = toggle_soundLimits;
window.handleDelete = handleDelete;
window.handle_measurementpoint_submit = handle_measurementpoint_submit;

set_tables();
populateSelects();
