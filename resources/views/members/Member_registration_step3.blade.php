@extends('layouts.app')

@section('content')

<style>
   #radioDiv div.checkbox, #clubDjDiv div.checkbox { display: inline; }
</style>
<!-- Register Block Starts-->
<section class="content-area bg-login modal-custom">
     <div class="container">
      <div class="row">
        <div class="col-md-10 col-lg-10 col-sm-12 mx-auto">
<div class="modal-dialog">
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
            <h3 class="text-center mb-4">My Information</h3>
            <form action="" method="post" id="registrationForm" autocomplete="off">   
                @csrf
                <div class="form-group mb-4 gender-opt">
                    <!--<span class="man"></span>-->
                    <select name="sex" id="sex" class="selectpicker form-control">
                        <option value="">Sex</option>
                        <option value="male" {{ (Session::get('sess-member-sex')=="male") ? "selected" : "" }}>Male</option>
                        <option value="female" {{ (Session::get('sess-member-sex')=="female") ? "selected" : "" }}>Female</option>
                    </select>
                </div>
                <div class="form-group">
                    <!--<span class="man"></span>-->
                    <input name="stageName" id="stageName" class="form-control input" size="20" placeholder="Stage name/DJ name (proper spelling and caps)" type="text" value="{{ Session::get('sess-member-stageName') }}">
                </div>               
                <div class="form-group mb-4 {{ Session::get('sess-member-contributor-3') }}">
                    <label style="margin-right:10px;">Are you a playlist contributor?</label>
                    <div class="form-check-inline">
                        <input type="radio" name="playlist_contributor" id="playlist_contributor1" value="1" onclick="getContributor()" {{ (Session::get('sess-member-contributor')=="1") ? "checked" : "" }} class="form-check-input">
                    
                    <label class="form-check-label" for="playlist_contributor1">
                      Yes
                    </label>
                  </div>
                  <div class="form-check-inline">
                    <input type="radio" name="playlist_contributor" id="playlist_contributor2" value="0" onclick="getContributor()" {{ (Session::get('sess-member-contributor')=="0") ? "checked" : "" }} class="form-check-input">

                    <label class="form-check-label" for="playlist_contributor2">
                      No 
                    </label>
                  </div>
                    <!-- <div class="radio dja">
                        <label>
                            <input type="radio" name="playlist_contributor" id="playlist_contributor1" value="1" onclick="getContributor()" {{ (Session::get('sess-member-contributor')=="1") ? "checked" : "" }}>
                            Yes
                        </label>
                    </div>
                    <div class="radio dja">
                        <label>
                            <input type="radio" name="playlist_contributor" id="playlist_contributor2" value="0" onclick="getContributor()" {{ (Session::get('sess-member-contributor')=="0") ? "checked" : "" }}> No
                        </label>
                    </div> -->
                </div>
                
                <div class="form-group djf mb-4" id="contributorDiv" style="{{ (Session::get('sess-member-contributor')=='1') ? 'display:block' : 'display:none' }}">
                    <label>What music streaming services(s):</label>
                    <div class="checkbox">
                        <label>
                            <input type="checkbox" value="1" name="playlists[]" id="tidal" {{ (Session::get('sess-member-contributor-1')=="1") ? "checked" : "" }}>
                            Tidal
                        </label>
                    </div>
                    <div class="checkbox">
                        <label>
                            <input type="checkbox" value="2" name="playlists[]" id="spotify" {{ (Session::get('sess-member-contributor-2')=="2") ? "checked" : "" }}>
                            Spotify
                        </label>
                    </div>
                    <div class="checkbox">
                        <label>
                            <input type="checkbox" value="3" name="playlists[]" id="apple_music" {{ (Session::get('sess-member-contributor-3')=="3") ? "checked" : "" }}>
                            Apple Music
                        </label>
                    </div>
                    <div class="checkbox">
                        <label>
                            <input type="checkbox" value="4" name="playlists[]" id="youtube" {{ (Session::get('sess-member-contributor-4')=="4") ? "checked" : "" }}>
                            YouTube
                        </label>
                    </div>
                    <div class="checkbox">
                        <label>
                            <input type="checkbox" value="5" name="playlists[]" id="pandora" {{ (Session::get('sess-member-contributor-5')=="5") ? "checked" : "" }}>
                            Pandora
                        </label>
                    </div>
                </div>
                <div class="form-group {{ Session::get('sess-member-artist') }}">
                    <label style="margin-right:10px;">Do you DJ for an artist?</label>

                      <div class="form-check-inline">
                        <input type="radio" name="artist" id="artist1" value="1" {{ (Session::get('sess-member-artist')=="1") ? "checked" : "" }}  class="form-check-input">
                    
                    <label class="form-check-label" for="artist1">
                     Yes
                    </label>
                  </div>
                  <div class="form-check-inline">
                    <input type="radio" name="artist" id="artist2" value="0" {{ (Session::get('sess-member-artist')=="0")? "checked" : "" }} class="form-check-input">

                    
                    <label class="form-check-label" for="artist2">
                     No
                    </label>
                  </div>

                    <!-- <div class="radio dja">
                        <label>
                            <input type="radio" name="artist" id="artist1" value="1" {{ (Session::get('sess-member-artist')=="1") ? "checked" : "" }} >
                            Yes
                        </label>
                    </div>
                    <div class="radio dja">
                        <label>
                            <input type="radio" name="artist" id="artist2" value="0" {{ (Session::get('sess-member-artist')=="0")? "checked" : "" }} >
                            No
                        </label>
                    </div> -->
                </div>                
                <div class="form-group my-4 gender-opt">
                    <select name="howheard" id="howheard" class="selectpicker form-control" onchange="howHeard()">
                        <option value="Internet Search">Internet Search</option>
                        <option value="Magazine Article">Magazine Article</option>
                        <option value="Record Pool">Record Pool</option>
                        <option value="DJ Crew">DJ Crew</option>
                        <option value="A Current Member">A Current Member</option>
                        <option selected="selected" value="">How did you find us ?</option>
                    </select>
                </div>
                <div class="form-group" style="display:none;" id="howHeardDiv">
                    <input name="howheardvalue" class="form-control" id="howheardvalue" value="" placeholder="Name" size="25" maxlength="255">
                </div>

                <div class="form-group checkbox my-4">

                  <label>
                     Radio Station (Non-DJ/Mixer)

                    <input type="checkbox" value="1" class="form-check-input" name="radioStation" id="radio_station" onclick="getDiv('radioDiv',this.id)" />                   

                  </label>

                </div>

                <div class="form-group checkbox my-4">
                   <label>

                    <span class="lbl">DJ/Mixer</span>

                   <input type="checkbox" name="djMixer" id="djMixer" value="1" class="form-check-input ace" onclick="getDiv('clubDjDiv',this.id)" /></label>
                </div>

                <!--radio Div-->
                <div id="radioDiv" style="display:none;">
                   <h3 class="mb-4" style="color:#fff;">Radio Station Information (Non-DJ/Mixer)</h3>
                   <div class="form-group">
                      <label class="col-sm-4">Station Call Letters <br /><br /></label>
                      <div class="col-sm-12">
                         <input name="stationCall" class="form-control input" size="20" placeholder="Station Call Letters" type="text" />
                      </div>
                   </div>
                   <div style="clear:both;"></div>
                   <div class="form-group">
                      <label class="col-sm-4">Station Name <br /><br /></label>
                      <div class="col-sm-12">
                         <input name="stationName" class="form-control input" size="20" placeholder="Station Name" type="text" />
                      </div>
                   </div>
                   <div style="clear:both;"></div>
                   <div class="form-group">
                      <label class="col-sm-4">Station Frequency <br /><br /></label>
                      <div class="col-sm-12">
                         <input name="stationFrequency" class="form-control input" size="20" placeholder="Station Frequency" type="text" />
                      </div>
                   </div>

                   <div class="form-group">
                      <label class="col-sm-4">What is your field?</label>
                      <div class="col-sm-8">
                         <div class="checkbox">
                            <label>
                            <input type="checkbox" name="radioMusic" id="radioMusic" value="1"  onclick="getRadioInner('musicInnerDiv',this.id)" class="form-check-input ace"  {{ (Session::get('radiotype_musicdirector')=="1")? "checked" : "" }} />
                            <span class="lbl"> Music Director</span>
                            </label>
                         </div>
                         <div class="checkbox">
                            <label>
                            <input type="checkbox" name="radioProgram" id="radioProgram" value="1" onclick="getRadioInner('programInnerDiv',this.id)" class="form-check-input ace" {{ (Session::get('radiotype_programdirector')=="1")? "checked" : "" }} />
                            <span class="lbl"> Program Director</span>
                            </label>
                         </div>
                         <div class="checkbox">
                            <label>
                            <input type="checkbox" name="radioAir" id="radioAir" value="1" onclick="getRadioInner('airInnerDiv',this.id)" {{ (Session::get('radiotype_jock')=="1")? "checked" : "" }} class="form-check-input ace" />
                            <span class="lbl">On-Air Personality/Jock</span>
                            </label>
                         </div>
                         <div class="checkbox">
                            <label>
                            <input type="checkbox" name="radioPromotion" id="radioPromotion" value="1" {{ (Session::get('radiotype_promotion')=="1")? "checked" : "" }} class="form-check-input ace" />
                            <span class="lbl">Promotion</span>
                            </label>
                         </div>
                         <div class="checkbox">
                            <label>
                            <input type="checkbox" name="radioProduction" id="radioProduction" value="1" {{ (Session::get('radiotype_production')=="1")? "checked" : "" }} class="form-check-input ace" />
                            <span class="lbl">Production</span>
                            </label>
                         </div>
                         <div class="checkbox">
                            <label>
                            <input type="checkbox" name="radioSales" id="radioSales" value="1" {{ (Session::get('radiotype_sales')=="1")? "checked" : "" }} class="form-check-input ace" />
                            <span class="lbl">Sales</span>
                            </label>
                         </div>
                         <div class="checkbox">
                            <label>
                            <input type="checkbox" name="radioIt" id="radioIt" value="1" {{ (Session::get('radiotype_tech')=="1")? "checked" : "" }} class="form-check-input ace" />
                            <span class="lbl">I.T./Tech</span>
                            </label>
                         </div>
                      </div>
                   </div>                  
                   <div style="clear:both;"></div>
                   <div id="musicInnerDiv" style="display:none;">
                      <h3 class="col-sm-8 mb-4" style="color:#fff; font-size:26px;">Music Director</h3>
                      <div style="clear:both;"></div>
                      <!--<div class="col-sm-9">-->
                      <div class="form-group">
                         <label class="col-sm-5">Music Call Times <br /><br /></label>
                         <div class="col-sm-7">
                            <input name="musicCall" id="musicCall" class="form-control input" size="20" placeholder="Music Call Times" type="text" >
                         </div>
                      </div>
                      <div style="clear:both;"></div>
                      <div class="form-group">
                         <label class="col-sm-5">Do You Host a Show? <br /><br /></label>
                         <div class="col-sm-7">
                            <input name="musicHost" id="musicHost" class="form-control input" size="20" placeholder="Do You Host a Show?" type="text" >
                         </div>
                      </div>
                      <div style="clear:both;"></div>
                      <div class="form-group">
                         <label class="col-sm-5">Show Name</label>
                         <div class="col-sm-7">
                            <input name="musicName" id="musicName" class="form-control input" size="20" placeholder="Show Name" type="text">
                         </div>
                      </div>
                      <div class="form-group">
                         <label class="col-sm-5">Show Days</label>
                         <div class="col-sm-7">
                            <div class="checkbox">
                               <label>
                               <input type="checkbox" name="musicMonday" id="musicMonday" value="1"  class="form-check-input ace"  />
                               <span class="lbl">Monday</span>
                               </label>
                            </div>
                            <div class="checkbox">
                               <label>
                               <input type="checkbox" name="musicTuesday" id="musicTuesday" value="1"  class="form-check-input ace"  />
                               <span class="lbl">Tuesday</span>
                               </label>
                            </div>
                            <div class="checkbox">
                               <label>
                               <input type="checkbox" name="musicWednesday" id="musicWednesday" value="1"  class="form-check-input ace"  />
                               <span class="lbl">Wednesday</span>
                               </label>
                            </div>
                            <div class="checkbox">
                               <label>
                               <input type="checkbox" name="musicThursday" id="musicThursday" value="1"  class="form-check-input ace" />
                               <span class="lbl">Thursday</span>
                               </label>
                            </div>
                            <div class="checkbox">
                               <label>
                               <input type="checkbox" name="musicFriday" id="musicFriday" value="1"  class="form-check-input ace" />
                               <span class="lbl">Friday</span>
                               </label>
                            </div>
                            <div class="checkbox">
                               <label>
                               <input type="checkbox" name="musicSaturday" id="musicSaturday" value="1"  class="form-check-input ace" />
                               <span class="lbl">Saturday</span>
                               </label>
                            </div>
                            <div class="checkbox">
                               <label>
                               <input type="checkbox" name="musicSunday" id="musicSunday" value="1"  class="form-check-input ace" />
                               <span class="lbl">Sunday</span>
                               </label>
                            </div>
                            <div class="checkbox">
                               <label>
                               <input type="checkbox" name="musicVaries" id="musicVaries" value="1"  class="form-check-input ace" />
                               <span class="lbl">Varies</span>
                               </label>
                            </div>
                         </div>
                      </div>
                      <div class="form-group">
                         <label class="col-sm-5">Show Time/s</label>
                         <div class="col-sm-7">
                            <input name="musicTime" id="musicTime" class="form-control input" size="20" placeholder="Show Time/s" type="text" >
                         </div>
                      </div>
                      <!--</div>-->
                   </div>
                   <!--music inner div ends-->
                   <div id="programInnerDiv" style="display:none;">
                      <h3 class="col-sm-12 mb-4" style="color:#fff; font-size:26px;">Program Director</h3>
                      <!--<div class="col-sm-9">-->
                      <div class="form-group">
                         <label class="col-sm-5">Music Call Times <br /><br /></label>
                         <div class="col-sm-7">
                            <input name="programCall" id="programCall" class="form-control input" size="20" placeholder="Music Call Times" type="text" >
                         </div>
                      </div>
                      <div style="clear:both;"></div>
                      <div class="form-group">
                         <label class="col-sm-5">Do You Host a Show? <br /><br /></label>
                         <div class="col-sm-7">
                            <input name="programHost" id="programHost" class="form-control input" size="20" placeholder="Do You Host a Show?" type="text" >
                         </div>
                      </div>
                      <div style="clear:both;"></div>
                      <div class="form-group">
                         <label class="col-sm-5">Show Name</label>
                         <div class="col-sm-7">
                            <input name="programName" id="programName" class="form-control input" size="20" placeholder="Show Name" type="text" >
                         </div>
                      </div>
                      <div class="form-group">
                         <label class="col-sm-5">Show Days</label>
                         <div class="col-sm-7">
                            <div class="checkbox">
                               <label>
                               <input type="checkbox" name="programMonday" id="programMonday" value="1"  class="form-check-input ace" />
                               <span class="lbl">Monday</span>
                               </label>
                            </div>
                            <div class="checkbox">
                               <label>
                               <input type="checkbox" name="programTuesday" id="programTuesday" value="1"  class="form-check-input ace"  />
                               <span class="lbl">Tuesday</span>
                               </label>
                            </div>
                            <div class="checkbox">
                               <label>
                               <input type="checkbox" name="programWednesday" id="programWednesday" value="1"  class="form-check-input ace"  />
                               <span class="lbl">Wednesday</span>
                               </label>
                            </div>
                            <div class="checkbox">
                               <label>
                               <input type="checkbox" name="programThursday" id="programThursday" value="1"  class="form-check-input ace"  />
                               <span class="lbl">Thursday</span>
                               </label>
                            </div>
                            <div class="checkbox">
                               <label>
                               <input type="checkbox" name="programFriday" id="programFriday" value="1"  class="form-check-input ace"  />
                               <span class="lbl">Friday</span>
                               </label>
                            </div>
                            <div class="checkbox">
                               <label>
                               <input type="checkbox" name="programSaturday" id="programSaturday" value="1"  class="form-check-input ace" />
                               <span class="lbl">Saturday</span>
                               </label>
                            </div>
                            <div class="checkbox">
                               <label>
                               <input type="checkbox" name="programSunday" id="programSunday" value="1"  class="form-check-input ace"  />
                               <span class="lbl">Sunday</span>
                               </label>
                            </div>
                            <div class="checkbox">
                               <label>
                               <input type="checkbox" name="programVaries" id="programVaries" value="1"  class="form-check-input ace"  />
                               <span class="lbl">Varies</span>
                               </label>
                            </div>
                         </div>
                      </div>
                      <div class="form-group">
                         <label class="col-sm-5">Show Time/s</label>
                         <div class="col-sm-7">
                            <input name="programTime" id="programTime" class="form-control input" size="20" placeholder="Show Time/s" type="text" >
                         </div>
                      </div>
                      <!--</div>-->
                   </div>
                   <!--program div ends-->
                   <div id="airInnerDiv" style="display:none;">
                      <h3 class="col-sm-12 mb-4" style="color:#fff; font-size:26px;">On-Air Personality/Jock</h3>
                      <div class="form-group">
                         <label class="col-sm-5">Show Name</label>
                         <div class="col-sm-7">
                            <input name="airName" id="airName" class="form-control input" size="20" placeholder="Show Name" type="text" >
                         </div>
                      </div>
                      <div class="form-group">
                         <label class="col-sm-5">Show Days</label>
                         <div class="col-sm-7">
                            <div class="checkbox">
                               <label>
                               <input type="checkbox" name="airMonday" id="airMonday" value="1"  class="form-check-input ace" />
                               <span class="lbl">Monday</span>
                               </label>
                            </div>
                            <div class="checkbox">
                               <label>
                               <input type="checkbox" name="airTuesday" id="airTuesday" value="1"  class="form-check-input ace" />
                               <span class="lbl">Tuesday</span>
                               </label>
                            </div>
                            <div class="checkbox">
                               <label>
                               <input type="checkbox" name="airWednesday" id="airWednesday" value="1"  class="form-check-input ace" />
                               <span class="lbl">Wednesday</span>
                               </label>
                            </div>
                            <div class="checkbox">
                               <label>
                               <input type="checkbox" name="airThursday" id="airThursday" value="1"  class="form-check-input ace" />
                               <span class="lbl">Thursday</span>
                               </label>
                            </div>
                            <div class="checkbox">
                               <label>
                               <input type="checkbox" name="airFriday" id="airFriday" value="1"  class="form-check-input ace" />
                               <span class="lbl">Friday</span>
                               </label>
                            </div>
                            <div class="checkbox">
                               <label>
                               <input type="checkbox" name="airSaturday" id="airSaturday" value="1"  class="form-check-input ace" />
                               <span class="lbl">Saturday</span>
                               </label>
                            </div>
                            <div class="checkbox">
                               <label>
                               <input type="checkbox" name="airSunday" id="airSunday" value="1"  class="form-check-input ace" />
                               <span class="lbl">Sunday</span>
                               </label>
                            </div>
                            <div class="checkbox">
                               <label>
                               <input type="checkbox" name="airVaries" id="airVaries" value="1"  class="form-check-input ace" />
                               <span class="lbl">Varies</span>
                               </label>
                            </div>
                         </div>
                      </div>
                      <div class="form-group">
                         <label class="col-sm-5">Show Time/s</label>
                         <div class="col-sm-7">
                            <input name="airTime" id="airTime" class="form-control input" size="20" placeholder="Show Time/s" type="text" >
                         </div>
                      </div>
                      <!--</div>-->
                   </div>
                   <!--air div ends-->
                </div>
                <!--radio div ends-->
                <div style="clear:both;"></div>                
                <!--Club DJ-->
                <div id="clubDjDiv" style="display:none;">
                   <h3 class="col-sm-12 mb-4" style="color:#fff;">Club DJ</h3>
                   <div class="form-group">
                      <!--<div class="col-sm-9">-->
                      <label class="col-sm-5">Club Name</label>
                      <div class="col-sm-7 form-group">
                         <input name="clubdj_clubname" class="form-control input" size="20" placeholder="Club Name" type="text" value="" >
                      </div>
                      <div style="clear:both;"></div>
                      <label class="col-sm-5">Estimated Venue Capacity</label>
                      <div class="col-sm-7 form-group">
                         <input name="clubdj_capacity" class="form-control input" size="20" placeholder="Estimated Venue Capacity" type="text" value="" >
                      </div>
                      <div style="clear:both;"></div>
                      <label class="col-sm-5">Party Type</label>
                      <div class="col-sm-7 form-group">
                         <select name="clubdj_partytype" size="1" id="clubdj_partytype" class="form-control input selectpicker">
                            <option value="mainstream">Mainstream
                            </option>
                            <option value="urban">Urban
                            </option>
                            <option value="local">Local
                            </option>
                            <option value="upscale_or_trendy">Upscale/Trendy
                            </option>
                            <option value="eclectic">Eclectic
                            </option>
                            <option value="lounge">Lounge
                            </option>
                            <option value="industry">Industry
                            </option>
                            <option value="afterwork">Afterwork
                            </option>
                            <option value="spoken_word">Spoken Word
                            </option>
                         </select>
                      </div>
                      <div style="clear:both;"></div>
                      <label class="col-sm-5 my-4">Party Format</label>
                      <div class="col-sm-7">
                         <div class="checkbox">
                            <label>
                            <input type="checkbox" name="clubdj_hiphop" value="1" class="ace" />
                            <span class="lbl"> Hip Hop</span>
                            </label>
                         </div>
                         <div class="checkbox">
                            <label>
                            <input type="checkbox" name="clubdj_rb" value="1" class="ace" />
                            <span class="lbl">R&B</span>
                            </label>
                         </div>
                         <div class="checkbox">
                            <label>
                            <input type="checkbox" name="clubdj_pop" value="1" class="ace" />
                            <span class="lbl">Pop/Top 40</span>
                            </label>
                         </div>
                         <div class="checkbox">
                            <label>
                            <input type="checkbox" name="clubdj_reggae" value="1" class="ace" />
                            <span class="lbl">Reggae</span>
                            </label>
                         </div>
                         <div class="checkbox">
                            <label>
                            <input type="checkbox" name="clubdj_house" value="1" class="ace" />
                            <span class="lbl">House</span>
                            </label>
                         </div>
                         <div class="checkbox">
                            <label>
                            <input type="checkbox" name="clubdj_calypso" value="1" class="ace" />
                            <span class="lbl">Calypso/Soca</span>
                            </label>
                         </div>
                         <div class="checkbox">
                            <label>
                            <input type="checkbox" name="clubdj_rock" value="1" class="ace" />
                            <span class="lbl">Rock</span>
                            </label>
                         </div>
                         <div class="checkbox">
                            <label>
                            <input type="checkbox" name="clubdj_techno" value="1" class="ace" />
                            <span class="lbl"> Techno</span>
                            </label>
                         </div>
                         <div class="checkbox">
                            <label>
                            <input type="checkbox" name="clubdj_trance" value="1" class="ace" />
                            <span class="lbl">Trance</span>
                            </label>
                         </div>
                         <div class="checkbox">
                            <label>
                            <input type="checkbox" name="clubdj_afro" value="1" class="ace" />
                            <span class="lbl">Afro-Beat</span>
                            </label>
                         </div>
                         <div class="checkbox">
                            <label>
                            <input type="checkbox" name="clubdj_reggaeton" value="1" class="ace" />
                            <span class="lbl">Reggaeton</span>
                            </label>
                         </div>
                         <div class="checkbox">
                            <label>
                            <input type="checkbox" name="clubdj_gogo" value="1" class="ace" />
                            <span class="lbl">Go-Go</span>
                            </label>
                         </div>
                         <div class="checkbox">
                            <label>
                            <input type="checkbox" name="clubdj_neosoul" value="1" class="ace" />
                            <span class="lbl">Neo-Soul</span>
                            </label>
                         </div>
                         <div class="checkbox">
                            <label>
                            <input type="checkbox" name="clubdj_oldschool" value="1" class="ace" />
                            <span class="lbl">Old School</span>
                            </label>
                         </div>
                         <div class="checkbox">
                            <label>
                            <input type="checkbox" name="clubdj_electronic" value="1" class="ace" ?>
                            <span class="lbl">Electronic</span>
                            </label>
                         </div>
                         <div class="checkbox">
                            <label>
                            <input type="checkbox" name="clubdj_latin" value="1" class="ace" />
                            <span class="lbl">Latin Soul</span>
                            </label>
                         </div>
                         <div class="checkbox">
                            <label>
                            <input type="checkbox" name="clubdj_dance" value="1" class="ace" />
                            <span class="lbl">Electronica/Dance</span>
                            </label>
                         </div>
                         <div class="checkbox">
                            <label>
                            <input type="checkbox" name="clubdj_jazz" value="1" class="ace" />
                            <span class="lbl">Jazz</span>
                            </label>
                         </div>
                         <div class="checkbox">
                            <label>
                            <input type="checkbox" name="clubdj_country" value="1" class="ace" />
                            <span class="lbl">Country</span>
                            </label>
                         </div>
                         <div class="checkbox">
                            <label>
                            <input type="checkbox" name="clubdj_world" value="1" class="ace" />
                            <span class="lbl">World</span>
                            </label>
                         </div>
                      </div>
                      <div style="clear:both;"></div>
                      <label class="col-sm-5 my-4">Show Day/s</label>
                      <div class="col-sm-7"  style="margin-bottom:10px;">
                         <div class="checkbox">
                            <label>
                            <input type="checkbox" name="clubdj_monday" value="1" class="ace" />
                            <span class="lbl">Monday</span>
                            </label>
                         </div>
                         <div class="checkbox">
                            <label>
                            <input type="checkbox" name="clubdj_tuesday" value="1" class="ace" />
                            <span class="lbl">Tuesday</span>
                            </label>
                         </div>
                         <div class="checkbox">
                            <label>
                            <input type="checkbox" name="clubdj_wednesday" value="1" class="ace" />
                            <span class="lbl">Wednesday</span>
                            </label>
                         </div>
                         <div class="checkbox">
                            <label>
                            <input type="checkbox" name="clubdj_thursday" value="1" class="ace" />
                            <span class="lbl">Thursday</span>
                            </label>
                         </div>
                         <div class="checkbox">
                            <label>
                            <input type="checkbox" name="clubdj_friday" value="1" class="ace" />
                            <span class="lbl">Friday</span>
                            </label>
                         </div>
                         <div class="checkbox">
                            <label>
                            <input type="checkbox" name="clubdj_saturday" value="1" class="ace"  />
                            <span class="lbl">Saturday</span>
                            </label>
                         </div>
                         <div class="checkbox">
                            <label>
                            <input type="checkbox" name="clubdj_sunday" value="1" class="ace" />
                            <span class="lbl">Sunday</span>
                            </label>
                         </div>
                         <div class="checkbox">
                            <label>
                            <input type="checkbox" name="clubdj_varies" value="1" class="ace" />
                            <span class="lbl">Varies</span>
                            </label>
                         </div>
                      </div>
                      <div style="clear:both;"></div>
                      <label class="col-sm-5">City</label>
                      <div class="col-sm-7 form-group">
                         <input name="clubdj_city" class="form-control input"  size="20" placeholder="City" type="text" />
                      </div>
                      <div style="clear:both;"></div>
                      <label class="col-sm-5">State</label>
                      <div class="col-sm-7 form-group">
                         <input name="clubdj_state" class="form-control input"  size="20" placeholder="State" type="text" />
                      </div>
                      <div style="clear:both;"></div>
                      <label class="col-sm-5">Country</label>
                      <div class="col-sm-7 form-group">
                         <input name="clubdj_intcountry" class="form-control input" size="20" placeholder="Country Name" type="text" />
                      </div>
                      <!--    </div>-->
                   </div>
                </div>
                <!--club dj ends-->
                <div class="btn-center">
                    <input name="addMember" class="login_btn btn btn-theme btn-gradient" value="Continue" type="submit">
                </div>
            </form>
        </div>
    </div>
    <!-- /.modal-content -->
