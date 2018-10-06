/**
 * Journal: Simple Static Blog CMS
 * 
 * theme_watch: Simplify the creation of themes for Journal
 * 
 * Licensed under MIT License
 * For full copyright and license information, please see the LICENSE file
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright (c) vantezzen (https://github.com/vantezzen/)
 * @link          https://github.com/vantezzen/journal
 * @license       https://opensource.org/licenses/mit-license.php MIT License
 */

// Include dependecies
const fs = require('fs');
const Jetty = require('jetty');
const exec = require("child_process").exec;

const theme = process.argv[2];
const folder = 'themes/' + theme;

let jetty = new Jetty(process.stdout);
let cooldown_time = 0;

// Initiate terminal display
jetty.clear();
jetty.moveTo([0,0]);
jetty.text('Journal Theme Creation Helper');

// Watch for changes in theme folder
fs.watch(folder, { recursive: true }, function (event, filename) {
    // Stop if in cooldown time
    if ((+new Date()) < cooldown_time) {
        return;
    }
    // Set new cooldown time (100ms)
    cooldown_time = (+new Date()) + 100;

    jetty.moveTo([3,0]).erase(100).moveTo([3,0]).rgb(255);
    jetty.text('Change detected, updating...');

    // Start php to generate static files
    exec('php generate_static.php ' + theme, (error, stdout, stderr) => {
        if (error == null) {
            jetty.moveTo([3,0]).erase(30).moveTo([3,0]).rgb([0,255,0]);
            jetty.text('Updated successfully');
        } else {
            jetty.moveTo([3,0]).erase(30).moveTo([3,0]).rgb([5, 0, 0]);
            jetty.text('ERROR while updating: ' + error);
        }
    });
});

jetty.moveTo([1,0]);
jetty.text('Listening for changes in ' + folder);

// Reset terminal color on exit
process.stdin.resume();
function exitHandler(options, exitCode) {
    jetty.rgb(255);
    if (options.exit) process.exit();
}
process.on('exit', exitHandler.bind(null,{cleanup:true}));
process.on('SIGINT', exitHandler.bind(null, {exit:true}));
process.on('SIGUSR1', exitHandler.bind(null, {exit:true}));
process.on('SIGUSR2', exitHandler.bind(null, {exit:true}));
process.on('uncaughtException', exitHandler.bind(null, {exit:true}));