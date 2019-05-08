@extends('layouts.app')
@section('content')
<div class="container">
    <h3>MPESA</h3>
    <button onclick="getToken()">Get Token</button>
</div>
@endsection
@section('js')
<script>
	    function getToken() {
	        let url = 'https://sandbox.safaricom.co.ke/oauth/v1/generate';

	        $.ajax({
	            url: url,
	            type: 'GET',

	            beforeSend: function(xhr) {
	                xhr.setRequestHeader('Authorization', 'Basic WmNIMUJwMzFGdFJwcm5vTDN3R1RyU0lNR2YwdGVBZmV2REFkc2NpdnByRVB5MHNP');
	            },
	            success : function(response) {
	            	console.log(response);
	            }, 
	            error : function(error) {
	                console.log(error);
	            }
	        });
    	}
</script>
@endsection