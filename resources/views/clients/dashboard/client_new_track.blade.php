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

                        <form id="client_add_trackform" role="form" action="" name="addtrackform" method="post"
                            enctype="multipart/form-data" onsubmit="return validate()" autocomplete="off"
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
                                            class="form-control artist_title" value="{{ old('title') }}"
                                            placeholder="Enter Song Title">
                                    </div>
                                </div>

                                <div class="col-sm-6 track-time">
                                    <div class="form-group">
                                        <label class="control-label no-padding-right req-label" for="form-field-1">Track
                                            Time </label>
                                        <div class="input-group">
                                            <input placeholder="00:00" required type="text" id="time" name="time"
                                                value="" class="form-control" pattern="[0-9]{2}:[0-9]{2}">
                                            {{-- <span class="input-group-addon">00:00</span> --}}
                                        </div>
                                    </div>

                                </div>

                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label class="control-label no-padding-right req-label" for="form-field-1"> Page
                                            Image (Artwork)</label>
                                        <input required type="file" id="pageImage" name="pageImage"
                                            class="form-control form-file" value=""
                                            accept="image/png, image/gif, image/jpeg">
                                    </div>
                                </div>
                                <input type="hidden" name="client_id" value="{{  isset($sessClientID) && !empty($sessClientID) ? $sessClientID : '' }}"> 
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
        if ('undefined' === typeof window.lsData) {
            window.lsData = {};
        }
        window.lsData['urlSaveTrack'] = "{{ route('client.save.add.track') }}";
        window.lsData['deleteduplicate'] = "{{ route('client.delete.duplicate.track') }}";
    </script>
    <script src="{{ asset('public/js/jquery.min.js') }}"></script>
    <script src="{{ asset('public/js/jquery.validate.min.js') }}"></script>
    <script src="{{ asset('public/js/client_new_track.js') }}"></script>

@endsection
