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