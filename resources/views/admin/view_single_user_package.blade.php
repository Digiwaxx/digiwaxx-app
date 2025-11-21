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
                <li class="active"><i class="ace-icon fa fa-list list-icon"></i> USER PACKAGES DETAILS</li>
            </ul>
            <!--/.breadcrumb -->
            <!--#section:basics/content.searchbox -->
            <!--/.nav-search -->
            <!--/section:basics/content.searchbox -->
        </div>
        <!-- /section:basics/content.breadcrumbs -->
        <div class="page-content">
              <div class="plan-section">
                        <?php if(!empty($class)){?>
                            <div class=" <?php  echo $class;?>">
                                  <?php  echo $result;?>
                                </div>
                        <?php } ?>
                    </div>
            
            
            <!-- PAGE CONTENT BEGINS -->
            <div class="row">
                <div class="col-xs-12">

                    <div class="table-responsive">
                        <table id="sample-table-1" class="table table-striped table-bordered table-hover">
                            <!--<thead>-->
                            <!--    <tr>-->
                            <!--        <th class="center" width="100">-->
                            <!--            S. No.-->
                            <!--        </th>-->
                            <!--        <th>User Name</th>-->
                            <!--        <th>User Type</th>-->
                            <!--        <th>Package Type</th>-->
                            <!--        <th>Package Price</th>-->
                            <!--        <th>Payment Status</th>-->
                            <!--        <th>Package Start Date</th>-->
                            <!--        <th>Package Expiry Date</th>-->
                            <!--        <th width="160">Action</th>-->
                            <!--        <?php //$count_announ = 1; ?>-->
                            <!--    </tr>-->
                            <!--</thead>-->
                            <h3><?php if($details[0]->user_type == 1){echo 'Member';}else{echo 'Client';} ?></h3>
                            <tbody>
                                <input type="hidden" name="_token" id="csrf-token-announ" value="{{ Session::token() }}" />
                                <?php //echo '<pre>'; print_r($get_news); die; 
                                foreach ($details as $value) { ?>
                                    <tr>
                                        <td>Full Name </td>
                                        <td><?php echo urldecode($value->full_name) ?></td>
                                    </tr>
                                    <tr>
                                        <td>User Name </td>
                                        <td><?php echo urldecode($value->user_name) ?></td>
                                    </tr>
                                    <tr>
                                        <td>User Email </td>
                                        <td><?php echo urldecode($value->email) ?></td>
                                    </tr>
                                    <tr>
                                        <td>Phone Number </td>
                                        <td><?php echo $value->phone ?></td>
                                    </tr>
                                    <tr>
                                        <td>Package Name </td>
                                        <td><?php echo $value->package_type ?></td>
                                    </tr>
                                    <tr>
                                        <td>Package Duration </td>
                                        <td><?php if($value->package_id == 7){echo 'Life Time';}else{echo $value->package_type;} ?></td>
                                    </tr>
                                    <tr>
                                        <td>Package Start Date </td>
                                        <td><?php if($value->package_id == 7){echo '-';}else{ echo $value->package_start_date;} ?></td>
                                    </tr>
                                    <tr>
                                        <td>Package Expiry Date </td>
                                        <td><?php if($value->package_id == 7){echo '-';}else{echo $value->package_expiry_date;} ?></td>
                                    </tr>
                                    <tr>
                                        <td>Amount </td>
                                        <td><?php if($value->package_id == 7){echo '-';}else{echo '$'.$value->payment_amount;} ?></td>
                                    </tr>
                                    <tr>
                                        <td>Payment Status </td>
                                        <td><?php if($value->payment_status == 0){echo 'User hasn\'t paid yet!';}else{ echo 'Payment is complete';} ?></td>
                                    </tr>
                                    <?php
                                    if($value->payment_status == 1){
                                    ?>
                                    <tr>
                                        <td>Payment Method </td>
                                        <td><?php echo $value->payment_method ?></td>
                                    </tr>
                                    <?php } ?>
                                    
                                <?php } ?>
                            </tbody>
                        </table>
                    </div>

                </div>
                <!-- /.span -->
            </div>
                          <?php if(!empty($sel_pck)){?>
                            <div class="select_package">
                                <h4>UPDATE PACKAGE</h4>
                                <hr>
                                 <div class="row">
                                <?php foreach ($sel_pck as $package){?>     
                                    <div class="col-sm-4" >
                                               <div class="plan-box additional" style=" border-left: 5px double #00cccc;">
                                                    <form action="{{route('update_package_user')}}" method="post" name="myForm" id="package_select" autocomplete="off" onSubmit="if(!confirm('Do you want to confirm the subscription?')){return false;}">
                                                   @csrf
                                                   <input type="hidden" name="package_id" value="<?php echo $package->id;?>">
                                                   <input type="hidden" name="user_type"  value="<?php echo $user_type;?>">
                                                   <input type="hidden" name="user_id"    value="<?php echo $user_id;?>">
                                                      <!--<h3><?php //echo $package->package_name ?></h3>-->
                                                      <h3 style="color:#00cccc;text-align:center"><?php echo $package->package_type ?></h3>
                                                     
                                                      <ul>
                                                          <?php $arr=json_decode($package->package_features);?>
                                                          <?php foreach($arr as $feature){ ?>
                                                        <li><?php echo $feature; ?></li>
                                                        <?php } ?>
                                                      </ul>
                                                      <div class="btn-plan" style="text-align:center">
                                                        <button class="btn btn-info" type="submit" >UPDATE</button>
                                                      </div>
                                                  </form>
                                                </div>
                                    </div>
                                <?php }?>
                                  </div>
                                  
                            </div> 

                    <?php }?> 
  
            <!-- /.row -->

            <!-- PAGE CONTENT ENDS -->
        </div>
                
        <!-- /.col -->
        <!-- /.page-content -->
@endsection