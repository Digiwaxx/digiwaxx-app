<div class="container">
     <div class="row justify-content-center">
         <div class="col-md-8">
             <div class="card">
                   <div class="card-body">                    
                     <div class="card-header {{ $data['name'] }}">Verify Your Email Address</div>
					   <div class="card-body">
						<p>Hi, {{ $data['name'] }}</p>
						<p>Your credentials are the following.,</p>					
						<p>You can also click the link to reset the password: <a href="{{ url('/reset-password/'.$data['token']) }}"><b>Click Here</b></a> to reset it.</p>
						<p>Thank You,</p>
					</div>
                </div>
            </div>
        </div>
    </div>
</div>