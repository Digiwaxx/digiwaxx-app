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
                        <a href="">
                            <i class="ace-icon fa fa-list list-icon"></i>
                            Tracks</a>
                    </li>
                    <li class="active">Manage Mp3</li>
                </ul><!-- /.breadcrumb -->
                <!-- /section:basics/content.searchbox -->
            </div>

            <br>
            <br>
            <center><h1>Track not Found</h1></center>

            

        @endsection
