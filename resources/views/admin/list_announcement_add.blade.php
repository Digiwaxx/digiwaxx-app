@extends('admin.admin_dashboard_active_sidebar')
@section('content')

<div class="main-content">
	<div class="main-content-inner">

		<!-- #section:basics/content.breadcrumbs -->
		<div class="breadcrumbs" id="breadcrumbs">
			<script type="text/javascript">
				try {
					ace.settings.check('breadcrumbs', 'fixed')
				} catch (e) {}
			</script>


			<ul class="breadcrumb">
				<li class="active"><i class="ace-icon fa fa-list list-icon"></i> ADD ANNOUNCEMENTS</li>
			</ul>
		</div>
		<!-- /section:basics/content.breadcrumbs -->
		<div class="page-content">
			<div class="row">
				<div class="col-xs-12">
					<div class="news-form">
						<?php $route = route('add_announcement'); ?>
						<form method="post" action="<?php echo $route; ?>" enctype=multipart/form-data id="add_announ">
							@csrf
							<div class="form-group">
								<label for="news_title">TITLE</label>
								<input type="text" class="form-control" id="ann_title" name="ann_title" aria-describedby="emailHelp" placeholder="Enter Title">

							</div>
							<div class="form-group">
								<label for="news_description">Description</label>
								<div class="row">
                    				 <div class="col-xs-12">
                    					<?php if(isset($alert_class)) 
                    					   { ?>
                    					<div class="<?php echo $alert_class; ?>">
                    					   <button class="close" data-dismiss="alert">
                    					   <i class="ace-icon fa fa-times"></i>
                    					   </button>
                    					   <?php echo $alert_message; ?>
                    					</div>
                    					<?php } ?>
                    					<script src="{{ asset('assets_admin/assets/ckeditor/ckeditor.js') }}"></script>
                    					<script src="{{ asset('assets_admin/assets/ckeditor/samples/js/sample.js') }}"></script>
                    					<link rel="stylesheet" href="{{ asset('assets_admin/assets/ckeditor/samples/css/samples.css') }}">
                    					<link rel="stylesheet" href="{{ asset('assets_admin/assets/ckeditor/samples/toolbarconfigurator/lib/codemirror/neo.css') }}">
                    					<!--<form action="" method="post">-->
                    						@csrf
                    					   <textarea class="ckeditor" name="ann_description"><?php //echo stripslashes(urldecode($bannerText[0]->bannerText)); ?></textarea>
                    					   <br />
                    					   <!--<input type="submit"  name="addBannerText" value="Submit" class="btn btn-sm btn-primary"  />-->
                    					<!--</form>-->
                    				 </div>
                    				 <!-- /.span -->
                    			  </div>
							</div>
							<div class="form-group form-check">
								<label class="form-check-label" for="ann_avail">Availability</label>
								<select class="form-select" aria-label="Default select example" name="ann_avail" id="select_check" onchange="my_select()" required>
                                  <option value="All" selected>All</option>
                                  <option value="Selected_Members">Selected Members</option>
                                  
                                </select>

							</div>
							<div class="dyn_avail" style="display:none">
    							<div class="form-group form-check">
    							    <?php   $route_dis = route('fetch_mem'); ?>
    							    <input type="hidden" id="fetch_route" value="<?php echo $route_dis; ?>">
    							    <input type="hidden" name="_token" id="csrf-token-announ" value="{{ Session::token() }}" />
    							    <label class="form-check-label" for="search_member">Search Member Name</label>
                                    <input type="text" id="search_member" name="search_member"  placeholder="Enter a alphabet" aria-label="search" onkeyup="sel_mem()">
                                    <ul class="typeahead dropdown-menu" id="mem_list" style=" display: none;top: auto;left: 155px;width: 181px; overflow:auto; overflow-y:scroll;height:250px"></ul>

                                  </div>
                                  
                                  <div class="form-group form-check">
                                     <label class="form-check-label" for="search_member">Selected Members :- </label>
                                      <div class="selected_members">
                                         
                                          
                                      </div>
                                     
                                  </div>
    						
							</div>
							<button type="submit" class="btn btn-primary">Submit</button>
							<br>
						</form>
					</div>
				</div>
				<!-- /.span -->
			</div>
			<!-- /.row -->

			<!-- PAGE CONTENT ENDS -->
		</div>
		<!-- /.col -->
	</div>
	<!-- /.row -->
</div>
<!-- /.page-content -->
<script>

    function my_select(){
        var sel = $('#select_check').val();
        if(sel=='Selected_Members'){
            $('.dyn_avail').show();
         
        }
        else{
            $('.dyn_avail').hide();
            $('#search_member').val('');
            $("#mem_list").empty();
            $("#mem_list").hide();
            $('input[name="check_mem[]"]').removeAttr('checked');
            $('.selected_members').empty();
            
        }
        
    }


    function sel_mem(){
        var search=$("#search_member").val();
         $("#mem_list").empty();
        if(search.length>0){
           
             var get_url=$("#fetch_route").val();
             var CSRF_TOKEN = $('#csrf-token-announ').val();
            $.ajax({
        
            url: get_url,
            type: 'POST',
            
            data: {_token: CSRF_TOKEN,search:search},
            dataType: 'JSON',
            
            success: function (data) { 
 
                    $("#mem_list").show(); 
                    var myArray = JSON.parse(JSON.stringify(data));
                   for (let i = 0; i < myArray.length; i++) {
                    //  $("#append_data").append(
                    //      "<tr><td>" + myArray[i]['uname'] + "</td><td>" + myArray[i]['fname'] + "</td><td>" + myArray[i]['lname'] +"</td><td> <input type='checkbox' name='check_mem[]' value='" +myArray[i]['id'] + "'></td></tr>"

                    // );
                    
                    var str1="add_val('"+myArray[i]['fname']+"','"+myArray[i]['id']+"')";
                    
                    $("#mem_list").append("<li><label><input type='checkbox' onclick="+str1+" id='list_item' name='check_mem[]' value='" +myArray[i]['id'] + "' > "+ myArray[i]['fname'] +"</label></li>");
                  }
               // }
            }
        });
         
        }
        else{
             $("#mem_list").empty();;
              console.log("--------------------------");
        }
       
        
    }
    
    function add_val( name, id){
        
        $("#mem_list").hide()
        var str2="remove_val('"+name+"','"+id+"')";
        // console.log(name+"-----"+id);
    //   $('#selected_mem').val(id);
        var span_id="member_id_"+id;
         var str2="remove_val('"+name+"','"+id+"')";
        console.log(span_id);
        if($("#"+span_id).length==0){
            $(".selected_members").append("<span id="+span_id+"><input type='text'  id='sel_item' name='selected_mem_id[]' value='" +name + "' > <button id='remove' class='btn btn-danger' type='button' onclick="+str2+">X</button><input type='hidden'  id='hidden_sel_item' name='hidden_selected_mem_id[]' value='" +id + "' ></span> ");
        }
    }
    
    function remove_val(name,id){
        $("#member_id_"+id).empty();
        $("#member_id_"+id).remove();

    }
</script>    
    
      <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>
    <script src="{{ asset('public/js/jquery.validate.min.js') }}"></script>
    <script>
        jQuery(document).ready(function(){
           
                jQuery("#add_announ").submit(function() {
                    if(jQuery("#ann_title").val().length>0){
                        if(jQuery("#ann_title").val().trim().length==0){
                           alert("Please enter all the details.");
                           return false; 
                        }
                        
                    }else{
                          alert("Please enter all the details.");
                          return false;
                    }
                });
        })
    
</script>

@endsection

