let passwordService = {
    changePassword: function() {
        const token = profileService.getAuthToken();
        if (!token) return;

        try {
            const decodedToken = Utils.parseJwt(token);
            const userId = decodedToken.user.id;

            const currentPassword = $('#currentPassword').val().trim();
            const newPassword = $('#newPassword').val().trim();
            const confirmPassword = $('#confirmPassword').val().trim();

            const validationErrors = this.validatePasswords(currentPassword, newPassword, confirmPassword);
            if (validationErrors.length > 0) {
                toastr.error(validationErrors.join('<br>'));
                return;
            }

            const passwordData = {
                current_password: currentPassword,
                new_password: newPassword
            };

            $.ajax({
                url: Constants.PROJECT_BASE_URL + `users/${userId}/password`,
                type: 'PUT',
                contentType: 'application/json',
                data: JSON.stringify(passwordData),
                beforeSend: function(xhr) {
                    xhr.setRequestHeader('Authorization', `Bearer ${token}`);
                },
                success: function(response) {
                    toastr.success('Password changed successfully');
                    modalService.closeModal('changePasswordModal');
                    
                    $('#currentPassword').val('');
                    $('#newPassword').val('');
                    $('#confirmPassword').val('');
                },
                error: function(xhr) {
                    console.error('Error changing password:', xhr.responseText);
                    let errorMsg = 'Failed to change password';
                    try {
                        const errorResponse = JSON.parse(xhr.responseText);
                        errorMsg = errorResponse.message || errorMsg;
                    } catch (e) {
                        
                    }
                    toastr.error(errorMsg);
                }
            });
        } catch (e) {
            console.error('Token parsing error:', e);
        }
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
    }
};

$('#changePasswordForm').submit(function(e) {
    e.preventDefault();
    passwordService.changePassword();
});