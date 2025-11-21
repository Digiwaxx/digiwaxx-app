@extends('layouts.app')
@section('content')
<section class="content-area bg-login modal-custom">
     <div class="container">
      <div class="row">
        <div class="col-md-8 col-lg-6 col-sm-12 mx-auto">
        <div class="modal-dialog login">
            <div class="modal-content">
              <div class="modal-header">
               <div class="top-modal">
                <div class="music-icon">
                <img src="{{ asset('public/images/path/music-icon.png') }}" class="img-fluid">
              </div>
              </div>
            </div>
              
              <div class="modal-body">
            
            <?php if (!empty($result)){?>  
                      <div class="<?php echo $class;?>" role="alert" id="veri-status">
                          <?php echo $result;?>
                        </div>
            <?php }?>        
                  
                  
			 @if(!empty($alert_message))

			  <div class="alert {{ $alert_class }}">{{ $alert_message }}</div>

              @endif
				@error('email')
					<span class="invalid-feedback" role="alert">
						<strong>{{ $message }}</strong>
					</span>
				@enderror
				<form action="" method="post" id="loginForm">
				@csrf
        <div class="forget-form">
		
					<div class="form-group">
                        <div class="radio-inline sg1">
                            <label>
                                <input type="radio" name="user" value="2" checked>
                                DJ's/Members
                            </label>
                        </div>
                        <div class="radio-inline sg1">
                            <label>
                                <input type="radio" name="user" value="1" >
                                Artists/Promoters
                            </label>
                        </div>
                    </div>
		
				   <div class="form-group reg-email">                  
						<input name="email" id="email"  class="form-control input"  size="20" placeholder="email address" type="text" />
                  
				   </div>
                
                    <input name="sendMail" class="login_btn btn btn-theme btn-gradient" value="Send Mail" type="submit">
                  </div>
                  
				</form>
              </div>              
              <div class="modal-footer">            
              </div>
            </div>
			</div>
    </div>
  </div>
</div>
</section>
        
<script>
  // Wait for the DOM to be ready
$(function() {

 $("#loginForm").validate();
 
 $("#email").rules("add", {
         required:true,
         messages: {
                required: "Please enter email"
         }
      });

  var xx=$("#veri-status").length;
  if (xx>0){
      
      setTimeout(function(){
             $("#veri-status").fadeOut("slow", function() {
                $(this).remove();
            });
       }, 5000);
  }

	
});

</script>
                            

@endsection