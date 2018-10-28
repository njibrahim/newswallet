@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="card">
                <div class="card-header">View Article</div>
                <div class="card-body">
                        <div class="media border p-3">
                          <div class="media-body">
                            @if(!$isPocketed)
                                <a href="{{url("article/$article->id?pocket=true")}}" class="btn btn-primary float-right">Pocket Article</a>
                            @endif

                            <h4>{{ $article->title }}</h4> 
                            <small><i>Posted on {{ $article->created_at->format("M d, Y H:i") }}</i></small> <br>
                            Author {{ $article->user->name }}</p>
                            <p>{{ $article->description }}</p>
                          </div>
                        </div>
                        <p>
                            <a href="{{url('home')}}" class="btn btn-default">Go Back</a>
                        </p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
