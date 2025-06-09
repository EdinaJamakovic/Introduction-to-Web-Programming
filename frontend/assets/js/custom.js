$(document).ready(function() {
    window.spApp = $.spapp({
        defaultView: '#landingPage',
        templateDir: './views/'
    });

    spApp.run();
});
