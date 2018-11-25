@extends('base')

@section('content')
<a href="{{ $url }}" class="btn btn-block btn-outline-dark my-3">Go back</a>
<h1>{!! $post['title'] !!}</h1>
<p>{!! $post['text'] !!}</p>

{!! $post['comments'] !!}
@endsection