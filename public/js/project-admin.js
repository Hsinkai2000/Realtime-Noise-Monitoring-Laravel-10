// var baseUri = `${window.location.protocol}//${window.location.hostname}`;
// if (window.location.port) {
//     baseUri += `:${window.location.port}`;
// }
// var tabledata = null;
// var userList = [];
// var inputprojectId = null;
// var modalType = "";
// var inputUserId = null;
// var tab = "rental";
// var selectedProject = [];

// function settable(tabledata) {
//     if (window.table) {
//         window.table.destroy();
//     }
//     if (tab == "rental") {
//         console.log("in rental");
//         var table = new Tabulator("#example-table", {
//             pagination: "local",
//             layout: "fitColumns",
//             data: tabledata,
//             placeholder: "Not authorised",
//             paginationSize: 10,
//             paginationCounter: "rows",
//             selectable: 1,
//             responsiveLayout: "collapse",
//             columns: [
//                 {
//                     formatter: "responsiveCollapse",
//                     width: 30,
//                     minWidth: 30,
//                     hozAlign: "center",
//                     resizable: false,
//                     headerSort: false,
//                 },
//                 {
//                     title: "PJO Number",
//                     field: "job_number",
//                     headerFilter: "input",
//                     minWidth: 150,
//                     frozen: true,
//                 },
//                 {
//                     title: "Client Name",
//                     field: "client_name",
//                     minWidth: 150,
//                     headerFilter: "input",
//                 },
//                 {
//                     title: "Jobsite Location",
//                     field: "jobsite_location",
//                     minWidth: 150,
//                     headerFilter: "input",
//                 },
//                 {
//                     title: "Project Description",
//                     field: "project_description",
//                     minWidth: window.innerWidth * 0.25,
//                     headerSort: false,
//                 },
//                 {
//                     title: "BCA Reference Number",
//                     field: "bca_reference_number",
//                     minWidth: 150,
//                     headerSort: false,
//                 },
//                 {
//                     title: "SMS Contacts (Number of alerts)",
//                     field: "sms_count",
//                     minWidth: 150,
//                     headerSort: false,
//                 },
//                 // {
//                 //     title: "Status (Ongoing/Completed)",
//                 //     field: "status",
//                 //     editor: "list",
//                 //     editorParams: {
//                 //         values: ["", "Ongoing", "Completed"],
//                 //         clearable: true,
//                 //     },
//                 //     minWidth: 150,
//                 //     headerFilter: true,
//                 //     headerFilterParams: {
//                 //         values: {
//                 //             "": "select...",
//                 //             Ongoing: "Ongoing",
//                 //             Completed: "Completed",
//                 //         },
//                 //         clearable: true,
//                 //     },
//                 // },
//                 {
//                     title: "Created At",
//                     field: "created_at",
//                     minWidth: 150,
//                 },
//             ],
//         });
//     } else {
//         var table = new Tabulator("#example-table", {
//             pagination: "local",
//             data: tabledata,
//             layout: "fitColumns",
//             placeholder: "Not authorised",
//             paginationSize: 10,
//             paginationCounter: "rows",
//             dataTree: true,
//             dataTreeStartExpanded: true,
//             selectable: 1,
//             responsiveLayout: "collapse",
//             columns: [
//                 {
//                     formatter: "responsiveCollapse",
//                     width: 30,
//                     minWidth: 30,
//                     hozAlign: "center",
//                     resizable: false,
//                     headerSort: false,
//                 },
//                 {
//                     title: "Name",
//                     field: "name",
//                     headerFilter: "input",
//                     minWidth: 150,
//                     frozen: true,
//                     responsive: 0,
//                 },
//                 {
//                     title: "Jobsite Location",
//                     field: "jobsite_location",
//                     minWidth: 150,
//                     headerFilter: "input",
//                 },
//                 {
//                     title: "Project Description",
//                     field: "project_description",
//                     minWidth: window.innerWidth * 0.3,
//                     headerSort: false,
//                 },
//                 {
//                     title: "BCA Reference Number",
//                     field: "bca_reference_number",
//                     headerSort: false,
//                     minWidth: 150,
//                 },
//                 {
//                     title: "Created At",
//                     field: "created_at",
//                     minWidth: 150,
//                 },
//             ],
//             rowFormatter: function (row) {
//                 // Apply special formatting for parent rows
//                 if (row.getTreeChildren().length) {
//                     row.getElement().style.pointerEvents = "none";
//                 }
//             },
//         });
//     }
//     table.on("rowClick", function (e, row) {
//         window.location.href = "/project/" + row.getIndex();
//     });
//     table.on("rowSelectionChanged", function (data, rows) {
//         selectedProject = data[0];
//         // table_row_changed(selectedProject);
//     });
//     window.table = table;
// }

