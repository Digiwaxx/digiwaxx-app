<?php
// echo "<pre>";
// print_r($get_announ);
// echo '</pre>';
// die();
?>
@extends('admin.admin_dashboard_active_sidebar')
@section('content')
<div class="main-content">
    <div class="main-content-inner">

        <!-- #section:basics/content.breadcrumbs -->
        <div class="breadcrumbs" id="breadcrumbs">
             <script type="text/javascript">
              try{ace.settings.check('breadcrumbs' , 'fixed')}catch(e){}
           </script>



            <ul class="breadcrumb">
                <li class="active"><i class="ace-icon fa fa-list list-icon"></i> CLIENT PACKAGES</li>
            </ul>
            <!--/.breadcrumb -->
            <!--#section:basics/content.searchbox -->

            <!--/.nav-search -->
            <!--/section:basics/content.searchbox -->
        </div>
        <!-- /section:basics/content.breadcrumbs -->
        <div class="page-content">

            <!-- PAGE CONTENT BEGINS -->
            <div class="row">
                <div class="col-xs-12">
  
                    <div class="table-responsive">
                        <table id="sample-table-1" class="table table-striped table-bordered table-hover">
                            <thead>
                                <tr>
                                    <th class="center" width="100">
                                        S. No.
                                    </th>
                                    <th>Package Name</th>
                                    <th>Package Type</th>
                                    <th>Package Price</th>
                                    <th>Added</th>
                                    <th width="160">Action</th>
                                    <?php $count_announ=1;?>
                                </tr>
                            </thead>
                            <tbody>
                                <input type="hidden" name="_token" id="csrf-token-announ" value="{{ Session::token() }}" />
                                <?php //echo '<pre>'; print_r($get_news); die; 
                                // dd($get_packages);
	                      foreach  ($get_packages as $value){?>
                                <tr>
                                    <td><?php echo $count_announ; $count_announ++; ?></td>
                                    <td><?php  echo strtoupper($value->package_name); ?> </td>
                                    <td><?php echo strtoupper($value->package_type); ?></td>
                                    <td>$<?php echo $value->package_price; ?></td>
                                    <td><?php  $date = new DateTime($value->created_on);
                                                $result = $date->format('d M Y'); echo $result; ?></td>
                                    <td> <div class="btn-group">

                                        <a href="{{route("edit_package_view",['id'=>$value->id])}}" title="Edit Package" class="btn btn-xs btn-info">
                                        <i class="ace-icon fa fa-pencil bigger-120"></i>
                                        </a>
                                          <?php 
                                          if($value->package_status == 0){
                                          $route_app = route('approve_package');
                                          ?>
                                          <input type="hidden" id="appr_url" name="approve_url" value="<?php echo $route_app; ?>">
                                          <?php echo '<button title="Enable Package" onclick="approveannoun(\''.$value->id.'\')" class="btn btn-xs btn-success">'; 
                                          echo '<i class="ace-icon fa fa-check bigger-120"></i>';
                                          echo '</button>';
                                          }else{
                                              
                                              $route_dis = route('package_disable');
                                              
                                          ?>
                                        <input type="hidden" id="dis_url" name="dis_url" value="<?php echo $route_dis; ?>">

                                           
                                         <?php echo '<button title="Disable Package" onclick="disableannoun(\''.$value->id.'\')" class="btn btn-xs btn-warning">';
                                          echo '<i class="ace-icon fa fa-times bigger-120"></i>';
                                          echo '</button>';   
                                          }
                                       ?>


       


                    </div>
                    </td>

                    </tr>
                    <?php }?>


                    </tbody>
                    </table>
                </div>
                <!--     	<?php //if($numPages>1) { ?>-->

                <!--<?php //} ?>-->
            </div>
            <!-- /.span -->
        </div>
        <!-- /.row -->

        <!-- PAGE CONTENT ENDS -->
    </div>
    <!-- /.col -->
    <!-- /.page-content -->
    <script>
    
    //approve
    function approveannoun(id){
        
           // alert('Do you really want to delete this list');
        var CSRF_TOKEN = $('#csrf-token-announ').val();
        var get_url= $('#appr_url').val();
        $.ajax({
        
            url: get_url,
            type: 'POST',
            
            data: {_token: CSRF_TOKEN,id:id},
            dataType: 'JSON',
            
            success: function (data) { 
                // $(".writeinfo").append(data.msg);
                if(data == 'success'){
                    location.reload();
                }
            }
        }); 
        
    }
    
    // disable anoun    
    
    function disableannoun(id){
        var CSRF_TOKEN = $('#csrf-token-announ').val();
         var get_url= $('#dis_url').val();
        $.ajax({
        
            url: get_url,
            type: 'POST',
            
            data: {_token: CSRF_TOKEN,id:id},
            dataType: 'JSON',
            
            success: function (data) { 

                if(data == 'success'){
                    location.reload();
                }
            }
        }); 
    }




    </script>
    @endsection