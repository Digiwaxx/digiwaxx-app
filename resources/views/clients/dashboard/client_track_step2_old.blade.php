@extends('layouts.client_dashboard')
<link rel="stylesheet" href="{{ URL::to('public/assets/css/add_audio_files.css') }}">
    <link rel="stylesheet" href="{{ URL::to('public/assets/css/blueimp-gallery.min.css') }}">
    <link rel="stylesheet" href="{{ URL::to('public/assets/css/jquery.fileupload.css') }}">
    <link rel="stylesheet" href="{{ URL::to('public/assets/css/jquery.fileupload-ui.css') }}">
    <noscript>
        <link rel="stylesheet" href="{{ URL::to('public/assets/css/jquery.fileupload-noscript.css') }}">
    </noscript>
    <noscript>
        <link rel="stylesheet" href="{{ URL::to('public/assets/css/jquery.fileupload-ui-noscript.css') }}">
    </noscript>
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
        <div class="main-content add-track-page">
            <div class="main-content-inner">


                <div class="page-content">
                    <div class="row">
                        <div class="col-xs-12">
                            <form id="fileupload" action="" method="POST" enctype="multipart/form-data">
                                @csrf
                                {{-- <form id="mp3_tracks" name="mp3_tracks" role="form" action="{{ route('save.mp3.track') }}" method="post" enctype="multipart/form-data" autocomplete="off"> --}}

                                <!-- Redirect browsers with JavaScript disabled to the origin page -->
                                <noscript><input type="hidden" name="redirect"
                                        value="https://blueimp.github.io/jQuery-File-Upload/" /></noscript>
                                <!-- The fileupload-buttonbar contains buttons to add/delete files and start/cancel the upload -->
                                <div class="row fileupload-buttonbar">
                                    <div class="col-lg-7">
                                        <!-- The fileinput-button span is used to style the file input field as button -->
                                        <!-- <span class="btn btn-success fileinput-button">
                                                <i class="glyphicon glyphicon-plus"></i>
                                                <span>Drag Files here</span>
                                                <input type="file" name="files[]" accept=".mp3,audio/*" multiple />
                                            </span> -->
                                        <button type="submit" class="btn btn-primary start">
                                            <i class="glyphicon glyphicon-upload"></i>
                                            <span>Start upload</span>
                                        </button>
                                        <button type="reset" class="btn btn-warning cancel">
                                            <i class="glyphicon glyphicon-ban-circle"></i>
                                            <span>Cancel upload</span>
                                        </button>
                                        <button type="button" class="btn btn-danger delete">
                                            <i class="glyphicon glyphicon-trash"></i>
                                            <span>Delete selected</span>
                                        </button>
                                        <input type="checkbox" class="toggle" />
                                        <!-- The global file processing state -->
                                        <span class="fileupload-process"></span>
                                    </div>
                                    <!-- The global progress state -->
                                    <div class="col-lg-5 fileupload-progress fade">
                                        <!-- The global progress bar -->
                                        <div class="progress progress-striped active" role="progressbar" aria-valuemin="0"
                                            aria-valuemax="100">
                                            <div class="progress-bar progress-bar-success" style="width: 0%;"></div>
                                        </div>
                                        <!-- The extended global progress state -->
                                        <div class="progress-extended">&nbsp;</div>
                                    </div>
                                </div>
                                <div class="row my-4">
                                    <div class="col-md-12 col-xs-12">
                                        <div class="fileinput-button btn-drag">
                                            <i class="glyphicon glyphicon-cloud-upload"></i>
                                            <h3>Drag Files here</h3>
                                            <span>or</span>
                                            <button>Choose files</button>
                                            <input type="file" name="files[]" accept=".mp3,audio/*" multiple />
                                        </div>
                                    </div>

                                </div>
                                <!-- The table listing the files available for upload/download -->
                                <div class="table-responsive table-track">
                                    <table role="presentation" class="table table-striped">
                                        <tbody class="files">
                                            @if (isset($all_mp3) && !empty($all_mp3))
                                                @foreach ($all_mp3 as $mp3)
                                                    <tr class="template-download">
                                                        <td>
                                                            <span class="preview">
                                                                @if (!empty($mp3->audio_url))
                                                                    <audio controls>
                                                                        <source src="{{ $mp3->audio_url }}"
                                                                            type="audio/mpeg">
                                                                        Your browser does not support the html audio tag.
                                                                    </audio>
                                                                @endif
                                                            </span>
                                                        </td>
                                                        <td class="left">
                                                            <input type="radio" value="{{ $mp3->id }}"
                                                                id="preview{{ $mp3->id }}" name="preview"
                                                                {{ $mp3->preview == 1 ? 'checked' : '' }}>
                                                        </td>
                                                        <td>
                                                            <select name="version[{{ $mp3->id }}]"
                                                                id="version[{{ $mp3->id }}]"
                                                                class="form-control version">
                                                                <option value="">Version</option>
                                                                <option value="Acapella"
                                                                    {{ $mp3->version == 'Acapella' ? 'selected' : '' }}>
                                                                    Acapella
                                                                </option>
                                                                <option value="Clean"
                                                                    {{ $mp3->version == 'Clean' ? 'selected' : '' }}>
                                                                    Clean</option>
                                                                <option value="Clean Accapella"
                                                                    {{ $mp3->version == 'Clean Accapella' ? 'selected' : '' }}>
                                                                    Clean
                                                                    Accapella</option>
                                                                <option value="Clean (16 Bar Intro)"
                                                                    {{ $mp3->version == 'Clean (16 Bar Intro)' ? 'selected' : '' }}>
                                                                    Clean (16 Bar Intro)</option>
                                                                <option value="Dirty"
                                                                    {{ $mp3->version == 'Dirty' ? 'selected' : '' }}>
                                                                    Dirty</option>
                                                                <option value="Dirty Accapella"
                                                                    {{ $mp3->version == 'Dirty Accapella' ? 'selected' : '' }}>
                                                                    Dirty
                                                                    Accapella</option>
                                                                <option value="Dirty (16 Bar Intro)"
                                                                    {{ $mp3->version == 'Dirty (16 Bar Intro)' ? 'selected' : '' }}>
                                                                    Dirty (16 Bar Intro)</option>
                                                                <option value="Instrumental"
                                                                    {{ $mp3->version == 'Instrumental' ? 'selected' : '' }}>
                                                                    Instrumental</option>
                                                                <option value="Main"
                                                                    {{ $mp3->version == 'Main' ? 'selected' : '' }}>
                                                                    Main</option>
                                                                <option value="TV Track"
                                                                    {{ $mp3->version == 'TV Track' ? 'selected' : '' }}>TV
                                                                    Track
                                                                </option>
                                                            </select>
                                                        </td>
                                                        <td class="left">
                                                            <input class="form-control" type="text"
                                                                value="{{ !empty($mp3->other_version) ? $mp3->other_version : '' }}"
                                                                id="otherversion[{{ $mp3->id }}]"
                                                                name="otherversion[{{ $mp3->id }}]"
                                                                placeholder="Other Version">
                                                        </td>
                                                        <td>
                                                            <p class="name">

                                                                <a href="{{ $mp3->audio_url }}"
                                                                    title="{{ $mp3->title }}"
                                                                    download="{{ $mp3->title }}">{{ $mp3->title }}</a>
                                                            </p>
                                                        </td>
                                                        <td>
                                                            <span class="size">2.11 MB</span>
                                                        </td>
                                                        <td>
                                                            <button class="btn btn-danger delete" data-type="GET"
                                                                data-url="{{ $mp3->audio_delete_url }}">
                                                                <i class="glyphicon glyphicon-trash"></i>
                                                                <span>Delete</span>
                                                            </button>
                                                            <input type="checkbox" name="delete" value="1"
                                                                class="toggle">

                                                        </td>
                                                    </tr>
                                                @endforeach
                                            @endif
                                        </tbody>
                                    </table>
                                </div>
                                {{-- </form> --}}
                        </div>
                    </div>
                </div>
                <!-- The blueimp Gallery widget -->
                <div id="blueimp-gallery" class="blueimp-gallery blueimp-gallery-controls" aria-label="image gallery"
                    aria-modal="true" role="dialog" data-filter=":even">
                    <div class="slides" aria-live="polite"></div>
                    <h3 class="title"></h3>
                    <a class="prev" aria-controls="blueimp-gallery" aria-label="previous slide"
                        aria-keyshortcuts="ArrowLeft"></a>
                    <a class="next" aria-controls="blueimp-gallery" aria-label="next slide"
                        aria-keyshortcuts="ArrowRight"></a>
                    <a class="close" aria-controls="blueimp-gallery" aria-label="close" aria-keyshortcuts="Escape"></a>
                    <a class="play-pause" aria-controls="blueimp-gallery" aria-label="play slideshow"
                        aria-keyshortcuts="Space" aria-pressed="false" role="button"></a>
                    <ol class="indicator"></ol>
                </div>

                <script id="template-upload" type="text/x-tmpl">
                {% for (var i=0, file; file=o.files[i]; i++) { %}
                    <tr class="template-upload fade{%=o.options.loadImageFileTypes.test(file.type)?' image':''%}">
                        <td>
                            <span class="preview"></span>
                        </td>
                        <td></td>
                        <td>
                            <p class="name">{%=file.name%}</p>
                            <strong class="error text-danger"></strong>
                        </td>
                        <td></td>
                        <td></td>
                        <td>
                            <p class="size">Processing...</p>
                            <div class="progress progress-striped active" role="progressbar" aria-valuemin="0" aria-valuemax="100" aria-valuenow="0"><div class="progress-bar progress-bar-success" style="width:0%;"></div></div>
                        </td>
                        <td>
                            {% if (!o.options.autoUpload && o.options.edit && o.options.loadImageFileTypes.test(file.type)) { %}
                            <button class="btn btn-success edit" data-index="{%=i%}" disabled>
                                <i class="glyphicon glyphicon-edit"></i>
                                <span>Edit</span>
                            </button>
                            {% } %}
                            {% if (!i && !o.options.autoUpload) { %}
                                <button class="btn btn-primary start" disabled>
                                    <i class="glyphicon glyphicon-upload"></i>
                                    <span>Start</span>
                                </button>
                            {% } %}
                            {% if (!i) { %}
                                <button class="btn btn-warning cancel">
                                    <i class="glyphicon glyphicon-ban-circle"></i>
                                    <span>Cancel</span>
                                </button>
                            {% } %}
                        </td>
                    </tr>
                {% } %}
                </script>


                {{--  --}}
                {{-- <table role="presentation" class="table table-striped">
                <tbody class="files"><tr class="template-upload fade in"> --}}

                {{--  --}}

                <!-- The template to display files available for download -->
                <script id="template-download" type="text/x-tmpl">
                {% for (var i=0, file; file=o.files[i]; i++) { %}
                    <tr class="template-download fade{%=file.thumbnailUrl?' image':''%}">
                        <td>
                            <span class="preview">
                                {% if (file.thumbnailUrl) { %}
                                    <a href="{%=file.url%}" title="{%=file.name%}" download="{%=file.name%}" data-gallery><img src="{%=file.thumbnailUrl%}"></a>
                                    
                                {% } %}
                                {% if (file.audio_url) { %}

                                <audio controls>  
                                <source src="{%=file.url%}" type="audio/mpeg">  
                            Your browser does not support the html audio tag.  
                            
                            </audio>  
                            {% } %}
                            </span>
                        </td>
                        <td class="left">					
                            <input type="radio" value="{%=file.mp3_id%}" id="preview{%=file.mp3_id%}" name="preview">												
                        </td>
                        <td>
                            <input type="hidden" name="trackinfo[mp3_track_id]" value="{%=file.mp3_id%}">     
                            <select name="version[{%=file.mp3_id%}]" id="version[{%=file.mp3_id%}]" class="form-control version">
                                <option value="">Version</option>
                                <option value="Acapella">Acapella</option>
                                <option value="Clean">Clean</option>
                                <option value="Clean Accapella">Clean Accapella</option>
                                <option value="Clean (16 Bar Intro)">Clean (16 Bar Intro)</option>
                                <option value="Dirty">Dirty</option>
                                <option value="Dirty Accapella">Dirty Accapella</option>
                                <option value="Dirty (16 Bar Intro)">Dirty (16 Bar Intro)</option>
                                <option value="Instrumental">Instrumental</option>
                                <option value="Main">Main</option>
                                <option value="TV Track">TV Track</option>
                            </select>
                        </td>
                        <td class="left">					
                            <input class="form-control" type="text" value="" id="otherversion[{%=file.mp3_id%}]" name="otherversion[{%=file.mp3_id%}]"  placeholder="Other Version">												
                        </td>
                        <td>
                            <p class="name">
                                {% if (file.url) { %}
                                    <a href="{%=file.url%}" title="{%=file.name%}" download="{%=file.name%}" {%=file.thumbnailUrl?'data-gallery':''%}>{%=file.name%}</a>
                                {% } else { %}
                                    <span>{%=file.name%}</span>
                                {% } %}
                            </p>
                            {% if (file.error) { %}
                                <div><span class="label label-danger">Error</span> {%=file.error%}</div>
                            {% } %}
                        </td>
                        <td>
                            <span class="size">{%=o.formatFileSize(file.size)%}</span>
                        </td>
                        <td>
                            {% if (file.deleteUrl) { %}
                                <button class="btn btn-danger delete" data-type="{%=file.deleteType%}" data-url="{%=file.deleteUrl%}"{% if (file.deleteWithCredentials) { %} data-xhr-fields='{"withCredentials":true}'{% } %}>
                                    <i class="glyphicon glyphicon-trash"></i>
                                    <span>Delete</span>
                                </button>
                                <input type="checkbox" name="delete" value="1" class="toggle">
                            {% } else { %}
                                <button class="btn btn-warning cancel">
                                    <i class="glyphicon glyphicon-ban-circle"></i>
                                    <span>Cancel</span>
                                </button>
                            {% } %}
                        </td>
                    </tr>
                {% } %}

                
            </script>

                <div class="col-xs-12">
                    <div class="form-actions text-right">
                        <button class="btn btn-info btn-sm" id="submit_mp3_track_button" type="submit" name="mp3Track">
                            <i class="ace-icon fa fa-check bigger-110"></i>
                            Submit
                        </button>

                    </div>
                </div>
                </form>


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
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"
        integrity="sha384-nvAa0+6Qg9clwYCGGPpDQLVpLNn0fRaROjHqs13t4Ggj3Ez50XnGQqc/r8MhnRDZ" crossorigin="anonymous">
    </script>

    <script src="{{ asset('public/assets/js/vendor/jquery.ui.widget.js') }}"></script>
    <script src="{{ asset('public/assets/js/tmpl.min.js') }}"></script>
    <script src="{{ asset('public/assets/js/load-image.all.min.js') }}"></script>
    <script src="{{ asset('public/assets/js/canvas-to-blob.min.js') }}"></script>
    <script src="{{ asset('public/assets/js/jquery.blueimp-gallery.min.js') }}"></script>
    <script src="{{ asset('public/assets/js/jquery.iframe-transport.js') }}"></script>
    <script src="{{ asset('public/assets/js/jquery.fileupload.js') }}"></script>
    <script src="{{ asset('public/assets/js/jquery.fileupload-process.js') }}"></script>
    <script src="{{ asset('public/assets/js/jquery.fileupload-image.js') }}"></script>
    <script src="{{ asset('public/assets/js/jquery.fileupload-audio.js') }}"></script>
    <script src="{{ asset('public/assets/js/jquery.fileupload-video.js') }}"></script>
    <script src="{{ asset('public/assets/js/jquery.fileupload-validate.js') }}"></script>
    <script src="{{ asset('public/assets/js/jquery.fileupload-ui.js') }}"></script>
    <script src="{{ asset('public/assets/js/add_audio_files.js') }}"></script>
    <script src="{{ asset('public/js/client_track_step2.js') }}"></script>

    <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>

@endsection
