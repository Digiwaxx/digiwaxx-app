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

                    <i class="ace-icon fa fa-users users-icon"></i>

                    <a href="<?php echo url('admin/clients'); ?>">Clients</a>

                </li>

                <li class="active">View Client</li>

            </ul><!-- /.breadcrumb -->



            <!-- #section:basics/content.searchbox -->

            <div class="nav-search" id="nav-search" style="display:none">

                <form class="form-search">

                    <span class="input-icon">

                        <input type="text" placeholder="Search ..." class="nav-search-input" id="nav-search-input"
                            autocomplete="off" />

                        <i class="ace-icon fa fa-search nav-search-icon"></i>

                    </span>

                </form>

            </div><!-- /.nav-search -->



            <!-- /section:basics/content.searchbox -->

        </div>



        <!-- /section:basics/content.breadcrumbs -->

        <div class="page-content">
         <?php if(!empty($clients['data'][0])){?>
            <div class="space-10"></div>

            <div class="row">

                <div class="col-xs-12">

                    <!-- PAGE CONTENT BEGINS -->

                    <div class="row">

                        <div class="col-xs-12">





                            <div>
                                <?php $client = $clients['data'][0]; ?>



                                <div class="profile-user-info profile-user-info-striped">

                                    <div class="profile-info-row">

                                        <div class="profile-info-name"> Username </div>



                                        <div class="profile-info-value">

                                            <?php echo urldecode($client->uname); ?>

                                        </div>

                                    </div>

                                    <div class="profile-info-row">

                                        <div class="profile-info-name"> Company </div>



                                        <div class="profile-info-value">

                                            <?php echo urldecode($client->name); ?>

                                        </div>

                                    </div>

                                    <div class="profile-info-row">

                                        <div class="profile-info-name"> Contact Name</div>



                                        <div class="profile-info-value">

                                            <?php echo ucfirst(urldecode($client->ccontact)); ?>

                                        </div>

                                    </div>



                                    <div class="profile-info-row">
                                        <div class="profile-info-name"> Email </div>
                                        <div class="profile-info-value">
                                            <a
                                                href="mailto:<?php echo urldecode($client->email); ?>"><?php echo urldecode($client->email); ?></a>
                                        </div>
                                    </div>
                                    <div class="profile-info-row">
                                        <div class="profile-info-name"> Phone </div>
                                        <div class="profile-info-value">
                                            <a
                                                href="tel:<?php echo urldecode($client->phone); ?>"><?php echo urldecode($client->phone); ?></a>
                                        </div>
                                    </div>
                                    <div class="profile-info-row">
                                        <div class="profile-info-name"> Mobile </div>
                                        <div class="profile-info-value">
                                            <a
                                                href="tel:<?php echo urldecode($client->mobile); ?>"><?php echo urldecode($client->mobile); ?></a>
                                        </div>
                                    </div>








                                    <div class="profile-info-row">

                                        <div class="profile-info-name"> Address1 </div>



                                        <div class="profile-info-value">

                                            <?php echo urldecode($client->address1); ?>

                                        </div>

                                    </div>

                                    <div class="profile-info-row">

                                        <div class="profile-info-name"> Address2 </div>



                                        <div class="profile-info-value">

                                            <?php echo urldecode($client->address2); ?>

                                        </div>

                                    </div>

                                    <div class="profile-info-row">

                                        <div class="profile-info-name"> City </div>



                                        <div class="profile-info-value">

                                            <?php echo urldecode($client->city); ?>

                                        </div>

                                    </div>



                                    <div class="profile-info-row">

                                        <div class="profile-info-name"> State </div>



                                        <div class="profile-info-value">

                                            <?php echo urldecode($client->state); ?>

                                        </div>

                                    </div>

                                    <div class="profile-info-row">

                                        <div class="profile-info-name"> Country </div>



                                        <div class="profile-info-value">

                                            <?php echo urldecode($client->country); ?>

                                        </div>

                                    </div>

                                    <div class="profile-info-row">

                                        <div class="profile-info-name"> Zip </div>



                                        <div class="profile-info-value">

                                            <?php echo urldecode($client->zip); ?>

                                        </div>

                                    </div>

                                    <div class="profile-info-row">

                                        <div class="profile-info-name"> Website </div>



                                        <div class="profile-info-value">

                                            <?php echo urldecode($client->website); ?>

                                        </div>

                                    </div>
                                    <div class="profile-info-row">

                                        <div class="profile-info-name"> Facebook </div>



                                        <div class="profile-info-value">
                                            <?php if(isset($social['data'][0]->facebook)) { echo $social['data'][0]->facebook; } ?>

                                        </div>

                                    </div>

                                    <div class="profile-info-row">

                                        <div class="profile-info-name"> Twitter </div>



                                        <div class="profile-info-value">

                                            <?php if(isset($social['data'][0]->twitter)) { echo $social['data'][0]->twitter; } ?>

                                        </div>

                                    </div>
                                    <div class="profile-info-row">

                                        <div class="profile-info-name"> Instagram </div>



                                        <div class="profile-info-value">

                                            <?php if(isset($social['data'][0]->instagram)) { echo $social['data'][0]->instagram; } ?>

                                        </div>

                                    </div>
                                    <div class="profile-info-row">

                                        <div class="profile-info-name"> Linkedin </div>



                                        <div class="profile-info-value">

                                            <?php if(isset($social['data'][0]->linkedin)) { echo $social['data'][0]->linkedin; } ?>

                                        </div>

                                    </div>


                                    <div class="profile-info-row">
                                        <div class="profile-info-name"> snapchat </div>
                                        <div class="profile-info-value">
                                            <?php if(isset($social['data'][0]->snapchat)) { echo $social['data'][0]->snapchat; } ?>
                                        </div>
                                    </div>
                                    <div class="profile-info-row">

                                        <div class="profile-info-name"> Tiktok </div>
                                        <div class="profile-info-value">
                                            <?php if(isset($social['data'][0]->tiktok)) { echo $social['data'][0]->tiktok; } ?>
                                        </div>
                                    </div>
                                    <div class="profile-info-row">

                                        <div class="profile-info-name"> Triller </div>
                                        <div class="profile-info-value">
                                            <?php if(isset($social['data'][0]->triller)) { echo $social['data'][0]->triller; } ?>
                                        </div>
                                    </div>
                                    <div class="profile-info-row">

                                        <div class="profile-info-name"> Twitch </div>
                                        <div class="profile-info-value">
                                            <?php if(isset($social['data'][0]->twitch)) { echo $social['data'][0]->twitch; } ?>
                                        </div>
                                    </div>
                                    <div class="profile-info-row">

                                        <div class="profile-info-name"> Mixcloud </div>
                                        <div class="profile-info-value">
                                            <?php if(isset($social['data'][0]->mixcloud)) { echo $social['data'][0]->mixcloud; } ?>
                                        </div>
                                    </div>
                                    <div class="profile-info-row">

                                        <div class="profile-info-name"> Reddit </div>
                                        <div class="profile-info-value">
                                            <?php if(isset($social['data'][0]->reddit)) { echo $social['data'][0]->reddit; } ?>
                                        </div>
                                    </div>

                                </div>
                                <?php if($package_count == 0){
                                      }else{
                                ?>
                                <div class="table-header">
                                    Package Details
                                </div>
                                <div class="profile-user-info profile-user-info-striped">
                                    <div class="profile-info-row">
                                        <div class="profile-info-name"> Package Name </div>
                                        <div class="profile-info-value">
                                            <?php //dd($client_package_details); ?>
                                            <?php if($client_package_details == 'Standard'){echo 'Standard';}else{ echo $client_package_details->package_name; } ?>
                                        </div>
                                    </div>
                                    <div class="profile-info-row">
                                        <div class="profile-info-name"> Package Type </div>
                                        <div class="profile-info-value">
                                            <?php if($client_package_details == 'Standard'){echo 'Lifetime';}else{ echo $client_package_details->package_type; } ?>
                                        </div>
                                    </div>
                                    <div class="profile-info-row">
                                        <div class="profile-info-name"> Package Amount </div>
                                        <div class="profile-info-value">
                                            <?php if($client_package_details == 'Standard'){echo 'Free';}else{ echo '$'.$client_package_details->payment_amount; } ?>
                                        </div>
                                    </div>
                                    
                                    <div class="profile-info-row">
                                        <div class="profile-info-name"> Payment Method </div>
                                        <div class="profile-info-value">
                                            <?php if($client_package_details == 'Standard'){echo '-';}else{ echo $client_package_details->payment_method; } ?>
                                        </div>
                                    </div>
                                    <div class="profile-info-row">
                                        <div class="profile-info-name"> Payment Status </div>
                                        <div class="profile-info-value">
                                            <?php if($client_package_details == 'Standard'){echo '-';}else{ if($client_package_details->payment_status == 1){echo 'Paid';}else{echo 'Not Paid';} } ?>
                                        </div>
                                    </div>
                                    <div class="profile-info-row">
                                        <div class="profile-info-name"> Payment Start Date </div>
                                        <div class="profile-info-value">
                                            <?php if($client_package_details == 'Standard'){echo '-';}else{ echo $client_package_details->package_start_date; } ?>
                                        </div>
                                    </div>
                                    <div class="profile-info-row">
                                        <div class="profile-info-name"> Payment Expiry Date </div>
                                        <div class="profile-info-value">
                                            <?php if($client_package_details == 'Standard'){echo '-';}else{ echo $client_package_details->package_expiry_date; } ?>
                                        </div>
                                    </div>
                                    <?php } ?>
                                </div>





                            </div>













                        </div><!-- /.span -->

                    </div><!-- /.row -->



                    <div class="hr hr-18 dotted hr-double"></div>





                    <!-- PAGE CONTENT ENDS -->

                </div><!-- /.col -->

            </div><!-- /.row -->
            <?php }else{echo "Client doesn't exists.";}?>

        </div><!-- /.page-content -->

        @endsection