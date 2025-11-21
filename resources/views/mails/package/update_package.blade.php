<html><body>
<div class="container">
     <div class="row justify-content-center">
         <div class="col-md-8">
             <div class="card">
                   <div class="card-body">	
                        <h3>Hi, {{ $data['name'] }}</h3>
						<p>Your package has been successfully updated.</p>
                        <p>You have subscribed {{$data['title']}} package.</p>
        <?php if(!empty($data['amount'])){?><p>You have paid ${{$data['amount']}} via {{$data['method']}}. </p><?php }?>
        <?php  $date = new DateTime($data['start']);
              $result = $date->format('d M Y');?>
                <p class="start date">Your subscription starts on -<b><?php echo $result ?></b><?php if($data['expiry']!='0000-00-00'){?> and ends on -<?php  $date = new DateTime($data['expiry']);
                                                             
                 $result1 = $date->format('d M Y');?>
                 <b><?php echo $result1; ?></b><?php }?>.</p>
                                                          

						<p>Thank You,</p>
						
                </div>
            </div>
        </div>
    </div>
</div>
</body></html>