</div>
</div>
</div>
</div>
</section>
<!-- Register Block Ends -->
<!-- <script src="<?php // echo base_url('assets/js/jquery.validate.min.js'); 
                    ?>"></script> -->
<script>
    function howHeard() {
        howheard_dropdown = document.getElementById('howheard');
        howheard_value = howheard_dropdown.options[howheard_dropdown.selectedIndex].value;
        howheard_referred = document.getElementById('howHeardDiv');
        howheardvalueinput = document.getElementById('howheardvalue');
        if (howheard_value == "Record Pool" || howheard_value == "A Current Member" || howheard_value == "DJ Crew") {
            howheard_referred.style.display = "";
            howheardvalueinput.style.display = "";
        } else {
            howheard_referred.style.display = "none";
            howheardvalueinput.style.display = "none";
            howheardvalueinput.value = "";
        }
    }
    // Wait for the DOM to be ready
    //$(function() {
    $(document).ready(function() {
        $("#registrationForm").validate();
        // $("#website").rules("add", {
        //          required:true,
        //          messages: {
        //                 required: "Please enter website."
        //          }
        //       });
        //  $("#age").rules("add", {
        //          required:true,
        //          messages: {
        //                 required: "Please enter Date of Birth."
        //          }
        //       });
        $("#sex").rules("add", {
            required: true,
            messages: {
                required: "Please select sex."
            }
        });
        $("#stageName").rules("add", {
            required: true,
            messages: {
                required: "Please enter stage name."
            }
        });
        
        $("#howheard").rules("add", {
            required: true,
            messages: {
                required: "Please tell us, how did you hear about us."
            }
        });
        
        
        $("#email").rules("add", {
            required: true,
            messages: {
                required: "Please enter email."
            }
        });
    });
    function getContributor() {
        if (document.getElementById('playlist_contributor1').checked == true) {
            document.getElementById('contributorDiv').style.display = 'block';
        } else {
            document.getElementById('contributorDiv').style.display = 'none';
        }
    }
    function getDiv(divId, id) {
        if (document.getElementById(id).checked == true) {
            document.getElementById(divId).style.display = 'block';
        } else {
            document.getElementById(divId).style.display = 'none';
        }
    }
    function getRadioInner(divId, id) {
        if (document.getElementById(id).checked == true) {
            document.getElementById(divId).style.display = 'block';
        } else {
            document.getElementById(divId).style.display = 'none';
        }
    }
    function getManagementInner() {
        if (document.getElementById('managementArtist').checked == true || document.getElementById('managementPersonal').checked == true) {
            document.getElementById('managementInnerDiv').style.display = 'block';
        } else {
            document.getElementById('managementInnerDiv').style.display = 'none';
        }
    }
</script>
@endsection