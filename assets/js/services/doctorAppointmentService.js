let doctorAppointmentService = {
    openEditTreatmentModal: function(patientId) {
        const patientData = {
            prescription: 'Example prescription for patient ' + patientId,
            diagnosis: 'Diagnosis for patient ' + patientId,
            prognosis: 'Prognosis for patient ' + patientId,
            notes: 'Some additional notes for patient ' + patientId
        };

        document.getElementById('prescription').value = patientData.prescription;
        document.getElementById('diagnosis').value = patientData.diagnosis;
        document.getElementById('prognosis').value = patientData.prognosis;
        document.getElementById('notes').value = patientData.notes;

        modalService.openModal('editTreatmentModal');
    },

    closeEditTreatmentModal: function() {
        modalService.closeModal('editTreatmentModal');
    },

    handleEditTreatmentFormSubmit: function(event) {
        event.preventDefault();

        const prescription = document.getElementById('prescription').value;
        const diagnosis = document.getElementById('diagnosis').value;
        const prognosis = document.getElementById('prognosis').value;
        const notes = document.getElementById('notes').value;

        console.log('Updated treatment data:', { prescription, diagnosis, prognosis, notes });
        this.closeEditTreatmentModal();
    },

    cancelAppointment: function(patientId) {
        const confirmCancel = confirm(`Are you sure you want to cancel the appointment for patient ${patientId}?`);
        if (confirmCancel) {
            console.log(`Appointment for patient ${patientId} has been canceled.`);
        }
    }
};

let DoctorService = {

    createDoctorCard(doctor) {
        return `
            <div class="col">
                <div class="card h-100 d-flex flex-column">
                    <img class="card-img-top" src="${doctor.image}" alt="${doctor.name}">
                    <div class="card-body d-flex flex-column">
                        <h5 class="card-title">${doctor.name}</h5>
                        <p class="card-text"><strong>Specialization:</strong> ${doctor.specialization}</p>
                        <p class="card-text"><strong>Contact:</strong> ${doctor.email}, ${doctor.phone}</p>
                        <div class="mt-auto">
                            <a href="#bookAppointment"><button class="btn btn-primary w-100 mt-2">Book Appointment</button></a>
                            <button class="btn btn-success w-100 mt-2" onclick="DoctorService.openReviewModal()">View Reviews</button>
                        </div>
                    </div>
                </div>
            </div>
        `;
    },

    renderDoctors(doctors) {
        doctorsGrid = document.getElementById('doctorsGrid');
        doctors.forEach(doctor => {
            const cardHTML = this.createDoctorCard(doctor);
            doctorsGrid.innerHTML += cardHTML;
        });
    }, 
    
    async loadDoctors(filename) {
        try {
            const response = await fetch(filename);
            const doctors = await response.json();
            this.renderDoctors(doctors);
        } catch (error) {
            console.error('Error loading doctors:', error);
        }
    },


    openReviewModal() {
        const reviews = [
            { reviewer: "John Doe", rating: 5, comment: "Excellent doctor!" },
            { reviewer: "Jane Smith", rating: 4, comment: "Very good, but the wait was long." },
            { reviewer: "Emily Johnson", rating: 5, comment: "Highly recommend! Very professional." }
        ];
    
        const reviewContent = reviews.map(review => {
            return `
                <div class="review">
                    <p><strong>${review.reviewer}</strong> - Rating: ${review.rating}</p>
                    <p>${review.comment}</p>
                </div>
            `;
        }).join('');
    
        document.getElementById('reviewContent').innerHTML = reviewContent;
    
        // Open the modal (using Bootstrap Modal)
        const reviewModal = new bootstrap.Modal(document.getElementById('reviewModal'));
        reviewModal.show();
    },
    

    
    createReviewCard(review){
        return `
            <div class="review">
                <p><strong>${review.reviewer}</strong> - Rating: ${review.rating}</p>
                <p>${review.comment}</p>
            </div>
        `;
    },

    renderReviews(reviews){
        reviews.forEach(review => {
            reviewContent = document.getElementById('reviewContent');
            reviews.forEach(review => {
                reviewContent.innerHTML += this.createReviewCard(review);
            })
        })
    },

    async loadReviews(filename) {
        try {
            const response = await fetch(filename);
            const reviews = await response.json();
            this.renderReviews(reviews);
        } catch (error) {
            console.error('Error loading reviews:', error);
        }
    }

    
};


