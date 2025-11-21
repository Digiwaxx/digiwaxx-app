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

        .profile-info-name {
            background-color: #181818;
            border-top: 1px solid #4a4949;
            color: #fff;
            display: table-cell;
            font-weight: 400;
            padding: 6px 10px 6px 4px;
            text-align: right;
            vertical-align: middle;
            width: 200px;
        }

        .profile-info-value {
            background-color: #222121;
            color: #fff;
            border-top: 1px solid #4a4949;
            display: table-cell;
            padding: 6px 4px 6px 6px;
        }

        .profile-user-info-striped {
            border: 1px solid #4a4949;
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
                            <h2>Submitted Tracks </h2>
                        </div>

                        <!-- Main Content-->

                        <table id="sample-table-1" class="table table-striped table-bordered table-hover">
                            <thead>
                                <tr>
                                    <th class="center" width="60">S.No.</th>
                                    <th class="">Title</th>
                                    <th class="">Artist</th>
                                    <th class="hidden-xs">Album</th>
                                    <th class="hidden-md hidden-sm hidden-xs">Time</th>
                                    <th class="hidden-md hidden-sm hidden-xs">Submitted On</th>
                                    <th width="140">Action</th>
                                </tr>
                            </thead>

                            <tbody>
                                {{-- <tr>
                                    <td>1</td>
                                    <td>title</td>
                                    <td>artist</td>
                                    <td>album</td>
                                    <td>06:23</td>
                                    <td>21-05-2023</td>
                                    <td></td>
                                    
                                </tr> --}}

                                @if (isset($client_tracks) && !empty($client_tracks))
                                    @php($sno = 0)
                                    @foreach ($client_tracks as $track)
                                        @php($sno = $sno + 1)
                                        <tr>
                                            <td>{{ $sno }}</td>
                                            <td>{{ isset($track->title) && !empty($track->title) ? urldecode($track->title) : '' }}
                                            </td>
                                            <td>{{ isset($track->artist) && !empty($track->artist) ? urldecode($track->artist) : '' }}
                                            </td>
                                            <td>{{ isset($track->album) && !empty($track->album) ? urldecode($track->album) : '' }}
                                            </td>
                                            <td>{{ isset($track->time) && !empty($track->time) ? urldecode($track->time) : '' }}
                                            </td>
                                            <td>{{ isset($track->added) && !empty($track->added) ? date('d-M-Y', strtotime($track->added)) : '' }}
                                            </td>
                                            <td>
                                                <button title="Preview Track" type="button"
                                                    data-title="{{ urldecode($track->title) }}"
                                                    data-track="{{ json_encode($track) }}" data-toggle="modal"
                                                    data-target="#exampleModal" class="btn btn-xs btn-success">
                                                    <i class="ace-icon fa fa-eye bigger-120"></i>
                                                </button>
                                            </td>

                                        </tr>
                                    @endforeach
                                @endif

                            </tbody>

                        </table>




                        <!--------- Single Modal ==========================================================-->
                        <div id="exampleModal" class="modal fade" tabindex="-1" role="dialog">
                            <!-- <div class="modal-backdrop fade in"></div> -->

                            <div class="modal-dialog">

                                <!-- Modal content-->

                                <div class="modal-content">

                                    <div class="modal-header">
                                        <h5 class="modal-title" id="modalTitle">Title</h5>
                                        <!-- <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button> -->
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>

                                    <div class="modal-body" id="trackData4561">
                                        <div class="row">

                                            <div class="col-xs-12 col-sm-3">



                                                <img src="" id="track_image" class="img-fluid">
                                            </div>

                                            <div class="col-xs-12 col-sm-9">


                                                <div class="profile-user-info profile-user-info-striped">

                                                    <div class="profile-info-row">
                                                        <div class="profile-info-name"> Artist </div>

                                                        <div class="profile-info-value" id="track_artist">
                                                            wckjnkewjf </div>
                                                    </div>
                                                    <div class="profile-info-row">
                                                        <div class="profile-info-name"> Title </div>

                                                        <div class="profile-info-value" id="track_title">
                                                            ewiurwiuu </div>
                                                    </div>

                                                    <div class="profile-info-row">
                                                        <div class="profile-info-name"> Album </div>

                                                        <div class="profile-info-value" id="track_album">
                                                        </div>
                                                    </div>
                                                    <div class="profile-info-row">
                                                        <div class="profile-info-name">Time </div>

                                                        <div class="profile-info-value" id="track_time">
                                                            05:32 </div>
                                                    </div>
                                                    <div class="profile-info-row">
                                                        <div class="profile-info-name">Bpm </div>

                                                        <div class="profile-info-value" id="track_bpm">
                                                        </div>
                                                    </div>
                                                    <div class="profile-info-row">
                                                        <div class="profile-info-name">Genre </div>

                                                        <div class="profile-info-value" id="track_genre">
                                                        </div>
                                                    </div>
                                                    <div class="profile-info-row">
                                                        <div class="profile-info-name">Sub Genre </div>

                                                        <div class="profile-info-value" id="track_subgenre">
                                                        </div>
                                                    </div>
                                                    <div class="profile-info-row">
                                                        <div class="profile-info-name">Link</div>

                                                        <div class="profile-info-value" id="track_link">
                                                        </div>
                                                    </div>
                                                    <div class="profile-info-row">
                                                        <div class="profile-info-name">Link 1</div>

                                                        <div class="profile-info-value" id="track_link1">
                                                        </div>
                                                    </div>
                                                    <div class="profile-info-row">
                                                        <div class="profile-info-name">Link 2</div>

                                                        <div class="profile-info-value" id="track_link2">
                                                        </div>
                                                    </div>

                                                    <div class="profile-info-row">
                                                        <div class="profile-info-name">Facebook</div>

                                                        <div class="profile-info-value" id="track_fb">
                                                        </div>
                                                    </div>

                                                    <div class="profile-info-row">
                                                        <div class="profile-info-name">Twitter</div>

                                                        <div class="profile-info-value" id="track_twitter">
                                                        </div>
                                                    </div>

                                                    <div class="profile-info-row">
                                                        <div class="profile-info-name">Instagram</div>

                                                        <div class="profile-info-value" id="track_insta">
                                                        </div>
                                                    </div>

                                                    <div class="profile-info-row">
                                                        <div class="profile-info-name"> Producers </div>

                                                        <div class="profile-info-value" id="track_producers">
                                                        </div>
                                                    </div>
                                                    <div class="profile-info-row">
                                                        <div class="profile-info-name"> More Info </div>

                                                        <div class="profile-info-value" id="track_moreinfo">
                                                        </div>
                                                    </div>
                                                    <div class="profile-info-row">
                                                        <div class="profile-info-name"> Release Date </div>
                                                        <div class="profile-info-value" id="track_releasedate"></div>
                                                    </div>
                                                </div>





                                                <div class="space-24"></div>

                                            </div>


                                            <div style="clear:both;"></div>
                                            <h3 class="header smaller lighter blue mt-3">Audio Files</h3>
                                            <table id="simple-table" class="table  table-bordered table-hover">
                                                <thead>
                                                    <tr>
                                                        <th class="center" width="100">
                                                            S. No
                                                        </th>
                                                        <th class="detail-col" width="150">Version</th>

                                                        <th>Track</th>
                                                    </tr>
                                                </thead>

                                                <tbody>
                                                    <tr id='amr1'>
                                                        <td class="center">1</td>
                                                        <td class="left" id="audio_version1"></td>
                                                        <td>
                                                            <audio id="audio_amr1" controls style="width:100%">
                                                                Your browser does not support the audio element.
                                                            </audio>
                                                        </td>
                                                    </tr>
                                                    <tr id='amr2'>
                                                        <td class="center">2</td>
                                                        <td class="left" id="audio_version2"></td>
                                                        <td>
                                                            <audio id="audio_amr2" controls style="width:100%">
                                                                Your browser does not support the audio element.
                                                            </audio>
                                                        </td>
                                                    </tr>
                                                    <tr id='amr3'>
                                                        <td class="center">3</td>
                                                        <td class="left" id="audio_version3"></td>
                                                        <td>
                                                            <audio id="audio_amr3" controls style="width:100%">
                                                                Your browser does not support the audio element.
                                                            </audio>
                                                        </td>
                                                    </tr>
                                                    <tr id='amr4'>
                                                        <td class="center">4</td>
                                                        <td class="left" id="audio_version4"></td>
                                                        <td>
                                                            <audio id="audio_amr4" controls style="width:100%">
                                                                Your browser does not support the audio element.
                                                            </audio>
                                                        </td>
                                                    </tr>
                                                    <tr id='amr5'>
                                                        <td class="center">5</td>
                                                        <td class="left" id="audio_version5"></td>
                                                        <td>
                                                            <audio id="audio_amr5" controls style="width:100%">
                                                                Your browser does not support the audio element.
                                                            </audio>
                                                        </td>
                                                    </tr>
                                                    <tr id='amr6'>
                                                        <td class="center">6</td>
                                                        <td class="left" id="audio_version6"></td>
                                                        <td>
                                                            <audio id="audio_amr6" controls style="width:100%">
                                                                Your browser does not support the audio element.
                                                            </audio>
                                                        </td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                        <div style="clear:both;"></div>
                                    </div>

                                    <div class="modal-footer">

                                        <button type="button" class="btn btn-default"
                                            data-dismiss="modal">Close</button>

                                    </div>

                                </div>

                            </div>

                        </div>
                        <!--------- Single Modal ==========================================================-->


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
    <script src="{{ asset('public/js/client_submitted_tracks.js') }}"></script>
    <!-- <script src="https://digiwaxx.com/digiwaxx-dev/assets_admin/assets/js/bootstrap.js"></script> -->
    <script src="{{ asset('public/js/popper.min.js') }}"></script>
<script src="{{ asset('public/js/bootstrap.min.js') }}"></script>

@endsection
