<?php
// echo "<pre>";
// print_r($get_article);
// echo '</pre>';
// die();
?>
@extends('admin.admin_dashboard_active_sidebar')
@section('content')
<div class="main-content">
    <div class="main-content-inner">

        <!-- #section:basics/content.breadcrumbs -->
        <div class="breadcrumbs" id="breadcrumbs">
            <script type="text/javascript">
                try {
                    ace.settings.check('breadcrumbs', 'fixed')
                } catch (e) {}
            </script>



            <ul class="breadcrumb">
                <li class="active"><i class="ace-icon fa fa-list list-icon"></i> FORUM-ARTICLES</li>
            </ul>


            <div class="nav-search" id="nav-search">
                <form class="form-search">
                    <label class="hidden-md hidden-sm hidden-xs"> No. Records</label>
                    <span class="input-icon">
                        <select class="nav-search-input" id="numRecords" onchange="get_selected_data()">
                           
                            <option <?php if ($numRecords == 10) { ?> selected="selected" <?php } ?> value="10">10
                            </option>
                            <option <?php if ($numRecords == 30) { ?> selected="selected" <?php } ?> value="30">30
                            </option>
                            <option <?php if ($numRecords == 50) { ?> selected="selected" <?php } ?> value="50">50
                            </option>
                            <option <?php if ($numRecords == 100) { ?> selected="selected" <?php } ?> value="100">100
                            </option>
                            <option <?php if ($numRecords == 500) { ?> selected="selected" <?php } ?> value="500">500
                            </option>
                        </select>
                    </span>
                </form>
            </div>

        </div>

        <div class="page-content">

            <!-- PAGE CONTENT BEGINS -->
            <div class="row">
                <div class="col-xs-12">

                    <div class="table-responsive">
                        <table id="sample-table-1" class="table table-striped table-bordered table-hover">
                            <thead>
                                <tr>
                                    <th class="center" width="100">
                                        S. No.
                                    </th>
                                    <th>Title</th>
                                    <!--th>Description</th-->
                                    <th>Published By</th>
                                    <th>User Type</th>
                                    <th>Status</th>
                                    <th>Added</th>
                                    <th>Views</th>
                                    <th width="160">Action</th>
                                    <?php $count_announ = 1; ?>
                                </tr>
                            </thead>
                            <tbody>
                                <input type="hidden" name="_token" id="csrf-token-announ" value="{{ Session::token() }}" />
                                <?php //echo '<pre>'; print_r($get_news); die; 
                                foreach ($get_article as $value) {
                                ?>
                                    <tr>
                                        <td><?php echo $count_announ;
                                            $count_announ++; ?></td>
                                        <td><?php echo $value['art_title']; ?> </td>
                                        <!--td><?php //echo $value['art_desc']; ?></td-->
                                        <td><?php echo urldecode($value['fname']); if(!empty($value['email'])){echo " (".urldecode($value['email']).")";}?></td>
                                        <td><?php if(urldecode($value['created_user_type']==2)){
                                                        echo "MEMBER";
                                                    }
                                                     if(urldecode($value['created_user_type']==3)){
                                                        echo "ARTIST-PROMOTER";
                                                    }
                                         ?>
                                        </td>
                                        <td><?php if ($value['art_status'] == 1) {
                                                echo 'Approved';
                                            } else {
                                                echo 'Disapproved';
                                            } ?></td>
                                        <td><?php echo $value['art_created_at']; ?></td>
                                        <td><?php echo $value['art_views']; ?> &nbsp <i class="fa fa-eye"></i></td>
                                        <td><div class="btn-group">
                                             <a href="{{route("view_forum",['id'=>$value['art_id']])}}" title="Preview" class="btn btn-xs btn-success">
                                            <i class="ace-icon fa fa-eye bigger-120"></i>
                                            </a>
                                            <a href="{{route("edit_forum",['id'=>$value['art_id']])}}" title="Edit" class="btn btn-xs btn-info">
                                            <i class="ace-icon fa fa-pencil bigger-120"></i>
                                            </a>
                                            <?php
                                            if ($value['art_status'] == 0) {
                                                $route_app = route('approve_forum');
                                            ?>
                                                <input type="hidden" id="appr_url" name="approve_url" value="<?php echo $route_app; ?>">
                                            <?php echo '<button title="Enable" onclick="approveannoun(\'' . $value['art_id'] . '\')" class="btn btn-xs btn-success">';
                                                echo '<i class="ace-icon fa fa-check bigger-120"></i>';
                                                echo '</button>';
                                            } else {

                                                $route_dis = route('forum_disable');

                                            ?>
                                                <input type="hidden" id="dis_url" name="dis_url" value="<?php echo $route_dis; ?>">


                                            <?php echo '<button title="Disable" onclick="disableannoun(\'' . $value['art_id'] . '\')" class="btn btn-xs btn-warning">';
                                                echo '<i class="ace-icon fa fa-times bigger-120"></i>';
                                                echo '</button>';
                                            }
                                            ?>
                                            <?php $route_del = route('forum_delete'); ?>
                                            <input type="hidden" id="del_url" name="del_url" value="<?php echo $route_del; ?>">
                                            <?php echo '<button title="Delete" onclick="deleteannoun(\'' . $value['art_id'] . '\')" class="btn btn-xs btn-danger">'; ?>
                                            <i class="ace-icon fa fa-trash-o bigger-120"></i>
                                            </button>
                                            
                                            <?php if(!empty($value['comment_count'])){?>
                                            <a href="{{route("list_comment",['id'=>$value['art_id']])}}" title="Comments" class="btn btn-xs btn-info">
                                            <i class='fa fa-comments-o'></i>
                                            </a>
                                            <?php }?>
                                            
                                            
                                        </div>    
                                        </td>


                                    </tr>
                                <?php } ?>
                                <tr>
                                    <td colspan="9">
                                        <?php //dd($firstPageLink) 
                                        ?>
                                        <ul class="pager pager-rounded" style="float:right; margin-bottom:10px; margin-right:10px;">
                                            <li class="<?php echo $firstPageLink; ?>"><a href="javascript:void(0)" onclick="goToPage('<?php echo $currentPage; ?>','1')">
                                                    << </a>
                                            </li>
                                            <li class="<?php echo $preLink; ?>"><a href="javascript:void(0)" onclick="goToPage('<?php echo $currentPage; ?>','<?php echo $currentPageNo - 1; ?>')">
                                                    < </a>
                                            </li>
                                            <li> &nbsp; page <?php echo $currentPageNo; ?> of <?php echo $numPages; ?>
                                                &nbsp; </li>
                                            <li class="<?php echo $nextLink; ?>"><a href="javascript:void(0)" onclick="goToPage('<?php echo $currentPage; ?>','<?php echo $currentPageNo + 1; ?>')">
                                                    > </a></li>
                                            <li class="<?php echo $lastPageLink; ?>"><a href="javascript:void(0)" onclick="goToPage('<?php echo $currentPage; ?>','<?php echo $numPages; ?>')">>></a>
                                            </li>
                                        </ul>


                                    </td>
                                </tr>

                            </tbody>
                        </table>
                    </div>

                </div>

            </div>

        </div>
    </div>

