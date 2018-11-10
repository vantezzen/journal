
var quill = new Quill('#text', {
    modules: {
        toolbar: false
        // [
        //     ['bold', 'italic', 'code'],
        //     [
        //         { 'header': '1' },
        //         { 'header': '2' }
        //     ]
        // ]
    },
    placeholder: 'Write your post...',
    theme: 'bubble'
});

// Insert text into Quill Editor
if (window.Journal.text) {
    quill.setText(window.Journal.text);

    let lines = quill.getLines();
    applyIntelliformat(lines, quill);
}

// Convert text on form submit
$('#form').submit(function () {
    let input = $('input[name=text]');
    input.attr('value', quill.getText());

    return true;
});

// Listen for Text changed to update auto-formatting
quill.on('text-change', function (delta, oldDelta, source) {
    if (source !== 'api' && window.localStorage.getItem('write.disable-autoformat') !== 'yes') {
        let lines = quill.getLines();

        applyIntelliformat(lines, quill);
    }
});

// Enable and disable IntelliFormat
function enableAutoformat() {
    window.localStorage.removeItem('write.disable-autoformat', 'yes');

    let lines = quill.getLines();
    applyIntelliformat(lines, quill);

    $('#toggle-autoformat').text('Disable Formatting Preview');
}
function disableAutoformat() {
    window.localStorage.setItem('write.disable-autoformat', 'yes');
    quill.removeFormat(0, quill.getLength());
    $('#toggle-autoformat').text('Enable Formatting Preview');
}
function toggleAutoformat() {
    if (window.localStorage.getItem('write.disable-autoformat') === 'yes') {
        enableAutoformat();
    } else {
        disableAutoformat();
    }
}

// Toggle on button click
$('#toggle-autoformat').click(toggleAutoformat);

// Check if already disabled
if (window.localStorage.getItem('write.disable-autoformat') === 'yes') {
    disableAutoformat();
}

// Publish post
$('#publish-btn').click(function() {
    // Change action to also publish post
    let action = $('#form').attr('action') + '/0/1';
    $('#form').attr('action', action);

    $('#form').submit();
});


// Auto-Save
// setInterval(function() {
//     let data = {
//         title: '',
//         text: '',
//         id: ''
//     }
// }, 1000 * 60);