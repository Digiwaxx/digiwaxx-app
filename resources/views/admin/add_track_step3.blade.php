<x-slot name="page_title">{{ __('Add Track') }}</x-slot>
@extends('admin.admin_dashboard_active_sidebar')
{{-- <link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.1.0/css/select2.min.css" rel="stylesheet" /> --}}
@section('content')
    <!--<h1>Add Track New </h1>-->

    <div class="main-content add-track-page">
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
                        <a href="{{ route('admin_tracks_listing') }}">
                            <i class="ace-icon fa fa-list list-icon"></i>
                            Tracks</a>
                    </li>
                    <li class="active">Add Track - Step 3 &nbsp;&nbsp;</li>
                </ul><!-- /.breadcrumb -->
                <form method="POST" style="display: inline-block;" action="<?php echo url('Member_track_download?tid=' .$track_id ); ?>" target="_blank">

                    @csrf
                   
                    @if(Session::has('admin_Id'))
                        <input type="hidden" name="adminID" value="{{ Session::get('admin_Id')}}">
                    @else
                        <input type="hidden" name="adminID" value="">
                    @endif
                    <input type="hidden" name="is_admin_view" value="yes">

                    <input type="submit" value="FrontEnd Preview" class="btn btn-info btn-sm">

                </form>
                <!-- /section:basics/content.searchbox -->
            </div>
            <!-- /section:basics/content.breadcrumbs -->


            {{-- <div class="alert alert-danger">
					<a href="javascript:void(0)" class="close" data-dismiss="alert">&times;</a>
					<strong>Error!</strong>
				</div>	  --}}
            <div class="alert alert-success" id="track-exist-check" style="display:none">
                <a href="javascript:void(0)" class="close" data-dismiss="alert">&times;</a>
                <strong>Success!</strong><span class="show-error"></span>
            </div>

            <div class="page-content">
                <div class="row">




                    {{-- <div class="">
                                    <button class="close" data-dismiss="alert">
                                        <i class="ace-icon fa fa-times"></i>
                                    </button>
                                </div> --}}

                    <div class="col-xs-12">
                        <!-- PAGE CONTENT BEGINS -->
                        {{-- onsubmit="return validate()" --}}
                        <form id="add_trackform" role="form" name="addtrackform" enctype="multipart/form-data" onsubmit="return validate()" autocomplete="off" style="color:white;">
                            @csrf

                            <h3 class="header smaller lighter">
                                Track Information
                            </h3>
                            <div class="row">
                                <input type="hidden" id="divId" name="divId" value="1" />

                                <!--div class="col-sm-6">
                                    <div class="form-group">
                                        <label class="control-label no-padding-right " for="form-field-1"> Featured Artist-1
                                        </label>

                                        <input type="text" id="featured_artist_1" name="featured_artist_1"
                                            class="form-control" value="{{ isset($track_data->featured_artist_1) && !empty($track_data->featured_artist_1) ?  urldecode($track_data->featured_artist_1) : '' }}" placeholder="Featured Artist-1">
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label class="control-label no-padding-right" for="form-field-1"> Featured Artist-2
                                        </label>

                                        <input type="text" id="featured_artist_2" name="featured_artist_2"
                                            class="form-control" value="{{ isset($track_data->featured_artist_2) && !empty($track_data->featured_artist_2) ? urldecode($track_data->featured_artist_2) : '' }}" placeholder="Featured Artist-2">
                                    </div>
                                </div-->


                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label class="control-label no-padding-right req-label" for="form-field-1"> Type
                                        </label>

                                        <select required id="type" name="type" class="form-control">
                                            <option value="track" {{ isset($track_data->featured_artist_1) && !empty($track_data->featured_artist_1) && ($track_data->featured_artist_1=='track') ? 'selected' : '' }}>Track</option>
                                            <option value="product" {{ isset($track_data->featured_artist_1) && !empty($track_data->featured_artist_1) && ($track_data->featured_artist_1=='product') ? 'selected' : '' }}>Product</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label class="control-label no-padding-right" for="form-field-1"> Client </label>


                                        <select id="client" name="client" class="form-control">
                                            <option value="">Select Client</option>
                                            <?php 
                                                foreach ($clients as $client) { ?>
                                            <option value="<?php echo $client->id; ?>"
                                                {{ isset($track_data->client) && !empty($track_data->client) && ($track_data->client==$client->id) ? 'selected' : '' }}>
                                                <?php echo urldecode($client->name); ?>
                                            </option>
                                            <?php } ?>
                                        </select>

                                        <!-- <input type="text" id="client" name="client" />
                                                                        <input type="text" id="clientSearch" onkeyup="getList1(this.value)" class="form-control" onfocus="getList1(this.value)" onMouseOver="showList1()" onMouseOut="removeList1()" />
                                                                        <br />
                                                                        <div style="clear:both;"></div>
                                                                        <div style="position:relative;">
                                                                            <div onMouseOver="showList1()" onMouseOut="removeList1()" id="searchListDisplay1" class="form-control" style=" position:absolute; background:#E5E5E5; padding:10px; padding-right:0px; top:0px; z-index:100; display:none;">
                                                                                <div style="max-height:200px; overflow-y:scroll;">
                                                                                    <ul id="searchList1" style="list-style:none; margin:0px;">
                                                                                        <li>Loading ...</li>
                                                                                    </ul>
                                                                                </div>
                                                                            </div>
                                                                        </div> -->
                                    </div>
                                </div>

                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label class="control-label no-padding-right " for="form-field-1"> Label/Company
                                        </label>

                                        <input type="text" id="company" placeholder="Label / Company" name="company"
                                            class="form-control" value="{{ isset($track_data->label) && !empty($track_data->label) ? urldecode($track_data->label) : '' }}" placeholder="Enter Label/Company">
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label class="control-label no-padding-right" for="form-field-1">Producer(s)
                                        </label>

                                        <input type="text" id="producers" name="producers" class="form-control"
                                            value="{{ isset($track_data->producer) && !empty($track_data->producer) ? urldecode($track_data->producer) : '' }}" placeholder="Producer(s)">
                                    </div>
                                </div>

                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label class="control-label no-padding-right" for="form-field-1">Release Type
                                        </label>

                                        {{-- <select name="albumType" id="albumType" class="form-control">
                                            <!--<option value="">Release Type</option>-->
                                            <option value="" selected></option>

                                        </select> --}}

                                        <select name="albumType" id="albumType" class="form-control">

                                            <!--<option value="">Release Type</option>-->

                                            <?php foreach ($releasetypes as $release) { ?>

                                            <option value="<?php echo $release->id; ?>"
                                                {{ isset($track_data->albumType) && !empty($track_data->albumType) && ($track_data->albumType==$release->id) ? 'selected' : '' }}>
                                                
                                                <?php echo $release->release_name; ?>
                                            </option>

                                            <?php } ?>



                                        </select>
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label class="control-label no-padding-right" for="form-field-1">Writer</label>

                                        <input type="text" id="writer" name="writer" class="form-control"
                                            value="{{ isset($track_data->writer) && !empty($track_data->writer) ? urldecode($track_data->writer) : '' }}" placeholder="Writer">
                                    </div>
                                </div>

                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label class="control-label no-padding-right" for="form-field-1">Album </label>

                                        <input type="text" id="album" name="album" class="form-control"
                                            value="{{ isset($track_data->album) && !empty($track_data->album) ? urldecode($track_data->album) : '' }}" placeholder="Album">
                                    </div>
                                </div>

                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label class="control-label no-padding-right" for="form-field-1">BPM </label>

                                        <input type="text" id="bpm" name="bpm" class="form-control"
                                            value="{{ isset($track_data->bpm) && !empty($track_data->bpm) ? urldecode($track_data->bpm) : '' }}" placeholder="BPM">
                                    </div>
                                </div>



                                <div class="col-sm-6">
                                    <div class="form-group">
                                        {{-- <label class="control-label no-padding-right req-label" for="form-field-1">Genre
                                        </label>

                                        <select required name="genre" id="genre" class="form-control"
                                            onchange="change_genre(this.value)">
                                            <option value="">Genre</option>

                                            <option value="option">option</option>

                                        </select> --}}

                                        <label class="control-label no-padding-right req-label" for="form-field-1">Genre
                                        </label>
                                        <select required name="genre" id="genre" class="form-control"
                                            onchange="change_genre(this.value)">
                                            {{-- <select required name="genre" id="genre" class="form-control" > --}}
                                            <option value="">Genre</option>
                                            <?php if ($genres['numRows'] > 0) {
                                                    foreach ($genres['data'] as $genre) { ?>
                                            <option value="<?php echo $genre->genreId; ?>"
                                                {{ isset($track_data->genreId) && !empty($track_data->genreId) && ($track_data->genreId==$genre->genreId) ? 'selected' : '' }}
                                                
                                                ><?php echo $genre->genre; ?>
                                            </option>
                                            <?php }
                                                } ?>
                                        </select>
                                    </div>
                                </div>

                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label class="control-label no-padding-right" for="form-field-1">Sub Genre
                                        </label>

                                        <select name="subGenre" id="subGenre" class="form-control">
                                            <option value="">Sub Genre</option>

                                            <option value=""></option>

                                        </select>

                                    </div>
                                </div>


                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label class="control-label no-padding-right" for="form-field-1"> More Info.
                                        </label>

                                        <textarea id="moreInfo" placeholder="More Info." name="moreInfo" class="form-control">{{ isset($track_data->moreInfo) && !empty($track_data->moreInfo) ? urldecode($track_data->moreInfo) : '' }}</textarea>
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label class="control-label no-padding-right" for="form-field-1">Notes </label>

                                        <textarea id="notes" placeholder="Track Notes" name="notes" class="form-control">{{ isset($track_data->notes) && !empty($track_data->notes) ? urldecode($track_data->notes) : '' }}</textarea>
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label class="control-label no-padding-right" for="form-field-1">Song key</label>

                                        <input type="text" id="songkey" name="songkey" class="form-control"
                                            placeholder="Song key" value="{{ isset($track_data->songkey) && !empty($track_data->songkey) ? urldecode($track_data->songkey) : '' }}">
                                    </div>
                                </div>

                                <div style="clear:both;"></div>
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label class="control-label no-padding-right" for="form-field-1">
                                            Available to Members </label>

                                        <div class="radio">
                                            <label>
                                                <input name="availableMembers" type="radio" class="ace"
                                                    value="1"{{ isset($track_data->active) && !empty($track_data->active) && ($track_data->active=='1') ? 'checked' : '' }}
                                                    >
                                                <span class="lbl"> Yes</span>
                                            </label>
                                            <label>
                                                <input required name="availableMembers" type="radio" class="ace"
                                                    value="0"{{ ((isset($track_data->active) && ($track_data->active != '1') ) || empty($track_data->active)) ? 'checked' : '' }}
                                                    >
                                                <span class="lbl"> No</span>
                                            </label>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label class="control-label no-padding-right" for="form-field-1">
                                            Reviewable </label>

                                        <div class="radio">
                                            <label>
                                                <input required name="reviewable" type="radio" class="ace"
                                                    value="1"{{ (isset($track_data->review) && !empty($track_data->review) && ($track_data->review=='1')) ? 'checked' : '' }}
                                                    >
                                                <span class="lbl"> Yes </span>
                                            </label>
                                            <label>
                                                <input required name="reviewable" type="radio" class="ace"
                                                    value="0"{{ (isset($track_data->review) && ($track_data->review!='1') || empty($track_data->review)) ? 'checked' : '' }}
                                                    >
                                                <span class="lbl"> No</span>
                                            </label>
                                        </div>
                                    </div>
                                </div>


                                <div class="col-xs-12">
                                    <h3 class="header smaller lighter">
                                        Contact Details
                                    </h3>
                                </div>
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label class="control-label no-padding-right" for="form-field-1">Name </label>
                                        <input type="text" id="contact_name" name="contact_name" value="{{ isset($track_data->contact_name) && !empty($track_data->contact_name) ? urldecode($track_data->contact_name) : '' }}"
                                            class="form-control" placeholder="Contact Name">

                                    </div>

                                </div>


                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label class="control-label no-padding-right req-label" for="form-field-1">Email
                                        </label>
                                        <input type="email" id="contact_email" name="contact_email" value="{{ isset($track_data->contact_email) && !empty($track_data->contact_email) ? urldecode($track_data->contact_email) : '' }}"
                                            class="form-control" placeholder="Contact Email" required>
                                    </div>
 
                                </div>
								
								<div class="col-sm-6">
									<div class="form-group">
										<label class="control-label no-padding-right" for="form-field-1">Second Email</label>
										<input type="email" id="second_contact_email" name="second_contact_email" 
											value="{{ isset($track_data->second_contact_email) && !empty($track_data->second_contact_email) ? urldecode($track_data->second_contact_email) : '' }}" 
											class="form-control" placeholder="Contact Email">
									</div>
								</div>

								<div class="col-sm-6">
									<div class="form-group">
										<label class="control-label no-padding-right " for="form-field-1">Third Email</label>
										<input type="email" id="third_contact_email" name="third_contact_email" 
											value="{{ isset($track_data->third_contact_email) && !empty($track_data->third_contact_email) ? urldecode($track_data->third_contact_email) : '' }}" 
											class="form-control" placeholder="Contact Email">
									</div>
								</div>

								<div class="col-sm-6">
									<div class="form-group">
										<label class="control-label no-padding-right" for="form-field-1">Fourth Email</label>
										<input type="email" id="fourth_contact_email" name="fourth_contact_email" 
											value="{{ isset($track_data->fourth_contact_email) && !empty($track_data->fourth_contact_email) ? urldecode($track_data->fourth_contact_email) : '' }}" 
											class="form-control" placeholder="Contact Email" >
									</div>
								</div>
								
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label class="control-label no-padding-right" for="form-field-1">Phone</label>
                                        <input type="text" id="contact_phone" name="contact_phone" value="{{ isset($track_data->contact_phone) && !empty($track_data->contact_phone) ? urldecode($track_data->contact_phone) : '' }}"
                                            class="form-control" placeholder="Contact Phone">
                                    </div>

                                </div>



                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label class="control-label no-padding-right" for="form-field-1"> Relationship to
                                            Artist</label>
                                        <input type="text" id="relationship_to_artist" name="relationship_to_artist"
                                            value="{{ isset($track_data->relationship_to_artist) && !empty($track_data->relationship_to_artist) ? urldecode($track_data->relationship_to_artist) : '' }}" class="form-control" placeholder="Relationship to Artist">
                                    </div>
                                </div>

                                {{--  --}}

                                {{-- <div style="clear:both;"></div>
                                <div class="space-24"></div>
                                <h3 class="header smaller lighter blue">Logos</h3> --}}

                                <div class="col-xs-12">
                                    <h3 class="header smaller lighter">
                                        Logos
                                    </h3>
                                </div>

                                <div class="col-sm-6">
                                    <span class="col-sm-4 col-sm-offset-3" style="color:#428bca;">Select
                                        Logo</span>
                                    <div class="col-sm-12 form-group">
                                        <label class="col-sm-3 control-label no-padding-right" for="form-field-1">
                                            Logos </label>
                                        <div class="col-sm-9">
                                            <style>
                                                /* .chosen-container {
                                                                    width: 100% !important;
                                                                } */
                                            </style>

                                            <select data-placeholder="Begin typing a name to filter..." multiple
                                                class="chosen-select-logos col-xs-10 col-sm-10" name="logos[]">
                                                {{-- style="display:none !important;" --}}

                                                @foreach ($all_logos as $logo)
                                                    @if (!empty($logo->company))
                                                        <option value="{{ $logo->id }}"
                                                            @if(in_array($logo->id, $track_data_logos))
                                                           {{'selected'}}
                                                           @endif
                                                            >{{ $logo->company }}</option>
                                                    @endif
                                                @endforeach

                                            </select>
                                            <div class="logos row">
                                                @foreach ($logo_fileid as $fileid)
                                                    <div class="col-auto">
                                                        <div id="16">
                                                            <img
                                                                src="{{$fileid}}"
                                                                width="50" height="56">
                                                        </div>
                                                    </div>
                                                @endforeach
                                            
                                            
                                            </div>
                                            {{-- <select name="logos[]" size="5" multiple=""
                                                class="col-xs-10 col-sm-10" id="logos[]">
                                                <option value=""></option>
                                            </select> --}}
                                            {{-- @if(isset($track_data->logos) && !empty($track_data->logos))
                                            <div class="col-auto">
                                                @foreach ($track_data_logos as $logo)
                                                <div id="">
                                                    <img src="{{ env('APP_URL') }}/admin/audio_stream_pcloud/{{$logo}}" width="50" height="56" />
                                                </div>
                                                @endforeach
                                            </div>
                                            @endif --}}
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <span class="col-xs-4 col-xs-offset-3" style="color:#428bca;">Add New</span>
                                    <div class="col-xs-12 form-group">
                                        <label class="col-xs-3 control-label no-padding-right" for="form-field-1">Company
                                        </label>
                                        <div class="col-xs-9">
                                            <input type="text" id="logoCompany" name="logoCompany"
                                                class="col-xs-10 col-sm-10">
                                        </div>
                                    </div>
                                    <div class="col-xs-12 form-group">
                                        <label class="col-xs-3 control-label no-padding-right" for="form-field-1">Link
                                        </label>
                                        <div class="col-xs-9">
                                            <input type="text" id="logoLink" name="logoLink"
                                                class="col-xs-10 col-sm-10">
                                        </div>
                                    </div>
                                    <div class="col-xs-12 form-group">
                                        <label class="col-xs-3 control-label no-padding-right" for="form-field-1">Logo
                                        </label>
                                        <div class="col-xs-9">
                                            <input type="file" id="logoImage" name="logoImage"
                                                class="col-xs-10 col-sm-10">
                                        </div>
                                    </div>
                                </div>


                                {{-- <div style="clear:both;"></div>

                                <div class="space-24"></div>

                                <h3 class="header smaller lighter blue">Artist Information</h3> --}}

                                <div class="col-xs-12">
                                    <h3 class="header smaller lighter">
                                        Artist Information
                                    </h3>
                                </div>

                                <div class="col-md-6 form-group">

                                    <label class="col-xs-3 control-label no-padding-right" for="form-field-1">Video

                                        Link </label>

                                    <div class="col-xs-9">

                                        <input type="url" id="video" name="video" class="col-xs-10 col-sm-10"
                                            value="{{ isset($track_data->videoURL) && !empty($track_data->videoURL) ? $track_data->videoURL : '' }}" />

                                    </div>

                                </div>

                                <div class="col-md-6 form-group">

                                    <label class="col-xs-3 control-label no-padding-right" for="form-field-1">

                                        Website </label>

                                    <div class="col-xs-9">

                                        <input type="url" id="website" placeholder="" name="website"
                                            class="col-xs-10 col-sm-10" value="{{ isset($track_data->link) && !empty($track_data->link) ? $track_data->link : '' }}">

                                    </div>

                                </div>

                                <div style="clear:both;"></div>

                                <div class="col-md-6 form-group">

                                    <label class="col-xs-3 control-label no-padding-right" for="form-field-1">Website 1
                                    </label>

                                    <div class="col-xs-9">

                                        <input type="url" id="website1" name="website1" class="col-xs-10 col-sm-10"
                                            value="{{ isset($track_data->link1) && !empty($track_data->link1) ? $track_data->link1 : '' }}" />

                                    </div>

                                </div>

                                <div class="col-md-6 form-group">

                                    <label class="col-xs-3 control-label no-padding-right" for="form-field-1">Website 2
                                    </label>

                                    <div class="col-xs-9">

                                        <input type="url" id="website2" name="website2" class="col-xs-10 col-sm-10"
                                            value="{{ isset($track_data->link2) && !empty($track_data->link2) ? $track_data->link2 : '' }}" />

                                    </div>

                                </div>

                                <div style="clear:both;"></div>

                                <div class="col-md-6 form-group">

                                    <label class="col-xs-3 control-label no-padding-right" for="form-field-1">Facebook
                                    </label>

                                    <div class="col-xs-9">

                                        <input type="url" id="facebookLink" name="facebookLink"
                                            class="col-xs-10 col-sm-10" value="{{ isset($track_data->facebookLink) && !empty($track_data->facebookLink) ? $track_data->facebookLink : '' }}" />

                                    </div>

                                </div>

                                <div class="col-md-6 form-group">

                                    <label class="col-xs-3 control-label no-padding-right" for="form-field-1">Twitter
                                    </label>

                                    <div class="col-xs-9">

                                        <input type="url" id="twitterLink" name="twitterLink"
                                            class="col-xs-10 col-sm-10" value="{{ isset($track_data->twitterLink) && !empty($track_data->twitterLink) ? $track_data->twitterLink : '' }}" />

                                    </div>

                                </div>

                                <div style="clear:both;"></div>

                                <div class="col-md-6 form-group">

                                    <label class="col-xs-3 control-label no-padding-right" for="form-field-1">Instagram
                                    </label>

                                    <div class="col-xs-9">

                                        <input type="url" id="instagramLink" name="instagramLink"
                                            class="col-xs-10 col-sm-10" value="{{ isset($track_data->instagramLink) && !empty($track_data->instagramLink) ? $track_data->instagramLink : '' }}" />

                                    </div>

                                </div>

                                <div class="col-md-6 form-group">

                                    <label class="col-xs-3 control-label no-padding-right" for="form-field-1"> Tik Tok
                                    </label>

                                    <div class="col-xs-9">

                                        <input type="url" id="tiktokLink" name="tiktokLink"
                                            class="col-xs-10 col-sm-10" value="{{ isset($track_data->tiktokLink) && !empty($track_data->tiktokLink) ? $track_data->tiktokLink : '' }}">

                                    </div>

                                </div>

                                <div style="clear:both;"></div>

                                <div class="col-md-6 form-group">

                                    <label class="col-xs-3 control-label no-padding-right" for="form-field-1"> Snapchat
                                    </label>

                                    <div class="col-xs-9">

                                        <input type="url" id="snapchatLink" name="snapchatLink"
                                            class="col-xs-10 col-sm-10" value="{{ isset($track_data->snapchatLink) && !empty($track_data->snapchatLink) ? $track_data->snapchatLink : '' }}">

                                    </div>

                                </div>

                                <div class="col-md-6 form-group">

                                    <label class="col-xs-3 control-label no-padding-right" for="form-field-1"> Others
                                    </label>

                                    <div class="col-xs-9">

                                        <input type="url" id="othersLink" name="othersLink"
                                            class="col-xs-10 col-sm-10" value="{{ isset($track_data->othersLink) && !empty($track_data->othersLink) ? $track_data->othersLink : '' }}">

                                    </div>

                                </div>

                                <div class="col-md-6 form-group">

                                    <label class="col-xs-3 control-label no-padding-right" for="form-field-1"> Youtube
                                    </label>

                                    <div class="col-xs-9">

                                        <input type="url" id="youtubeLink" name="youtubeLink"
                                            class="col-xs-10 col-sm-10" value="{{ isset($track_data->youtube_link) && !empty($track_data->youtube_link) ? $track_data->youtube_link : '' }}">

                                    </div>

                                </div>
								
								<div class="col-sm-6 form-group"> 

									<label class="col-sm-3 control-label no-padding-right" for="form-field-1"> Apple Music URL </label>
									
									<div class="col-sm-9">
									
										<input type="url" id="applemusicLink" name="applemusicLink" class="col-xs-10 col-sm-10" value="{{ isset($track_data->applemusicLink) && !empty($track_data->applemusicLink) ? $track_data->applemusicLink : '' }}">										
										
									</div> 
									
									</div> 

									<div class="col-sm-6 form-group"> 

										<label class="col-sm-3 control-label no-padding-right" for="form-field-1"> Amazon URL </label> 

										<div class="col-sm-9">

											<input type="url" id="amazonLink" name="amazonLink" class="col-xs-10 col-sm-10" value="{{ isset($track_data->amazonLink) && !empty($track_data->amazonLink) ? $track_data->amazonLink : '' }}"> 

										</div>
									
									</div>

									<div class="col-sm-6 form-group">

										<label class="col-sm-3 control-label no-padding-right" for="form-field-1"> Spotify URL </label>  

										<div class="col-sm-9">
	
											<input type="url" id="spotifyLink" name="spotifyLink" class="col-xs-10 col-sm-10" value="{{ isset($track_data->spotifyLink) && !empty($track_data->spotifyLink) ? $track_data->spotifyLink : '' }}"> 

										</div> 

									</div>	
								

                                <div style="clear:both;"></div>

                                <div class="col-xs-12">
                                    <div class="form-actions text-right">
                                        <input type="hidden" name="addTrack" value="addTrack">
                                        <button class="btn btn-info btn-sm" id="submit_track_button" type="submit"
                                            name="addTrack">
                                            <i class="ace-icon fa fa-check bigger-110"></i>
                                            Submit
                                        </button>
                                        &nbsp;
                                        {{-- <button class="btn btn-sm btn-reset" type="reset"
                                            onclick="addtrackform.reset();">
                                            <i class="ace-icon fa fa-undo bigger-110"></i>
                                            Reset
                                        </button>
                                        &nbsp;
                                        <button class="btn btn-info btn-sm" type="button" onclick="savedraft();">
                                            <i class="ace-icon fa fa-check bigger-110"></i>
                                            Save Draft
                                        </button> --}}
                                    </div>
                                </div>
                            </div>
                        </form>

                        <!-- PAGE CONTENT ENDS -->
                    </div><!-- /.col -->
                </div><!-- /.row -->
            </div><!-- /.page-content -->




            <script>
                function change_genre(genreId) {
                    $.ajax({
                        url: "add_track?getSubGenres=1&genreId=" + genreId,
                        success: function(result) {
                            var obj = JSON.parse(result);
                            var count = obj.length;
                            var liList = '';
                            var optionList = ''; //'<option value="">What country do you live in</option>';
                            for (var i = 0; i < count; i++) {
                                //		  liList += '<li data-original-index="'+i+'"><a tabindex="0" class="" style="" data-tokens="null" role="option" aria-disabled="false" aria-selected="false"><span class="text">'+obj[i].name+'</span><span class="glyphicon glyphicon-ok check-mark"></span></a></li>';
                                optionList += '<option value="' + obj[i].id + '">' + obj[i].name + '</option>';
                            }
                            //	 document.getElementsByClassName('dropdown-menu inner')[5].innerHTML = liList;
                            document.getElementById('subGenre').innerHTML = optionList;
                        }
                    });
                }
                if ('undefined' === typeof window.lsData) {
                    window.lsData = {};
                }
                window.lsData['track_id'] = "{{ $track_id }}";
                window.lsData['urlSaveTrack'] = "{{ route('admin.save.add.track.step3', ['id' => $track_id]) }}";
                 window.lsData['tracksListing'] = "{{ route('admin_tracks_listing') }}";

                window.onload = function() {

                    change_genre(<?php if (!empty($trackData->genreId)) {
                        echo $trackData->genreId;
                    } ?>);

                    setTimeout(function() {
                        $('#subGenre').val('<?php if (!empty($trackData->subGenreId)) {
                            echo $trackData->subGenreId;
                        } ?>')
                    }, 700);
                };
            </script>


            <script src="{{ asset('public/js/jquery.min.js') }}"></script>
            <script src="{{ asset('public/js/jquery.validate.min.js') }}"></script>

            <script src="https://cdn.rawgit.com/harvesthq/chosen/gh-pages/chosen.jquery.min.js"></script>
            <script src="{{ asset('public/js/add_track_step3.js') }}"></script>

            <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
            <script src="https://cdn.jsdelivr.net/npm/select2@4.0.13/dist/js/select2.min.js"></script>
        @endsection