// // function table_row_changed(data) {
// //     const editButton = document.getElementById("editButton");
// //     const deleteButton = document.getElementById("deleteButton");

// //     if (data) {
// //         editButton.disabled = false;
// //         deleteButton.disabled = false;
// //         inputprojectId = data.id;
// //     } else {
// //         editButton.disabled = true;
// //         deleteButton.disabled = true;
// //     }
// // }

// function changeTab(event, project_type) {
//     // Toggle active class on tabs
//     document.querySelectorAll(".nav-link").forEach((tab) => {
//         tab.classList.remove("active");
//     });

//     event.currentTarget.classList.add("active");
//     console.log(window.sales_projects);
//     switch (project_type) {
//         case "rental":
//             tab = "rental";
//             settable(window.rental_projects);
//             break;

//         default:
//             tab = "sales";
//             settable(window.sales_projects);
//             break;
//     }
// }

// function toggleEndUserName() {
//     var rentalRadio = document.getElementById("projectTypeRental");
//     var endUserNameDiv = document.getElementById("endUserNameDiv");
//     if (rentalRadio.checked) {
//         endUserNameDiv.style.display = "none";
//     } else {
//         endUserNameDiv.style.display = "flex";
//     }
// }

// function fetch_project_data(data = null) {
//     var updatejobNumber = document.getElementById("inputJobNumber");
//     var clientName = document.getElementById("inputClientName");
//     var projectDescription = document.getElementById("inputProjectDescription");
//     var jobsiteLocation = document.getElementById("inputJobsiteLocation");
//     var bcaReferenceNumber = document.getElementById("inputBcaReferenceNumber");
//     var sms_count = document.getElementById("inputSmsCount");
//     var projectTypeRental = document.getElementById("projectTypeRental");
//     var projectTypeSales = document.getElementById("projectTypeSales");
//     var endUserName = document.getElementById("inputEndUserName");
//     var endUserNameDiv = document.getElementById("endUserNameDiv");

//     if (data != null) {
//         updatejobNumber.value = data.job_number;
//         clientName.value = data.client_name;
//         projectDescription.value = data.project_description;
//         jobsiteLocation.value = data.jobsite_location;
//         bcaReferenceNumber.value = data.bca_reference_number;
//         sms_count.value = data.sms_count;
//         if (data.end_user_name) {
//             projectTypeSales.checked = true;
//             endUserNameDiv.style.display = "block"; // Show the end user name field
//             endUserName.value = data.end_user_name;
//         } else {
//             projectTypeRental.checked = true;
//             endUserNameDiv.style.display = "none"; // Hide the end user name field
//         }
//         populateUser("userselectList", data.id);
//     } else {
//         updatejobNumber.value = null;
//         clientName.value = null;
//         projectDescription.value = null;
//         jobsiteLocation.value = null;
//         bcaReferenceNumber.value = null;
//         sms_count.value = 0;
//         populateUser("userselectList");
//     }
// }

// function handleDelete(event) {
//     if (event) {
//         event.preventDefault();
//     }
//     var csrfToken = document
//         .querySelector('meta[name="csrf-token"]')
//         .getAttribute("content");
//     var confirmation = document.getElementById("inputDeleteConfirmation").value;

