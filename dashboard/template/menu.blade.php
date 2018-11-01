@extends('base')

@section('content')
<h1>Menu</h1>
<ul class="list-group" id="menu_items">
    @foreach($items as $item)
        <li class="list-group-item menu-list-item" draggable="true">
            <div class="form-group">
                <label>Link text</label>
                <input type="text" class="form-control item_text" placeholder="Enter text" value="{{ $item['text'] }}">
                <small class="form-text text-muted">When clicked, this text will redirect to the specified page.</small>
            </div>
            <div class="form-group">
                <label>URL</label>
                <input type="url" class="form-control item_url" placeholder="https://example.com" value="{{ $item['url'] }}">
                <small class="form-text text-muted">Page URL to redirect to after clicking the link.</small>
            </div>
            <button type="button" class="btn btn-block btn-outline-dark delete-btn">Delete link</button>
        </li>
    @endforeach
</ul>
<button type="button" id="add_item" class="btn btn-block btn-outline-dark mt-3"><span class="icon-plus"></span> Add item</button>

<button type="button" id="save_menu" class="btn btn-block btn-outline-dark mt-3"><span class="icon-check"></span> Save</button>

<!-- Hidden Form for submitting menu items -->
<form action="{{ $base }}/menu" method="post" id="menu_form">
    <input type="hidden" name="menu_list" value="" id="menu_list">
</form>

<div id="item_template">
    <li class="list-group-item menu-list-item" draggable="true">
        <div class="form-group">
            <label>Link text</label>
            <input type="text" class="form-control item_text" placeholder="Enter text">
            <small class="form-text text-muted">When clicked, this text will redirect to the specified page.</small>
        </div>
        <div class="form-group">
            <label>URL</label>
            <input type="url" class="form-control item_url" placeholder="https://example.com">
            <small class="form-text text-muted">Page URL to redirect to after clicking the link.</small>
        </div>
        <button type="button" class="btn btn-block btn-outline-dark delete-btn">Delete link</button>
    </li>
</div>

<script>
/*
 * Drag and drop behaviour for list items
 * 
 * Source: https://stackoverflow.com/a/27796275
 */ 
// Currently dragged element
var dragSrcEl = null;

// Data of the dragged element
var draggedData = {
    text: '',
    url: ''
};

// Start dragging
function handleDragStart(e) {
    this.style.opacity = '0.4';

    dragSrcEl = this;

    e.dataTransfer.effectAllowed = 'move';
    e.dataTransfer.setData('text/html', this.innerHTML);

    // Set dragged data for data swapping
    draggedData = {
        text: $(this).find('.item_text').val(),
        url: $(this).find('.item_url').val()
    };
}

function handleDragOver(e) {
    if (e.preventDefault) {
        e.preventDefault();
    }

    e.dataTransfer.dropEffect = 'move';

    return false;
}

function handleDragEnter(e) {
    this.classList.add('over');
}

function handleDragLeave(e) {
    this.classList.remove('over');
}

// Handle item drop
function handleDrop(e) {
    if (e.stopPropagation) {
        e.stopPropagation();
    }

    // Check if not dropping on same element
    if (dragSrcEl != this) {
        // Swap input values
        // Set dragged element inputs to current inputs
        $(dragSrcEl).find('.item_text').val(
            $(this).find('.item_text').val()
        );
        $(dragSrcEl).find('.item_url').val(
            $(this).find('.item_url').val()
        );

        // Set current inputs to dragged element inputs
        $(this).find('.item_text').val(draggedData.text);
        $(this).find('.item_url').val(draggedData.url);
    }

    // Set opacity back to 100%
    dragSrcEl.style.opacity = '1';

    return false;
}

function handleDragEnd(e) {
    [].forEach.call(cols, function (col) {
        col.classList.remove('over');
    });
}

// Add listeners
var cols = document.querySelectorAll('#menu_items .list-group-item');
[].forEach.call(cols, function (col) {
    col.addEventListener('dragstart', handleDragStart, false);
    col.addEventListener('dragenter', handleDragEnter, false)
    col.addEventListener('dragover', handleDragOver, false);
    col.addEventListener('dragleave', handleDragLeave, false);
    col.addEventListener('drop', handleDrop, false);
    col.addEventListener('dragend', handleDragEnd, false);
});


/*
 * Convert menu items to JSON array 
 */
function getItemList() {
    let items = [];
    $('#menu_items .list-group-item').each(function() {
        items.push({
            'text': $(this).find('.item_text').val(),
            'url': $(this).find('.item_url').val()
        });
    });
    return JSON.stringify(items);
}

function removeItem() {
    this.parentElement.remove();
}

document.addEventListener('DOMContentLoaded', function() {
    // Add item button
    $('#add_item').click(function() {
        let element = $('#item_template').children().first().clone();
        let col = element.get()[0];

        // Add event listeners for dragging
        col.addEventListener('dragstart', handleDragStart, false);
        col.addEventListener('dragenter', handleDragEnter, false)
        col.addEventListener('dragover', handleDragOver, false);
        col.addEventListener('dragleave', handleDragLeave, false);
        col.addEventListener('drop', handleDrop, false);
        col.addEventListener('dragend', handleDragEnd, false);

        // Add event listener for deleting
        $(col).find('.delete-btn').click(removeItem);

        $('#menu_items').append(element);
    });

    // Add event listeners for deleting
    $('.delete-btn').click(removeItem);

    // Save menu
    $('#save_menu').click(function() {
        $('#menu_list').val(getItemList());
        $('#menu_form').submit();
    });
});
</script>

<style>
.menu-list-item {
    cursor: grab;
}
#item_template {
    display: none;
}
</style>
@endsection