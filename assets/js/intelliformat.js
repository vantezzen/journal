/**
 * Journal: Simple Static Blog CMS
 * 
 * dashboard\intelliformat: Live IntelliFormat preview for WYSIWYG editor
 * 
 * Licensed under MIT License
 * For full copyright and license information, please see the LICENSE file
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright (c) vantezzen (https://github.com/vantezzen/)
 * @link          https://github.com/vantezzen/journal
 * @license       https://opensource.org/licenses/mit-license.php MIT License
 */

function applyIntelliformat(lines, quill) {
    if (window.Journal.intelliformat_headings && window.Journal.intelliformat_headings == 'yes') {
        applyIntelliformatHeadings(lines, quill);
    }
    if (window.Journal.intelliformat_code && window.Journal.intelliformat_code == 'yes') {
        applyIntelliformatCode(lines, quill);
    }   
}

function applyIntelliformatHeadings(lines, quill) {
    let lastEmpty = false;
    let firstChar = '';
    let text = '';
    for(line in lines) {
        text = lines[line].domNode.innerText;

        if (text.trim() == '') {
            lastEmpty = true;
            continue;
        }

        firstChar = text.trim().charAt(0);
        var index = quill.getIndex(lines[line]);
        if (
            lastEmpty &&            // Last line has to be empty
            text.length <= 40 &&    // Can not be longer than 40
            firstChar !== '-' &&    // Can not be a list (starting with -)
            firstChar !== '#' &&    // Not a markdown formatted heading
            !intelliFormatIsCode(text) // Not code
        ) {
            quill.formatLine(index, 1, 'header', '3');
        } else {
            quill.formatLine(index, 1, 'header', false);
        }

        lastEmpty = false;
    }
}

function applyIntelliformatCode(lines, quill) {
    let text = '';
    for(line in lines) {
        text = lines[line].domNode.innerText;

        if (text.trim() == '') {
            continue;
        }

        if (intelliFormatIsCode(text)) {
            quill.formatText(quill.getIndex(lines[line]), text.length, 'code', true);
        } else {
            quill.formatText(quill.getIndex(lines[line]), text.length, 'code', false);
        }

        lastEmpty = false;
    }
}

function intelliFormatIsCode(string) {
    string = string.trim();

    // Ends in semicolon: Probably code (but not an HTML encoded character)
    if (string.slice(-1) == ';' && !/&\w{1,8};$/.test(string)) {
        return true;
    }

    // Matches typical code:
    // function_name(arguments)
    // Deactivated because e.g. "I am just a(nother) text" will also match
    // if (preg_match('/^\w+( )?\(.*\)/', trim($string))) {
    //     return true;
    // }

    // End with { (for if)
    if (string.slice(-1) == '{') {
        return true;
    }

    // End with } (for end of if)
    if (string.slice(-1) == '}') {
        return true;
    }

    // End with }, (for end of if)
    if (string.slice(-2) == '},') {
        return true;
    }

    // Matches HTML:
    // <something> or </something>
    if (/<\/?\w+.*>/.test(string)) {
        return true;
    }

    // Is a comment
    // // ...
    if (/^(\/\/|#).*$/.test(string)) {
        return true;
    }

    // End with a semicolon, then a comment
    // ...; // I am a comment
    if (/;.?(\/\/|#).*$/.test(string)) {
        return true;
    }

    // Has double- or triple-equals
    if (/={2,3}/.test(string)) {
        return true;
    }

    return false;
}