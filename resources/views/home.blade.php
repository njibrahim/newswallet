@extends('layouts.app')
@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="card">
                <div class="card-header">Articles</div>

                <div class="card-body">
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif

                    <select class="float-right" onchange="categoryClicked(this.value)">
                        <option value="">All</option>
                        @foreach(\App\Category::get() as $c)
                            <option value="{{$c->id}}" @if($cat->id==$c->id) selected  @endif>{{$c->name}}</option>
                        @endforeach
                    </select>
                    <h4>Articles - <span class="text-muted"> {{ $cat->name ?? "All" }}</span></h4>

                    @foreach($articles as $a)
                        <div class="media border p-3">
                          <div class="media-body">
                            <h4> <a href="{{url("article/$a->id")}}">{{ $a->title }}</a></h4> 
                            <small><i>Posted on {{ $a->created_at->format("M d, Y H:i") }}</i></small> <br>
                            
                            @isset($a->image)
                            <div class="news-image">
                                <img src="{{$a->image}}" alt="{{$a->title}} image">
                            </div>
                            @endisset

                            <span class="text-muted">{{ $a->category->name }} : Total views {{ $a->getViews() }} </span>

                            @if(auth()->check() && $a->getViews(true) > 0)
                                <small class="text-muted"> <i>&nbsp;(You have viewed this article {{ $a->getViews(true) }} times)</i></small>
                            @endauth
                             <br>
                            <b>Author </b> : {{ $a->user->name }} 
                        </p>

                            <p>{{ str_limit($a->description, 250) }}</p>

                            @if($a->user_id==auth()->id())
                            <p>
                                <a href="{{url("articles?id=$a->id")}}" class="btn btn-primary btn-sm">Edit</a>
                                <a href="{{url("articles?delete=$a->id")}}" class="btn btn-danger btn-sm">Delete</a>
                            </p>
                            @endif

                          </div>
                        </div>
                    @endforeach
                    <br>
                    {{ $articles->appends(["cat" => request("cat")])->render() }}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
@section("js")
<script>
    // $(document).ready(function(){
    //     var div = $("#articles");
    //     var html = "";
    //     $.get(domain + '/api/articles', function(response) {
    //         var d = response.data;
    //         for(var i in d) {
    //             html += '<h4>'+ d[i].title +'</h4>';
    //             html += '<p class="text-muted">'+ d[i].created_at +'</p>';
    //             html += '<p>'+ d[i].description +'</p><hr>'
    //         }
    //         div.html(html);
    //     });
    // });

    function categoryClicked(val)
    {
        window.location.href = domain + "/home?cat=" + val;
    }

</script>
@endsection
