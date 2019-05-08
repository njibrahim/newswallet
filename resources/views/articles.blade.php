@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="card">
                <div class="card-header">@isset($article) Edit @else New @endif Article</div>
                <div class="card-body">
                    <form method="post" action="">
                        @csrf
                        <label>Title :</label>
                        <input type="text" name="title" value="{{@$article->title}}" required><br>

                         <label>Category :</label>
                         <select name="category_id">
                         @foreach($categories as $c)
                            <option value="{{$c->id}}" @if(@$article->category_id==$c->id) selected @endif>{{ $c->name }}</option>
                         @endforeach
                        </select><br>

                        <label>Image :</label>
                        <input type="text" name="image" value="{{@$article->image}}"><br>

                         <label>Description :</label><br>
                        <textarea name="description" required rows="10" style="width: 100%">{{@$article->description}}</textarea><br>
                        <br> 
                        <button>Submit</button>
                        <a href="{{url("articles")}}">Cancel</a>
                    </form>
                    <br>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
