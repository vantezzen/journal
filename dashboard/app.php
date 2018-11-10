<?php
/**
 * Journal: Simple Static Blog CMS
 * 
 * dashboard/app: Prepare and run dashboard app
 * 
 * Licensed under MIT License
 * For full copyright and license information, please see the LICENSE file
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright (c) vantezzen (https://github.com/vantezzen/)
 * @link          https://github.com/vantezzen/journal
 * @license       https://opensource.org/licenses/mit-license.php MIT License
 */
// Bootstrap core
require 'bootstrap.php';

use Slim\App as Slim;

// Configure Slim settings
$configuration = [
    'settings' => [
        // Full error reporting
        'displayErrorDetails' => true,

        // Needed for installation middleware
        'determineRouteBeforeAppMiddleware' => true
    ],
];

// Set up Slim container
$container = new \Slim\Container($configuration);

// Add custom 404 Handler
$container['notFoundHandler'] = function ($c) use ($core) {
    return function ($request, $response) use ($c, $core) {
        $url = $request->getUri()->getBaseUrl();
        $content = $core->component('dashboard')->renderNotFound($url);
        return $c['response']
            ->withStatus(404)
            ->withHeader('Content-Type', 'text/html')
            ->write($content);
    };
};

// Set up Slim App
$app = new Slim($container);

// Add installation middleware
$app->add(function ($request, $response, $next) use ($core) {
    // Redirect to install page if not installed
    if (!strstr($request->getUri()->getPath(), 'install')) {
        $db = $core->component('database')->table('settings');
        $db->select(['key' => 'installation_done']);
        if (count($db->selected()) == 0) {
            $base = $request->getUri()->getBaseUrl();
            return $response->withHeader('Location', $base . '/install');
        }
    }

	$response = $next($request, $response);

	return $response;
});

// Get routes for Slim app
require 'routes.php';

// Run Slim
$app->run();