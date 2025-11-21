<html>
<body>
<div class="container">
     <div class="row justify-content-center">
         <div class="col-md-8">
             <div class="card">
                   <div class="card-body">					  
						<h3>Hi <?php echo $data['name']; ?>,</h3>
						<p>Your client account is approved at DigiWaxx for you by the Admin.</p>
		                <?php $link=route('login');?>
		                <p><a href="<?php echo $link;?>"><b>Click Here</b></a> to login.</p>
		                
                </div>
            </div>
        </div>
    </div>
</div>
</body>
</html>