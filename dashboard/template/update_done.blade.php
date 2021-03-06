@extends('base')

@section('content')
<style>
    .huge-icon {
        display:flex;
        justify-content:center;
        align-items:center;
        font-size: 8rem;
        color: #2ecc71;
    }
</style>
<div class="huge-icon">
    <span class="icon-check"></span>
</div>
<h1 class="mt-4">Journal has been updated successfully.</h1>
<p>You can now continue writing awesome posts! If you want to know what has changed, take a look at <a href="https://github.com/vantezzen/journal/releases/latest">the changelog</a>.</p>
@endsection