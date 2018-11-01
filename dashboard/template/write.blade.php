@extends('base')

@section('content')
<div class="container-fluid">
        <form action="{{ $base }}/save" method="post" id="form">
            <input 
                type="text" 
                class="form-control title" 
                id="title" 
                name="title" 
                placeholder="Add a title for your post..." 
                value="{{ $post_title }}">
            <div id="text" class="text"></div>
            <input type="hidden" name="text">
            {{-- <textarea id="text" placeholder="Write your post..." name="text" class="text">{{ $post_text }}</textarea> --}}
            @if ($edit)
            <input type="hidden" name="edit" value="{{ $post_id }}">
            @endif
    </div>
    <footer class="footer">
        <div class="container float-right">
            <button class="btn btn-outline-success" id="save-btn">Save</button>
            </form>
            @if ($edit)
                <button class="btn btn-outline-danger" id="delete-btn" data-toggle="modal" data-target="#delete">Delete post</button>
            @endif
            <button class="btn btn-outline-dark" id="toggle-autoformat">Disable Formatting Preview</button>
        </div>
    </footer>
    <div id="saving_screen">
        <div id="saving_content">
            <div class="icon-pencil"></div>
            <h3 class="mt-4">Saving...</h3>
        </div>
    </div>

    <script>
    // Lock inputs on save and delete
    document.addEventListener('DOMContentLoaded', function() {
        $('#save-btn').click(function() {
            $('#confirm-delete').prop('disabled', true);
            $('#save-btn').prop('disabled', true);
            $('#delete-btn').prop('disabled', true);
            $('#text').prop('readonly', true);
            $('#title').prop('readonly', true);
    
            $('#saving_screen').css('display', 'flex');
    
            return true;
        });
        $('#confirm-delete').click(function() {
            $('#confirm-delete').prop('disabled', true);
            $('#save-btn').prop('disabled', true);
            $('#delete-btn').prop('disabled', true);
            $('#text').prop('readonly', true);
            $('#title').prop('readonly', true);
            return true;
        });
    });
    </script>
    
    <div class="modal fade" id="delete" tabindex="-1" role="dialog" aria-labelledby="deleteTitle" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="deleteTitle">Delete post</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                Are you sure you want to delete this post? This step can't be reversed
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-outline-success" data-dismiss="modal">Cancel</button>
                <form action="{{ $base }}/delete" method="post">
                    <input type="hidden" name="delete" value="{{ $post_id }}">
                    <button type="submit" class="btn btn-outline-danger" id="confirm-delete">Delete</button>
                </form>
            </div>
            </div>
        </div>
        </div>
@endsection

@section('styles')
    <link href="{{ $base }}/assets/css/quill.css" rel="stylesheet">    
@endsection

@section('scripts')
    <script>
        window.Journal.text = `{!! str_replace("`", "\\`", $post_text)  !!}`;
    </script>
    <script src="{{ $base }}/assets/js/intelliformat.js"></script>
    <script src="{{ $base }}/assets/js/quill.js"></script>
    <script src="{{ $base }}/assets/js/write.js"></script>
@endsection