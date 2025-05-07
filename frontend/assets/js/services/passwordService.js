let passwordService = {

    changePassword: function() {
        const currentPassword = document.getElementById('currentPassword').value.trim();
        const newPassword = document.getElementById('newPassword').value.trim();
        const confirmPassword = document.getElementById('confirmPassword').value.trim();
        const patientId = document.getElementById('patientId').value.trim();

        const validationErrors = this.validatePasswords(currentPassword, newPassword, confirmPassword);
        if (validationErrors.length > 0) {
            alert(validationErrors.join('\n'));
            return;
        }

        const data = newPassword;

        //this.updatePasswordOnServer(patientId, data);
    },

    validatePasswords: function(currentPassword, newPassword, confirmPassword) {
        const errors = [];
        
        if (!currentPassword) {
            errors.push("Current password is required.");
        }

        if (!newPassword) {
            errors.push("New password is required.");
        } else if (newPassword.length < 8) {
            errors.push("New password must be at least 8 characters long.");
        } else if (!/[A-Z]/.test(newPassword) || !/[a-z]/.test(newPassword) || !/\d/.test(newPassword)) {
            errors.push("New password must contain at least one uppercase letter, one lowercase letter, and one number.");
        }

        if (newPassword !== confirmPassword) {
            errors.push("New password and confirm password do not match.");
        }

        return errors;
    },

    updatePasswordOnServer: function(data) {
    }
};
