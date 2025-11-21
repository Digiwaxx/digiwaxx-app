

@extends('layouts.app')
@section('content')
<style>
   .download_link, .download_link:hover
   {
   color: #FFF;
   font-weight: bold;
   display: block;
   margin-top: 6px;
   }
   #form-toggle
   {
   cursor: pointer;
   }
   h2.logo_headings {
   width: 100%;
   margin-bottom: 10px;
   }
   .col-auto {
   margin: 6px 10px;
   /*margin:2px;*/
   }
   .col-auto img {
   height: 55px;
   width: auto;
   }
   .logos {
   display: flex;
   flex-wrap: wrap;
   margin: 0;
   margin-bottom: 15px;
   justify-content: flex-start;
   }
</style>
<section class="main-dash">
  
   <?php
      $link_text = 'UNLOCK DOWNLOAD';
      $member_session_pkg = Session::get('memberPackage');
      if(isset($member_session_pkg) && $member_session_pkg > 2)
      {
      $link_text = 'WRITE A REVIEW';
      }
      ?>
   <div class="dash-container">
      <div class="container">
     
               <div class="dso-blk">
                     <h1>DIGIWAXX <span>DIGICOINS</span></h1>
                     <div class="dso-sec center-block">
                        <div class="row">
                           <div class="col-lg-4 col-md-4 col-sm-4">
                              <div class="dso-pay bg silver-plan">
                                 <div class="price">
                                    <h3 class="pkg">Silver</h3>
                                 </div>
                                 <!-- eof price -->
                                 <h4>$50</h4>
                                 <ul>
                                    <li><i class="fa fa-check"></i>50 <span>Digicoins</span></li>
                                 </ul>
                                 <div class="sub-btn">
                                    <form action="" method="post">
                                       @csrf
                                       <input class="btn btn-theme btn-gradient" type="submit" name="silver" value="CHOOSE"  />
                                    </form>
                                 </div>
                              </div>
                              
                           </div>
                           <div class="col-lg-4 col-md-4 col-sm-4">
                              <div class="dso-pay bg gold-plan">
                                 <div class="price">
                                    <h3 class="pkg">Gold</h3>
                                 </div>
                                 <!-- eof price -->
                                 <h4>$80</h4>
                                 <ul>
                                    <li><i class="fa fa-check"></i>80 <span>Digicoins</span></li>
                                 </ul>
                                 <div class="sub-btn">
                                    <form action="" method="post">
                                       @csrf
                                       <input class="btn btn-theme btn-gradient" type="submit" name="gold" value="CHOOSE" /> 
                                    </form>
                                 </div>
                              </div>
                              
                           </div>
                           <div class="col-lg-4 col-md-4 col-sm-4">
                              <div class="dso-pay bg1 purple-plan">
                                 <div class="price">
                                    <h3 class="pkg">Purple</h3>
                                 </div>
                                 <!-- eof price -->
                                 <h4>$100</h4>
                                 <ul>
                                    <li><i class="fa fa-check"></i>100 <span>Digicoins</span></li>
                                 </ul>
                                 <div class="sub-btn">
                                    <form action="" method="post">
                                       @csrf
                                       <input class="btn btn-theme btn-gradient" type="submit" name="purple" value="CHOOSE" />
                                    </form>
                                 </div>
                              </div>
                              
                           </div>
                        </div>
                     </div>
                  
               </div>
               <!-- eof dso-blk -->
          
      </div>
   </div>
</section>
@endsection

