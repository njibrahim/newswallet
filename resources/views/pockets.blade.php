@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="card">
                <div class="card-header">My Pockets</div>
                <div class="card-body">
                    <table class="table">
                        <thead>
                    <tr>
                        <th>Sn</th>
                        <th>Article</th>
                        <th>Author</th>
                        <th>Link</th>
                        <th>Pocketed</th>
                        <th>Action</th>
                    </tr> 
                    </thead>
                        @foreach($pockets as $p)
                        <tr>
                            <td>{{ @++$i }}</td>
                            <td>{{ $p->article->title }}</td>
                            <td>{{ $p->article->user->name }}</td>
                            <td><a href="{{ url("article/$p->article_id") }}" target="_blank">{{ url("article/$p->article_id") }}</a></td>
                            <td>{{ $p->created_at->format("M,d y H:i")}}</td>
                            <td><a href="{{ url("pockets?rm=$p->id") }}">Delete</a></td>
                        </tr>
                        @endforeach
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
