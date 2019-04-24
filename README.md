<p align="center"><img src="assets/img/logo.png"></p>

# Archived
This project is no longer maintained and has been archived.

I originally created this project for creating a small, static blog on my own website but as I removed this blog I no longer have any use for Journal.

Table of Contents
- [About Journal](#about-journal)
- [Features](#features)
- [Installing Journal](#installing-journal)
- [Demo](#demo)
- [Screenshots](#screenshots)
- [Security](#security)
- [Official themes](#official-themes)
- [Comments](#comments)
- [Automatic uploading](#automatic-uploading)
- [IntelliFormat](#intelliformat)
- [Markdown support](#markdown-support)
- [How can I publish my blog on...](#how-can-i-publish-my-blog-on)
    - [...my (S)FTP server, AWS S3 Bucket or DigitalOcean Spaces](#my-sftp-server-aws-s3-bucket-or-digitalocean-spaces)
    - [...Netlify](#netlify)
    - [...GitHub Pages](#github-pages)
    - [...Dropbox or my WebDAV server](#dropbox-or-my-webdav-server)
- [Custom themes](#custom-themes)
    - [Variables](#variables)
    - [Files and folders](#files-and-folders)
        - [home.blade.php](#homebladephp)
        - [post.blade.php](#postbladephp)
        - [assets/](#assets)
    - [Supporting Journal features in custom themes](#supporting-journal-features-in-custom-themes)
        - [Menu support](#menu-support)
        - [Pagination support](#pagination-support)
    - [Theme watcher](#theme-watcher)
- [Backing up](#backing-up)
- [License](#license)

# About Journal
Journal is a simple CMS for static blogs. It aims to simplify the creation and maintenance of blogs, letting the author focus on writing posts instead of wasting time on the CMS.

# Features
- Simple, minimalistic dashboard
- Easy installation
- [Automatic upload of static blog files to server](#automatic-uploading)
- No SQL-/Database-Server required
- [Comment support through Disqus](#comments)
- [Intelligent post formatting](#intelliformat)
- [Markdown support](#markdown-support)
- Custom theme support
- Custom theme creation with [Laravel Blade](https://laravel.com/docs/5.7/blade) views
- Easy theme developement through [theme watcher](#theme-watcher)
- Build-in updater

# Installing Journal
1. Download the latest release from [https://github.com/vantezzen/journal/releases](https://github.com/vantezzen/journal/releases) or - if you're feeling dangerous - download the current developement version from [https://github.com/vantezzen/journal/archive/master.zip](https://github.com/vantezzen/journal/archive/master.zip). If you download a release version you can choose between the GitHub version (`Source Code`) or a full Journal version with composer dependencies pre-installed (`Full`).
2. If you downloaded the `Source Code` release or the developement version you'll have to install composer dependencies via `composer install`. If you downloaded the `Full` release version this step is not required.
3. Unpack the files to a local PHP server and make sure PHP can read and write to these folders:
    - public/
    - tables/
4. Open index.php through your webserver and start using Journal
5. Once you create a new post you'll see files in the public/ folder. This is where Journal will put your static blog files. You can now upload these files to a static webserver.

# Demo
You can use a static demo of the Journal dashboard at [https://vantezzen.github.io/journal/](https://vantezzen.github.io/journal/).

# Screenshots
<p align="center"><img src="assets/img/screenshot_1.png"></p>
"Posts" list. This is where you can see all your posts and edit them.
<p align="center"><img src="assets/img/screenshot_2.png"></p>
Create post

# Security
Journals dashboard is **_in no way_** meant to be used on a public server. There is no login screen, your settings (including server credentials when using automatic uploading) are stored in plain text in a publicly availible folder. Please **_only_** use Journal on a local or otherwise protected webserver.

Please note that these warning do not apply to the final static files stored in the `public/` folder.

# Official themes
- default (build-in, very simple theme to get developers started)
- [clean_blog](https://github.com/vantezzen/journal-cleanblog)
- [gin](https://github.com/vantezzen/journal-gin)

# Comments
Journal allows you to add a Disqus comment section to every post. To activate comments, in your Journal dashboard, go to "Comments", as the Provider select "Disqus". You then need to get the identifier of your Disqus admin account (`my-blog-6`.disqus.com). After saving, the comments section shoud appear at the end of your post.

# Automatic uploading
Journal can upload files to your webserver. To activate this feature, in your Journal dashboard, go to "Upload", choose an upload method (FTP, SFTP etc.) and enter your server credentials. 

Journal currently supports the following upload methods:
- FTP
- SFTP
- Amazon AWS S3
- DigitalOcean Spaces

You should now see a "Upload files to server" button on your "Posts" page. When clicked, Journal will upload your current static files to your server, overwriting the old ones but not deleting any other files.

# IntelliFormat
Journal uses a system called `IntelliFormat` to apply different formatting methods to the posts.
These formatting methods are:
- Links: Automatically convert Links into clickable HTML-Links
- Headings: Automatically find headings and make them bigger
- Markdown: Convert Markdown formatting to HTML

The goal of IntelliFormats formatting methods (except Markdown) is to intelligentely and automatically apply formatting to posts without the user having to add them. This is why - by default - IntelliFormats Markdown formatter is disabled.

All formatting methods can be enabled and disabled through Settings > Formatting

# Markdown support
Journal supports Markdown through the PHP library [parsedown](https://github.com/erusev/parsedown). Markdown formatting needs to be enabled first through Settings > Formatting > Markdown. When enabling Markdown it is highly recommended to disable all other [IntelliFormat](#intelliformat) formatters as they can interfere with the Markdown formatting.

# How can I publish my blog on...
After creating your blog you can publish it using different methods - here are the most common ones:
## ...my (S)FTP server, AWS S3 Bucket or DigitalOcean Spaces
Journal supports automatic uploading to (S)FTP servers, AWS S3 Buckets and DigitalOcean Spaces. You can activate it in Journals' settings under "Upload".

## ...Netlify
Unfortunately, Journal does not support Netlify by default. You can however create a new git repository in Journals' `public/` directory and manually commit and push changes to your blog - changes should only happen when you change Journals' settings or create or edit post.
You can now choose this git repository as your Netlify repository.

## ...GitHub Pages
See [How can I publish my blog on Netlify](#netlify).

## ...Dropbox or my WebDAV server
Dropbox and WebDAV support will be added in one of the next versions of Journal.

# Custom themes
Journal allows for the creation of custom themes. It utilizes [Laravel Blade](https://laravel.com/docs/5.7/blade) to turn themes into pages. To see an example theme, look at `themes/default/`.
Every theme has its own folder in `themes/` with its name. It is highly adviced to have a basic understanding of the syntax of [Laravel Blade](https://laravel.com/docs/5.7/blade) before creating custom themes.

## Variables
Journal settings are availible in custom themes by their internal name (look at `tables/settings.csv` for setting names). There are some additional variables (mainly `$menu`) availible.

Most important variables are:
- $title : Title of the page
- $description : Blog description
- $copyright : Blog copyright text
- $language : Language code of the blogs language (for use in html lang tag)
- $url : URL of the final blog (e.g. https://example.com)
- [$menu](#menu-support) : Menu array


## Files and folders

Journal requires the following files and folders to be existent in a themes folder:

### home.blade.php
This file will be used to create your blog homepage. You should loop through the `$posts` array to create a list of posts. This array will be pre-shortened if pagination is enabled.
You should add [support for pagination](#pagination-support) to this file.

Additional variables availible in this view are:
- $posts : Array of all posts
- [$pagination](#pagination-support) : True, if pagination is activated
- [$prev](#pagination-support) : Previous page of pagination
- [$next](#pagination-support) : Next page of pagination

Each element of `$posts` contains:
- title : Title of the post. This should always be inserted unescaped as the title has already been escaped
- text : Full post text. This should always be inserted unescaped as the text has already been escaped
- trimmedText : Trimmed text to 200 characters
- path : Relative path to the post (e.g. my-post.html)
- url : Absolute URL to the post (e.g. https://example.com/my-post.html)
- id : ID of the post

Example home.blade.php
```html
@extends('base')

@section('content')
    @foreach ($posts as $post)
        <h1>{!! $post['title'] !!}</h1>
        <p>{!! $post['trimmedText'] !!}</p>
        <a href="{{ $post['path'] }}">Open post</a>
        <a href="{{ $post['url'] }}">Absolute URL</a>
    @endforeach

    @if($pagination)
        <br />
        @if($prev)
            <a href="{{ $prev }}">&lt;Previous page</a>
        @endif
        @if ($next)
            <a href="{{ $next }}">Next page &gt;</a>
        @endif
    @endif
@endsection
```
### post.blade.php
This file will be used to create pages for all posts.

Additional variables availible in this view are:
- $post: Data for current post:
    - ['title']: Title of the post. This should always be inserted unescaped as the title has already been escaped
    - ['text']: Full post text. This should always be inserted unescaped as the title has already been escaped
    - ['path']: Relative path to the post (e.g. my-post.html)
    - ['comments']: Code for comment section. This should always be inserted unescaped.*

*This will be replaced with the comments section of the chosen comment provider when generating static pages. 

Example post.blade.php
```html
@extends('base')

@section('content')
<h1>{!! $post['title'] !!}</h1>
<p>{!! $post['text'] !!}</p>

{!! $post['comments'] !!}
@endsection
```

### assets/
The assets folder should contain all additional assets that are required for the theme (i.e. css and js files). This folder will be copied to public/assets/ automatically.

Example assets/
```
assets/
    css/
        style.css
        bootstrap.min.css
    js/
        plugins.js
        main.js
        jquery.min.js
        bootstrap.min.js
```


## Supporting Journal features in custom themes
Journal ships with features that allow you to customize the theme without having to edit the theme source files. In order for these features to work, the theme has to have build-in support - otherwise these features can't be used.

### Menu support
Journal has a build-in menu editor that allows adding custom links to the main page menu. To support menues, add a `menu` area to on of your base files. Inside this area you can use the variables `url` and `text` to create the links

Example menu:
```html
@foreach ($menu as $menuItem)
    <a href="{{ $menuItem['url'] }}">{{ $menuItem['text'] }}</a>
@endforeach
```

### Pagination support
Journal allows you to add automatic pagination to your page. To implement pagination into your theme, add a `pagination` area to your [home.blade.php](#homebladephp). This area will only be shown if pagination is activated. Inside the `pagination` area add a `prev` and `next` area for the previous and next page. Inside these you can use the variables `prev` and `next` as URLs for the previous and next page. The `prev` and `next` areas will be hidden if there is no previous or next page availible.

Example pagination:
```html
@if($pagination)
    @if($prev)
        <a href="{{ $prev }}">&lt;Previous page</a>
    @endif
    @if ($next)
        <a href="{{ $next }}">Next page &gt;</a>
    @endif
@endif
```

## Theme watcher
When developing themes it can get very annoying to regenerate static blog files on every change in the theme. To help with this work, Journal comes with a theme watcher. The theme watcher will watch for changes in your theme folder and automatically regenerate the static blog files.
Journals theme watcher is build with Node.JS. You will first need to install its dependencies with
```bash
npm install
``` 
You can then start the theme watcher via
```bash
npm run watch [theme name]
```
replacing `[theme name]` with the name of your themes folder.

Theme watcher will now watch your folder and trigger a regeneration when a file changes. It is not required to change Journals theme to the developed theme - it will automatically be used for regeneration of static files when using the theme watcher.

# Backing up
Backuping up Journal is easy: Simply backup Journals `tables/`, `public/` and `themes/` folder. If something goes wrong, redownload Journal in the version you had installed and restore your backed-up folders.

# License
Journal is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).
