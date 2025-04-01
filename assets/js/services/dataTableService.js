let dataTableService = {
    loadAppointments: function () {
        $('#appointmentsTable').DataTable({
            ajax: {
                url: "./assets/data/appointments.json",
                dataSrc: ""
            },
            columns: [
                { data: "id" },
                { data: "date" },
                { data: "time" },
                { data: "doctor" },
                {
                    data: null,
                    render: function () {
                        return `<button class="btn btn-danger btn-sm" onclick="modalService.openModal('cancelAppointmentModalPatient')">Cancel</button>`;
                    }
                }
            ],
            responsive: true,
            paging: true,
            searching: true,
            ordering: true
        });
    },

 
    loadAvailableAppointments: function () {
        $('#availableAppointmentsTable').DataTable({
            ajax: {
                url: "./assets/data/availableAppointments.json",
                dataSrc: ""
            },
            columns: [
                { data: "date" },
                { data: "time" },
                { data: "doctor" },
                {
                    data: null,
                    render: function () {
                        return `<button class="btn btn-success btn-sm" id="bookModalButton" data-bs-toggle="modal" data-bs-target="#serviceSelectionModal">Book</button>`;
                    }
                }
            ],
            responsive: true,
            paging: true,
            searching: true,
            ordering: true
        });
    },

    loadPatients: function () {
        $('#patientsTable').DataTable({
            ajax: {
                url: "./assets/data/doctorAppointments.json",
                dataSrc: ""
            },
            columns: [
                { data: "id" },
                { data: "name" },
                { data: "date" },
                { data: "time" },
                {
                    data: null,
                    render: function (data, type, row) {
                        return `
                            <button class="btn btn-primary edit-treatment-button" data-patient-id="${row.id}" onclick="doctorAppointmentService.openEditTreatmentModal()">Edit Treatment</button>
                            <button class="btn btn-danger cancel-appointment-button" data-patient-id="${row.id}" onclick="modalService.openModal('cancelAppointmentModal')">Cancel Appointment</button>
                        `;
                    }
                }
            ],
            responsive: true,
            paging: true,
            searching: true,
            ordering: true
        });
    }
};

let medicalHistory = {
    loadMedicalHistory: function () {
        $('#medicalHistoryTable').DataTable({
            ajax: {
                url: './assets/data/medicalHistory.json',
                dataSrc: ''
            },
            columns: [
                { data: 'id' },
                { data: 'date' },
                { data: 'time' },
                { data: 'doctor' },
                { data: 'serviceDone' },
                {
                    data: null,
                    render: function (data, type, row) {
                        return `<button class="btn btn-warning btn-sm" onclick="viewDetails(${row.id})">View Details</button>`;
                    }
                },
                {
                    data: null,
                    render: function (data, type, row) {
                        return `<button class="btn btn-primary btn-sm" onclick="openReviewModal(${row.id})">Add Review</button>`;
                    }
                }
            ]
        });
    }
};

function viewDetails(id) {
    $.get(`./assets/data/medicalHistory.json/`, function (data) {
        $('#diagnosisDetails').text(data.diagnosis);
        $('#prognosisDetails').text(data.prognosis);
        $('#notesDetails').text(data.notes);
        modalService.openModal('viewDetailsModal');
    });
}

function openReviewModal(id) {
    $('#addReviewModal').data('recordId', id);
    modalService.openModal('addReviewModal');
}