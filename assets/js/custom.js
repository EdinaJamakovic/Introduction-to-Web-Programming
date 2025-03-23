$(document).ready(function(){
    var app = $.spapp({
        defaultView: '#landingPage',
        templateDir: './views/'
    });

    app.run();
})