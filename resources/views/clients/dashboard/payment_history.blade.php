@extends('layouts.member_dashboard')
@section('content')

<section class="main-dash">
    <aside>@include('clients.dashboard.includes.sidebar-left')</aside>
    <div class="dash-container">
        <div class="container">
            <div class="row">
                <div class="col-12">
                    <div class="dash-heading">
                        <?php if (isset($alert_class)) { ?>
                            <div class="<?php echo $alert_class; ?>">
                                <p><?php echo $alert_message; ?></p>
                            </div>
                        <?php } ?>
                        <h2>PACKAGE INFO</h2>
                    </div>
                    <?php
                    //   echo '<pre>';
                    // print_r($alert_class);
                    // die;
                    ?>

               <div class="container mt-5">
                <div class="table-responsive">
                <?php if(!empty($pay_detail)){?>  
                <div style="height:300px;overflow-y: auto;">
                    <table class="table table-striped table-dark text-white table-hover" id="history" >
                        <thead>
                            <tr>
                                <th class="text-center">S.no<?php $count=1;?></th>
                                <th colspan="2">SUBSCRIPTION</th>
                                <th>AMOUNT PAID</th>
                                <th>START DATE</th>
                                <th>EXPIRY DATE</th>
                                <th>STATUS</th>
                            
                            </tr>
                        </thead>
                        <tbody>
                        <?php foreach ($pay_detail as $value) {?>   
                            <tr>
                                <td class="text-center"><?php echo $count; $count++;?></td>
                                <td colspan="2">
                                    <h6><?php echo $value->package_type;?></h6>
                                </td>
                                <td>
                                    <div class="d-flex align-items-center"><span class="ml-2"><?php if(!empty($value->payment_amount)){echo "$".$value->payment_amount;}else{echo "FREE";}?></span></div>
                                </td>
                                <td><?php if($value->package_start_date !='0000-00-00'){?>
                                                          <?php  $date = new DateTime($value->package_start_date);
                                                                $result = $date->format('d M Y');?>
                                                                <p class="start date"><b><?php echo $result ?></b></p>
                                                         <?php }?> </td>
                                <td class="font-weight-bold"><?php if($value->package_expiry_date !='0000-00-00'){?>
                                                         <?php  $date = new DateTime($value->package_expiry_date);
                                                             
                                                                $result1 = $date->format('d M Y');?>
                                                          <p class="end date"><b><?php echo $result1 ?></b></p>
                                                   <?php }?></td>
                                <td><?php if($value->package_active==1){echo '<mark>ACTIVE</mark>';}else{echo "EXPIRED" ;}?></td>
                                
                            </tr>
                       <?php }?>



                        </tbody>
                    </table>
                </div>    
                    <?php } else{echo "NO RECORDS FOUND";}?>
                </div>
            </div>
                </div>
            </div>
        </div>
    </div>
</section>

@endsection