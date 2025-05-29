var UserService = {
  init: function () {
    var token = localStorage.getItem("user_token");
    if (token && token !== undefined) {
      window.location.hash = "#landingPage";
    }
    $("#login-form").validate({
      submitHandler: function (form) {
        var entity = Object.fromEntries(new FormData(form).entries());
        UserService.login(entity);
      },
    });
  },

  login: function(entity) {
    $.ajax({
      url: Constants.PROJECT_BASE_URL + "auth/login",
      type: "POST",
      data: JSON.stringify(entity),
      contentType: "application/json",
      dataType: "json",
      success: function(result) {
        localStorage.setItem("user_token", result.data.token);
        UserService.generateMenuItems();
        // Force a navigation to landingPage after menu generation
        window.location.hash = "#landingPage";
        // Force SPA to reload content
        if (typeof spApp !== 'undefined') {
          spApp.navigate("#landingPage", true);
        }
      },
      error: function(XMLHttpRequest) {
        let errorMsg = XMLHttpRequest.responseJSON?.message || "Login failed";
        toastr.error(errorMsg);
      }
    });
  },

  logout: function () {
    localStorage.clear();
    window.location.hash = "#login";
    if (typeof spApp !== 'undefined') {
      spApp.navigate("#login", true);
    }
  },

  generateMenuItems: function(){
    const token = localStorage.getItem("user_token");
    const user = Utils.parseJwt(token).user;

    if (user && user.role){
      let nav = "";
      let main = "";
      switch(user.role) {
        case Constants.PATIENT_ROLE:
          nav = `
            <li class="nav-item">
              <a class="nav-link" href="#services">Services</a>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="#viewDoctors">Our Doctors</a>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="#viewProfile">Profile</a>
            </li>
            <li class="nav-item">
              <button class="btn btn-primary" onclick="UserService.logout()">Logout</button>
            </li>
          `;
          main = `
            <section id="landingPage" data-load="landingPage.html"></section>
            <section id="services" data-load="services.html"></section>
            <section id="viewProfile" data-load="viewProfile.html"></section>
            <section id="bookAppointment" data-load="bookAppointment.html"></section>
          `;
          break;
        case Constants.DOCTOR_ROLE:
          nav = `
            <li class="nav-item">
              <a class="nav-link" href="#services">Services</a>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="#viewDoctors">Our Doctors</a>
            </li>
            <li class="nav-item">
              <button class="btn btn-primary" onclick="UserService.logout()">Logout</button>
            </li>
          `;
          main = `
            <section id="landingPage" data-load="landingPage.html"></section>
            <section id="services" data-load="services.html"></section>
            <section id="doctor" data-load="doctor.html"></section>
            <section id="viewDoctors" data-load="viewDoctors.html"></section>
          `;
          break;
        default:
          nav = '';
          main = '';
      }
      
      $("#tabs").html(nav);
      $("#spapp").html(main);
      
      // Initialize the SPA app after updating the DOM
      if (typeof spApp !== 'undefined') {
        spApp.init();
      }
    } else {
      window.location.hash = "#login";
      if (typeof spApp !== 'undefined') {
        spApp.navigate("#login", true);
      }
    }
  }
};