//     if (confirmation == "DELETE") {
//         fetch(`${baseUri}/project/${inputprojectId}`, {
//             method: "DELETE",
//             headers: {
//                 "X-CSRF-TOKEN": csrfToken,
//                 Accept: "application/json",
//                 "X-Requested-With": "XMLHttpRequest",
//             },
//         })
//             .then((response) => {
//                 if (!response.ok) {
//                     throw new Error("Network response was not ok");
//                 }
//                 return response.json();
//             })
//             .then((data) => {
//                 resetTable(data);
//                 closeModal("deleteConfirmationModal");
//             })
//             .catch((error) => {
//                 console.error("Error:", error);
//             });
//     } else {
//         var error = document.getElementById("deleteConfirmationError");
//         error.hidden = false;
//     }
// }

// function deleteUser(event) {
//     if (event) {
//         event.preventDefault();
//     }
//     var csrfToken = document
//         .querySelector('meta[name="csrf-token"]')
//         .getAttribute("content");

//     fetch(`${baseUri}/users/${inputUserId}`, {
//         method: "DELETE",
//         headers: {
//             "X-CSRF-TOKEN": csrfToken,
//             Accept: "application/json",
//             "X-Requested-With": "XMLHttpRequest",
//         },
//     })
//         .then((response) => {
//             if (!response.ok) {
//                 throw new Error("Network response was not ok");
//             }
//             return response.json();
//         })
//         .then((data) => {
//             populateUser("userselectList", inputprojectId);
//             closeModal("deleteModal");
//         })
//         .catch((error) => {
//             console.error("Error:", error);
//         });
// }

// function handle_create_dummy_user() {
//     var inputUsername = document.getElementById("inputUsername").value;
//     var inputPassword = document.getElementById("inputPassword").value;
//     userList.push({
//         username: inputUsername,
//         password: inputPassword,
//     });
//     if (modalType == "update") {
//         populateUser("userselectList", inputprojectId);
//     } else {
//         populateUser("userselectList");
//     }

//     closeModal("userCreateModal");
// }

// function populateUser(element, project_id = null) {
//     window.userselectList = document.getElementById(element);
//     if (project_id) {
//         fetch(`${baseUri}/users/${project_id}`)
//             .then((response) => {
//                 if (!response.ok) {
//                     throw new Error("Network response was not ok");
//                 }
//                 return response.json();
//             })
//             .then((data) => {
//                 populateList(data.users);
//             })
//             .catch((error) => {
//                 console.error("Error fetching data:", error);
//             });
//     } else {
//         populateList([]);
//     }
// }

// function handleSelection(item) {
//     inputUserId = item.id;
// }

// function populateList(data) {
//     window.userselectList.innerHTML = "";

//     let selectedListItem = null;
//     if (userList != []) {
//         userList.forEach((user) => {
//             data.push(user);
//         });
//     }

//     data.forEach((item) => {
//         const listItem = document.createElement("div");
//         listItem.textContent = item.username;
//         listItem.className = "list-item";

//         listItem.addEventListener("click", function () {
//             handleSelection(item);

//             if (selectedListItem) {
//                 selectedListItem.classList.remove("selected");
//             }

//             listItem.classList.add("selected");

//             selectedListItem = listItem;
//         });

//         window.userselectList.appendChild(listItem);
//     });
// }

// function create_users(projectId, csrfToken) {
//     userList.forEach((user) => {
//         user.project_id = projectId;
//         fetch(`${baseUri}/user/`, {
//             method: "POST",
//             headers: {
//                 "X-CSRF-TOKEN": csrfToken,
//                 Accept: "application/json",
//                 "X-Requested-With": "XMLHttpRequest",
//             },
//             body: JSON.stringify(user),
//         }).then((response) => {});
//     });
//     return true;
// }

// function handleCreate() {
//     const form = document.getElementById("projectForm");
//     const csrfToken = document.querySelector('input[name="_token"]').value;
//     const formData = new FormData(form);

//     fetch(`${baseUri}/project`, {
//         method: form.method,
//         headers: {
//             "X-CSRF-TOKEN": csrfToken,
//             Accept: "application/json",
//             "X-Requested-With": "XMLHttpRequest",
//         },
//         body: formData,
//     })
//         .then((response) => {
//             if (response.status == 422) {
//                 response.json().then((errorData) => {
//                     document.getElementById("error_message").innerHTML =
//                         errorData["Unprocessable Entity"];
//                 });
//             }
//             return response.json();
//         })
//         .then((json) => {
//             console.log(json);
//             create_users(json.project_id, csrfToken);
//             console.log(json);
//             resetTable(json);
//             closeModal("projectModal");
//         });
// }

