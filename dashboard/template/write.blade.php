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
            <textarea id="text" placeholder="Write your post..." name="text" class="text">{{ $post_text }}</textarea>
            @if ($edit)
            <input type="hidden" name="edit" value="{{ $post_id }}">
            @endif
    </div>
    <footer class="footer">
        <div class="container float-right">
            <button class="btn btn-outline-success" id="save-btn">Save</button>
            </form>
            @if (!$post_published)
                <button class="btn btn-outline-dark" id="publish-btn">Save and publish</button>
            @endif
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
                <form action="{{ $base }}/delete" method="post" id="delete_form">
                    <input type="hidden" name="delete" value="{{ $post_id }}">
                    <button type="submit" class="btn btn-outline-danger" id="confirm-delete">Delete</button>
                </form>
            </div>
            </div>
        </div>
        </div>
@endsection

@section('scripts')
    <script src="{{ $base }}/assets/js/write.js"></script>
@endsection