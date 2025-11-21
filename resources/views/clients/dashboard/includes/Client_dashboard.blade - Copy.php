@extends('layouts.client_dashboard')

@section('content')
 <div class="client-block">
			<!-- MY RECORD POOL MENU, NOTIFICATIONS AND MY TRACKS FOR SMALLER DEVICES -->
			<div class="container">
				<div class="row">
					<div class="hidden-lg col-md-6 col-sm-6 col-xs-6">
						<div class="mrp-sec">MY RECORD POOL</div>

						<div class="mrp-con">
							@include('clients.dashboard.sidebar-left')
						</div>
                    </div>
					
					<div class="hidden-lg col-md-6 col-sm-6 col-xs-6">
						<div class="ntf-blk" style="display:block;">
							<div class="ntf-sec"><i class="fa fa-flag"></i>MY TRACKS</div>

							<div class="ntf-con">

								<div class="ntf-lst-blk">
									<ul class="ntf-list mCustomScrollbar">
										<!--?php foreach ($rightTracks['data'] as $track)
										{


											?>
											<li class="trk">
												<p class="atst"><a href="#"><!--?php echo urldecode($track->title); ?></a></p>
												<p class="alb"><!--?php echo urldecode($track->album); ?></p>
												<p class="up-dt"><!--?php
													$added = explode(' ', $track->added);
													$added = explode('-', $added[0]);
													echo $added = $added[1].'/'.$added[2].'/'.$added[0];

													?></p>
												<p class="rlb"><!--?php echo urldecode($track->label); ?></p>
												<div class="clearfix st-blk">
													<div class="st">
														<i class="fa fa-star"></i>
														<span><!--?php echo $trackData[$track->id]['rating']; ?></span>
													</div>

													<div class="st">
														<i class="fa fa-cloud-download"></i>
														<span><!--?php echo $trackData[$track->id]['downloads']; ?></span>
													</div>

													<div class="st">
														<i class="fa fa-comment"></i>
														<span>0</span>
													</div>

													<div class="st">
														<i class="fa fa-play-circle"></i>
														<span><!--?php echo $trackData[$track->id]['plays']; ?></span>
													</div>

													<div class="st">
														<i class="fa fa-share-alt"></i>
														<span>0</span>
													</div>

												</div><!-- eof st-blk>
											</li><!-- eof trk >
										<!--?php } ?-->
									</ul>
								</div>


							</div><!-- eof ntf-con -->

						</div><!-- eof ntf-blk -->
					</div><!-- eof ntf col -->
					
					<div class="hidden-lg col-md-6 col-sm-6 col-xs-6">
						<div class="mts-blk" style="display:none;">
							<div class="mts-sec"><i class="fa fa-music"></i>MY TRACKS</div>

							<div class="mts-con">

								<div class="mts-lst-blk">
									<ul class="mts-list">
										<li class="trk">
											<p class="atst">Puff Daddy & The Family ft. Pharrell</p>
											<p class="alb">Finna Get Loose</p>
											<p class="up-dt">2015/10/20</p>
											<p class="rlb">Bad Boy Records</p>
											<div class="clearfix st-blk">
												<div class="st">
													<i class="fa fa-star"></i>
													<span>4</span>
												</div>

												<div class="st">
													<i class="fa fa-cloud-download"></i>
													<span>21</span>
												</div>

												<div class="st">
													<i class="fa fa-comment"></i>
													<span>2</span>
												</div>

												<div class="st">
													<i class="fa fa-play-circle"></i>
													<span>31</span>
												</div>

												<div class="st">
													<i class="fa fa-share-alt"></i>
													<span>3</span>
												</div>

											</div><!-- eof st-blk -->
										</li><!-- eof trk -->


										<li class="trk">
											<p class="atst">Chronixx</p>
											<p class="alb">Eternal Fire</p>
											<p class="up-dt">2015/10/20</p>
											<p class="rlb">Zinc Fence Records</p>
											<div class="clearfix st-blk">
												<div class="st">
													<i class="fa fa-star"></i>
													<span>4</span>
												</div>

												<div class="st">
													<i class="fa fa-cloud-download"></i>
													<span>21</span>
												</div>

												<div class="st">
													<i class="fa fa-comment"></i>
													<span>2</span>
												</div>

												<div class="st">
													<i class="fa fa-play-circle"></i>
													<span>31</span>
												</div>

												<div class="st">
													<i class="fa fa-share-alt"></i>
													<span>3</span>
												</div>

											</div><!-- eof st-blk -->
										</li><!-- eof trk -->


										<li class="trk">
											<p class="atst">Curren$y feat. August Alsina & Lil� Wayne</p>
											<p class="alb">Bottom of the Bottle</p>
											<p class="up-dt">2015/10/20</p>
											<p class="rlb">Bad Boy Records</p>
											<div class="clearfix st-blk">
												<div class="st">
													<i class="fa fa-star"></i>
													<span>4</span>
												</div>

												<div class="st">
													<i class="fa fa-cloud-download"></i>
													<span>21</span>
												</div>

												<div class="st">
													<i class="fa fa-comment"></i>
													<span>2</span>
												</div>

												<div class="st">
													<i class="fa fa-play-circle"></i>
													<span>31</span>
												</div>

												<div class="st">
													<i class="fa fa-share-alt"></i>
													<span>3</span>
												</div>

											</div><!-- eof st-blk -->
										</li><!-- eof trk -->


										<li class="trk">
											<p class="atst">Puff Daddy & The Family ft. Pharrell</p>
											<p class="alb">Finna Get Loose</p>
											<p class="up-dt">2015/10/20</p>
											<p class="rlb">Bad Boy Records</p>
											<div class="clearfix st-blk">
												<div class="st">
													<i class="fa fa-star"></i>
													<span>4</span>
												</div>

												<div class="st">
													<i class="fa fa-cloud-download"></i>
													<span>21</span>
												</div>

												<div class="st">
													<i class="fa fa-comment"></i>
													<span>2</span>
												</div>

												<div class="st">
													<i class="fa fa-play-circle"></i>
													<span>31</span>
												</div>

												<div class="st">
													<i class="fa fa-share-alt"></i>
													<span>3</span>
												</div>

											</div><!-- eof st-blk -->
										</li><!-- eof trk -->


									</ul>
								</div>


							</div><!-- eof mts-con -->

						</div><!-- eof mts-blk -->
					</div><!-- eof mts col -->
                </div>
            </div>
			
			    <!-- END OF MY RECORD POOL MENU, NOTIFICATIONS AND MY TRACKS FOR SMALLER DEVICES -->

    <!-- eof container -->


    <div class="container">
        <div class="row">
            <div class="col-lg-3 hidden-md hidden-sm hidden-xs">
                <div class="client-lb">
                    <div class="rp-block">
                        <h1>MY RECORD POOL</h1>
						@include('clients.dashboard.sidebar-left')
                    </div><!-- eof rp-block -->

                    <div class="s-menu">
                        <ul>

                            <li><a href="{{ url('WhatIsDigiwaxx') }}">HOME</a></li>

                            <li><a href="{{ url('PromoteYourProject') }}">PROMOTE YOUR PROJECT</a></li>
                            <li><a href="{{ url('Charts') }}">CHARTS</a></li>
                            <li><a href="{{ url('DigiwaxxRadio') }}">DIGIWAXX RADIO</a></li>
                            <li><a href="{{ url('Contactus') }}">CONTACT US</a></li>

                        </ul>
                    </div><!-- eof s-menu -->

                    <div class="s-social">
                        <a href="https://twitter.com/Digiwaxx"><i class="fa fa-twitter"></i></a>
                        <a href="https://www.facebook.com/digiwaxx"><i class="fa fa-facebook"></i></a>
                        <a href="#"><i class="fa fa-google-plus"></i></a>
                        <a href="https://www.instagram.com/digiwaxx"><i class="fa fa-instagram"></i></a>
                        <a href="https://www.linkedin.com/company/digiwaxx-media"><i class="fa fa-linkedin"></i></a>
                    </div>

                </div><!-- eof client-lb -->
            </div><!-- eof left block -->


            <!-- START MIDDLE BLOCK -->
            <div class="col-lg-6 col-md-12">
                <div class="dash-blk f-block">


                    <h1>MY DASHBOARD</h1>

					@if(isset($welcomeMsg))
                        <div class="alert alert-primary alert-dismissable">
                            <a href="#" class="close" data-dismiss="alert"
                               aria-label="close">&times;</a>{{ $welcomeMsg }}
                        </div>
					@endif

                    <div class="dsh clearfix">
                        <a href="{{ url('Client_dashboard') }}" class="pull-left active">ALL</a>
                        <a href="{{ url('Client_dashboard_rated') }}" id="world-1"
                           class="pull-left">RATED</a>
                        <a href="{{ url('Client_dashboard_downloaded') }}" id="world-2" class="pull-left">DOWNLOADED</a>
                        <a href="{{ url('Client_dashboard_commented') }}" id="world-3" class="pull-left">COMMENTED</a>
                        <a href="{{ url('Client_dashboard_played') }}" id="world-4" class="pull-left">PLAYED</a>
                    </div>


                    <div class="vmap-blk">
                        <div id="world-map-1" style="width:100%; height:100%;"></div>


                    </div>


                    <div class="map-lgd">
                        <p><span class="gc-all"></span><label>ALL</label></p>
                        <p><span class="gc-rtd"></span><label>RATED</label></p>
                        <p><span class="gc-dwd"></span><label>DOWNLOADED</label></p>
                        <p><span class="gc-cmt"></span><label>COMMENTED</label></p>
                        <p><span class="gc-pld"></span><label>PLAYED</label></p>
                    </div>

                    <div style="width:300px; height:400px;">


                    </div>


                </div>
            </div><!-- eof middle block -->

            <div class="col-lg-3 hidden-md hidden-sm hidden-xs">
                <div class="client-rb">
                    <div class="ntf-block" style="display:block;">
                        <h1><i class="fa fa-flag"></i>MY TRACKS</h1>
                        <div class="ntf-lst-blk">
                            <ul class="ntf-list mCustomScrollbar">
								<!--?php foreach ($rightTracks['data'] as $track)
										{


											?>
											<li class="trk">
												<p class="atst"><a href="#"><!--?php echo urldecode($track->title); ?></a></p>
												<p class="alb"><!--?php echo urldecode($track->album); ?></p>
												<p class="up-dt"><!--?php
													$added = explode(' ', $track->added);
													$added = explode('-', $added[0]);
													echo $added = $added[1].'/'.$added[2].'/'.$added[0];

													?></p>
												<p class="rlb"><!--?php echo urldecode($track->label); ?></p>
												<div class="clearfix st-blk">
													<div class="st">
														<i class="fa fa-star"></i>
														<span><!--?php echo $trackData[$track->id]['rating']; ?></span>
													</div>

													<div class="st">
														<i class="fa fa-cloud-download"></i>
														<span><!--?php echo $trackData[$track->id]['downloads']; ?></span>
													</div>

													<div class="st">
														<i class="fa fa-comment"></i>
														<span>0</span>
													</div>

													<div class="st">
														<i class="fa fa-play-circle"></i>
														<span><!--?php echo $trackData[$track->id]['plays']; ?></span>
													</div>

													<div class="st">
														<i class="fa fa-share-alt"></i>
														<span>0</span>
													</div>

												</div><!-- eof st-blk>
											</li><!-- eof trk >
										<!--?php } ?-->
                            </ul>
                        </div>
                    </div><!-- eof ntf-block-->

                    <div class="mts-block" style="display:none;">
                        <h1><i class="fa fa-music"></i>MY TRACKS</h1>
                        <div class="mts-lst-blk">
                            <ul class="mts-list">
                                <li class="trk">
                                    <p class="atst">Puff Daddy & The Family ft. Pharrell</p>
                                    <p class="alb">Finna Get Loose</p>
                                    <p class="up-dt">2015/10/20</p>
                                    <p class="rlb">Bad Boy Records</p>
                                    <div class="clearfix st-blk">
                                        <div class="st">
                                            <i class="fa fa-star"></i>
                                            <span>4</span>
                                        </div>

                                        <div class="st">
                                            <i class="fa fa-cloud-download"></i>
                                            <span>21</span>
                                        </div>

                                        <div class="st">
                                            <i class="fa fa-comment"></i>
                                            <span>2</span>
                                        </div>

                                        <div class="st">
                                            <i class="fa fa-play-circle"></i>
                                            <span>31</span>
                                        </div>

                                        <div class="st">
                                            <i class="fa fa-share-alt"></i>
                                            <span>3</span>
                                        </div>

                                    </div><!-- eof st-blk -->
                                </li><!-- eof trk -->


                                <li class="trk">
                                    <p class="atst">Chronixx</p>
                                    <p class="alb">Eternal Fire</p>
                                    <p class="up-dt">2015/10/20</p>
                                    <p class="rlb">Zinc Fence Records</p>
                                    <div class="clearfix st-blk">
                                        <div class="st">
                                            <i class="fa fa-star"></i>
                                            <span>4</span>
                                        </div>

                                        <div class="st">
                                            <i class="fa fa-cloud-download"></i>
                                            <span>21</span>
                                        </div>

                                        <div class="st">
                                            <i class="fa fa-comment"></i>
                                            <span>2</span>
                                        </div>

                                        <div class="st">
                                            <i class="fa fa-play-circle"></i>
                                            <span>31</span>
                                        </div>

                                        <div class="st">
                                            <i class="fa fa-share-alt"></i>
                                            <span>3</span>
                                        </div>

                                    </div><!-- eof st-blk -->
                                </li><!-- eof trk -->


                                <li class="trk">
                                    <p class="atst">Curren$y feat. August Alsina & Lil� Wayne</p>
                                    <p class="alb">Bottom of the Bottle</p>
                                    <p class="up-dt">2015/10/20</p>
                                    <p class="rlb">Bad Boy Records</p>
                                    <div class="clearfix st-blk">
                                        <div class="st">
                                            <i class="fa fa-star"></i>
                                            <span>4</span>
                                        </div>

                                        <div class="st">
                                            <i class="fa fa-cloud-download"></i>
                                            <span>21</span>
                                        </div>

                                        <div class="st">
                                            <i class="fa fa-comment"></i>
                                            <span>2</span>
                                        </div>

                                        <div class="st">
                                            <i class="fa fa-play-circle"></i>
                                            <span>31</span>
                                        </div>

                                        <div class="st">
                                            <i class="fa fa-share-alt"></i>
                                            <span>3</span>
                                        </div>

                                    </div><!-- eof st-blk -->
                                </li><!-- eof trk -->


                                <li class="trk">
                                    <p class="atst">Puff Daddy & The Family ft. Pharrell</p>
                                    <p class="alb">Finna Get Loose</p>
                                    <p class="up-dt">2015/10/20</p>
                                    <p class="rlb">Bad Boy Records</p>
                                    <div class="clearfix st-blk">
                                        <div class="st">
                                            <i class="fa fa-star"></i>
                                            <span>4</span>
                                        </div>

                                        <div class="st">
                                            <i class="fa fa-cloud-download"></i>
                                            <span>21</span>
                                        </div>

                                        <div class="st">
                                            <i class="fa fa-comment"></i>
                                            <span>2</span>
                                        </div>

                                        <div class="st">
                                            <i class="fa fa-play-circle"></i>
                                            <span>31</span>
                                        </div>

                                        <div class="st">
                                            <i class="fa fa-share-alt"></i>
                                            <span>3</span>
                                        </div>

                                    </div><!-- eof st-blk -->
                                </li><!-- eof trk -->


                            </ul>
                        </div>
                    </div><!-- eof mts-block-->


                    <div class="d-app">
                        <h2>YOUR RECORD POOL</h2>
                        <h1>EVERYWHERE</h1>

                        <div class="lg"><img src="assets/img/logo.png"></div>

                        <div class="d-btn">
                            <span>APP Coming soon</span>
                        </div>


                    </div><!-- eof d-app -->

                </div><!-- eof client-rb -->
            </div><!-- eof right block -->


            <!--- Download App Code for Smaller Devices -->

            <div class="hidden col-md-12  col-sm-12 col-xs-12">
                <div class="d-app">
                    <h2>YOUR RECORD POOL</h2>
                    <h1>EVERYWHERE</h1>

                    <div class="lg"><img src="assets/img/logo.png"></div>

                    <div class="d-btn">
                        <span>APP Coming soon</span>
                    </div>


                    <div class="f-social">
                        <a href="https://twitter.com/Digiwaxx"><i class="fa fa-twitter"></i></a>
                        <a href="https://www.facebook.com/digiwaxx"><i class="fa fa-facebook"></i></a>
                        <a href="#"><i class="fa fa-google-plus"></i></a>
                        <a href="https://www.instagram.com/digiwaxx"><i class="fa fa-instagram"></i></a>
                        <a href="https://www.linkedin.com/company/digiwaxx-media"><i class="fa fa-linkedin"></i></a>
                    </div>

                </div><!-- eof d-app -->


            </div>

            <!--- eof of download app -->


        </div>
    </div>
        </div>
@endsection