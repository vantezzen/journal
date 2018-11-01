@extends('base')

@section('content')
<h1>{!! $title !!}</h1>
<p>{!! $text !!}</p>

{!! $comments !!}
@endsection