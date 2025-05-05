let editProfileService = {
    openModal: function () {
        modalService.openModal('editProfileModal');
    },

    closeModal: function () {
        modalService.closeModal('editProfileModal');
    },

    saveProfile: function () {
        const name = document.getElementById('name').value;
        const email = document.getElementById('email').value;
        const phone = document.getElementById('phone').value;
        const address = document.getElementById('address').value;

        console.log('Profile Updated:', { name, email, phone, address });
        this.closeModal();
    }
};

// Event listeners for edit profile
document.getElementById('editProfileButton').addEventListener('click', function () {
    editProfileService.openModal();
});

document.getElementById('editProfileForm').addEventListener('submit', function (event) {
    event.preventDefault();
    editProfileService.saveProfile();
});

document.getElementById('closeEditModalButton').addEventListener('click', function () {
    editProfileService.closeModal();
});

document.getElementById('closeEditModalFooterButton').addEventListener('click', function () {
    editProfileService.closeModal();
});