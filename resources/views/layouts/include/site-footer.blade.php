<!---footer--->
<footer>
  <div class="container">
    <div class="footer-top">
      <div class="row align-items-center">
        <div class="col-md-6 col-sm-12">
          <?php
          $get_logo = '';

          $logo_details = DB::table('website_logo')->where('logo_id', 1)->first();
          if (!empty($logo_details) && !empty($logo_details->pCloudFileID)) {
            $get_logo = $logo_details->pCloudFileID;
          }

          ?>
          <div class="foot-logo">
            <?php if (!empty($get_logo)) { ?>
              <a href="https://digiwaxx.com"><img src="<?php echo url('/pCloudImgDownload.php?fileID=' . $get_logo); ?>" class="img-fluid"></a>
            <?php   } else { ?>
              <a href="https://digiwaxx.com"><img src="{{ asset('public/images/logo.png') }}" class="img-fluid"></a>
            <?php    } ?>
            <!--<a href="{{ url('/') }}"><img src="{{ asset('public/images/logo.png') }}" class="img-fluid"></a>-->
          </div>
        </div>
        <!-- <div class="col-md-6 col-sm-12">
             <div class="app-store">
              <span> App coming soon </span>
            <a href="#"><i class="fa fa-apple"></i></a>
            <a href="#"><i class="fa fa-android"></i></a>        
             </div>
           </div> -->
        <div class="col-md-6 col-sm-12">
          <div class="foot-social">
            <ul>
              <li><a target="_blank" href="https://www.facebook.com/digiwaxx"><i class="fa fa-facebook"></i></a></li>
              <li><a target="_blank" href="https://twitter.com/Digiwaxx"><i class="fa fa-twitter"></i></a></li>
              <li><a target="_blank" href="https://www.linkedin.com/company/digiwaxx-media"><i class="fa fa-linkedin"></i></a></li>
              <li><a target="_blank" href="https://www.instagram.com/digiwaxx"><i class="fa fa-instagram"></i></a></li>
            </ul>
          </div>
        </div>
      </div>
      <div class="row align-items-center">
        <div class="col-md-6 col-sm-12">
          <div class="privacy-link">
            <a href="{{ url('Privacy_policy') }}">Privacy Policy</a>
          </div>
        </div>

      </div>

    </div>
    <!--foot nav-->
    <div class="foot-menu">
      <ul>
        <li><a href="https://digiwaxx.com">HOME</a></li>
        <li><a href="{{ route('Client_tracks') }}">MY TRACKS</a></li>
        <li><a href="{{ url('/PromoteYourProject') }}">PROMOTE YOUR PROJECT</a></li>
        <li><a href="{{ url('/Charts') }}">CHARTS</a></li>
        <li><a href="https://digiwaxxradio.com">DIGIWAXX RADIO</a></li>
        <li><a href="{{ url('/Contactus') }}">CONTACT US</a></li>
      </ul>
    </div>
    <div class="copy-right">
      <p>&copy; Digiwaxx, LLC.</p>
    </div>
  </div>

  <script>
    $(document).ready(function() {

      $(document).on("click", ".mejs__playpause-button", function() {
        console.log("-----------------------------------------");

        $("#jquery_jplayer_1").jPlayer("stop");

      });

    });
  </script>
  <script type="text/javascript">
    window.jQuery || document.write("<script src='{{ asset('assets_admin/assets/js/jquery.js')}}'>" + "<" + "/script>");
  </script>
  <script type="text/javascript">
    if ('ontouchstart' in document.documentElement) document.write("<script src='{{ asset('assets_admin/assets/js/jquery.mobile.custom.js')}}'>" + "<" + "/script>");
  </script>

  <script src="{{ asset('assets_admin/assets/js/bootstrap.js')}}"></script>

  <script src="{{ asset('assets_admin/assets/js/ace/elements.scroller.js')}}"></script>

  <script src="{{ asset('assets_admin/assets/js/ace/elements.colorpicker.js')}}"></script>

  <script src="{{ asset('assets_admin/assets/js/ace/elements.fileinput.js')}}"></script>

  <script src="{{ asset('assets_admin/assets/js/ace/elements.typeahead.js')}}"></script>

  <script src="{{ asset('assets_admin/assets/js/ace/elements.wysiwyg.js')}}"></script>

  <script src="{{ asset('assets_admin/assets/js/ace/elements.spinner.js')}}"></script>

  <script src="{{ asset('assets_admin/assets/js/ace/elements.treeview.js')}}"></script>

  <script src="{{ asset('assets_admin/assets/js/ace/elements.wizard.js')}}"></script>

  <script src="{{ asset('assets_admin/assets/js/ace/elements.aside.js')}}"></script>

  <script src="{{ asset('assets_admin/assets/js/ace/ace.js')}}"></script>

  <script src="{{ asset('assets_admin/assets/js/ace/ace.ajax-content.js')}}"></script>

  <script src="{{ asset('assets_admin/assets/js/ace/ace.touch-drag.js')}}"></script>

  <script src="{{ asset('assets_admin/assets/js/ace/ace.sidebar.js')}}"></script>

  <script src="{{ asset('assets_admin/assets/js/ace/ace.sidebar-scroll-1.js')}}"></script>

  <script src="{{ asset('assets_admin/assets/js/ace/ace.submenu-hover.js')}}"></script>

  <script src="{{ asset('assets_admin/assets/js/ace/ace.widget-box.js')}}"></script>

  <script src="{{ asset('assets_admin/assets/js/ace/ace.settings.js')}}"></script>

  <script src="{{ asset('assets_admin/assets/js/ace/ace.settings-rtl.js')}}"></script>

  <script src="{{ asset('assets_admin/assets/js/ace/ace.settings-skin.js')}}"></script>

  <script src="{{ asset('assets_admin/assets/js/ace/ace.widget-on-reload.js')}}"></script>

  <script src="{{ asset('assets_admin/assets/js/ace/ace.searchbox-autocomplete.js')}}"></script>


</footer>
<!---footer end--->