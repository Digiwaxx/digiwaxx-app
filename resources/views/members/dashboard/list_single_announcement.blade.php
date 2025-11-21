<?php  
 foreach ($single_announ as $key=>$value){
     $announ_title=$value->ma_title;
     $announ_date=$value->ma_created_on;
     $announ_desc=$value->ma_description;
 }


?>

@extends('layouts.member_dashboard')

@section('content')
	<section class="main-dash">
		@include('layouts.include.sidebar-left')
	 <div class="dash-container">
       <div class="container">
         <div class="row">
           <div class="col-12">
            <div class="dash-heading">
                <h2>Announcements</h2>
              </div>
              <div class="tabs-section">
				<div class="msg-blk f-block">
					
				  <div class="hidden-lg d-none" style="">Internal chat with users</div>
				   <div class="nav nav-tabs">
				       
						<a href="{{route('member-list-announcement')}}" class="ats pull-left nav-link active"><i class="fa fa-arrow-left" aria-hidden="true"></i>
                        GO BACK</a>
					</div>
					
					<div class="msgs">

                    </div>
                  
                <table class="table" id="single_announcement">
                  <tbody>
 
                    <tr>
                      <th>Title</th>
                      <td><?php echo $announ_title;?></td>
                
                    </tr>
                    <tr>
                      <th>Published-On</th>
                      <td><?php echo $announ_date;?></td>
                
                    </tr>
                    <tr>
                      <th>Description</th>
                      <td><?php echo $announ_desc;?></td>
                
                    </tr>
                  </tbody>
                </table>
					
					
					
						
					</div><!-- eof msgs-->
				</div>   
              <!---tab section end--->
				@include('layouts.include.content-footer') 
                         
			</div>
         </div>
       </div>
     </div>
	 </section>
	 
@endsection