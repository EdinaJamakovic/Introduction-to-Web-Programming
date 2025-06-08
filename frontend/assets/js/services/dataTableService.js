let dataTableService = {
    loadAppointments: function() {
       const token = localStorage.getItem('user_token');
        if (!token) return;

        try {
            const decodedToken = Utils.parseJwt(token);
            const patientId = decodedToken.user.id;

            $('#appointmentsTable').DataTable({
                ajax: {
                    url: Constants.PROJECT_BASE_URL + `appointments/patient/${patientId}`,
                    dataSrc: "",
                    beforeSend: function(xhr) {
                        xhr.setRequestHeader("Authentication", token);
                    }
                },
                columns: [
                    { data: "appointment_id" },
                    { data: "date" },
                    { data: "time" },
                    { data: "title" },
                    { 
                        data: "doctor",
                        render: function(data, type, row) {
                            return data ? data : 'N/A';
                        }
                    },
                    { data: "status" },
                    {
                        data: null,
                        render: function(data, type, row) {
                            let buttons = '';
                            if (row.status === 'scheduled' || row.status === 'confirmed') {
                                buttons += `<button class="btn btn-danger btn-sm" 
                                           onclick="appointmentService.cancelAppointment(${row.appointment_id})">
                                           Cancel</button>`;
                            }
                            return buttons;
                        }
                    }
                ],
                responsive: true,
                paging: true,
                searching: true,
                ordering: true
            });
        } catch (e) {
            console.error('Token parsing error:', e);
        }
    },

    loadAvailableAppointments: function () {
        $.ajax({
            url: Constants.PROJECT_BASE_URL + "appointments/free",
            type: "GET",
            contentType: "application/json",
            dataType: "json",
            beforeSend: function(xhr) {
                const token = localStorage.getItem("user_token");
                if (token) {
                    xhr.setRequestHeader("Authentication", token);
                }   
            },
            success: function(result) {
                console.log("API Response:", result);
                
                if (!result || !result.data || !Array.isArray(result.data)) {
                    console.error("Invalid data format received");
                    toastr.error("Invalid appointments data received");
                    return;
                }

                $('#availableAppointmentsTable').empty();
                
                if ($.fn.DataTable.isDataTable('#availableAppointmentsTable')) {
                    $('#availableAppointmentsTable').DataTable().destroy();
                    $('#availableAppointmentsTable').empty();
                }

                try {
                    $('#availableAppointmentsTable').DataTable({
                        data: result.data,
                        columns: [
                            { 
                                data: "date",
                                render: function(data) {
                                    return data || 'N/A';
                                }
                            },
                            { 
                                data: "time",
                                render: function(data) {
                                    return data || 'N/A';
                                }
                            },
                            { 
                                data: "doctor",
                                render: function(data) {
                                    return data || 'N/A';
                                }
                            },
                            {
                                data: null,
                                render: function(data, type, row) {
                                    return `<button class="btn btn-success btn-sm book-btn" 
                                            data-appointment-id="${row.id}"
                                            data-bs-toggle="modal" 
                                            data-bs-target="#serviceSelectionModal">
                                            Book
                                            </button>`;
                                }
                            }
                        ],
                        responsive: true,
                        paging: true,
                        searching: true,
                        ordering: true,
                        initComplete: function() {
                            console.log("DataTable initialized", this.api().rows().data());
                        }
                    });
                } catch (e) {
                    console.error("DataTable error:", e);
                    toastr.error("Failed to initialize appointments table");
                }
            },
            error: function(xhr) {
                console.error("AJAX Error:", xhr.responseText);
                toastr.error("Failed to load appointments");
            }
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
}

let medicalHistory = {
    loadMedicalHistory: function() {
        const token = localStorage.getItem('user_token');
        if (!token) return;

        try {
            const decodedToken = Utils.parseJwt(token);
            const patientId = decodedToken.user.id;

            $('#medicalHistoryTable').DataTable({
                ajax: {
                    url: Constants.PROJECT_BASE_URL + `medical-history/patient/${patientId}`,
                    dataSrc: '',
                    beforeSend: function(xhr) {
                        xhr.setRequestHeader("Authentication", token);
                    }
                },
                columns: [
                    { data: 'id' },
                    { data: 'appointment_date' },
                    { 
                        data: 'doctor_first_name',
                        render: function(data, type, row) {
                            if (data && row.doctor_last_name) {
                                return `${data} ${row.doctor_last_name}`;
                            }
                            return 'N/A';
                        }
                    },
                    { data: 'service_title' },
                    {
                        data: null,
                        render: function(data, type, row) {
                            return `<button class="btn btn-warning btn-sm" 
                                    onclick="medicalHistory.viewDetails(${row.id})">
                                    View Details</button>`;
                        }
                    },
                    {
                        data: null,
                        render: function(data, type, row) {
                            return `<button class="btn btn-primary btn-sm" 
                                    onclick="medicalHistory.openReviewModal(${row.id}, ${row.doctor_id})">
                                    Add Review</button>`;
                        }
                    }
                ]
            });
        } catch (e) {
            console.error('Token parsing error:', e);
        }
    },

    viewDetails: function(recordId) {
        const token = localStorage.getItem('user_token');
        if (!token) return;

        $.ajax({
            url: Constants.PROJECT_BASE_URL + `medical-history/${recordId}`,
            type: 'GET',
            beforeSend: function(xhr) {
                xhr.setRequestHeader("Authentication", token);
            },
            success: function(data) {
                $('#prognosisDetails').text(data.prognosis || 'Not provided');
                $('#diagnosisDetails').text(data.diagnosis || 'Not provided');
                $('#prescriptionDetails').text(data.prescription || 'Not provided');
                modalService.openModal('viewDetailsModal');
            },
            error: function(xhr) {
                console.error('Error loading medical details:', xhr.responseText);
                toastr.error('Failed to load medical details');
            }
        });
    },

    openReviewModal: function(recordId, doctorId) {
        $('#addReviewModal').data('recordId', recordId);
        $('#addReviewModal').data('doctorId', doctorId);
        modalService.openModal('addReviewModal');
    },

    submitReview: function() {
        const token = localStorage.getItem('user_token');
        if (!token) return;

        try {
            const decodedToken = Utils.parseJwt(token);
            const patientId = decodedToken.user.id;
            const recordId = $('#addReviewModal').data('recordId');
            const doctorId = $('#addReviewModal').data('doctorId');

            const reviewData = {
                doctor_id: doctorId,
                patient_id: patientId,
                rating: $('#reviewRating').val(),
                comment: $('#reviewText').val(),
                medical_history_id: recordId
            };

            $.ajax({
                url: Constants.PROJECT_BASE_URL + 'reviews',
                type: 'POST',
                contentType: 'application/json',
                data: JSON.stringify(reviewData),
                beforeSend: function(xhr) {
                    xhr.setRequestHeader("Authentication", token);
                },
                success: function(response) {
                    toastr.success('Review submitted successfully');
                    modalService.closeModal('addReviewModal');
                    $('#medicalHistoryTable').DataTable().ajax.reload();
                },
                error: function(xhr) {
                    console.error('Error submitting review:', xhr.responseText);
                    toastr.error('Failed to submit review');
                }
            });
        } catch (e) {
            console.error('Token parsing error:', e);
        }
    }
};

$('#submitReviewButton').click(function() {
    medicalHistory.submitReview();
});;

let appointmentService = {
    cancelAppointment: function(appointmentId) {
        if (!confirm('Are you sure you want to cancel this appointment?')) return;

        const token = localStorage.getItem('user_token');
        if (!token) return;

        $.ajax({
            url: Constants.PROJECT_BASE_URL + `appointments/${appointmentId}`,
            type: 'PUT',
            contentType: 'application/json',
            data: JSON.stringify({ status: 'cancelled' }),
            beforeSend: function(xhr) {
                xhr.setRequestHeader("Authentication", token);
            },
            success: function(response) {
                toastr.success('Appointment cancelled successfully');
                $('#appointmentsTable').DataTable().ajax.reload();
            },
            error: function(xhr) {
                console.error('Error cancelling appointment:', xhr.responseText);
                toastr.error('Failed to cancel appointment');
            }
        });
    }
}

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