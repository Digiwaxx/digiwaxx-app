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
      <li class="active"><i class="ace-icon fa fa-list list-icon"></i> WEBSITE PAGES</li>
   </ul>
    <!--/.breadcrumb -->
    <!--#section:basics/content.searchbox -->

    <!--/.nav-search -->
    <!--/section:basics/content.searchbox -->
</div>
<!-- /section:basics/content.breadcrumbs -->
<div class="page-content">
    
    
   <!-- /.page-header -->
         <!-- PAGE CONTENT BEGINS -->
         
         <?php 
         $pages_array = array(  
                                '1'=>array(
                                    'name' => 'Home Page',
                                    'view_route' => "home",
                                    'edit_route' => "admin_PageWhatIsDigiwaxx"
                                    ),
                                '2'=>array(
                                        'name' => 'Promote Your Project',
                                        'view_route' => "PromoteYourProject",
                                        'edit_route' => "admin_PagePromoteYourProject"
                                        ),
                                '3'=>array(
                                        'name' => 'Charts',
                                        'view_route' => "Charts",
                                        'edit_route' => "admin_PageCharts"
                                        ),
                                '4'=>array(
                                        'name' => 'Digiwaxx Radio',
                                        'view_route' => "DigiwaxxRadio",
                                        'edit_route' => "admin_PageDigiwaxxRadio"
                                        ),
                                '5'=>array(
                                        'name' => 'Contact Us',
                                        'view_route' => "Contactus",
                                        'edit_route' => "admin_PageContactUs"
                                        ),
                                '6'=>array(
                                        'name' => 'Press Page',
                                        'view_route' => "PressPage",
                                        'edit_route' => "admin_PagePress"
                                        ),
                                '7'=>array(
                                        'name' => 'Clients Page',
                                        'view_route' => "Client_tracks",
                                        'edit_route' => "admin_PageClientsPage"
                                        ),
                                '8'=>array(
                                        'name' => 'Wall Of Scratch',
                                        'view_route' => "WallOfScratch",
                                        'edit_route' => "admin_PageWallOfScratch"
                                        ),
                                '9'=>array(
                                        'name' => 'What We Do',
                                        'view_route' => "WhatWeDo",
                                        'edit_route' => "admin_PageWhatWeDo"
                                        ),
                                '10'=>array(
                                        'name' => 'Free Promo',
                                        'view_route' => "FreePromo",
                                        'edit_route' => "admin_PageFreePromo"
                                        ),
                                '11'=>array(
                                        'name' => 'Events',
                                        'view_route' => "Events",
                                        'edit_route' => "admin_PageEvents"
                                        ),
                                '12'=>array(
                                        'name' => 'Testimonials',
                                        'view_route' => "testimonials",
                                        'edit_route' => "admin_PageTestimonials"
                                        ),
                                '13'=>array(
                                        'name' => 'Why Join',
                                        'view_route' => "WhyJoin",
                                        'edit_route' => "admin_PageWhyJoin"
                                        ),
                                '14'=>array(
                                        'name' => 'Sponsor Advertise',
                                        'view_route' => "SponsorAdvertise",
                                        'edit_route' => "admin_PageSponsorAdvert"
                                        ),
                                '15'=>array(
                                        'name' => 'Help',
                                        'view_route' => "Help",
                                        'edit_route' => "admin_PageHelp"
                                        ),
                                '16'=>array(
                                        'name' => 'I\'m a DJ',
                                        'view_route' => "ImDj",
                                        'edit_route' => "admin_PageImDj"
                                        ),
                                        
                                        );
         
         
        //  dd($pages_array);
         ?>
         
         
         <div class="row">
            <div class="col-xs-12">
               <?php 
                  if(isset($alert_message))
                  {
                   ?>
               <div class="<?php echo $alert_class; ?>"><?php echo $alert_message; ?></div>
               <?php 
                  }
                  
                  
                  ?>
                  <div class="table-responsive">
	               <table id="sample-table-1" class="table table-striped table-bordered table-hover">
	                  <thead>
	                     <tr>
	                        <th class="center" width="100">
	                           S. No.
	                        </th>
	                        <th>Title</th>
	                        <th width="160">Action</th>
	                        <?php $count_news=1;?>
	                     </tr>
	                  </thead>
	                  <tbody>
	                      <!--<input type="hidden" name="_token" id="csrf-token" value="{{ Session::token() }}" />-->
	                      <?php 
	                      foreach ($pages_array as $pages){?>
	                     <tr>
	                       <td><?php echo $count_news; $count_news++; ?></td>
	                       <td><?php  echo $pages['name']; ?> </td>
	                       <td
                           <div class="btn-group">
	                           <?php $route_view = $pages['view_route']; ?>
                              <a href="<?php echo route($route_view) ?>" target="_blank" title="Preview Page" class="btn btn-xs btn-success">
                              <i class="ace-icon fa fa-eye bigger-120"></i>
                              </a>
                              <?php $route = $pages['edit_route']; ?>
                              <a href="<?php echo url(route($route)) ?>" title="Edit Page" class="btn btn-xs btn-info">
                              <i class="ace-icon fa fa-pencil bigger-120"></i>
                              </a>
                           </div>
                            </td>
	                       
	                     </tr>
	                     <?php }?>

	                    
	                  </tbody>
	               </table>
	           </div>
            </div>
            <!-- /.span -->
         </div>
         <!-- /.row -->
         
         <!-- PAGE CONTENT ENDS -->
      </div>
      <!-- /.col -->
<!-- /.page-content -->
<script>

</script>
@endsection

