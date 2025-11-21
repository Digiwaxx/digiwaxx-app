@extends('layouts.app')

@section('content')
<?php foreach ($package_details as $value) {
    $package_type = urldecode($value->package_type);
    $package_price = urldecode($value->package_price);
    $package_features = json_decode($value->package_features);
    $package_id = urldecode($value->id);
} ?>

<!-- Register Block Starts-->
<section class="content-area bg-login modal-custom">
    <div class="container">
        <div class="row">
            <div class="col-md-8 col-lg-8 col-sm-12 mx-auto">
                <div class="modal-dialog ">
                    <div class="modal-content">
                        <div class="modal-header">
                            <div class="top-modal">
                                <div class="music-icon">
                                    <img src="{{ asset('public/images/path/music-icon.png') }}" class="img-fluid">
                                </div>
                                <h2 class="text-center">Create a Member Account</h2>

                            </div>

                        </div>
                        <div class="modal-body">
                            <form action="" method="post" id="registrationForm">
                                @csrf
                                <input type="hidden" name="new_pack_id" value="<?php echo $package_id; ?>">
                                <div class="rgt1">
                                    <p class="text-center">
                                        A valid email address MUST be supplied to receive your member approval. <br>
                                        By clicking submit you are agreeing to our terms of service.<br>
                                        Please check your inbox and spam for confirmation email.
                                    </p>
                                </div>
                                <div class="package_info">

                                    <p class="package_type text-center">PACKAGE TYPE - <?php echo $package_type; ?></p>

                                    <p class="package_price text-center">PACKAGE PRICE - <?php if (!empty($package_price)) {
                                                                                                echo "$" . $package_price;
                                                                                            } else {
                                                                                                echo "FREE";
                                                                                            } ?></p>
                                    <p class="package_features">PACKAGE FEATURES-
                                    <ul class="pck_fea">
                                        <?php foreach ($package_features as $key => $value) {
                                            echo '<li>';
                                            echo $value;
                                            echo '</li>';
                                            echo '<hr>';
                                        } ?>
                                    </ul>

                                    </p>
                                    <p class="package_link">
                                        <a href="{{route('package_selection_registration')}}"><button type="button" class="btn btn-link">SELECT ANOTHER PACKAGE</button></a>
                                    </p>
                                </div>
                                <div class="follow-us">
                                    <p>Follow us on:</p>
                                    <ul class="social-icons">
                                        <li><a href="https://www.facebook.com/digiwaxx" target="_blank"><i class="fab fa-facebook"></i></a></li>
                                        <li><a href="https://www.instagram.com/digiwaxx" target="_blank"><i class="fab fa-instagram"></i></a></li>
                                        <li><a href="https://twitter.com/Digiwaxx" target="_blank"><i class="fab fa-twitter"></i></a></li>
                                        <li><a href="https://www.linkedin.com/company/digiwaxx-media" target="_blank"><i class="fab fa-linkedin"></i></a></li>
                                    </ul>
                                </div>

                                <div class="btn-center">
                                    <?php if ($package_id == 7) { ?>
                                        <input name="addMember4" class="login_btn btn btn-theme btn-gradient" value="CONFIRM PACKAGE" type="submit">
                                    <?php } ?>
                                </div>
                            </form>
                            <?php if ($package_id != 7) { ?>
                                <form action="package_payment/checkout" method="post">
                                    @csrf
                                    <input type="hidden" name="buyId" value="<?php echo $package_id; ?>" />
                                    <input type="hidden" name="amount" value="<?php echo $package_price; ?>" />
                                    <!-- pk_live_5BBqzoPi5GoH5UYqZHQTwMHY  -->
                                    <script src="https://checkout.stripe.com/checkout.js" class="stripe-button" data-key="pk_test_51JmHVaSFsniFu3P5aDzyOj62m7QM1XZWcbqWlJQPKCM2MH6hPFoW6Zj3GNr3dF9SSxIRkxY4tODUaMqivzRt1l8e00xKfv3mc0" data-name="Digiwaxx" data-email="<?php echo $email; ?>" data-image="{{ asset('public/images/logo2.png') }}" data-label="PAY NOW" data-amount="<?php echo $package_price * 100; ?>" data-locale="auto">

                                    </script>
                                </form>

                            <?php } ?>

                        </div>
                    </div>
                    <!-- /.modal-content -->
                </div>
            </div>
        </div>
    </div>
</section>
@endsection