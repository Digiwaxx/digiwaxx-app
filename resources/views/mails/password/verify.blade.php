<html>
<body>
<div class="container">
     <div class="row justify-content-center">
         <div class="col-md-8">
             <div class="card">
                   <div class="card-body">							
						<h3>Hi, {{ $data['name'] }}</h3>
						<!--form method="get" action="{{ url('/password-reset/'.$data['token']) }}">Please <button type="submit">Click Here</button>to reset the password.</form-->
						<p>Please <a href="{{ url('/password-reset/'.$data['token']) }}"><b>Click Here</b></a> to reset the password.</p>
						<p>Thank You,</p>					
                </div>
            </div>
        </div>
    </div>
</div>
</body>
</html>
