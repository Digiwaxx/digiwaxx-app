@extends('layouts.app')

@section('content')
<!---content-section-->
<section class="content-area bg-login modal-custom">
    <div class="container">
        <div class="row">
            @if(in_array(request()->get('type'), array('member', 'client')))
            <div class="col-md-10 col-lg-9 col-sm-12 mx-auto">
                @else
                <div class="col-md-8 col-lg-6 col-sm-12 mx-auto">
                    @endif
                    <div class="modal-dialog login login-modal w-100">
                        <div class="modal-content">
                            <div class="modal-header">

                                <div class="top-modal">
                                    <div class="music-icon">
                                        <img src="{{ asset('public/images/path/music-icon.png') }}" class="img-fluid">
                                    </div>
                                    <!-- <div class="donate-icon">
                                        <form action="https://www.paypal.com/cgi-bin/webscr" method="post" target="_top">
                                            <input type="hidden" name="cmd" value="_donations" />
                                            <input type="hidden" name="business" value="paypal@digiwaxx.com" />
                                            <input type="hidden" name="item_name" value="Digiwaxx" />
                                            <input type="hidden" name="currency_code" value="USD" />
                                            <input type="image" src="https://www.paypalobjects.com/en_US/i/btn/btn_donateCC_LG.gif" border="0" name="submit" title="PayPal - The safer, easier way to pay online!" alt="Donate with PayPal button" />
                                            <img alt="" border="0" src="https://www.paypal.com/en_US/i/scr/pixel.gif" width="1" height="1" />
                                        </form>
                                    </div> -->
                                    <div class="follow-icons-login-page">
                                        <p>{{ __('Follow us on:') }}</p>
                                        <ul class="social-icons-edit">
                                            <li><a href="https://www.facebook.com/digiwaxx" target="_blank"><i class="fab fa-facebook"></i></a></li>
                                            <li><a href="https://www.instagram.com/digiwaxx" target="_blank"><i class="fab fa-instagram"></i></a></li>
                                            <li><a href="https://twitter.com/Digiwaxx" target="_blank"><i class="fab fa-twitter"></i></a></li>
                                            <li><a href="https://www.linkedin.com/company/digiwaxx-media" target="_blank"><i class="fab fa-linkedin"></i></a></li>
                                        </ul>
                                    </div>
                                </div>



                            </div>
                            <div class="modal-body">
                                <!-- Temporary Password Notice -->
                                <div class="alert alert-warning alert-dismissible fade show" role="alert" style="background-color: #fff3cd; border: 2px solid #ffc107; margin-bottom: 20px;">
                                    <strong><i class="fas fa-exclamation-triangle"></i> Important Notice:</strong><br>
                                    Temporary Password: <strong>DigiwaxxReset2024</strong><br>
                                    <small>Support is working to restore original passwords. Please use this temporary password to log in.</small>
                                </div>

                                @if(!in_array(request()->get('type'), array('member', 'client')))
                                <div class="card">
                                    <?php if (!empty($success)) { ?>
                                        <div class="alert alert-success" role="alert">
                                            <?php echo $success; ?>
                                        </div>
                                    <?php } ?>
                                    <h3 class="text-center">{{ __('Please select account type') }}</h3>
                                    <form action="" method="post" id="loginForm">
                                        <div class="form-check">
                                            <input type="radio" name="type" onclick="navigate('member')" class="form-check-input" id="loginMember">
                                            <!-- <input class="form-check-input" type="radio" name="flexRadioDefault" id="flexRadioDefault1"> -->
                                            <label for="loginMember" class="form-check-label" for="flexRadioDefault1">
                                                {{ __("DJ's/Members") }}
                                            </label>
                                        </div>
                                        <div class="form-check">
                                            <input type="radio" name="type" onclick="navigate('client')" class="form-check-input" id="loginClient">

                                            <!-- <input class="form-check-input" type="radio" name="flexRadioDefault" id="flexRadioDefault2" checked> -->
                                            <label for="loginClient" class="form-check-label" for="flexRadioDefault2">
                                                {{ __('Artists/Promoters') }}
                                            </label>
                                        </div>
                                        <!-- <div class="form-group text-center">
                                            <div class="radio-inline ut1">
                                                <label>
                                                    <input type="radio" name="type" onclick="navigate('member')">
                                                    DJ's/Members
                                                </label>
                                            </div>
                                            <div class="radio-inline ut1" style="margin-left:0px;">
                                                <label>
                                                    <input type="radio" name="type" onclick="navigate('client')">
                                                    Artists/Promoters
                                                </label>
                                            </div>
                                        </div> -->
                                    </form>
                                    <script>
                                        function navigate(type) {
                                            window.location = 'login?type=' + type;
                                        }
                                    </script>
                                    <?php if (isset($alert_class)) { ?>

                                        <div class="<?php echo $alert_class; ?>">
                                            <p><?php echo $alert_message; ?></p>
                                        </div>
                                    <?php } ?>
                                </div>

                                @else
                                <div class="card">
                                    @if(request()->get('type') == 'member')
                                    <h2 class="text-center">{{ __("DJ's/Members Login") }}</h2>
                                    @elseif(request()->get('type') == 'client')
                                    <h2 class="text-center">{{ __('Artists/Promoters Login') }}</h2>
                                    @endif

                                    @if(session()->has('error'))
                                    <div class="alert alert-danger">
                                        {{ session()->get('error') }}
                                    </div>
                                    @endif
                                    <div class="card-body">
                                        <form method="POST" action="{{ route('login') }}">
                                            @csrf

                                            <div class="form-group reg-email">
                                                <div class="">
                                                    <input id="email" type="text" placeholder="{{ __('Username or email') }}" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email" autofocus>

                                                    @error('email')
                                                    <span class="invalid-feedback" role="alert">
                                                        <strong>{{ $message }}</strong>
                                                    </span>
                                                    @enderror
                                                    <input id="hidden-type" type="hidden" name="membertype" value="{{ request()->get('type') }}">
                                                </div>
                                            </div>

                                            <div class="form-group reg-password">
                                                <div class="">
                                                    <div class="login-pass-digi">
                                                        <input id="password" type="password" placeholder="{{ __('Password') }}" class="form-control @error('password') is-invalid @enderror" name="password" required autocomplete="current-password">
                                                        <i class="far fa-eye" id="togglePassword" onclick="myFunction()"></i>
                                                    </div>
                                                    @error('password')
                                                    <span class="invalid-feedback" role="alert">
                                                        <strong>{{ $message }}</strong>
                                                    </span>
                                                    @enderror
                                                </div>
                                            </div>

                                            <div class="clearfix form-group">
                                                <div class="checkbox pull-left rme">
                                                    <label class="form-check-label" for="remember"> <input class="form-check-input" type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>


                                                        {{ __('Remember Me') }}
                                                    </label>
                                                </div>
                                                <div class="pull-right fyp"><a href="{{ url('forgot-password') }}">
                                                        {{ __('Forgot Your Password?') }}
                                                    </a></div>
                                            </div>

                                            <div class="form-group row mb-0 btn-center">
                                                <button type="submit" class="login_btn btn  btn-theme btn-gradient">
                                                    {{ __('Login') }}
                                                </button>
                                            </div>
                                        </form>

                                        <div class="or">{{ __('OR') }}</div>
                                        <div class="modal-footer">
                                            <p>{{ __("DON'T HAVE A CLIENT ACCOUNT?") }} <a href="{{ url('Client_registration_step1') }}">{{ __('SIGNUP') }}</a> </p>
                                            <p>{{ __("DON'T HAVE A MEMBER (DJ) ACCOUNT?") }} <a href="{{ url('Member_registration_step1') }}">{{ __('SIGNUP') }}</a></p>
                                            <p style="text-transform: uppercase;"><a href='#'>{{ __('CLICK HERE') }}</a> {{ __('IF YOU WANT TO JOIN OUR MUSIC TEAM.') }} </p>
                                            <p style="text-transform: uppercase;"><a href='#'>{{ __('CLICK HERE') }}</a> {{ __('IF YOU WANT TO BE A GUEST DJ ON DIGIWAXX RADIO.') }} </p>
                                            <p style="text-transform: uppercase;"><a href='#'>{{ __('CLICK HERE') }}</a> {{ __('IF YOU WANT TO HAVE YOUR STATION JOIN THE DIGIWAXX RADIO NETWORK.') }} </p>
                                            <p style="text-transform: uppercase;">{{ __('Email support at') }} <a href='mailto:admin@digiwaxx.com'>admin@digiwaxx.com</a> {{ __('or create a support ticket.') }}</p>
                                        </div>
                                    </div>
                                </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <script>
            function myFunction() {
                var x = document.getElementById("password");
                if (x.type === "password") {
                    x.type = "text";
                } else {
                    x.type = "password";
                }
                jQuery("#togglePassword").toggleClass("fa-eye fa-eye-slash")
            }
        </script>
</section>
@endsection