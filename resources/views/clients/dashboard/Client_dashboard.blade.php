@extends('layouts.client_dashboard')

@section('content')
 <section class="main-dash">
	<aside>@include('clients.dashboard.includes.sidebar-left')</aside>
	<div class="dash-container">
		 <div class="container">
			<div class="row">
			<div class="col-xl-9 col-12">
            	<div class="dash-heading">
                	<h2>{{ __('My Dashboard') }}</h2>
             	</div>
	            <div class="tabs-section">
					<!-- START MIDDLE BLOCK -->
					<div class="client-middle-block">
						<div class="dash-blk f-block">

							@if(isset($welcomeMsg))
								<div class="alert alert-primary alert-dismissable">
									<a href="#" class="close" data-dismiss="alert"
									   aria-label="close">&times;</a>{{ $welcomeMsg }}
								</div>
							@endif
							<div class="dsh nav nav-tabs">
								<a href="javascript:void(0)" class="nav-link active">{{ __('ALL') }}</a>
								<a href="{{ route('ClienDashboardRated') }}" id="world-1"
								   class="nav-link ">{{ __('RATED') }}</a>
								<a href="{{ route('ClienDashboardDownloaded') }}" id="world-2" class="nav-link ">{{ __('DOWNLOADED') }}</a>
								<a href="{{ route('ClienDashboardCommented') }}" id="world-3" class="nav-link ">{{ __('COMMENTED') }}</a>
								<a href="{{ route('ClienDashboardPlayed') }}" id="world-4" class="nav-link ">{{ __('PLAYED') }}</a>
							</div>
							<div class="vmap-blk">
								<div id="world-map-1"></div>
							</div>

							<div class="map-lgd">
								<p><span class="gc-all"></span><label>{{ __('ALL') }}</label></p>
								<p><span class="gc-rtd"></span><label>{{ __('RATED') }}</label></p>
								<p><span class="gc-dwd"></span><label>{{ __('DOWNLOADED') }}</label></p>
								<p><span class="gc-cmt"></span><label>{{ __('COMMENTED') }}</label></p>
								<p><span class="gc-pld"></span><label>{{ __('PLAYED') }}</label></p>
							</div>
						</div>
					</div><!-- eof middle block -->
				</div>
			</div>
			<div class="col-xl-3 col-12">
				@include('clients.dashboard.includes.my-tracks')
			</div>
		</div>
	</div>
</div>
		
 </section>
@endsection