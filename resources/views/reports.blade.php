@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="card">
                <div class="card-header">Reports</div>
                <div class="card-body">
                    <h5>Most Viewed Article</h5>
                    <table class="table">
                        <tr>
                            <th>Title</th>
                            <th>Author</th>
                            <th>Views</th>
                        </tr>
                        @foreach($articles as $a)
                        <tr>
                            <td>{{ $a->title }}</td>
                            <td>{{ $a->user->name }}</td>
                            <td>{{ $a->views_count }}</td>
                        </tr>
                        @endforeach
                    </table>

                    <h5>Popular Categories</h5>
                    <table class="table table-bordered">
                        <tr class="bg-info">
                            <th>Name</th>
                            <th>Articles</th>
                        </tr>
                        @foreach($categories as $c)
                        <tr>
                            <td>{{ $c->name }}</td>
                            <td>{{ $c->articles_count }}</td>
                        </tr>
                        @endforeach
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
