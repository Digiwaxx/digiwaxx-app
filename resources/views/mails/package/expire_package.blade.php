<html><body>
<div class="container">
     <div class="row justify-content-center">
         <div class="col-md-8">
             <div class="card">
                   <div class="card-body">	
                        <h3>Hi, {{ $data['name'] }}</h3>
						<p>Your {{$data['title']}} subscription is about to expire on {{$data['expiry']}}.</p>	
						<p>Thank You,</p>
				
                </div>
            </div>
        </div>
    </div>
</div>
</body></html>