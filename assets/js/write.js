
// Publish post
$('#publish-btn').click(function() {
    // Change action to also publish post
    let action = $('#form').attr('action') + '/0/1';
    $('#form').attr('action', action);

    $('#form').submit();
});

// Lock inputs on save and delete
document.addEventListener('DOMContentLoaded', function() {
    $('#save-btn').click(function() {
        $('#confirm-delete').prop('disabled', true);
        $('#save-btn').prop('disabled', true);
        $('#delete-btn').prop('disabled', true);
        $('#text').prop('readonly', true);
        $('#title').prop('readonly', true);

        $('#saving_screen').css('display', 'flex');

        $('#form').submit();

        return true;
    });
    $('#confirm-delete').click(function() {
        $('#confirm-delete').prop('disabled', true);
        $('#save-btn').prop('disabled', true);
        $('#delete-btn').prop('disabled', true);
        $('#text').prop('readonly', true);
        $('#title').prop('readonly', true);
        $('#delete_form').submit();
        return true;
    });
});


// Auto-Save
// setInterval(function() {
//     let data = {
//         title: '',
//         text: '',
//         id: ''
//     }
// }, 1000 * 60);