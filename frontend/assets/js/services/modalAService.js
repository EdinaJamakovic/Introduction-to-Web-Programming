let modalService = {
    openModal: function(modalId) {
        document.getElementById(modalId).classList.remove('d-none');
    },
    closeModal: function(modalId) {
        document.getElementById(modalId).classList.add('d-none');
    },
};

let bookModalService = {
    openModal: function() {
        modalService.openModal('serviceSelectionModal');
    },
    closeModal: function() {
        modalService.closeModal('serviceSelectionModal');
    }
};

// Store the selected appointment ID globally
let selectedAppointmentId = null;

function handleBooking() {
    const selectedServiceId = $('#serviceOptions').val();
    if (!selectedServiceId) {
        toastr.error("Please select a service");
        return;
    }
    
    if (!selectedAppointmentId) {
        toastr.error("No appointment selected");
        return;
    }
    
    // Get patient ID from token
    const token = localStorage.getItem("user_token");
    if (!token) {
        toastr.error("Please log in to book an appointment");
        return;
    }
    
    let patientId;
    try {
        const decodedToken = Utils.parseJwt(token);
        patientId = decodedToken.user.id;
    } catch (e) {
        toastr.error("Invalid token. Please log in again.");
        return;
    }
    
    // Prepare booking data
    const bookingData = {
        status: 'scheduled',
        patient_id: parseInt(patientId),
        service_id: parseInt(selectedServiceId)
    };
    
    // Send booking request
    $.ajax({
        url: Constants.PROJECT_BASE_URL + `appointments/${selectedAppointmentId}`,
        type: "PUT",
        data: JSON.stringify(bookingData),
        contentType: "application/json",
        beforeSend: function(xhr) {
            if (token) {
                xhr.setRequestHeader("Authentication", token);
            }
        },
        success: function(response) {
            toastr.success("Appointment booked successfully!");
            
            // Close the modal
            $('#serviceSelectionModal').modal('hide');
            bookModalService.closeModal();
            
            // Reload the appointments table to reflect changes
            // Since we use static data (not AJAX), we need to reload the entire table
            dataTableService.loadAvailableAppointments();
            
            // Reset the selected appointment ID
            selectedAppointmentId = null;
        },
        error: function(xhr) {
            console.error("Booking failed:", xhr.responseText);
            let errorMsg = "Failed to book appointment";
            try {
                const errorResponse = JSON.parse(xhr.responseText);
                errorMsg = errorResponse.message || errorMsg;
            } catch (e) {
                // Use default error message
            }
            toastr.error(errorMsg);
        }
    });
}

function loadServicesIntoModal() {
    $.ajax({
        url: Constants.PROJECT_BASE_URL + "services",
        type: "GET",
        contentType: "application/json",
        beforeSend: function(xhr) {
            const token = localStorage.getItem("user_token");
            if (token) {
                xhr.setRequestHeader("Authentication", token);
            }
        },
        success: function(response) {
            const select = $('#serviceOptions');
            select.empty(); // Clear existing options
            
            // Add default option
            select.append($('<option>', {
                value: '',
                text: 'Please select a service...',
                disabled: true,
                selected: true
            }));
            
            // Add new options from database
            response.forEach(service => {
                select.append($('<option>', {
                    value: service.id,
                    text: service.title || service.name
                }));
            });
        },
        error: function(xhr) {
            console.error("Failed to load services:", xhr.responseText);
            toastr.error("Failed to load services");
        }
    });
}

// Event handler for book buttons - this captures the appointment ID
$(document).on('click', '.book-btn', function() {
    selectedAppointmentId = $(this).data('appointment-id');
    console.log("Selected appointment ID:", selectedAppointmentId);
});