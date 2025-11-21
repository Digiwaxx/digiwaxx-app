<x-slot name="page_title">{{ __('Add Track Audio Files') }}</x-slot>

@extends('admin.admin_dashboard_active_sidebar')

@section('content')
    <!--<h1>Add Track New </h1>-->
    <div class="main-content add-track-page">
        <div class="main-content-inner">
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
                            Tracks</a>
                    </li>
                    <li class="active">Manage Mp3</li>
                </ul><!-- /.breadcrumb -->
                <!-- /section:basics/content.searchbox -->
            </div>


            <div class="alert alert-success" id="track-exist-check" style="display:none">
                <a href="javascript:void(0)" class="close" data-dismiss="alert">&times;</a>
                <strong>Success!</strong><span class="show-error"></span>
            </div>

            <div class="page-content">
                <div class="row">

                    <div class="col-xs-12">
                        <h3 class="header smaller lighter">
                            Add Audio Files
                        </h3>
                    </div>

                    <form id="fileupload" action="" method="POST" enctype="multipart/form-data">
                        @csrf

                        <input type="hidden" name="album_id" value="{{ Crypt::encryptString($album_id) }}">

                        {{-- @if (isset($tracks) && !empty($tracks))
                            @foreach ($tracks as $track)
                                <br>
                                <a
                                    href="{{ route('manage_album_track_audios', ['id' => Crypt::encryptString($track->id), 'album_id' => Crypt::encryptString($album_id)]) }}">
                                    {{ $track->title }}</a>

                                <br>
                                </option>
                            @endforeach

                            </select>
                        @endif --}}


                        <div class="row">
                            <div class="col-xs-12">
                                <table id="simple-table" class="table table-bordered table-striped table-hover">
                                    <thead>
                                        <tr>
                                            <th class="center">S. No</th>
                                            <th colspan="3">Track</th>
                                            <th class="text-center">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @if (isset($tracks) && !empty($tracks))
                                            @foreach ($tracks as $track)
                                                <tr main="" data-track-id="{{ Crypt::encryptString($track->id) }}">
                                                    <td class="text-center" rowspan="{{ $track->rowspan }}">
                                                        {{ $track->sno }} </td>
                                                    {{-- {{$track->sno}}
                                                        {{$track->rowspan}} --}}
                                                    <td colspan="3">
                                                        <a
                                                            href="{{ route('manage_album_track_audios', ['id' => Crypt::encryptString($track->id), 'album_id' => Crypt::encryptString($album_id)]) }}">
                                                            {{ $track->title }}
                                                        </a>
                                                    </td>
                                                    <td class="text-center" rowspan="{{ $track->rowspan }}">
                                                        <button title="Delete Track" type="button"
                                                            class="mt-1 btn btn-xs btn-danger delete_track"
                                                            data-trackid="{{ Crypt::encryptString($track->id) }}"><i
                                                                class="ace-icon fa fa-trash-o bigger-120"></i> Delete
                                                            Track</button>

                                                        <a
                                                            href="{{ route('manage_album_track_audios', ['id' => Crypt::encryptString($track->id), 'album_id' => Crypt::encryptString($album_id)]) }}">
                                                            <button title="Add New Track" type="button"
                                                                class="mt-1 btn btn-xs btn-primary add_track_version"
                                                                id="add_track_version" value="31229"><i
                                                                    class="ace-icon fa fa-plus bigger-120"></i> Add
                                                                Version</button></a>
                                                    </td>
                                                </tr>
                                                @if (isset($track_mp3[$track->id]) && !empty($track_mp3[$track->id]))
                                                    @foreach ($track_mp3[$track->id] as $mp3)
                                                        <tr>
                                                            <td width="25%" class="left">
                                                                {{ $mp3->version }} </td>
                                                            <td>
                                                                {{-- <audio controls="">
                                                                    <source
                                                                    src="{{$mp3->audio_url }}"
                                                                        type="audio/mp3">
                                                                    Your browser does not support the audio element.
                                                                </audio> --}}
                                                                @if (!empty($mp3->audio_url))
                                                                    <button type="button" class="btn btn-primary"
                                                                        data-toggle="modal" data-target="#exampleModal"
                                                                        data-audio-url="{{ $mp3->audio_url }}"
                                                                        data-modal-title="{{ $track->title . ' [ ' . urldecode($mp3->version) . ' ] ' }}">
                                                                        <i class="fa fa-play"></i>
                                                                    </button>
                                                                @endif
                                                            </td>
                                                            <td style="vertical-align: middle;">
                                                                <button title="Delete Version" type="button"
                                                                    class="btn btn-xs btn-danger delete_track_mp3"
                                                                    data-url="{{ $mp3->audio_delete_url }}"><i
                                                                        class="ace-icon fa fa-trash-o bigger-120"></i></button>
                                                            </td>
                                                        </tr>
                                                    @endforeach
                                                @endif
                                            @endforeach
                                        @endif

                                    </tbody>
                                </table>
                                <!-- Single Modal -->
                                <div class="modal" id="exampleModal" tabindex="-1" role="dialog">
                                    <div class="modal-dialog" role="document">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="modalTitle">Title</h5>
                                                <button type="button" class="close" data-dismiss="modal"
                                                    aria-label="Close">
                                                    <span aria-hidden="true">&times;</span>
                                                </button>
                                            </div>
                                            <div class="modal-body">
                                                <audio id="audioPlayer" controls style="width:100%">
                                                    Your browser does not support the audio element.
                                                </audio>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <!-- Single Modal -->
                            </div>



                        </div>


                        <div class="col-xs-12">
                            <div class="form-actions text-right">
                                <button class="btn btn-info btn-sm" id="submit_mp3_album_button" type="submit"
                                    name="mp3Track">
                                    <i class="ace-icon fa fa-check bigger-110"></i>
                                    Submit
                                </button>

                            </div>
                        </div>

                    </form>
                </div>
            </div>

            <script>
                if ('undefined' === typeof window.lsData) {
                    window.lsData = {};
                }
                window.lsData['album_id'] = "{{ isset($album_id) && !empty($album_id) ? Crypt::encryptString($album_id) : '' }}";
                window.lsData['track_id'] = "{{ isset($album_id) && !empty($album_id) ? Crypt::encryptString($album_id) : '' }}";
                window.lsData['step3'] = "{{ route('manage_album_meta_info', ['id' => Crypt::encryptString($album_id)]) }}";
                window.lsData['delete_Track'] = "{{ route('delete_Track') }}";
                window.lsData['urlSaveMp3'] = "{{ route('save.mp3.album') }}";
            </script>

            <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"
                integrity="sha384-nvAa0+6Qg9clwYCGGPpDQLVpLNn0fRaROjHqs13t4Ggj3Ez50XnGQqc/r8MhnRDZ" crossorigin="anonymous">
            </script>

            <script src="{{ asset('public/js/add_album_step2.js') }}"></script>

            <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
        @endsection
