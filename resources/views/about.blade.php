@extends('layouts.app')
@section('content')
<div class="container">
    <h3>Hallo from view</h3>

    <button onclick="fetchArticles()" class="btn btn-primary">Fetch Articles</button>
    <br>

    <h4>Total users : <b id="loc"></b></h4>

    <table class="table">
        <thead>
         <tr class="bg-info">
            <th>Name</th>
            <th>Email</th>
        </tr>
        </thead>
        <tbody id="articles-location">
        </tbody>
    </table>

    {{-- <table class="table table-bordered">
        <tr class="bg-info">
            <th>Name</th>
            <th>Email</th>
        </tr>
        @foreach($users as $user)
        <tr>
            <td>{{ $user->name }}</td>
            <td>{{ $user->email }}</td>
        </tr>
        @endforeach
    </table> --}}
</div>
@endsection

@section('js')
<script>
    $(document).ready(function(){
        fetchArticles();
    }); 

    function fetchArticles() {
        let url = domain + '/api/users';
        $.ajax({
            url: url,
            type: 'GET',

            beforeSend: function(xhr) {
                xhr.setRequestHeader('Accept', 'application/json');
                xhr.setRequestHeader('Authorization', 'Bearer n4gciBtOObwxnR9XBuhYsaVek');
            },
            success : function(response) {
                let users = response.data;
                $('#loc').html(users.length);

                let rows = '';
                for(var i = 0; i < users.length; i++) {
                    rows += '<tr>'+
                        '<td>'+ users[i].name  +'</td>' +
                        '<td>'+ users[i].email  +'</td>' +
                        '</tr>';
                }

                $('#articles-location').html(rows);

            }, 
            error : function(error) {
                console.log(error);
            }
        });
    }
</script>
@endsection

