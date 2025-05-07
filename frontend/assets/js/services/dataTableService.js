let dataTableService = {
    loadAppointments: function (patientId) {
        $('#appointmentsTable').DataTable({
            ajax: {
                url: `../backend/appointments/patient/${patientId}`,
                dataSrc: ""
            },
            columns: [
                { data: "appointment_id" },
                { data: "date" },
                { data: "time" },
                { data: "title"},
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
                url: `../backend/appointments/free`,
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

    loadPatients: function (doctorId, status) {
        $('#patientsTable').DataTable({
            ajax: {
                url: `../backend/appointments/doctor/${doctorId}/${status}`,
                dataSrc: ""
            },
            columns: [
                { data: "appointment_id" },
                {
                    data: null,
                    render: function (data, type, row) {
                        return row.first_name + " " + row.last_name;
                    }
                },
                { data: "date" },
                { data: "time" },
                { data: "title" },
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
    loadMedicalHistory: function (patientId) {
        $('#medicalHistoryTable').DataTable({
            ajax: {
                url: `../backend/medical-history/patient/${patientId}`,
                dataSrc: ''
            },
            columns: [
                { data: 'id' },
                { data: 'appointment_date' }, 
                { data: 'appointment_time' },
                {
                    data: 'doctor_first_name',
                    render: function (data, type, row) {
                        if (data && row.doctor_last_name) {
                            return `${data} ${row.doctor_last_name}`;
                        }
                        return 'N/A';
                    }
                },
                { data: 'service_title' },
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
    $.get(`../backend/medical-history/${id}`, function (data) {
        $('#prognosisDetails').text(data.prognosis);
        $('#diagnosisDetails').text(data.diagnosis);
        $('#prescriptionDetails').text(data.prescription);
        modalService.openModal('viewDetailsModal');
    });
}

function openReviewModal(id) {
    $('#addReviewModal').data('recordId', id);
    modalService.openModal('addReviewModal');
}
