function activateDarkMode() {
    localStorage.setItem('darkMode', 'yes');
    $('#dark-style').attr('href', 'assets/css/dark.css');
    $('.btn-outline-dark').addClass('btn-outline-light').removeClass('btn-outline-dark');
}
function deactivateDarkMode() {
    localStorage.removeItem('darkMode');
    $('#dark-style').attr('href', '');
    $('.btn-outline-light').addClass('btn-outline-dark').removeClass('btn-outline-light');
}

function triggerDarkMode() {
    if (localStorage.getItem('darkMode') == 'yes') {
        deactivateDarkMode();
    } else {
        activateDarkMode();
    }
}

$(function() {
    if (localStorage.getItem('darkMode') == 'yes') {
        activateDarkMode();
    }
    $('#light-switch').click(triggerDarkMode);
});