<script>
    // no of Records            
    function get_selected_data() {
        let searchParams = new URLSearchParams(window.location.search);
        if (searchParams.has('page')) {
            var page = searchParams.get('page');
        }

        var numRecords = document.getElementById('numRecords').value;
        window.location = "forums?page=" + page + "&numRecords=" + numRecords;
    }

    // enable forum

    function approveannoun(id) {

        // alert('Do you really want to delete this list');
        var CSRF_TOKEN = $('#csrf-token-announ').val();
        var get_url = $('#appr_url').val();
        $.ajax({

            url: get_url,
            type: 'POST',

            data: {
                _token: CSRF_TOKEN,
                id: id
            },
            dataType: 'JSON',

            success: function(data) {
                // $(".writeinfo").append(data.msg);
                if (data == 'success') {
                    location.reload();
                }
            }
        });

    }

    //// disable forum    

    function disableannoun(id) {
        var CSRF_TOKEN = $('#csrf-token-announ').val();
        var get_url = $('#dis_url').val();
        $.ajax({

            url: get_url,
            type: 'POST',

            data: {
                _token: CSRF_TOKEN,
                id: id
            },
            dataType: 'JSON',

            success: function(data) {

                if (data == 'success') {
                    location.reload();
                }
            }
        });
    }

    //function delete anoun

    function deleteannoun(id) {
        if(confirm("Are you sure you want to delete this?")){
        var CSRF_TOKEN = $('#csrf-token-announ').val();
        var get_url = $("#del_url").val();
        $.ajax({

            url: get_url,
            type: 'POST',

            data: {
                _token: CSRF_TOKEN,
                id: id
            },
            dataType: 'JSON',

            success: function(data) {
                // $(".writeinfo").append(data.msg);
                if (data == 'success') {
                    location.reload();
                }
            }
        });
       }else{
        return false;
       }
    }
</script>
@endsection