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

                    <div class="tabs-section">
                        <div class="tab-content" id="myTabContent">
                            <div class="tab-pane fade show active" id="new-tracks" role="tabpanel" aria-labelledby="new-tracks-tab">
                                <div class="mtop-list">
                                    <div class="table-responsive">
                                        <table class="table">
                                            <tbody>
                                                <input type="hidden" name="_token" id="csrf-token-announ" value="{{ Session::token() }}" />
                                                <?php //echo '<pre>'; print_r($get_news); die; 
                                                if(isset($no_records_found) && $no_records_found == 0){
                                                
                                                    echo '<h4>No Package Selected Yet!</h4>';
                                                }else{
                                                foreach ($package_details as $value) { ?>
                                              
                                                    <tr>
                                                        <td><h6>Package Type </h6></td>
                                                        <td><?php echo $value->package_type ?></td>
                                                    </tr>
                                                    <tr>
                                                        <td><h6>Package Start Date </h6></td>
                                                        <td><?php echo $value->package_start_date ?></td>
                                                    </tr>
                                                    <tr>
                                                        <td><h6>Package Expiry Date </h6></td>
                                                        <td><?php echo $value->package_expiry_date ?></td>
                                                    </tr>
                                                    <tr>
                                                        <td><h6>Amount </h6></td>
                                                        <td>$<?php echo $value->payment_amount ?></td>
                                                    </tr>
                                                    <tr>
                                                        <td><h6>Payment Status </h6></td>
                                                        <td><?php if ($value->payment_status == 0) {
                                                                echo 'Not Paid';
                                                            } else {
                                                                echo 'Payment is complete';
                                                            } ?></td>
                                                    </tr>
                                                    <?php
                                                    if ($value->payment_status == 1) {
                                                    ?>
                                                        <tr>
                                                            <td><h6>Payment Method </h6></td>
                                                            <td><?php echo $value->payment_method ?></td>
                                                        </tr>
                                                    <?php } ?>

                                                <?php }
                                                }
                                                ?>
                                            </tbody>
                                        </table>
                                    </div>
                                </div><!-- eof mtop-list -->
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

@endsection