@extends('base')

@section('content')
    @foreach ($posts as $post)
        <div class="card">
            <div class="card-body">
                <h4>{!! $post['title'] !!}</h4>
                <p>{!! $post['trimmedText'] !!}</p>
                <a href="{{ $post['path'] }}" class="btn btn-block btn-outline-dark">Read more</a>
            </div>
        </div>
    @endforeach

    @if($pagination)
        <div class="row mt-3">
            <div class="col">
                @if($prev)
                    <a href="{{ $prev }}" class="btn btn-block btn-outline-dark">&lt; Previous page</a>
                @endif
            </div>
            <div class="col">
                @if ($next)
                    <a href="{{ $next }}" class="btn btn-block btn-outline-dark">Next page &gt;</a>
                @endif
            </div>
        </div>
    @endif
@endsection