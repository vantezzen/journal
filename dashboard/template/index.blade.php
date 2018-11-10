@extends('base')

@section('content')
<div class="container-fluid">
    @if ($uploading)
    <div class="alert alert-secondary" role="alert">
        <span class="icon-info"></span> Journal is currently uploading files to your server in the background - this might take a few minutes but you can continue using Journal in that time. This message will dissapear as soon as uploading is done.
    </div>
    @endif
    @if ($uploadable)
    <form action="{{ $base }}/" method="post">
        <button class="btn btn-block btn-dark" type="submit">
            <span class="icon-cloud-upload"></span> 
            @if ($au_enabled)
            Pause automatic uploading
            @else
            Resume automatic uploading
            @endif
        </button>
    </form>
    @endif
    @foreach($posts as $post)
    <div class="card">
        <div class="card-body">
            <h4>{!! $post['title'] !!}</h4>
            @if (!$post['published'])
            <div class="text-muted">Not yet published</div>
            @endif
            <p>{!! $post['text'] !!}</p>
            <a href="{{ $base }}/write/{{ $post['id'] }}" class="btn btn-block btn-outline-dark"><span class="icon-pencil"></span> Edit</a>
        </div>
    </div>
    @endforeach
    @if(count($posts) === 0)
        <h1>So empty in here!</h1>
        <p>Start your blog by <a href="{{ $base }}/write">creating your first post</a>.</p>
    @endif
</div>
@endsection