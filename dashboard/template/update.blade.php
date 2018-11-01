@extends('base')

@section('content')
<style>
        .icon-check {
            color: #2ecc71;
        }
        .icon-close {
            color: #e74c3c;
        }
        .icon-info {
            color: #3498db;
        }
    </style>
    
    <h1>Update Journal</h1>
    <table>
        <tr>
            <th scope="row">Your current version</th>
            <td>{{ $current_version }}</td>
        </tr>
        <tr>
            <th scope="row">Newest version</th>
            <td>{{ $newest_version }}</td>
        </tr>
    </table>
    <b>What has changed:</b><br />
    <div class="changelog">
        {!! $changelog !!}
    </div>
    
    @if ($update_availible)
        @if ($has_errors)
            <ul class="list-unstyled mt-3">
                @foreach ($folders as $folder)
                    <li>
                        @if ($folder['hasPermissions'])
                            <span class="icon-check"></span>
                        @else
                            <span class="icon-close"></span>
                        @endif
                        Can read and write to <code>{{ $folder['folder'] }}</code>
                    </li>
                @endforeach
            </ul>
            <span class="icon-info"></span> Please make sure that Journal can read and write to all these folders before updating.
        @else
            <div class="mt-3">
                <span class="icon-info"></span> Journal is ready to update. We advice you to create a backup of Journal before starting the update to make sure no data can get lost. 
                <form action="{{ $base }}/update" method="post">
                    <button type="submit" class="btn btn-block btn-outline-dark mt-3"><span class="icon-rocket"></span> Update Journal</button>
                </form>
            </div>
        @endif
    @else
    <div class="mt-3">
        There is no update availible
    </div>
    @endif
@endsection