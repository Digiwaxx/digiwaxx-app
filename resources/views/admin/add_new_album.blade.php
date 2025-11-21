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
                        <a href="{{ route('admin_albums_listing') }}">
                            <i class="ace-icon fa fa-list list-icon"></i>
                            Albums</a>
                    </li>
                    <li class="active">Add Album - Step 1</li>
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


            {{-- <a href="{{ route('edit_album',['id'=>  Crypt::encryptString(112) ]) }}">Edit test</a> --}}


            <div class="page-content">
                <div class="row">




                    {{-- <div class="">
                                    <button class="close" data-dismiss="alert">
                                        <i class="ace-icon fa fa-times"></i>
                                    </button>
                                </div> --}}

                    <div class="col-xs-12">
                        <!-- PAGE CONTENT BEGINS -->
                        <form id="add_albumform" role="form" action="" name="addtrackform" method="post"
                            enctype="multipart/form-data" onsubmit="return validate()" autocomplete="off"
                            style="color:white;">
                            @csrf

                            <h3 class="header smaller lighter">
                                Album Information
                            </h3>
                            <div class="row">
                                <input type="hidden" id="divId" name="divId" value="1" />

                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label class="control-label no-padding-right req-label" for="form-field-1">
                                            Album Title </label>

                                        <input required type="text" id="title" name="title"
                                            class="form-control artist_title" value="{{ old('title') }}"
                                            placeholder="Enter Album Title">
                                    </div>
                                </div>

                                

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
                                    <input type="hidden" id="coverimage" name="coverimage" class="form-control form-file"
                                        value="" accept="image/png, image/gif, image/jpeg">
                                    <!--div class="form-group">
                                                <label class="control-label no-padding-right" for="form-field-1"> Back Cover </label>
                                                
                                                    <input type="file" id="coverimage" name="coverimage" class="form-control form-file" value="" accept="image/png, image/gif, image/jpeg">
                   
                                                         <img src="" width="50" height="50" />
                    
                                                </div-->
                                </div>

                                <div class="col-sm-12">
                                    <h4 class="header smaller lighter">
                                        Add Track
                                    </h4>
                                    <div class="form-group album_track" id="album_track">
                                        <label class="control-label no-padding-right" for="form-field-1">
                                            Track Title </label>

                                        <input type="text" id="track_title" name="tracktitle[]"
                                            class="form-control mb-2 track_title" value=""
                                            placeholder="Enter Track Title">
                                        <br>
                                    </div>

                                    <div class="form-group album_track_div" id="album_track_div"></div>

                                    
                                    <div class="form-group">
                                        <a id="append_track" class="btn btn-success btn-sm">+ Add Track</a>
                                    </div>
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
                if ('undefined' === typeof window.lsData) {
                    window.lsData = {};
                }
                window.lsData['urlSaveAlbum'] = "{{ route('admin.save.add.album') }}";
                window.lsData['deleteduplicate'] = "{{ route('admin.delete.duplicate.album') }}";
            </script>
            <script src="{{ asset('public/js/jquery.min.js') }}"></script>
            <script src="{{ asset('public/js/jquery.validate.min.js') }}"></script>
            <script src="{{ asset('public/js/add_new_album.js') }}"></script>
        @endsection
