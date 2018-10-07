<?php
/**
 * Journal: Simple Static Blog CMS
 * 
 * dashboard/routes: Declare all routes for Slim app
 * 
 * Licensed under MIT License
 * For full copyright and license information, please see the LICENSE file
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright (c) vantezzen (https://github.com/vantezzen/)
 * @link          https://github.com/vantezzen/journal
 * @license       https://opensource.org/licenses/mit-license.php MIT License
 */

// Index
$app->get('/', function($request, $response, $args) use ($core) {
    $url = $request->getUri()->getBaseUrl();

    return $core->component('dashboard')->renderIndex($url);
});


// Write/create/edit post
$app->get('/write[/[{edit}[/]]]', function($request, $response, $args) use ($core) {
    if (isset($args['edit'])) {
        $post = $core->component('database')->table('posts')->select(['id' => $args['edit']])->selected()[0];
    } else {
        $post = false;
    }

    $url = $request->getUri()->getBaseUrl();

    return $core->component('dashboard')->renderWrite($post, $url);
});

// Save post
$app->post('/save[/]', function($request, $response, $args) use ($core) {
    $data = $request->getParsedBody();

    // Check if new post or edit existing post
    if (isset($data['edit'])) {
        $id = $data['edit'];
        $edit = true;
    } else {
        // Find ID for new post
        $posts = $core->component('database')->tableData('posts');
        $highestId = 0;
        foreach($posts as $post) {
            if ($post['id'] > $highestId) {
                $highestId = $post['id'];
            }
        }
        $id = $highestId + 1;
        $edit = false;
    }

    // Escape post data
    $text = $core->component('escape')->escape($data['text']);
    $title = $core->component('escape')->escape($data['title']);

    // Prepare data for insert/update into table
    $data = [
        'id' => $id,
        'title' => $title,
        'text' => $text,
        'revision' => 1,
        'updated' => time()
    ];
    if ($edit == false) {
        $data['created'] = time();
    }

    // Insert or update table
    if ($edit) {
        $core
            ->component('database')
            ->table('posts')
            ->select(['id' => $id])
            ->update($data)
            ->save();
    } else {
        $core
            ->component('database')
            ->table('posts')
            ->insert($data)
            ->save();
    }

    // Regenerate static files
    $core->component('convert')->all();

    // Upload to server
    $core->component('upload')->post($data);

    // Redirect to edit page
    $url = $request->getUri()->getBaseUrl();
    return $response->withHeader('Location', $url . '/write/' . $id);
});

// Delete post
$app->post('/delete[/]', function($request, $response, $args) use ($core) {
    $data = $request->getParsedBody();

    $id = $data['delete'];

    $core
        ->component('database')
        ->table('posts')
        ->select(['id' => $id])
        ->delete()
        ->save();

    // Regenerate static files
    $core->component('convert')->all();

    // Redirect to homepage
    $url = $request->getUri()->getBaseUrl();
    return $response->withHeader('Location', $url);
});


// Uploading page
$app->get('/uploading[/]', function($request, $response, $args) use ($core) {
    $url = $request->getUri()->getBaseUrl();
    return $core->component('dashboard')->renderUploading($url);
});


// Settings page
$app->get('/settings[/]', function($request, $response, $args) use ($core) {
    $url = $request->getUri()->getBaseUrl();
    return $core->component('dashboard')->renderSettings($url);
});

// Save settings
$app->post('/settings[/]', function($request, $response, $args) use ($core) {
    $data = $request->getParsedBody();

    $db = $core->component('database')->table('settings');
    $oldsettings = $core->settings;

    // Update settings table with new data
    foreach($data as $key => $value) {
        $db->select(['key' => $key]);
        if (count($db->selected()) == 0) {
            $db->insert(['key' => $key, 'value' => $value]);
        }  else {
            $db->update(['value' => $value]);
        }
    }
    $db->save();
    
    // Reload settings
    $core->component('database')->loadSettings();

    // Check if need to upload blog
    if (
        $core->setting('upload_uploader') !== 0 &&      // Uploader specified
        (!$core->component('upload')->serverHasBlog()  // Server doesn't have blog on it
        || (!isset($oldsettings['theme']) && $data['theme'] !== 'default') // Theme has been set initially
        || (isset($oldsettings['theme']) && $oldsettings['theme'] !== $data['theme'])) // Theme has been changed
    ) {
        // Redirect to upload blog
        $url = $request->getUri()->getBaseUrl();
        return $response->withHeader('Location', $url . '/perform_upload.php');
    } else {
        // Redirect to generate page to regenerate pages
        $url = $request->getUri()->getBaseUrl();
        return $response->withHeader('Location', $url . '/generate');
    }
});


// Force regenerate page
$app->get('/generate[/]', function($request, $response, $args) use ($core) {
    // Regenerate static files
    $core->component('convert')->all();

    // Redirect back to settings page
    $url = $request->getUri()->getBaseUrl();
    return $response->withHeader('Location', $url . '/settings');
});


// Install page
$app->get('/install[/]', function($request, $response, $args) use ($core) {
    $url = $request->getUri()->getBaseUrl();

    // Check if installation has already been completed
    $db = $core->component('database')->table('settings');
    $db->select(['key' => 'installation_done']);
    if (count($db->selected()) > 0) {
        return $response->withHeader('Location', $url);
    }

    return $core->component('dashboard')->renderInstall($url);
});

// Do installation page
$app->post('/install[/]', function($request, $response, $args) use ($core) {
    $db = $core->component('database')->table('settings');

    $db->select(['key' => 'installation_done']);
    if (count($db->selected()) == 0) {
        $db->insert(['key' => 'installation_done', 'value' => 'true'])
        ->save();
    }

    $url = $request->getUri()->getBaseUrl();
    return $response->withHeader('Location', $url . '/settings');
});


// Update page
$app->get('/update[/]', function($request, $response, $args) use ($core) {
    $url = $request->getUri()->getBaseUrl();

    return $core->component('dashboard')->renderUpdate($url);
});

// Do update page
$app->post('/update[/]', function($request, $response, $args) use ($core) {
    $core->component('update')->update();

    $url = $request->getUri()->getBaseUrl();
    return $core->component('dashboard')->renderUpdateDone($url);
});