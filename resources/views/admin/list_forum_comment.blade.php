<?php 
// echo "hello";
// die();
?>
<?php
// echo "<pre>";
// print_r($get_article);
// echo '</pre>';
// die();
?>
@extends('admin.admin_dashboard_active_sidebar')
@section('content')
<?php  $array1=json_decode($comments);?>

<div class="main-content">
    <div class="main-content-inner">

        <!-- #section:basics/content.breadcrumbs -->
        <div class="breadcrumbs" id="breadcrumbs">
            <script type="text/javascript">
                try {
                    ace.settings.check('breadcrumbs', 'fixed')
                } catch (e) {}
            </script>
			<style>
			.article_list li {
				margin: 10px 0;
			}
			.highlight-text{color: #52D0F8}
			</style>

            <?php
			
			$array=json_decode($article_data);
			
            foreach($array as $news_detail){
				$n_title = $news_detail->art_title;    
				$n_desc = $news_detail->art_desc;
				$n_id=$news_detail->art_id;
				$n_name=$news_detail->fname;
				$n_uname=$news_detail->uname;
				$n_email=$news_detail->email;
				$n_date=$news_detail->art_created_at;		 
			}
			
			?>
            <ul class="breadcrumb">
                <li class="active"><i class="ace-icon fa fa-comments-o list-icon"></i> Comments on <span class="highlight-text"><?= urldecode($n_title); ?></span></li>
            </ul>
            

        <?php //if($numRecords>1){?>
            <div class="nav-search" id="nav-search">
                <form class="form-search">
                    <label class="hidden-md hidden-sm hidden-xs"> No. Records</label>
                    <span class="input-icon">
                        <select class="nav-search-input" id="numRecords" onchange="get_selected_data()">
                            <option <?php if ($numRecords == 5) { ?> selected="selected" <?php } ?> value="5">5</option>
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
         <?php //}?>

        </div>
        <div class="article_data">
            <ul class="article_list" style="list-style-type:none; margin-left: 10px;">
                 <li> Published By - <span class="highlight-text"><?php echo urldecode($n_name); if(!empty($n_email)){ echo "(".urldecode($n_email).")" ;} ?> </span></li>
		    
		   
		    <li>  Published On - <span class="highlight-text"><?php echo urldecode($n_date); ?></span></li>
		    <li> Description - <?php echo urldecode($n_desc);?></li>
            </ul>
        </div>
        
 
        <div class="page-content">
         

            <!-- PAGE CONTENT BEGINS -->
            <div class="row">
                <div class="col-xs-12">
                <?php if(count($array1)!=0){?>
                    <div class="table-responsive">
                        <table id="sample-table-1" class="table table-striped table-bordered table-hover">
                            <thead>
                                <tr>
                                    <th class="center" width="100">
                                        S. No.
                                    </th>
                                    <th>Comments</th>
                                    <th>Posted By</th>
                                    <th>Posted On</th>
                                    <th>User Type</th>

                                    <th>Status</th>

                                    <th width="160">Action</th>
                                    <?php $count_announ = 1; ?>
                                </tr>
                            </thead>
                            <tbody>
                                <input type="hidden" name="_token" id="csrf-token-announ" value="{{ Session::token() }}" />
                                <input type="hidden" name="article_id" id="article_id" value="<?php echo $article_id; ?>">
                                <?php  
                                $array=json_decode($comments);
                                foreach ($array as $value) {?>
                                <tr>
                                <td><?php echo $count_announ; $count_announ++;?></td>
                                <td><?php echo urldecode($value->comment_posted);?></td> 
                                <td><?php echo urldecode($value->fname); if(!empty($value->email)){echo " (".urldecode($value->email).")" ;}?></td>
                                <td><?php echo urldecode($value->created_at)?></td>
                                <td>
                                    <?php if(urldecode($value->user_type)==2){
                                        echo "MEMBER";
                                    }
                                    if(urldecode($value->user_type)==3){
                                        echo "ARTIST/PROMOTER";
                                        
                                    }?>
                                    
                                    
                                </td>
                                <td><?php if (urldecode($value->comment_status)==0){
                                echo "Disapproved";
                                }else{
                                    echo "Approved";
                                }
                                ?></td>
                                <td><div class="btn-group">
                                      <a href="{{route("list_single_comment_admin",['id'=>$value->id])}}" title="Preview" class="btn btn-xs btn-success">
                                            <i class="ace-icon fa fa-eye bigger-120"></i>
                                            </a>
                                    <?php
                                            if ($value->comment_status == 0) {
                                                $route_app = route('comment_approve');
                                            ?>
                                                <input type="hidden" id="appr_url" name="approve_url" value="<?php echo $route_app; ?>">
                                                <input type="hidden" id="user_nameis" name="user_nameis" value="<?php echo urldecode($value->fname); ?>">
                                                <input type="hidden" id="user_emailis" name="user_emailis" value="<?php echo urldecode($value->email); ?>">
                                            <?php echo '<button title="Enable Comment" onclick="approveannoun(\'' . $value->id . '\')" class="btn btn-xs btn-success">';
                                                echo '<i class="ace-icon fa fa-check bigger-120"></i>';
                                                echo '</button>';
                                            } else {

                                                $route_dis = route('comment_disapprove');

                                            ?>
                                                <input type="hidden" id="dis_url" name="dis_url" value="<?php echo $route_dis; ?>">


                                            <?php echo '<button title="Disable Comment" onclick="disableannoun(\'' . $value->id . '\')" class="btn btn-xs btn-warning">';
                                                echo '<i class="ace-icon fa fa-times bigger-120"></i>';
                                                echo '</button>';
                                            }
                                            ?>
                                            <?php if($value->delete_status == 0){?>
                                             <?php $route_del = route('comment_delete'); ?>
                                             
                                            <input type="hidden" id="del_url" name="del_url" value="<?php echo $route_del; ?>">
                                            <?php echo '<button title="Delete" onclick="deleteannoun(\'' . $value->id . '\')" class="btn btn-xs btn-danger">'; ?>
                                            <i class="ace-icon fa fa-trash-o bigger-120"></i>
                                            
                                            </button>
                                           <?php }else{?>
                                           
                                            <?php $route_del = route('comment_undelete'); ?>
                                            <input type="hidden" id="restore_url" name="del_url" value="<?php echo $route_del; ?>">
                                            <?php echo '<button title="Restore" onclick="restore(\'' . $value->id . '\')" class="btn btn-xs btn-primary">'; ?>
                                            <i class="ace-icon fa fa-trash-restore bigger-120"> </i>
                                            
                                            </button>
                                           
                                           <?php }?>
                                            
                                </div></td>
                                </tr>
     
                           
                                <?php } ?>
                                <?php //if($numRecords>5){?>
                                    <tr>
                                        <td colspan="9">
                                            <?php //dd($firstPageLink) 
                                            ?>
                                            <ul class="pager pager-rounded" style="float:right; margin-bottom:10px; margin-right:10px;">
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
                                          
                                            </ul>
    
    
                                        </td>
                                    </tr>
                               <?php //} ?>

                            </tbody>
                        </table>
                    </div>
                    <? }else{echo "NO COMMENTS AVAILABLE";}?>
                    

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
        
        var art_id=$('#article_id').val();

        var numRecords = document.getElementById('numRecords').value;
        window.location = art_id+"?page=" + page + "&numRecords=" + numRecords;
    }
    
        function approveannoun(id) {

        // alert('Do you really want to delete this list');
        var CSRF_TOKEN = $('#csrf-token-announ').val();
        var get_url = $('#appr_url').val();
        var user_nameis = $('#user_nameis').val();
        var user_emailis = $('#user_emailis').val();
        $.ajax({

            url: get_url,
            type: 'POST',

            data: {
                _token: CSRF_TOKEN,
                id: id,
                usernameis: user_nameis,
                useremailis: user_emailis
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
    
    ///delete
    
      function deleteannoun(id) {
        alert('Do you really want to delete the comment');
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
    }
    
    
        ///restore
    
      function restore(id) {
        alert('Do you really want to restore the comment');
        var CSRF_TOKEN = $('#csrf-token-announ').val();
        var get_url = $("#restore_url").val();
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





</script>
@endsection