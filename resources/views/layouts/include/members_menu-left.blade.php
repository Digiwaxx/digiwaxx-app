<aside>
       <div class="left-sidebar">
         <div class="sidebar-heading">My Record Pool</div>       
         <div class="menu-first">
           <ul class="menu-default">
             <li class="<?php echo (isset($active) && $active == 'tracks')? 'active': '' ;?>" >
               <a href="{{ route('member-newest-tracks') }}"><span class="li-icon"><i class="fas fa-music"></i></span> Tracks</a>
             </li>
             <!--<li class="<?php //echo (isset($active) && $active == 'mytracks')? 'active': '' ;?>" >-->
             <!--  <a href="{{ route('member_my_tracks') }}"><span class="li-icon"><i class="fas fa-music"></i></span> My Tracks</a>-->
             <!--</li>-->
             <li class="<?php echo (isset($active) && $active == 'messages')? 'active': '' ;?>" >
               <a href="{{ route('member-messages') }}"><span class="li-icon"><i class="far fa-envelope"></i></span> Messages</a>
             </li>
             <li class="<?php echo (isset($active) && $active == 'announcements')? 'active': '' ;?>" >
               <a href="{{ route('member-list-announcement') }}"><span class="li-icon"><i class="far fa fa-bullhorn"></i></span> Announcements</a>
             </li>
             <li class="<?php echo (isset($active) && $active == 'add-article')? 'active': '' ;?>" >
               <a href="{{ route('add-article') }}" target="_blank"><span class="li-icon"><i class="far fa fa-newspaper-o"></i></span> Add article</a>
             </li>
            <!--<li class="<?php //echo (isset($active) && $active == 'uploadmedia')? 'active': '' ;?>">-->
            <!--   <a href="{{ route('member_uploadmedia') }}"><span class="li-icon"><i class="fas fa-coins"></i></span> Upload Your Music</a>-->
            <!-- </li>-->
             <li class="<?php echo (isset($active) && $active == 'meminfo')? 'active': '' ;?>">
               <a href="{{ route('member-info') }}"><span class="li-icon"><i class="far fa-user"></i></span> My Info</a>
             </li>
             <li class="<?php echo (isset($active) && $active == 'archives')? 'active': '' ;?>">
               <a href="{{ route('member-tracks-archives') }}"><span class="li-icon"><i class="fas fa-archive"></i></span> Archives</a>
             </li>
             <li class="<?php echo (isset($active) && $active == 'mycrate')? 'active': '' ;?>">
               <a href="{{ route('member-tracks-own-archives') }}"><span class="li-icon"><i class="fas fa-archive"></i></span> My Crate</a>
             </li>
             <li class="<?php echo (isset($active) && $active == 'products')? 'active': '' ;?>">
               <a href="{{ route('products-callback') }}"><span class="li-icon"><i class="fas fa-shopping-basket"></i></span> Products</a>
             </li>
             <li class="<?php echo (isset($active) && $active == 'orders')? 'active': '' ;?>">
               <a href="{{ route('member-orders') }}"><span class="li-icon"><i class="fas fa-truck"></i></span> Orders</a>
             </li>
             <li class="<?php echo (isset($active) && $active == 'mydigicoins')? 'active': '' ;?>">
               <a href="{{ route('member-my-digicoins') }}"><span class="li-icon"><i class="fas fa-coins"></i></span> My Digicoins</a>
             </li>

           </ul>
         </div>
           <div class="menu-second">
           <ul class="menu-default">
             <li>
               <a href="https://digiwaxx.com"><span class="li-icon"><i class="fas fa-chevron-right"></i></span> Home</a>
             </li>
             <li>
               <a href="{{ url('/PromoteYourProject') }}"><span class="li-icon"><i class="fas fa-chevron-right"></i></span> Promote your project
</a>
             </li>
             <li>
               <a href="{{ url('/Charts') }}"><span class="li-icon"><i class="fas fa-chevron-right"></i></span> Charts</a>
             </li>
             <li>
               <a href="https://digiwaxxradio.com"><span class="li-icon"><i class="fas fa-chevron-right"></i></span> Digiwaxx radio</a>
             </li>
             <!--  <li>-->
             <!--  <a href="{{route('package_history')}}"><span class="li-icon"><i class="fas fa-chevron-right"></i></span>Payment History</a>-->
             <!--</li> -->
             <li>
               <a href="{{ url('/Contactus') }}"><span class="li-icon"><i class="fas fa-chevron-right"></i></span> Contact us</a>
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
         <div class="menu-third"><iframe src="https://www3.cbox.ws/box/?boxid=3518713&boxtag=3z2NfX" width="100%" height="450" allowtransparency="yes" allow="autoplay" frameborder="0" marginheight="0" marginwidth="0" scrolling="auto"></iframe></div>
       </div>
     </aside>