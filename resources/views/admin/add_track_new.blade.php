<x-slot name="page_title">{{ __('Add Track') }}</x-slot>
@extends('admin.admin_dashboard_active_sidebar')
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
                    <li class="active">Add Track - Step 1</li>
                </ul><!-- /.breadcrumb -->
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
                        <form id="add_trackform" role="form" name="addtrackform" enctype="multipart/form-data" autocomplete="off"
                            style="color:white;">
                            @csrf

                            <h3 class="header smaller lighter">
                                Track Information
                            </h3>
                            <div class="row">
                                <input type="hidden" id="divId" name="divId" value="1" />
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label class="control-label no-padding-right req-label" for="form-field-1">Artist
                                            Name</label>

                                        <input required type="text" id="artist" name="artist"
                                            class="form-control artist_title" value="{{ old('artist') }}"
                                            placeholder="Enter Artist Name">
                                        @error('artist')
                                            <div class="alert alert-danger">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label class="control-label no-padding-right req-label" for="form-field-1"> Song
                                            Title </label>

                                        <input required type="text" id="title" name="title"
                                            class="form-control artist_title" value="{{ old('title') }}" placeholder="Enter Song Title">
                                    </div>
                                </div>

                                <div class="col-sm-6 track-time">
                                    <div class="form-group">
                                        <label class="control-label no-padding-right req-label" for="form-field-1">Track
                                            Time </label>
                                        <div class="input-group">
                                            <input placeholder="00:00" required type="text" id="time"
                                                name="time" value="" class="form-control"
                                                pattern="[0-9]{2}:[0-9]{2}">
                                            <span class="input-group-addon">00:00</span>
                                        </div>
                                    </div>

                                </div>

                                {{-- <div style="clear:both;"></div> --}}
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label class="control-label no-padding-right req-label" for="form-field-1"> Status
                                        </label>

                                        <select required name="status" id="status" class="form-control">
                                            <option value="">Select Status</option>
                                            <option value="draft">Draft</option>
                                            <option value="publish">Publish</option>
                                        </select>
                                    </div>
                                </div>
                                {{-- <div style="clear:both;"></div> --}}

                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label class="control-label no-padding-right req-label" for="form-field-1"> Page
                                            Image (Artwork)</label>


                                        <input required type="file" id="pageImage" name="pageImage"
                                            class="form-control form-file" value=""
                                            accept="image/png, image/gif, image/jpeg">
                                        <!-- <span style="color:red;">Dimension:900x900 (png/jpg/jpeg/gif/tiff/svg)</span> -->

                                            {{-- pending --}}
                                        {{-- <img src="" width="50" height="50" />
                                        <button type="button" class="btn btn-xs btn-danger"
                                            onclick="deletePageImage(, this);"><i
                                                class="ace-icon fa fa-trash-o bigger-120"></i></button> --}}

                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <input type="hidden" id="coverimage" name="coverimage"
                                        class="form-control form-file" value=""
                                        accept="image/png, image/gif, image/jpeg">
                                    <!--div class="form-group">
                                            <label class="control-label no-padding-right" for="form-field-1"> Back Cover </label>
                                            
                                                <input type="file" id="coverimage" name="coverimage" class="form-control form-file" value="" accept="image/png, image/gif, image/jpeg">
               
                                                     <img src="" width="50" height="50" />
                
                                            </div-->
                                </div>

                                
                                <div class="col-xs-12">
                                    <div class="form-actions text-right">
                                        <input type="hidden" name="addTrack" value="addTrack">
                                        <button class="btn btn-info btn-sm" id="submit_track_button" type="submit"
                                            name="addTrack">
                                            <i class="ace-icon fa fa-check bigger-110"></i>
                                            Submit - Step 1
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
            
            <!--Sweet Alert CDN-->
            <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
            <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>

            
            <script>
                // function change_genre(genreId) {
                //     $.ajax({
                //         url: "add_track?getSubGenres=1&genreId=" + genreId,
                //         success: function(result) {
                //             var obj = JSON.parse(result);
                //             var count = obj.length;
                //             var liList = '';
                //             var optionList = ''; //'<option value="">What country do you live in</option>';
                //             for (var i = 0; i < count; i++) {
                //                 //		  liList += '<li data-original-index="'+i+'"><a tabindex="0" class="" style="" data-tokens="null" role="option" aria-disabled="false" aria-selected="false"><span class="text">'+obj[i].name+'</span><span class="glyphicon glyphicon-ok check-mark"></span></a></li>';
                //                 optionList += '<option value="' + obj[i].id + '">' + obj[i].name + '</option>';
                //             }
                //             //	 document.getElementsByClassName('dropdown-menu inner')[5].innerHTML = liList;
                //             document.getElementById('subGenre').innerHTML = optionList;
                //         }
                //     });
                // }
                if ('undefined' === typeof window.lsData) {
                    window.lsData = {};
                }
                window.lsData['urlSaveTrack'] = "{{ route('admin.save.add.track') }}";
                window.lsData['deleteduplicate'] = "{{ route('admin.delete.duplicate.track') }}";
                
                window.onload = function() {		

                    // change_genre(<?php if(!empty($trackData->genreId)) echo $trackData->genreId ?>);

                    // setTimeout(function(){ $('#subGenre').val('<?php if(!empty($trackData->subGenreId)) echo $trackData->subGenreId;?>')}, 700);
                    };
            </script>
            <script src="{{ asset('public/js/jquery.min.js') }}"></script>
            <script src="{{ asset('public/js/jquery.validate.min.js') }}"></script>
            <script src="{{ asset('public/js/add_track_new.js') }}"></script>
            
            
        @endsection
