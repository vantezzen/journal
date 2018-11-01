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
    <span class="icon-cloud-upload"></span>
</div>
<h1 class="mt-4">Uploading...</h1>
<p>Journal will upload your files to your specified server in the background - this might take a few minutes. You can continue using Journal in that time.</p>
<a href="{{ $base }}" class="btn btn-block btn-outline-dark"><span class="icon-home"></span> Go back to posts</a>
@endsection