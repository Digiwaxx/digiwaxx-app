@extends('layouts.client_dashboard')

@section('content')
    <style>
        .nopadding {
            padding: 0px !important;
        }

        .amrFile {
            visibility: hidden !important;
            height: 5px !important;
        }

        .form_loader {
            position: fixed;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            border: 10px solid #b32672;
            border-radius: 50%;
            border-top: 10px solid #000;
            width: 64px;
            height: 64px;
            -webkit-animation: spin 2s linear infinite;
            animation: spin 2s linear infinite;
        }

        @-webkit-keyframes spin {
            0% {
                -webkit-transform: rotate(0deg);
            }

            100% {
                -webkit-transform: rotate(360deg);
            }
        }

        @keyframes spin {
            0% {
                transform: rotate(0deg);
            }

            100% {
                transform: rotate(360deg);
            }
        }

        .cus_modal_desg {
            position: absolute;
            right: 0;
            margin-top: 0;
            width: 400px;
        }

        .cus_modal_desg .modal-body p {
            font-size: 20px;
            font-weight: 600;
        }

        .cus_modal_desg .btn-default {
            background-color: #A02064;
            border: none;
            color: #fff;
            font-size: 15px;
            font-weight: 600;
            padding: 6px 50px;
        }

        .cus_modal_desg .modal-footer {
            padding-right: 25px;
            text-align: center;
        }

        .cus_modal_desg .modal-body {
            padding: 35px;
            text-align: center;
            padding-bottom: 15px;
        }

        .cus_modal_desg .modal-content {
            background-color: #9a9898;
        }
    </style>

    <section class="main-dash">
        <?php $bit_route = route('get_audio_bitrate'); ?>
        <input type="hidden" id="bit_route" value="<?php echo $bit_route; ?>">
        <meta name="csrf-token" content="{{ csrf_token() }}" />
        <aside>@include('clients.dashboard.includes.sidebar-left')</aside>

        <div class="dash-container">
            <div class="container">
                <div class="row">
                    <div class="col-xl-12 col-12">
                        <div class="dash-heading">
                            <h2>My Dashboard</h2>
                        </div>

                        <!-- Main Content-->

                        <form id="add_trackform" role="form" name="addtrackform"
                            enctype="multipart/form-data" onsubmit="return validate()" autocomplete="off"
                            style="color:white;">
                            @csrf

                            <h3 class="header smaller lighter">
                                Track Information
                            </h3>
                            <div class="row">
                                <input type="hidden" id="divId" name="divId" value="1" />

                                {{-- <div class="col-sm-6">
                                    <div class="form-group">
                                        <label class="control-label no-padding-right " for="form-field-1"> Featured Artist-1
                                        </label>

                                        <input type="text" id="feat_artist_1" name="feat_artist_1" class="form-control"
                                            value="{{ isset($track_data->feat_artist_1) && !empty($track_data->feat_artist_1) ? urldecode($track_data->feat_artist_1) : '' }}"
                                            placeholder="Featured Artist-1">
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label class="control-label no-padding-right" for="form-field-1"> Featured Artist-2
                                        </label>

                                        <input type="text" id="feat_artist_2" name="feat_artist_2" class="form-control"
                                            value="{{ isset($track_data->feat_artist_2) && !empty($track_data->feat_artist_2) ? urldecode($track_data->feat_artist_2) : '' }}"
                                            placeholder="Featured Artist-2">
                                    </div>
                                </div> --}}


                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label class="control-label no-padding-right req-label" for="form-field-1"> Type
                                        </label>
                                        
                                        <select name="priorityType" id="priorityType" class="form-control">

                                            <option value="">Select Priority</option>

                                            <option value="top-priority">Top Priority</option>

                                        </select>
                                    </div>
                                </div>


                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label class="control-label no-padding-right " for="form-field-1"> Label/Company
                                        </label>

                                        <input type="text" id="company" placeholder="Label / Company" name="company"
                                            class="form-control"
                                            value="{{ isset($track_data->label) && !empty($track_data->label) ? urldecode($track_data->label) : '' }}"
                                            placeholder="Enter Label/Company">
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label class="control-label no-padding-right" for="form-field-1">Producer(s)
                                        </label>

                                        <input type="text" id="producers" name="producers" class="form-control"
                                            value="{{ isset($track_data->producers) && !empty($track_data->producers) ? urldecode($track_data->producers) : '' }}"
                                            placeholder="Producer(s)">
                                    </div>
                                </div>

                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label class="control-label no-padding-right" for="form-field-1">Release Type
                                        </label>

                                        <select name="albumType" id="albumType" class="form-control">

                                            <option value="">Music Type</option>

                                            <option {{ isset($track_data->albumType) && !empty($track_data->albumType) && $track_data->albumType == '1' ? 'selected' : '' }}
                                                value="1">Single</option>

                                            <option {{ isset($track_data->albumType) && !empty($track_data->albumType) && $track_data->albumType == '2' ? 'selected' : '' }}
                                                value="2">Album</option>

                                            <option {{ isset($track_data->albumType) && !empty($track_data->albumType) && $track_data->albumType == '3' ? 'selected' : '' }}
                                                value="3">EP</option>

                                            <option  {{ isset($track_data->albumType) && !empty($track_data->albumType) && $track_data->albumType == '4' ? 'selected' : '' }}
                                                value="4">Mixtape</option>



                                        </select>
                                    </div>
                                </div>
                                {{-- <div class="col-sm-6">
                                    <div class="form-group">
                                        <label class="control-label no-padding-right" for="form-field-1">Writer</label>

                                        <input type="text" id="writer" name="writer" class="form-control"
                                            value="{{ isset($track_data->writer) && !empty($track_data->writer) ? urldecode($track_data->writer) : '' }}" placeholder="Writer">
                                    </div>
                                </div> --}}

                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label class="control-label no-padding-right" for="form-field-1">Album </label>

                                        <input type="text" id="album" name="album" class="form-control"
                                            value="{{ isset($track_data->album) && !empty($track_data->album) ? urldecode($track_data->album) : '' }}"
                                            placeholder="Album">
                                    </div>
                                </div>

                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label class="control-label no-padding-right" for="form-field-1">BPM </label>

                                        <input type="text" id="bpm" name="bpm" class="form-control"
                                            value="{{ isset($track_data->bpm) && !empty($track_data->bpm) ? urldecode($track_data->bpm) : '' }}"
                                            placeholder="BPM">
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
                                                {{ isset($track_data->genreId) && !empty($track_data->genreId) && $track_data->genreId == $genre->genreId ? 'selected' : '' }}>
                                                <?php echo $genre->genre; ?>
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

                                        <textarea id="moreInfo" placeholder="More Info." name="moreInfo" class="form-control">{{ isset($track_data->moreinfo) && !empty($track_data->moreinfo) ? urldecode($track_data->moreinfo) : '' }}</textarea>
                                    </div>
                                </div>
                                {{-- <div class="col-sm-6">
                                    <div class="form-group">
                                        <label class="control-label no-padding-right" for="form-field-1">Notes </label>

                                        <textarea id="notes" placeholder="Track Notes" name="notes" class="form-control">{{ isset($track_data->notes) && !empty($track_data->notes) ? urldecode($track_data->notes) : '' }}</textarea>
                                    </div>
                                </div> --}}
                                {{-- <div class="col-sm-6">
                                    <div class="form-group">
                                        <label class="control-label no-padding-right" for="form-field-1">Song key</label>

                                        <input type="text" id="songkey" name="songkey" class="form-control"
                                            placeholder="Song key" value="{{ isset($track_data->songkey) && !empty($track_data->songkey) ? urldecode($track_data->songkey) : '' }}">
                                    </div>
                                </div> --}}

                                {{-- <div style="clear:both;"></div>
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
                                                    value="0"{{ isset($track_data->active) && !empty($track_data->active) && ($track_data->active!='1') ? 'checked' : '' }}
                                                    >
                                                <span class="lbl"> No</span>
                                            </label>
                                        </div>
                                    </div>
                                </div> --}}

                                {{-- <div class="col-sm-6">
                                    <div class="form-group">
                                        <label class="control-label no-padding-right" for="form-field-1">
                                            Reviewable </label>

                                        <div class="radio">
                                            <label>
                                                <input required name="reviewable" type="radio" class="ace"
                                                    value="1"{{ isset($track_data->review) && !empty($track_data->review) && ($track_data->review=='1') ? 'checked' : '' }}
                                                    >
                                                <span class="lbl"> Yes </span>
                                            </label>
                                            <label>
                                                <input required name="reviewable" type="radio" class="ace"
                                                    value="0"{{ isset($track_data->review) && !empty($track_data->review) && ($track_data->review!='1') ? 'checked' : '' }}
                                                    >
                                                <span class="lbl"> No</span>
                                            </label>
                                        </div>
                                    </div>
                                </div> --}}


                                {{-- <div class="col-xs-12">
                                    <h3 class="header smaller lighter">
                                        Contact Details
                                    </h3>
                                </div>
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label class="control-label no-padding-right" for="form-field-1">Name </label>
                                        <input type="text" id="contact_name" name="contact_name"
                                            value="{{ isset($track_data->contact_name) && !empty($track_data->contact_name) ? urldecode($track_data->contact_name) : '' }}"
                                            class="form-control" placeholder="Contact Name">

                                    </div>

                                </div>


                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label class="control-label no-padding-right req-label" for="form-field-1">Email
                                        </label>
                                        <input type="email" id="contact_email" name="contact_email"
                                            value="{{ isset($track_data->contact_email) && !empty($track_data->contact_email) ? urldecode($track_data->contact_email) : '' }}"
                                            class="form-control" placeholder="Contact Email" required>
                                    </div>

                                </div>
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label class="control-label no-padding-right" for="form-field-1">Phone</label>
                                        <input type="text" id="contact_phone" name="contact_phone"
                                            value="{{ isset($track_data->contact_phone) && !empty($track_data->contact_phone) ? urldecode($track_data->contact_phone) : '' }}"
                                            class="form-control" placeholder="Contact Phone">
                                    </div>

                                </div>



                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label class="control-label no-padding-right" for="form-field-1"> Relationship to
                                            Artist</label>
                                        <input type="text" id="relationship_to_artist" name="relationship_to_artist"
                                            value="{{ isset($track_data->relationship_to_artist) && !empty($track_data->relationship_to_artist) ? urldecode($track_data->relationship_to_artist) : '' }}"
                                            class="form-control" placeholder="Relationship to Artist">
                                    </div>
                                </div> --}}


                                {{-- <div class="col-xs-12">
                                    <h3 class="header smaller lighter">
                                        Logos
                                    </h3>
                                </div> --}}

                                {{-- <div class="col-sm-6">
                                    <span class="col-sm-4 col-sm-offset-3" style="color:#428bca;">Select
                                        Logo</span>
                                    <div class="col-sm-12 form-group">
                                        <label class="col-sm-3 control-label no-padding-right" for="form-field-1">
                                            Logos </label>
                                        <div class="col-sm-9">
                                            <select data-placeholder="Begin typing a name to filter..." multiple
                                                class="chosen-select-logos col-xs-10 col-sm-10" name="logos[]">

                                                @foreach ($all_logos as $logo)
                                                    @if (!empty($logo->company))
                                                        <option value="{{ $logo->id }}"
                                                            @if (in_array($logo->id, $track_data_logos))
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
                                        </div>
                                    </div>
                                </div> --}}

                                {{-- <div class="col-md-6">
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
                                </div> --}}


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

                                        <input type="url" id="video" name="video"
                                            class="form-control col-xs-10 col-sm-10"
                                            value="{{ isset($track_data->videoURL) && !empty($track_data->videoURL) ? $track_data->videoURL : '' }}" />

                                    </div>

                                </div>

                                <div class="col-md-6 form-group">

                                    <label class="col-xs-3 control-label no-padding-right" for="form-field-1">

                                        Website </label>

                                    <div class="col-xs-9">

                                        <input type="url" id="website" placeholder="" name="website"
                                            class="form-control col-xs-10 col-sm-10"
                                            value="{{ isset($track_data->link) && !empty($track_data->link) ? $track_data->link : '' }}">

                                    </div>

                                </div>

                                <div style="clear:both;"></div>

                                <div class="col-md-6 form-group">

                                    <label class="col-xs-3 control-label no-padding-right" for="form-field-1">Website 1
                                    </label>

                                    <div class="col-xs-9">

                                        <input type="url" id="website1" name="website1"
                                            class="form-control col-xs-10 col-sm-10"
                                            value="{{ isset($track_data->link1) && !empty($track_data->link1) ? $track_data->link1 : '' }}" />

                                    </div>

                                </div>

                                <div class="col-md-6 form-group">

                                    <label class="col-xs-3 control-label no-padding-right" for="form-field-1">Website 2
                                    </label>

                                    <div class="col-xs-9">

                                        <input type="url" id="website2" name="website2"
                                            class="form-control col-xs-10 col-sm-10"
                                            value="{{ isset($track_data->link2) && !empty($track_data->link2) ? $track_data->link2 : '' }}" />

                                    </div>

                                </div>

                                <div style="clear:both;"></div>

                                <div class="col-md-6 form-group">

                                    <label class="col-xs-3 control-label no-padding-right" for="form-field-1">Facebook
                                    </label>

                                    <div class="col-xs-9">

                                        <input type="url" id="facebookLink" name="facebookLink"
                                            class="form-control col-xs-10 col-sm-10"
                                            value="{{ isset($track_data->facebookLink) && !empty($track_data->facebookLink) ? $track_data->facebookLink : '' }}" />

                                    </div>

                                </div>

                                <div class="col-md-6 form-group">

                                    <label class="col-xs-3 control-label no-padding-right" for="form-field-1">Twitter
                                    </label>

                                    <div class="col-xs-9">

                                        <input type="url" id="twitterLink" name="twitterLink"
                                            class="form-control col-xs-10 col-sm-10"
                                            value="{{ isset($track_data->twitterLink) && !empty($track_data->twitterLink) ? $track_data->twitterLink : '' }}" />

                                    </div>

                                </div>

                                <div style="clear:both;"></div>

                                <div class="col-md-6 form-group">

                                    <label class="col-xs-3 control-label no-padding-right" for="form-field-1">Instagram
                                    </label>

                                    <div class="col-xs-9">

                                        <input type="url" id="instagramLink" name="instagramLink"
                                            class="form-control col-xs-10 col-sm-10"
                                            value="{{ isset($track_data->instagramLink) && !empty($track_data->instagramLink) ? $track_data->instagramLink : '' }}" />

                                    </div>

                                </div>

                                <div class="col-md-6 form-group">

                                    <label class="col-xs-3 control-label no-padding-right" for="form-field-1"> Tik Tok
                                    </label>

                                    <div class="col-xs-9">

                                        <input type="url" id="tiktokLink" name="tiktokLink"
                                            class="form-control col-xs-10 col-sm-10"
                                            value="{{ isset($track_data->tiktokLink) && !empty($track_data->tiktokLink) ? $track_data->tiktokLink : '' }}">

                                    </div>

                                </div>

                                <div style="clear:both;"></div>

                                <div class="col-md-6 form-group">

                                    <label class="col-xs-3 control-label no-padding-right" for="form-field-1"> Snapchat
                                    </label>

                                    <div class="col-xs-9">

                                        <input type="url" id="snapchatLink" name="snapchatLink"
                                            class="form-control col-xs-10 col-sm-10"
                                            value="{{ isset($track_data->snapchatLink) && !empty($track_data->snapchatLink) ? $track_data->snapchatLink : '' }}">

                                    </div>

                                </div>

                                <div class="col-md-6 form-group">

                                    <label class="col-xs-3 control-label no-padding-right" for="form-field-1"> Others
                                    </label>

                                    <div class="col-xs-9">

                                        <input type="url" id="othersLink" name="othersLink"
                                            class="form-control col-xs-10 col-sm-10"
                                            value="{{ isset($track_data->othersLink) && !empty($track_data->othersLink) ? $track_data->othersLink : '' }}">

                                    </div>

                                </div>

                                <div class="col-md-6 form-group">

                                    <label class="col-xs-3 control-label no-padding-right" for="form-field-1"> Youtube
                                    </label>

                                    <div class="col-xs-9">

                                        <input type="url" id="youtubeLink" name="youtubeLink"
                                            class="form-control col-xs-10 col-sm-10"
                                            value="{{ isset($track_data->youtube_link) && !empty($track_data->youtube_link) ? $track_data->youtube_link : '' }}">

                                    </div>

                                </div>

                                <div style="clear:both;"></div>

                                <div class="col-xs-12">
                                    <div class="form-actions text-right">
                                        <input type="hidden" name="addTrack" value="addTrack">
                                        <button class="add_track_button" id="submit_track_button" type="submit"
                                            name="addTrack">
                                            {{-- <i class="ace-icon fa fa-check bigger-110"></i> --}}
                                            Submit
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </form>

                    </div>
                </div>
            </div>
        </div>
    </section>


    <!--Sweet Alert CDN-->
    <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>


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
        window.lsData['urlSaveTrack'] = "{{ route('client.save.add.track.step3', ['id' => $track_id]) }}";
        window.lsData['submitted_tracks'] = "{{ route('client_submitted_tracks') }}";
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
    <script src="{{ asset('public/js/client_track_step3.js') }}"></script>
@endsection
