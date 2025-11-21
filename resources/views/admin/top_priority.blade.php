@extends('admin.admin_dashboard_active_sidebar')
    @section('content')
<div class="main-content">

<div class="main-content-inner">

<!-- #section:basics/content.breadcrumbs -->

<div class="breadcrumbs" id="breadcrumbs">

    <script type="text/javascript">

        try{ace.settings.check('breadcrumbs' , 'fixed')}catch(e){}

    </script>



    <ul class="breadcrumb">

        <li class="active">

            <i class="ace-icon fa fa-list list-icon"></i>

            Tracks

        </li>

    </ul><!-- /.breadcrumb -->

</div>



<!-- /section:basics/content.breadcrumbs -->

<div class="page-content">

    <div class="row">

        <div class="col-xs-12">

            <!-- PAGE CONTENT BEGINS -->

            <div class="row">

                <div class="col-xs-12">

                

                

                <?php 

                    if(isset($alert_message))

                    {

                    ?>

                    

                    

                    <div class="<?php echo $alert_class; ?>"><?php echo $alert_message; ?></div>

                    <?php 

                    

                    }

                

                

                ?>

    

                    <table id="sample-table-1" class="table table-striped table-bordered table-hover draggable-table">

                        <thead>

                            <tr>

                                <th class="center" width="60">

                                    S. No.

                                </th>

                                <th>Artwork</th>

                                <th class="hidden-xs">Artist</th>

                                <th class="hidden-xs">Title</th>

                                <th class="hidden-xs">Album</th>

                                <th>User Type</th>
                                <th>Submitted By</th>

                                <!--<th class="hidden-md hidden-sm hidden-xs">Label</th>

                                <th class="hidden-md hidden-sm hidden-xs">Time</th>

                                --><th class="hidden-md hidden-sm hidden-xs">Added On</th>
                                
                                <th class="hidden-xs">Delete button</th>



                                

                            </tr>

                        </thead>



                        <tbody>

                        

                        <?php 

                            if(!empty($start ))  {

                                $start = $start ;
                            } 
                            else{
                                $start = 0;
                            }

                          $i = $start+1;
                    
                    
                    foreach($tracks as $track)

                    {

                    

                    ?>

                            <tr id="<?php echo $track->id; ?>">

                                <td class="center">

                                <?php echo $i; ?>									

                                </td>



                                <td>

                                    <?php
                                    
                                    //echo $track->product_name;

                                    if(!empty($track->pCloudFileID)){
                                        $artWork=url('/pCloudImgDownload.php?fileID='.$track->pCloudFileID);?>
                                        <img src="<?php echo $artWork; ?>" width="50" height="56" />
                               <?php }

                                   else  if(!empty($track->imgpage)){
                                        $artWork = asset('ImagesUp/'.$track->imgpage); ?>
                                        <img src="<?php echo $artWork; ?>" width="50" height="56" />
                                        <?php
                                    }

                                    ?>

                                    

                                </td>

                                <td class="hidden-xs"><?php echo urldecode($track->artist);  ?></td>

                                <td class="hidden-xs"><?php echo urldecode($track->title); ?></td>

                                

                                <td class="hidden-xs">

                                    <?php echo ucfirst(urldecode($track->album));  ?>

                                </td>
                                <td><?php if(!empty($track->name)){echo "CLIENT";} if(!empty($track->fname)){echo "MEMBER";}?></td>
                                <td><?php if(!empty($track->name)){echo urldecode($track->name);} if(!empty($track->fname)){echo urldecode($track->fname);}?></td>

                                

                                <?php // echo urldecode($track->label);  ?>

                                <?php  // echo ucfirst(urldecode($track->time)); ?>

                                

                                

                                <td class="hidden-md hidden-sm hidden-xs"><?php $addedOn = $track->added;

                                            $addedOn = explode(' ',$track->added);

                                            $addedDate =  explode('-',$addedOn[0]);

                                            $addedDate = $addedDate[2].'-'.$addedDate[1].'-'.$addedDate[0];

                                            echo $addedDate;

                                

                                

                                    ?></td>
                                    
                                    <td>
                                        <a title="Delete Track" href="<?php echo url("admin/top_priority")."?remove_priority=".$track->id; ?>" class="btn btn-xs btn-danger">
                                            <i class="ace-icon fa fa-trash-o bigger-120"></i>
                                        </a>
                                    </td>

                            </tr>

                            

                            <?php $i++; } 

                            
                                if(!empty($numPages)){

                                    $numPages = $numPages;
                                }
                                else{

                                    $numPages = 0;
                                }
                            
                            
                            
                            if($numPages>1) { ?>

    

        <tr>

            <td colspan="10">

                                <ul class="pager pager-rounded" style="float:right; margin-bottom:10px; margin-right:10px;">

            <li class="<?php echo $firstPageLink; ?>"><a href="javascript:void()" onclick="goToPage1('<?php echo $currentPage.$link_string; ?>','1')"> << </a></li>

            <li class="<?php echo $preLink; ?>"><a href="javascript:void()" onclick="goToPage1('<?php echo $currentPage.$link_string; ?>','<?php echo $currentPageNo-1; ?>')"> < </a></li>

            <li> &nbsp; page <?php echo $currentPageNo; ?> of <?php echo $numPages; ?> &nbsp;  </li>

            <li class="<?php echo $nextLink; ?>"><a href="javascript:void()" onclick="goToPage1('<?php echo $currentPage.$link_string; ?>','<?php echo $currentPageNo+1; ?>')"> > </a></li>

            <li class="<?php echo $lastPageLink; ?>"><a href="javascript:void()" onclick="goToPage1('<?php echo $currentPage.$link_string; ?>','<?php echo $numPages; ?>')">>></a></li>

            </ul>

            </td>

        </tr>

        <?php } ?>

                </tbody>

                    </table>

                </div><!-- /.span -->

            </div><!-- /.row -->



            <div class="hr hr-18 dotted hr-double"></div>



        

            <!-- PAGE CONTENT ENDS -->

        </div><!-- /.col -->

    </div><!-- /.row -->

</div><!-- /.page-content -->


@endsection 