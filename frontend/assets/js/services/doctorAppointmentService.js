let DoctorService = {

    createDoctorCard(doctor) {
        return `
            <div class="col">
                <div class="card h-100 d-flex flex-column">
                    <img class="card-img-top" src="${doctor.photo_url}" alt="${doctor.first_name} ${doctor.last_name}">
                    <div class="card-body d-flex flex-column">
                        <h5 class="card-title">Dr. ${doctor.first_name} ${doctor.last_name}</h5>
                        <p class="card-text"><strong>Specialization:</strong> ${doctor.specialization || 'General Dentistry'}</p>
                        <p class="card-text"><strong>Contact:</strong> ${doctor.email}, ${doctor.phone}</p>
                        <div class="mt-auto">
                            <a href="#bookAppointment"><button class="btn btn-primary w-100 mt-2">Book Appointment</button></a>
                            <button class="btn btn-success w-100 mt-2" onclick="DoctorService.getDoctorReviews(${doctor.id})">View Reviews</button>
                        </div>
                    </div>
                </div>
            </div>
        `;
    },

    //doc to page
    renderDoctors(doctors) {
        const doctorsGrid = document.getElementById('doctorsGrid');
        if (!doctorsGrid) return;
        
        doctorsGrid.innerHTML = '';
        doctors.forEach(doctor => {
            doctorsGrid.innerHTML += this.createDoctorCard(doctor);
        });
    },

    //doc
    getDoctors() {
    
    
    $.ajax({
        url: Constants.PROJECT_BASE_URL + 'users/doctors',
        type: 'GET',
        dataType: 'json',
        beforeSend: (xhr) => {
            const token = localStorage.getItem("user_token");
                if (token) {
                    xhr.setRequestHeader("Authentication", token);
                }
        },
        success: (data) => {
            this.renderDoctors(data);
        },
        error: (xhr) => {
            console.error('Error fetching doctors:', xhr.responseText);
            $('#doctorsGrid').html(`
                <div class="col-12 text-center text-danger">
                    Failed to load doctors. Please try again later.
                </div>
            `);
        }
    });
},
    
    //reveiews
    getDoctorReviews(doctorId) {
        const token = localStorage.getItem('user_token')
        
        $.ajax({
            url: Constants.PROJECT_BASE_URL + `reviews/doctor/${doctorId}`,
            type: 'GET',
            dataType: 'json',
             beforeSend: (xhr) => {
            const token = localStorage.getItem("user_token");
                if (token) {
                    xhr.setRequestHeader("Authentication", token);
                }
        },
            success: (reviews) => {
                this.displayReviewsModal(reviews);
            },
            error: (xhr) => {
                console.error('Error fetching reviews:', xhr.responseText);
                let errorMessage = 'Failed to load reviews';
                
                $('#reviewContent').html(`
                    <div class="alert alert-danger">
                        ${errorMessage}
                    </div>
                `);
                $('#reviewModal').modal('show');
            }
        });
    },

    displayReviewsModal(reviews) {
        let reviewContent = '';
        
        if (!reviews || reviews.length === 0) {
            reviewContent = '<p class="text-muted">No reviews yet for this doctor.</p>';
        } else {
            reviewContent = reviews.map(review => {
                const patientName = review.first_name ? 
                    `${review.first_name} ${review.last_name}` : 'Anonymous';
                const ratingStars = '★'.repeat(review.rating) + '☆'.repeat(5 - review.rating);
                const reviewDate = new Date(review.created_at).toLocaleDateString();
                
                return `
                    <div class="review mb-3 p-3 border-bottom">
                        <p><strong>${patientName}</strong> - 
                           <span class="text-warning">${ratingStars}</span></p>
                        <p>${review.comment || 'No comment provided'}</p>
                        <small class="text-muted">${reviewDate}</small>
                    </div>
                `;
            }).join('');
        }
        
        $('#reviewContent').html(reviewContent);
        $('#reviewModal').modal('show');
    },

    
    //treatment
    openEditTreatmentModal(patientId) {
        //update to api
        const patientData = {
            prescription: 'Example prescription for patient ' + patientId,
            diagnosis: 'Diagnosis for patient ' + patientId,
            prognosis: 'Prognosis for patient ' + patientId,
            notes: 'Some additional notes for patient ' + patientId
        };

        $('#prescription').val(patientData.prescription);
        $('#diagnosis').val(patientData.diagnosis);
        $('#prognosis').val(patientData.prognosis);
        $('#notes').val(patientData.notes);

        $('#editTreatmentModal').modal('show');
    },

    closeEditTreatmentModal() {
        $('#editTreatmentModal').modal('hide');
    },

    handleEditTreatmentFormSubmit(event) {
        event.preventDefault();
        
        const treatmentData = {
            prescription: $('#prescription').val(),
            diagnosis: $('#diagnosis').val(),
            prognosis: $('#prognosis').val(),
            notes: $('#notes').val(),
            patientId: $('#editTreatmentModal').data('patient-id')
        };

        //update to api
        console.log('Updated treatment data:', treatmentData);
        this.closeEditTreatmentModal();
        
        //success message
        alert('Treatment updated successfully!');
    },

    
    //apt
    cancelAppointment(patientId) {
        if (!confirm(`Are you sure you want to cancel the appointment for patient ${patientId}?`)) {
            return;
        }

        const token = this.getAuthToken();
        
        $.ajax({
            url: `/appointments/${patientId}`,
            type: 'DELETE',
            beforeSend: (xhr) => {
                xhr.setRequestHeader('Authorization', `Bearer ${token}`);
            },
            success: () => {
                alert('Appointment canceled successfully');
                // Refresh appointments or update UI as needed
            },
            error: (xhr) => {
                console.error('Error canceling appointment:', xhr.responseText);
                alert('Failed to cancel appointment. Please try again.');
            }
        });
    }
};

// Initialize when page loads
$(document).ready(function() {
    // Load doctors when page loads
    DoctorService.getDoctors();
    
    // Set up treatment form submission
    $('#treatmentForm').on('submit', function(e) {
        DoctorService.handleEditTreatmentFormSubmit(e);
    });
});