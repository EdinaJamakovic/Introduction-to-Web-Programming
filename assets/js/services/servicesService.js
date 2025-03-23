let servicesService = {
    loadServices: async function () {
        try {
            const response = await fetch("./assets/data/services.json"); // Path to your JSON file
            const services = await response.json();
            this.renderServices(services);
        } catch (error) {
            console.error("Error loading services:", error);
        }
    },

    renderServices: function (services) {
        const servicesContainer = document.querySelector("#servicesContainer");
        servicesContainer.innerHTML = ""; // Clear existing content

        services.forEach(service => {
            const serviceCard = `
                <div class="col">
                    <div class="card h-100 d-flex flex-column">
                        <img class="card-img-top" src="${service.image}" alt="${service.title}">
                        <div class="card-body d-flex flex-column">
                            <h5 class="card-title">${service.title}</h5>
                            <p class="card-text">${service.description}</p>
                            <div class="d-flex justify-content-end">
                                <button class="btn btn-danger me-2" onclick="servicesService.deleteService('${service.id}')">Delete</button>
                                <a href="#login" class="btn btn-primary mt-auto">Book Now</a>
                            </div>
                        </div>
                    </div>
                </div>
            `;
            servicesContainer.innerHTML += serviceCard;
        });
    },

    deleteService: function (serviceId) {
        console.log(`Service with ID ${serviceId} deleted.`);
        // You can add logic here to remove the service from the JSON file or backend
    },

    openAddServiceModal: function () {
        const addServiceModal = new bootstrap.Modal(document.getElementById('addServiceModal'));
        addServiceModal.show();
        console.log("clicked");
    },

    saveService: function () {
        const title = document.getElementById('serviceTitle').value;
        const description = document.getElementById('serviceDescription').value;
        const image = document.getElementById('serviceImage').value;

        if (!title || !description || !image) {
            alert("Please fill out all fields.");
            return;
        }

        const newService = {
            id: Date.now().toString(), // Generate a unique ID
            title,
            description,
            image
        };

        console.log("New Service Added:", newService);
        // You can add logic here to save the new service to the JSON file or backend

        // Close the modal
        const addServiceModal = bootstrap.Modal.getInstance(document.getElementById('addServiceModal'));
        addServiceModal.hide();

        // Clear the form
        document.getElementById('addServiceForm').reset();
    }
};
