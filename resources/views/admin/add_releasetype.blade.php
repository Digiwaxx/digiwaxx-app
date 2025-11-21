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
                <li>
                <a href="<?php echo url("admin/manage_releasetype"); ?>">
                        <i class="ace-icon fa fa-list list-icon"></i>
                        Manage Release Types</a>
                </li>
                <li class="active">Add Project</li>
            </ul><!-- /.breadcrumb -->
            <!-- /section:basics/content.searchbox -->
        </div>
        <!-- /section:basics/content.breadcrumbs -->
        <div class="page-content">
            <div class="row">
                <div class="col-xs-12">
                    <!-- PAGE CONTENT BEGINS -->
                    <div class="row">

                    <?php if(isset($alert_class)) 
				    { ?>
			
			
                        <div class="<?php echo $alert_class; ?>">
                                    <button class="close" data-dismiss="alert">
                                        <i class="ace-icon fa fa-times"></i>
                                    </button>
                                    <?php echo $alert_message; ?>
                                </div>
				        <?php } ?>
					
                        <div class="col-xs-12">
                            <form class="form-horizontal" role="form" action="" method="post" enctype="multipart/form-data" onsubmit="return validate()" autocomplete="off" style="color:white;">
                            @csrf
                                <div>
                                    <h3 class="header smaller lighter blue">
                                      Add Release Type
                                    </h3>
									<div class="col-sm-6 form-group">
                                        <label class="col-sm-3 control-label no-padding-right req-label" for="form-field-1">Release Type Name</label>
                                        <div class="col-sm-9">
                                            <input type="text" id="release_name" name="release_name" class="col-xs-10 col-sm-10" value="" >
                                        </div>
                                    </div>
                                    <div class="col-sm-6 form-group">
                            <label>Select Status</label>
                            <select id="status" name="status">
                                <option value="">Select Status</option>
                                <option value="1">Active</option>
                                <option value="0">Inactive</option>
                                                      
                                
                            </select>
                            <!-- <input type="text" class="nav-search-input" id="client" name="client" value="<?php  ?>" /> -->
                        </div>
                                          <div style="clear:both;"></div>
                                    <div class="space-24"></div>
                                    <div class="clearfix form-actions">
                                        <div class="col-md-offset-3 col-md-9">
                                            <button class="btn btn-info btn-sm" type="submit" name="addReleaseType">
                                                <i class="ace-icon fa fa-check bigger-110"></i>
                                                Add ReleaseType
                                            </button>
                                            &nbsp; &nbsp; &nbsp;
                                            <button class="btn btn-sm" type="reset">
                                                <i class="ace-icon fa fa-undo bigger-110"></i>
                                                Reset
                                            </button>
                                        </div>
                                    </div>
                                    
								
									</div>
									
                                </form>
                        </div><!-- /.span -->
                    </div><!-- /.row -->
                    <div class="hr hr-18 dotted hr-double"></div>
                    <!-- PAGE CONTENT ENDS -->
                </div><!-- /.col -->
            </div><!-- /.row -->
        </div><!-- /.page-content -->
        <script>
		
    
    

            function validate() {
                //	alert("asd");
                var release_name = document.getElementById('release_name');
                var status = document.getElementById('status');
            
				if ($('#release_name').val() == '') {
                    alert("Please enter release name!");
                    release_name.focus();
                    return false;
                }
                
                if ($('#status').val() == '') {
                    alert("Please select status!");
                    status.focus();
                    return false;
                }
 
            }

    		 
			
        </script>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
<!-- CSS -->
<link rel="stylesheet" type="text/css" href="//cdnjs.cloudflare.com/ajax/libs/chosen/1.1.0/chosen.min.css">

<!-- JS -->
<script type="text/javascript" src="//cdnjs.cloudflare.com/ajax/libs/chosen/1.1.0/chosen.jquery.min.js"></script>
 <script type="text/javascript">
        $(function () {
            $(".multipletrack").chosen();
        });
</script>
@endsection 

