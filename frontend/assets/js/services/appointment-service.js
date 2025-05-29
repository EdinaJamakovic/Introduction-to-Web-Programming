var AppointmentService = {
    dataTable: null,
    availableServices: [],
    currentUser: null,

    init: function() {
        console.log('Initializing AppointmentService');
        
        // Get current user info from token
        const token = localStorage.getItem("user_token");
        if (token) {
            this.currentUser = Utils.parseJwt(token).user;
        }
        
        // Load services and appointments
        this.loadServices();
        this.loadAvailableAppointments();
        
        // Setup event listeners
        this.setupEventListeners();
    },

    cleanup: function() {
        // Destroy DataTable if it exists
        if (this.dataTable && $.fn.dataTable.isDataTable('#availableAppointmentsTable')) {
            this.dataTable.destroy();
            this.dataTable = null;
        }
        
        // Clear any intervals or timeouts if you have them
        console.log('AppointmentService cleaned up');
    },

    setupEventListeners: function() {
        // Service selection form validation
        $('#serviceSelectionForm').off('submit').on('submit', function(e) {
            e.preventDefault();
            AppointmentService.confirmBooking();
        });

        // Modal events
        $('#serviceSelectionModal').off('show.bs.modal').on('show.bs.modal', function() {
            // Reset form when modal opens
            $('#serviceSelect').val('');
            $('#appointmentNotes').val('');
        });
    },

    loadServices: function() {
        RestClient.get('services', 
            (response) => {
                console.log('Services loaded:', response);
                this.availableServices = response.data || response;
                this.populateServiceSelect();
            },
            (error) => {
                console.error('Failed to load services:', error);
                toastr.error('Failed to load services. Please try again.');
            }
        );
    },

    populateServiceSelect: function() {
        const select = $('#serviceSelect');
        select.empty();
        select.append('<option value="">-- Please select a service --</option>');
        
        this.availableServices.forEach(service => {
            select.append(`<option value="${service.id}">${service.title}</option>`);
        });
    },

    loadAvailableAppointments: function() {
        console.log('Loading available appointments...');
        
        // Show loading spinner
        $('#loadingSpinner').show();
        $('#appointmentsTableContainer').hide();
        $('#noAppointmentsMessage').hide();

        RestClient.get('appointments/free', 
            (response) => {
                console.log('Available appointments loaded:', response);
                
                // Hide loading spinner
                $('#loadingSpinner').hide();
                
                let appointments = [];
                
                // Handle different response formats
                if (response.success && response.data) {
                    appointments = response.data;
                } else if (Array.isArray(response)) {
                    appointments = response;
                } else if (response.data && Array.isArray(response.data)) {
                    appointments = response.data;
                }

                if (appointments.length > 0) {
                    this.displayAppointments(appointments);
                    $('#appointmentsTableContainer').show();
                } else {
                    $('#noAppointmentsMessage').show();
                }
            },
            (error) => {
                console.error('Failed to load appointments:', error);
                $('#loadingSpinner').hide();
                $('#noAppointmentsMessage').show();
                
                let errorMessage = 'Failed to load appointments. Please try again.';
                if (error.responseJSON && error.responseJSON.message) {
                    errorMessage = error.responseJSON.message;
                }
                toastr.error(errorMessage);
            }
        );
    },

    displayAppointments: function(appointments) {
        console.log('Displaying appointments:', appointments);

        // Destroy existing DataTable if it exists
        if (this.dataTable && $.fn.dataTable.isDataTable('#availableAppointmentsTable')) {
            this.dataTable.destroy();
        }

        // Prepare data for DataTable
        const tableData = appointments.map(appointment => [
            this.formatDate(appointment.date),
            this.formatTime(appointment.time),
            appointment.doctor || 'Dr. ' + appointment.doctor_name || 'N/A',
            appointment.specialty || appointment.specialization || 'General',
            `<button class="btn btn-primary btn-sm" onclick="AppointmentService.selectAppointment(${appointment.id}, '${appointment.doctor}', '${appointment.date}', '${appointment.time}')">
                <i class="fas fa-calendar-plus me-1"></i>Book
            </button>`
        ]);

        // Initialize DataTable
        this.dataTable = $('#availableAppointmentsTable').DataTable({
            data: tableData,
            columns: [
                { title: "Date", width: "20%" },
                { title: "Time", width: "15%" },
                { title: "Doctor", width: "25%" },
                { title: "Specialty", width: "20%" },
                { title: "Actions", width: "20%", orderable: false }
            ],
            pageLength: 10,
            lengthMenu: [5, 10, 15, 25, 50],
            order: [[0, 'asc'], [1, 'asc']], // Sort by date, then time
            responsive: true,
            language: {
                emptyTable: "No available appointments found",
                zeroRecords: "No matching appointments found"
            }
        });
    },

    selectAppointment: function(appointmentId, doctorName, date, time) {
        console.log('Selecting appointment:', { appointmentId, doctorName, date, time });
        
        // Store appointment details
        $('#selectedAppointmentId').val(appointmentId);
        $('#selectedDoctorName').val(doctorName);
        $('#selectedDateTime').val(`${date} ${time}`);
        
        // Update modal display
        $('#modalDoctorName').text(doctorName);
        $('#modalDateTime').text(`${this.formatDate(date)} at ${this.formatTime(time)}`);
        
        // Show the service selection modal
        const modal = new bootstrap.Modal(document.getElementById('serviceSelectionModal'));
        modal.show();
    },

    confirmBooking: function() {
        const appointmentId = $('#selectedAppointmentId').val();
        const serviceId = $('#serviceSelect').val();
        const notes = $('#appointmentNotes').val();

        // Validate required fields
        if (!serviceId) {
            toastr.error('Please select a service for your appointment.');
            return;
        }

        if (!appointmentId) {
            toastr.error('No appointment selected. Please try again.');
            return;
        }

        // Prepare booking data
        const bookingData = {
            appointment_id: appointmentId,
            patient_id: this.currentUser ? this.currentUser.id : null,
            service_id: serviceId,
            notes: notes,
            status: 'scheduled'
        };

        console.log('Confirming booking with data:', bookingData);

        // Make the booking request
        RestClient.put(`appointments/${appointmentId}`, bookingData,
            (response) => {
                console.log('Booking confirmed:', response);
                
                // Hide service selection modal
                const serviceModal = bootstrap.Modal.getInstance(document.getElementById('serviceSelectionModal'));
                if (serviceModal) {
                    serviceModal.hide();
                }
                
                // Show success message
                toastr.success('Appointment booked successfully!');
                
                // Show confirmation modal
                const confirmModal = new bootstrap.Modal(document.getElementById('confirmationModal'));
                confirmModal.show();
                
                // Reload appointments to reflect changes
                setTimeout(() => {
                    this.loadAvailableAppointments();
                }, 1000);
            },
            (error) => {
                console.error('Booking failed:', error);
                
                let errorMessage = 'Failed to book appointment. Please try again.';
                if (error.responseJSON && error.responseJSON.message) {
                    errorMessage = error.responseJSON.message;
                } else if (error.status === 403) {
                    errorMessage = 'You are not authorized to book this appointment.';
                } else if (error.status === 404) {
                    errorMessage = 'The selected appointment is no longer available.';
                }
                
                toastr.error(errorMessage);
            }
        );
    },

    // Utility functions
    formatDate: function(dateString) {
        if (!dateString) return 'N/A';
        
        try {
            const date = new Date(dateString);
            return date.toLocaleDateString('en-US', {
                weekday: 'short',
                year: 'numeric',
                month: 'short',
                day: 'numeric'
            });
        } catch (e) {
            return dateString;
        }
    },

    formatTime: function(timeString) {
        if (!timeString) return 'N/A';
        
        try {
            // Handle time format like "09:00:00" or "09:00"
            const timeParts = timeString.split(':');
            if (timeParts.length >= 2) {
                const hour = parseInt(timeParts[0]);
                const minute = timeParts[1];
                const ampm = hour >= 12 ? 'PM' : 'AM';
                const displayHour = hour % 12 || 12;
                return `${displayHour}:${minute} ${ampm}`;
            }
            return timeString;
        } catch (e) {
            return timeString;
        }
    },

    // Method to refresh appointments (can be called from other parts of the app)
    refresh: function() {
        this.loadAvailableAppointments();
    }
};