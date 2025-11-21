<div class="container">
     <div class="row justify-content-center">
         <div class="col-md-8">
             <div class="card">
                   <div class="card-body">	
                        <h3>Hi,</h3>
						<p>Comment has been posted by the user.</p>	
                        <p>Comment is posted by, {{ $data['name'] }} (<?php echo urldecode($data['emailId']);?>)</p>
						<p>Posted comment is: <?php echo $data['pwd']; ?></p>					
						<p><a href="{{route("list_comment",['id'=>$data['artid']])}}"><b>Click Here</b></a> to approve the comment.</p>
						<p>Thank You,</p>					
                </div>
            </div>
        </div>
    </div>
</div>