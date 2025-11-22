<div class="left-sidebar">
	<div class="sidebar-heading">{{ __('My Record Pool') }}</div> 

	 <div class="menu-first">
		@include('clients.dashboard.includes.client_menu-left') 
	 
	 </div>
	   <div class="menu-second">
	   <ul class="menu-default">
		 <li>
		   <a href="https://digiwaxx.com"><span class="li-icon"><i class="fas fa-chevron-right"></i></span> {{ __('Home') }}</a>
		 </li>
		 <li>
		   <a href="{{ url('/PromoteYourProject') }}"><span class="li-icon"><i class="fas fa-chevron-right"></i></span> {{ __('Promote your project') }}</a>
		 </li>
		 <li>
		   <a href="{{ url('/Charts') }}"><span class="li-icon"><i class="fas fa-chevron-right"></i></span> {{ __('Charts') }}</a>
		 </li>
		 <li>
		   <a href="https://digiwaxxradio.com"><span class="li-icon"><i class="fas fa-chevron-right"></i></span> {{ __('Digiwaxx Radio') }}</a>
		 </li>
		 <!-- <li>-->
		 <!--  <a href="{{ url('/client_payment_history') }}"><span class="li-icon"><i class="fas fa-chevron-right"></i></span>Payment History</a>-->
		 <!--</li>-->
		 <li>
		   <a href="{{ url('/Contactus') }}"><span class="li-icon"><i class="fas fa-chevron-right"></i></span> {{ __('Contact us') }}</a>
		 </li>             
		 
	   </ul>

	   <div class="social-sidebar">
		   <a href="https://www.facebook.com/digiwaxx"><i class="fab fa-facebook-f"></i></a>
		   <a href="https://twitter.com/Digiwaxx"><i class="fab fa-twitter"></i></a>
		   <a href="https://www.linkedin.com/company/digiwaxx-media"><i class="fab fa-linkedin"></i></a>
		   <a href="https://www.instagram.com/digiwaxx"><i class="fab fa-instagram"></i></a>
		   <!--a href="#"><i class="fab fa-google"></i></a-->
		 </div>
	 </div>
</div>