<html>
<body>
<div class="container">
     <div class="row justify-content-center">
         <div class="col-md-8">
             <div class="card">
                   <div class="card-body">					  
						<h3>Hi, {{ $data['name'] }}</h3>
						<p>Password has been reset.</p>					
						<p>Password is: {{ $data['pwd'] }}</p>					
						<p><a href="{{ url('/login') }}"><b>Click Here</b></a> to login.</p>
						<p>Thank You,</p>					
                </div>
            </div>
        </div>
    </div>
</div>
</body>
</html>