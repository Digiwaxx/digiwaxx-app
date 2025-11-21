
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
 			<style>
			.article_list li {
				margin: 10px 0;
			}
			.highlight-text{color: #52D0F8}
			</style>
			<?php
			$array=json_decode($article_details);
            foreach($array as $news_detail){
		    $n_title = $news_detail->art_title;    
		    $n_desc = $news_detail->art_desc;
		    $n_id=$news_detail->art_id;
		    $n_name=$news_detail->fname;
		    $n_uname=$news_detail->uname;
		    $n_email=$news_detail->email;
		    $n_date=$news_detail->art_created_at;
		 
		   }?>

			<ul class="breadcrumb">
				<li class="active"><i class="ace-icon fa fa-list list-icon"></i> Comments on <span class="highlight-text"><?= urldecode($n_title); ?></span></li>
			</ul>
		
		</div>
	        <div class="article_data">

            <ul class="article_list" style="list-style-type:none;margin-left: 10px;">
                 <li> Published By - <?php echo urldecode($n_name); if(!empty($n_email)){ echo "(".urldecode($n_email).")" ;} ?> </li>
		    
		   <hr>
		    <li> Published On - <?php echo urldecode($n_date); ?></li>
		    <hr>
		    <li> Description - <?php echo urldecode($n_desc);?></li>
		    
		    <?php $comment=json_decode($comment_details); ?>
		    <hr>
		    <li> Comment- <?php echo urldecode($comment[0]->comment_posted);?></li>
            </ul>
        </div>
		<!-- /.col -->
	</div>
	<!-- /.row -->
</div>
<!-- /.page-content -->
@endsection
