@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="card">
                <div class="card-header">Category</div>
                <div class="card-body">
                    <form method="post" action="">
                        @csrf
                        <label>Category Name :</label>
                        <input type="text" name="name" value="{{@$cat->name}}" required>
                        <button class="btn btn-primary btn-sm">Submit</button>
                        @isset($cat)
                        <a href="{{url("category?rm=$cat->id")}}" class="btn btn-danger btn-sm">Delete</a>
                        @endif
                    </form>
                    <br>
                    <h5>My Categories</h5>
                    <ol>
                    @foreach(auth()->user()->categories as $c)
                        <li><a href="{{url("category?id=$c->id")}}">{{ $c->name }}</a></li>
                    @endforeach
                </ol>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
