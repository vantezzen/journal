@extends('base')

@section('content')
<h1>Settings</h1>
@if ($update)
<div class="card mb-2" role="alert">
    <div class="card-body">
        <span class="icon-info"></span> There is a new update availible for Journal adding new features and fixing bugs.<br />
        <a href="{{ $base }}/update" class="btn btn-block btn-outline-dark mt-4"><span class="icon-rocket"></span> Update Journal now</a>
    </div>
</div>
@endif
<p>Sections: <a href="#general">Journal</a> | <a href="#formatting">Formatting</a> | <a href="#blog">Blog</a> | <a href="#comments">Comments</a> | <a href="#upload">Upload</a></p>
<form action="{{ $base }}/settings" method="post">
    <button type="submit" class="btn btn-block btn-outline-dark submit-btn"><span class="icon-check"></span> Save</button>
    <small class="form-text text-muted">Saving will regenerate your static pages. This might take a few seconds.</small>

    <h3 class="mt-4" id="general">Journal</h3>
    <p class="text-muted">Settings abut your Journal installation.</p>
    <div class="form-group">
        <label for="search_updates">Search for updates</label>
        <select class="form-control" name="search_updates" id="search_updates" data-placeholder="Choose..." data-value="{{ $search_updates }}">
            <option value="yes">Yes, always search for updates</option>
            <option value="no">No, do not always search for updates</option>
        </select>
        <small class="form-text text-muted">By default, Journal searches for updates on page load. If a new update exists a red dot next to "Settings" in the sidebar will appear - this can increase page load time. You can search for updates manually by going to "Search for updates" in the settings even after disabling automatic search.</small>
    </div>
    
    <h3 class="mt-4" id="formatting">Formatting</h3>
    <p>Journal tries to intelligentely and automatically format your text - this way you can focus on the interesting part: writing posts. You can enable and disable formatting methods if you want to.</p>
    <div class="form-group">
        <label for="intelliformat_links">Links</label>
        <select class="form-control" name="intelliformat_links" id="intelliformat_links" data-placeholder="Choose..." data-value="{{ $intelliformat_links }}">
            <option value="yes">Enable</option>
            <option value="no">Disable</option>
        </select>
        <small class="form-text text-muted">Links will search for links to other pages in your text and turn them into clickable links. If disabled, all your links will not be clickable.</small>
    </div>
    <div class="form-group">
        <label for="intelliformat_markdown">Markdown</label>
        <select class="form-control" name="intelliformat_markdown" id="intelliformat_markdown" data-placeholder="Choose..." data-value="{{ $intelliformat_markdown }}">
            <option value="yes">Enable</option>
            <option value="no" selected>Disable</option>
        </select>
        <small class="form-text text-muted">Markdown will convert <a href="https://github.com/adam-p/markdown-here/wiki/Markdown-Cheatsheet" target="_blank">Markdown</a> to HTML. If disabled, no markdown will be converted. <br />If you do not use Markdown, it is adviced to disable it. <br /><span class="icon-info"></span> If you do enable Markdown it is advices to disable all other formatters, especially Links and Headings, as they can interfere with Markdown formatting</small>
    </div>
    <div class="form-group">
        <label for="intelliformat_headings">Headings</label>
        <select class="form-control" name="intelliformat_headings" id="intelliformat_headings" data-placeholder="Choose..." data-value="{{ $intelliformat_headings }}">
            <option value="yes">Enable</option>
            <option value="no">Disable</option>
        </select>
        <small class="form-text text-muted">Heading will search for possible headings in your text an make them bigger.</small>
    </div>
    <div class="form-group">
        <label for="intelliformat_code">Code</label>
        <select class="form-control" name="intelliformat_code" id="intelliformat_code" data-placeholder="Choose..." data-value="{{ $intelliformat_code }}">
            <option value="yes">Enable</option>
            <option value="no" selected>Disable</option>
        </select>
        <small class="form-text text-muted">Code will search for programming code in your post and format it accordingly. You should only enable this if you plan on adding code to your posts.</small>
    </div>
    <h3 class="mt-4" id="blog">Blog</h3>
    <p class="text-muted">Settings abut your Journal blog.</p>
    <div class="form-group">
        <label for="title">Title</label>
        <input type="text" class="form-control" id="title" name="title" placeholder="My beautiful blog" value="{{ $title }}">
        <small class="form-text text-muted">The title will be used as a heading for your blog</small>
    </div>
    <div class="form-group">
        <label for="description">Description</label>
        <input type="text" class="form-control" id="description" name="description" placeholder="This is where I write posts" value="{{ $description }}">
        <small class="form-text text-muted">Describe your blog in a few sentences</small>
    </div>
    <div class="form-group">
        <label for="url">URL</label>
        <input type="text" class="form-control" id="url" name="url" placeholder="https://example.com" value="{{ $url }}">
        <small class="form-text text-muted">What is the URL your blog will be located at (e.g. https://example.com)<br /><span class="icon-info"></span> Changing this option after enabling comments can lead to a loss of all comments as post urls will change</small>
    </div>
    <div class="form-group">
        <label for="language">Language</label>
        <select class="form-control" name="language" id="language" data-placeholder="Choose a Language..." data-value="{{ $language }}">
            <option value="" selected>Choose a language...</option>
            <option value="AF">Afrikanns</option>
            <option value="SQ">Albanian</option>
            <option value="AR">Arabic</option>
            <option value="HY">Armenian</option>
            <option value="EU">Basque</option>
            <option value="BN">Bengali</option>
            <option value="BG">Bulgarian</option>
            <option value="CA">Catalan</option>
            <option value="KM">Cambodian</option>
            <option value="ZH">Chinese (Mandarin)</option>
            <option value="HR">Croation</option>
            <option value="CS">Czech</option>
            <option value="DA">Danish</option>
            <option value="NL">Dutch</option>
            <option value="EN">English</option>
            <option value="ET">Estonian</option>
            <option value="FJ">Fiji</option>
            <option value="FI">Finnish</option>
            <option value="FR">French</option>
            <option value="KA">Georgian</option>
            <option value="DE">German</option>
            <option value="EL">Greek</option>
            <option value="GU">Gujarati</option>
            <option value="HE">Hebrew</option>
            <option value="HI">Hindi</option>
            <option value="HU">Hungarian</option>
            <option value="IS">Icelandic</option>
            <option value="ID">Indonesian</option>
            <option value="GA">Irish</option>
            <option value="IT">Italian</option>
            <option value="JA">Japanese</option>
            <option value="JW">Javanese</option>
            <option value="KO">Korean</option>
            <option value="LA">Latin</option>
            <option value="LV">Latvian</option>
            <option value="LT">Lithuanian</option>
            <option value="MK">Macedonian</option>
            <option value="MS">Malay</option>
            <option value="ML">Malayalam</option>
            <option value="MT">Maltese</option>
            <option value="MI">Maori</option>
            <option value="MR">Marathi</option>
            <option value="MN">Mongolian</option>
            <option value="NE">Nepali</option>
            <option value="NO">Norwegian</option>
            <option value="FA">Persian</option>
            <option value="PL">Polish</option>
            <option value="PT">Portuguese</option>
            <option value="PA">Punjabi</option>
            <option value="QU">Quechua</option>
            <option value="RO">Romanian</option>
            <option value="RU">Russian</option>
            <option value="SM">Samoan</option>
            <option value="SR">Serbian</option>
            <option value="SK">Slovak</option>
            <option value="SL">Slovenian</option>
            <option value="ES">Spanish</option>
            <option value="SW">Swahili</option>
            <option value="SV">Swedish </option>
            <option value="TA">Tamil</option>
            <option value="TT">Tatar</option>
            <option value="TE">Telugu</option>
            <option value="TH">Thai</option>
            <option value="BO">Tibetan</option>
            <option value="TO">Tonga</option>
            <option value="TR">Turkish</option>
            <option value="UK">Ukranian</option>
            <option value="UR">Urdu</option>
            <option value="UZ">Uzbek</option>
            <option value="VI">Vietnamese</option>
            <option value="CY">Welsh</option>
            <option value="XH">Xhosa</option>
        </select>
        <small class="form-text text-muted">Choose the language your blog is written in</small>
    </div>
    <div class="form-group">
        <label for="copyright">Copyright</label>
        <input type="text" class="form-control" id="copyright" name="copyright" placeholder="Copyright 2018, John" value="{{ $copyright }}">
        <small class="form-text text-muted">Add a copyright text for your blog</small>
    </div>
    <div class="form-group">
        <label for="url_format">URL Format</label>
        <select class="form-control" name="url_format" id="url_format" data-placeholder="Choose a URL format..." data-value="{{ $url_format }}">
            <option value="1">[post id].html => https://example.com/1.html</option>
            <option value="2">[title slug].html => https://example.com/my-blog-article.html</option>
        </select>
        <small class="form-text text-muted">Choose the language your blog is written in<br /><span class="icon-info"></span> Changing this option after enabling comments can lead to a loss of all comments as post urls will change</small>
    </div>
    <div class="form-group">
        <label for="theme">Theme</label>
        <select class="form-control" name="theme" id="theme" data-placeholder="Choose a Theme..." data-value="{{ $theme }}">
            @foreach($availible_themes as $theme)
                <option value="{{ $theme }}">{{ $theme }}</option>
            @endforeach
        </select>
        <small class="form-text text-muted">You can install new themes by dragging them into the themes/ folder</small>
    </div>
    <div class="form-group">
        <label for="pagination">Pagination</label>
        <select class="form-control" name="pagination" id="pagination" data-placeholder="Choose..." data-value="{{ $pagination }}">
            <option value="yes">Enable</option>
            <option value="no">Disable</option>
        </select>
        <small class="form-text text-muted">Pagination will split your homepage into multiple pages. This should be enabled if you have many posts to make your homepage less long.</small>
    </div>
    <div class="form-group">
        <label for="pagination_steps">Pagination steps</label>
        <input type="number" class="form-control" id="pagination_steps" name="pagination_steps" placeholder="20" value="{{ $pagination_steps }}">
        <small class="form-text text-muted">Blog posts per pagination page. Pagination needs to be enabled first.</small>
    </div>


    <h3 class="mt-4" id="comments">Comments</h3>
    <p class="text-muted">You can allow visitors on your site to comment on posts. As your blog pages are static you'll want to use a third-party provider to manage comments for you.</p>
    <div class="form-group">
        <label for="comments_provider">Provider</label>
        <select class="form-control" name="comments_provider" id="comments_provider" data-placeholder="Choose a Provider..." data-value="{{ $comments_provider }}">
            <option value="0">None/Disable comments</option>
            <option value="disqus">Disqus</option>
            <option disabled>Commento (comming soon)</option>
        </select>
        <small class="form-text text-muted">Choose a provider to supply comment support</small>
    </div>
    <div class="form-group">
        <label for="comments_identifier">Identifier</label>
        <input type="text" class="form-control" id="comments_identifier" name="comments_identifier" placeholder="john-blog" value="{{ $comments_identifier }}">
        <small class="form-text text-muted">
            Identifier/Authentification for your selected provider.<br />
            <b>Disqus</b>: Your identifier is the subdomain name before .disqus.com (e.g. <b>my-blog-6</b>.disqus.com, identifier is 'my-blog-6')
        </small>
    </div>

    <h3 class="mt-4" id="upload">Upload</h3>
    <p class="text-muted">You can allow Journal access to your webserver so it can automatically upload your blog files to your server.</p>
    <div class="form-group">
        <label for="upload_uploader">Upload method</label>
        <select class="form-control" name="upload_uploader" id="upload_uploader" data-placeholder="Choose an upload type..." data-value="{{ $upload_uploader }}">
            <option value="0">None/Disable upload</option>
            <option value="ftp">FTP</option>
            <option value="sftp">SFTP</option>
            <option value="s3">AWS S3</option>
            <option value="dospaces">DigitalOcean Spaces</option>
        </select>
        <small class="form-text text-muted">Choose an upload method to use when uploading the files.</small>
    </div>
    <div class="form-group">
        <label for="upload_server">Server</label>
        <input type="text" class="form-control" id="upload_server" name="upload_server" placeholder="example.com" value="{{ $upload_server }}">
        <small class="form-text text-muted">
            Server to upload to (without method)
        </small>
    </div>
    <div class="form-group">
            <label for="upload_username">Port</label>
            <input type="text" class="form-control" id="upload_port" name="upload_port" value="{{ $upload_port }}">
            <small class="form-text text-muted">
                Only needed for (S)FTP. Port to use for the server. Leave empty for default port
            </small>
        </div>
    <div class="form-group">
        <label for="upload_username">Username or key</label>
        <input type="text" class="form-control" id="upload_username" name="upload_username" placeholder="root" value="{{ $upload_username }}">
        <small class="form-text text-muted">
            Username to upload with or key for the AWS S3/DigitalOcean Spaces account
        </small>
    </div>
    <div class="form-group">
        <label for="upload_password">Password or secret</label>
        <input type="password" class="form-control" id="upload_password" name="upload_password" placeholder="1234" value="{{ $upload_password }}">
        <small class="form-text text-muted">
            Password for the uploading user or secret key for the AWS S3/DigitalOcean Spaces account
        </small>
    </div>
    <div class="form-group">
        <label for="upload_path">Path</label>
        <input type="text" class="form-control" id="upload_path" name="upload_path" placeholder="/" value="{{ $upload_path }}">
        <small class="form-text text-muted">
            Path on the server in which to upload the files to. Leave empty for /
        </small>
    </div>
    <div class="form-group">
        <label for="upload_region">Region</label>
        <input type="text" class="form-control" id="upload_region" name="upload_region" placeholder="" value="{{ $upload_region }}">
        <small class="form-text text-muted">
            Only needed for AWS S3 and DigitalOcean Spaces. Region of the bucket
        </small>
    </div>
    <div class="form-group">
        <label for="upload_bucket">Bucket name</label>
        <input type="text" class="form-control" id="upload_bucket" name="upload_bucket" placeholder="" value="{{ $upload_bucket }}">
        <small class="form-text text-muted">
            Only needed for AWS S3 and DigitalOcean Spaces. Name of the bucket
        </small>
    </div>

    <button type="submit" class="btn btn-block btn-outline-dark submit-btn"><span class="icon-check"></span> Save</button>
    <small class="form-text text-muted">Saving will regenerate your static pages. This might take a few seconds.</small>
</form>
<a href="{{ $base }}/update" class="btn btn-block btn-outline-dark mt-4"><span class="icon-rocket"></span> Search for updates</a>
<small class="form-text text-muted">New versions of Journal will add new features and remove bugs.</small>

<a href="{{ $base }}/generate" class="btn btn-block btn-outline-dark mt-4" id="regen-btn"><span class="icon-chemistry"></span> Force static page regeneration</a>
<small class="form-text text-muted">Static pages will be automatically regenerated when you save a post. You only need to use this when you changed files.</small>


@if ($uploadable)
<a href="{{ page_url }}/perform_upload.php" class="btn btn-block btn-outline-dark mt-4"><span class="icon-cloud-upload"></span> Upload files to server</a>
<small class="form-text text-muted">Journal will re-upload all your files to the specified server in the background.</small>
@endif
@if ($uploading)
<button disabled class="btn btn-block btn-outline-dark mt-4"><span class="icon-cloud-upload"></span> Upload files to server</button>
<small class="form-text text-muted">Journal is currently uploading your files. Please wait until it has finished uploading before starting a new upload.</small>
@endif

<script>
document.addEventListener('DOMContentLoaded', function() {
    function selectValue(element) {
        let value = element.data('value');
        if (value !== '') {
            element.val(value);
        }
    }

    selectValue($('#language'));
    selectValue($('#url_format'));
    selectValue($('#theme'));
    selectValue($('#comments_provider'));
    selectValue($('#upload_uploader'));
    selectValue($('#search_updates'));
    selectValue($('#intelliformat_links'));
    selectValue($('#intelliformat_markdown'));
    selectValue($('#intelliformat_headings'));
    selectValue($('#intelliformat_code'));

    function disable() {
        $('.submit-btn').prop('disabled', true);
        $('.btn').prop('disabled', true);
        return true;
    }
    $('.submit-btn').click(disable);
    $('#regen-btn').click(disable);
}, false);
</script>
@endsection