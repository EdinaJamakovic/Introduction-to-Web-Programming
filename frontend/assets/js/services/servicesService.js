let servicesService = {
    loadServices: function () {
        const token = localStorage.getItem("user_token");
        
        let userRole = null;
        try {
            const decodedToken = Utils.parseJwt(token);
            userRole = decodedToken.user?.role;
        } catch (e) {
            console.error("Error parsing token:", e);
            UserService.logout();
            return;
        }

        $.ajax({
            url: Constants.PROJECT_BASE_URL + "services",
            type: "GET",
            contentType: "application/json",
            beforeSend: function(xhr) {
                xhr.setRequestHeader("Authentication", token);
            },
            success: (response) => {  
                this.renderServices(response, userRole);
            },
            error: function(xhr) {
                console.error("Error loading services:", xhr);
                if (xhr.status === 401) {
                    toastr.error("Session expired. Please login again.");
                    UserService.logout();
                } else {
                    toastr.error("Failed to load services");
                }
            }
        });
    },

    renderServices: function (services, userRole) {
        const servicesContainer = document.querySelector("#servicesContainer");
        if (!servicesContainer) {
            console.error("Services container not found");
            return;
        }
        
        servicesContainer.innerHTML = ""; 

        if (!services || services.length === 0) {
            servicesContainer.innerHTML = `
                <div class="col-12 text-center py-5">
                    <h4>No services available</h4>
                </div>
            `;
            return;
        }

        services.forEach(service => {
            const canDelete = userRole === Constants.DOCTOR_ROLE || userRole === Constants.ADMIN_ROLE;
            
            const isLoggedIn = localStorage.getItem("user_token") !== null;

            const deleteButton = canDelete ? 
                `<button class="btn btn-danger me-2" onclick="servicesService.deleteService('${service.id}')">Delete</button>` : '';
            
            // Build book button - redirect to bookAppointment if logged in, login if not
            const bookButton = isLoggedIn ? 
                `<a href="#bookAppointment" class="btn btn-primary mt-auto">Book Now</a>` :
                `<a href="#login" class="btn btn-primary mt-auto">Login to Book</a>`;

            const serviceCard = `
                <div class="col">
                    <div class="card h-100 d-flex flex-column">
                        <img class="card-img-top" src="${service.image_url || 'assets/images/default-service.jpg'}" alt="${service.title}">
                        <div class="card-body d-flex flex-column">
                            <h5 class="card-title">${service.title}</h5>
                            <p class="card-text">${service.description}</p>
                            <div class="d-flex justify-content-end mt-auto">
                                ${deleteButton}
                                ${bookButton}
                            </div>
                        </div>
                    </div>
                </div>
            `;
            servicesContainer.innerHTML += serviceCard;
        });
    },

    // ... rest of the methods remain the same ...
    deleteService: async function (serviceId) {
        if (!confirm("Are you sure you want to delete this service?")) {
            return;
        }

        try {
            const token = localStorage.getItem("user_token");
            
            await $.ajax({
                url: Constants.PROJECT_BASE_URL + "services/" + serviceId,
                type: "DELETE",
                headers: {
                    'Authentication': token  // Removed 'Bearer ' prefix if not needed
                },
                dataType: "json"
            });

            toastr.success("Service deleted successfully");
            // Reload services to reflect changes
            this.loadServices();
        } catch (error) {
            console.error("Error deleting service:", error);
            toastr.error("Failed to delete service");
        }
    },

    openAddServiceModal: function () {
        // Check if user has permission to add services
        const token = localStorage.getItem("user_token");
        if (!token) {
            toastr.error("Please login to add services");
            window.location.hash = "#login";
            return;
        }

        const decodedToken = Utils.parseJwt(token);
        const userRole = decodedToken.user?.role;
        
        if (userRole !== Constants.DOCTOR_ROLE && userRole !== Constants.ADMIN_ROLE) {
            toastr.error("You don't have permission to add services");
            return;
        }

        const addServiceModal = new bootstrap.Modal(document.getElementById('addServiceModal'));
        addServiceModal.show();
    },

    saveService: async function () {
        const title = document.getElementById('serviceTitle').value.trim();
        const description = document.getElementById('serviceDescription').value.trim();
        const image = document.getElementById('serviceImage').value.trim();

        if (!title || !description) {
            toastr.error("Title and description are required");
            return;
        }

        try {
            const token = localStorage.getItem("user_token");
            
            const newService = {
                title: title,
                description: description,
                image_url: image || null  // Make image optional
            };

            await $.ajax({
                url: Constants.PROJECT_BASE_URL + "services",
                type: "POST",
                data: JSON.stringify(newService),
                contentType: "application/json",
                headers: {
                    'Authentication': token  // Removed 'Bearer ' prefix if not needed
                },
                dataType: "json"
            });

            toastr.success("Service added successfully");

            // Close the modal
            const addServiceModal = bootstrap.Modal.getInstance(document.getElementById('addServiceModal'));
            addServiceModal.hide();

            // Clear the form
            document.getElementById('addServiceForm').reset();

            // Reload services to show the new one
            this.loadServices();
        } catch (error) {
            console.error("Error saving service:", error);
            toastr.error("Failed to add service: " + (error.responseJSON?.error || "Server error"));
        }
    }
};