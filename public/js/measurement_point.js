import AirDatepicker from "air-datepicker";
import localeEn from "air-datepicker/locale/en.js";
import "air-datepicker/air-datepicker.css";

var baseUri = `${window.location.protocol}//${window.location.hostname}`;
if (window.location.port) {
    baseUri += `:${window.location.port}`;
}
var dpMin, dpMax;

function set_tables(data) {
    var noise_meter_table = new Tabulator("#noise_meter_table", {
        layout: "fitColumns",
        data: new Array(data.noise_meter),
        placeholder: "No linked Contacts",
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
        data: new Array(data.concentrator),
        placeholder: "No linked Contacts",
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
    document.getElementById("start_date").value = null;
    document.getElementById("end_date").value = null;
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

async function openPdf() {
    var select_start_date = document.getElementById("start_date");
    var select_end_date = document.getElementById("end_date");

    const newTab = window.open(
        `${baseUri}/pdf/${new URLSearchParams({
            id: window.measurementPointData.id,
            start_date: select_start_date.value,
            end_date: select_end_date.value,
        }).toString()}`,
        "Report"
    );
    newTab.focus();
    closeModal("viewPdfModal");
}

window.openPdf = openPdf;
window.openModal = openModal;
window.set_tables = set_tables;
set_tables(window.measurementPointData);
