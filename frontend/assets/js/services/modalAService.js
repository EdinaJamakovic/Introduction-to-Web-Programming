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

function handleBooking() {
    const service = document.getElementById('service').value;
    alert(`Service booked: ${service}`);
    bookModalService.closeModal();
};