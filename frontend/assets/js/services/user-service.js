var UserService = {
  init: function () {
    var token = localStorage.getItem("user_token");
    if (token) {
      try {
        this.generateMenuItems();
      } catch (e) {
        console.error("Token invalid, showing default view", e);
        localStorage.removeItem("user_token");
        this.showDefaultView();
      }
    } else {
      this.showDefaultView();
      this.setupLoginForm();
    }
  },

 login: function(entity) {
  $.ajax({
    url: Constants.PROJECT_BASE_URL + "auth/login",
    type: "POST",
    data: JSON.stringify(entity),
    contentType: "application/json",
    dataType: "json",
    success: function(result) {
      localStorage.setItem("user_token", result.token);
      localStorage.setItem("user", result.user);

      UserService.generateMenuItems();
      window.location.hash = "#landingPage";
    },
    error: function(XMLHttpRequest) {
      let errorMsg = XMLHttpRequest.responseJSON?.message || "Login failed";
      toastr.error(errorMsg);
    }
  });
},


  logout: function () {
    localStorage.clear();
    this.showDefaultView()
    window.location.hash="#login";
    location.reload();
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
              <a class="nav-link" onClick = "redirectProfile()">Profile</a>
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
            <section id="viewDoctors" data-load="ViewDoctors.html"></section>
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
            <section id="bookAppointment" data-load="bookAppointment.html"></section>
          `;
          break;
        default:
          nav = '';
          main = '';
        i
      }
      
      $("#tabs").html(nav);
      $("#spapp").html(main);
      
      if (typeof spApp !== 'undefined') {
        window.spApp.run();
      }
    } else {
      window.location.hash = "#login";
    }
  },

  showDefaultView: function() {
    const nav = `
      <li class="nav-item">
        <a class="nav-link" href="#"></a>
      </li>
      <li class="nav-item dropdown">
        <a class="nav-link" href="#services">Services</a>
      </li>
      <li class="nav-item">
        <a class="nav-link" href="#login">Our Doctors</a>
      </li>
      <li class="nav-item">
        <a class="nav-link" href="#login"><button type="button" class="btn btn-primary btn-inverted">Login</button></a>
      </li>
    `;
    
    const main = `
      <section id="landingPage" data-load="landingPage.html"></section>
      <section id="services" data-load="services.html"></section>
      <section id="login" data-load="login.html"></section>
      <section id="signup" data-load="signup.html"></section>
      <section id="viewDoctors" data-load="viewDoctors.html"></section>
    `;

    $("#tabs").html(nav);
    $("#spapp").html(main);


    if (window.spApp) {
      window.spApp.run();
    }
  },

  setupLoginForm: function() {
    $(document).on("submit", "#login-form", function(e) {
    e.preventDefault();
    var entity = {
      email: $(this).find("[name='email']").val(),
      password_hash: $(this).find("[name='password_hash']").val()
    };
    UserService.login(entity);
  });
  }
};