// function handleUpdate() {
//     var csrfToken = document
//         .querySelector('meta[name="csrf-token"]')
//         .getAttribute("content");
//     var form = document.getElementById("projectForm");

//     var formData = new FormData(form);

//     var formDataJson = {};
//     formData.forEach((value, key) => {
//         formDataJson[key] = value;
//     });

//     fetch(`${baseUri}/project/${inputprojectId}`, {
//         method: "PATCH",
//         headers: {
//             "X-CSRF-TOKEN": csrfToken,
//             "Content-Type": "application/json",
//             Accept: "application/json",
//             "X-Requested-With": "XMLHttpRequest",
//         },
//         body: JSON.stringify(formDataJson),
//     })
//         .then((response) => {
//             if (!response.ok) {
//                 throw new Error(
//                     "Network response was not ok " + response.statusText
//                 );
//             }
//             return response.json();
//         })
//         .then((json) => {
//             console.log(json);
//             create_users(inputprojectId, csrfToken);
//             console.log(json);
//             resetTable(json);
//             closeModal("projectModal");
//         })
//         .catch((error) => {
//             console.error("Error:", error);
//             alert("There was an error: " + error.message);
//         });

//     return false;
// }

// function submitClicked() {
//     if (modalType == "update") {
//         handleUpdate();
//     } else {
//         handleCreate();
//     }
// }

// function resetTable(json) {
//     window.rental_projects = json.rental_projects;
//     window.sales_projects = json.sales_projects;
//     switch (tab) {
//         case "rental":
//             console.log("here");
//             settable(window.rental_projects);
//             break;

//         default:
//             settable(window.sales_projects);
//             break;
//     }
// }

// function openModal(modalName, type) {
//     if (modalName == "deleteConfirmationModal") {
//         document.getElementById("deleteConfirmationError").hidden = true;
//     }
//     var modal = new bootstrap.Modal(document.getElementById(modalName));
//     modal.toggle();
//     document.getElementById("error_message").innerHTML = "";
//     document.getElementById("inputEndUserName").value = "";
//     document.getElementById("projectTypeRental").checked = true;
//     toggleEndUserName();

//     if (type == "update") {
//         userList = [];
//         modalType = "update";
//         document.getElementById("projectcreateLabel").innerText =
//             "Edit Project";
//         fetch_project_data(selectedProject);
//     } else if (type == "create") {
//         userList = [];
//         modalType = "create";
//         document.getElementById("projectcreateLabel").innerText =
//             "Create Project";
//         fetch_project_data();
//     }
// }

// function openSecondModal(initialModal, newModal) {
//     var firstModalEl = document.getElementById(initialModal);
//     var firstModal = bootstrap.Modal.getInstance(firstModalEl);

//     firstModal.hide();

//     firstModalEl.addEventListener(
//         "hidden.bs.modal",
//         function () {
//             var secondModal = new bootstrap.Modal(
//                 document.getElementById(newModal)
//             );

//             if (newModal == "userCreateModal") {
//                 document.getElementById("inputUsername").value = "";
//                 document.getElementById("inputPassword").value = "";
//             }
//             secondModal.show();

//             document.getElementById(newModal).addEventListener(
//                 "hidden.bs.modal",
//                 function () {
//                     firstModal.show();
//                 },
//                 { once: true }
//             );
//         },
//         { once: true }
//     );
// }

// function closeModal(modal) {
//     // Close the modal
//     const modalElement = document.getElementById(modal);
//     const modalInstance = bootstrap.Modal.getInstance(modalElement);
//     modalInstance.hide();
// }

// settable(window.rental_projects);
// toggleEndUserName();

// window.deleteUser = deleteUser;
// window.openModal = openModal;
// window.openSecondModal = openSecondModal;
// window.toggleEndUserName = toggleEndUserName;
// window.submitClicked = submitClicked;
// // window.handleDelete = handleDelete;
