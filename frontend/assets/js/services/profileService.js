let profileService = {
    loadProfileData: function() {
        const token = localStorage.getItem('user_token');
        if (!token) {
            window.location.href = '/login.html';
            return;
        }

        try {
            const decodedToken = Utils.parseJwt(token);
            const userId = decodedToken.user.id;

            $.ajax({
                url: Constants.PROJECT_BASE_URL + `users/${userId}`,
                type: 'GET',
                dataType: 'json',
                beforeSend: function(xhr) {
                    xhr.setRequestHeader('Authorization', `Bearer ${token}`);
                },
                success: function(userData) {
                    profileService.displayProfileData(userData);
                },
                error: function(xhr) {
                    console.error('Error loading profile:', xhr.responseText);
                    toastr.error('Failed to load profile data');
                }
            });
        } catch (e) {
            console.error('Token parsing error:', e);
            window.location.href = '/login.html';
        }
    },

    displayProfileData: function(userData) {
        const profileHtml = `
            <li class="list-group-item"><strong>Name:</strong> ${userData.first_name} ${userData.last_name}</li>
            <li class="list-group-item"><strong>Email:</strong> ${userData.email}</li>
            <li class="list-group-item"><strong>Phone:</strong> ${userData.phone || 'Not provided'}</li>
            <li class="list-group-item"><strong>Address:</strong> ${userData.address || 'Not provided'}</li>
            <li class="list-group-item"><strong>Role:</strong> ${userData.role}</li>
        `;
        $('#profileInfo').html(profileHtml);
    },

    populateEditForm: function() {
        const token = localStorage.getItem('user_token');
        if (!token) return;

        try {
            const decodedToken = Utils.parseJwt(token);
            const userId = decodedToken.user.id;

            $.ajax({
                url: Constants.PROJECT_BASE_URL + `users/${userId}`,
                type: 'GET',
                dataType: 'json',
                beforeSend: function(xhr) {
                    xhr.setRequestHeader('Authorization', `Bearer ${token}`);
                },
                success: function(userData) {
                    $('#firstName').val(userData.first_name);
                    $('#lastName').val(userData.last_name);
                    $('#email').val(userData.email);
                    $('#phone').val(userData.phone || '');
                    $('#address').val(userData.address || '');
                },
                error: function(xhr) {
                    console.error('Error loading profile for edit:', xhr.responseText);
                    toastr.error('Failed to load profile data for editing');
                }
            });
        } catch (e) {
            console.error('Token parsing error:', e);
        }
    },

    saveProfile: function() {
        const token = localStorage.getItem('user_token');
        if (!token) return;

        try {
            const decodedToken = Utils.parseJwt(token);
            const userId = decodedToken.user.id;

            const profileData = {
                first_name: $('#firstName').val(),
                last_name: $('#lastName').val(),
                email: $('#email').val(),
                phone: $('#phone').val(),
                address: $('#address').val()
            };

            $.ajax({
                url: Constants.PROJECT_BASE_URL + `users/${userId}`,
                type: 'PUT',
                contentType: 'application/json',
                data: JSON.stringify(profileData),
                beforeSend: function(xhr) {
                    xhr.setRequestHeader('Authorization', `Bearer ${token}`);
                },
                success: function(response) {
                    toastr.success('Profile updated successfully');
                    modalService.closeModal('editProfileModal');
                    profileService.loadProfileData();
                },
                error: function(xhr) {
                    console.error('Error updating profile:', xhr.responseText);
                    toastr.error('Failed to update profile');
                }
            });
        } catch (e) {
            console.error('Token parsing error:', e);
        }
    }
};

$('#editProfileForm').submit(function(e) {
    e.preventDefault();
    profileService.saveProfile();
});