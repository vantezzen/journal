@extends('base')

@section('content')
<h1>{!! $post['title'] !!}</h1>
<p>{!! $post['text'] !!}</p>

{!! $post['comments'] !!}
@endsection