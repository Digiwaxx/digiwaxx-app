

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
							<li>
								<a href="#"><i class="ace-icon fa fa-list list-icon"></i> Products</a>
							</li>
                       	    <li class="active">Product Review Report</li>
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
									if($questions['numRows']>0) {
									$i = 1; $graphCount = 1;
								foreach($questions['data'] as $question)
								{
								
								
								?><span style="text-anchor: start; font-family: Arial; font-size: 14;
    font-weight: bold;
    stroke: none;
    stroke-width: 0;
    color: rgb(76, 143, 189);"><?php echo $i.'. '.$question->question; ?></span>
	
	<?php
								
								
								
								
								
								 if(strcmp($question->type,'text')==0)
								 {
									 if($textData[$question->question_id]['numRows']>0)
									 { $a = 1;
									  foreach($textData[$question->question_id]['data'] as $text)
									   {
			if(strlen($text->stagename)>0) { $tmpName = urldecode($text->stagename); } else { $tmpName = urldecode($text->fname); } ?>
			<span><?php echo $a.'. '; ?><a href="javascript:void()" onclick="openMember('<?php echo $text->member_id; ?>')"><?php echo $tmpName; ?></a> <?php echo $text->city . ', ' . $text->state; ?></span><br />
			<span style="color:#FF0000;"><?php echo $text->answer; ?></span><br><br>
									
									<?php
									   
								$a++;  }
								    }
								 }
								 else
								 {
								 
								 
								 
								 
								 if($graphData[$question->question_id]['numRows']>0)
									 { $a = 1; $x = 1;
									  foreach($graphData[$question->question_id]['data'] as $data)
									   {
									
								  
                                        if(!empty($answersData[$data->answer_id]['data'])){

                                            $firstGraph[$graphCount][$x] = $answersData[$data->answer_id]['data'][0]->anscount;
                                            $firstGraphAvg[$graphCount][$x] = $data->answer;
                                            $x++;


                                        }
                                      
								    }
								  }
								  
								  ?> 
								  
								  <?php if(!empty($firstGraphAvg)){ ?>
								  <div id="graphDiv<?php echo $graphCount; ?>" style="width: 100%; height:400px;"></div>
						    	<?php } else{?>


									<div id="graphDiv<?php echo $graphCount; ?>">No Record found.</div>

								<?php } ?>
								  
								  <?php
								  	$graphCount++;					  
								 }
								
								$i++;
								}
								}
								else
								{
								
								 echo 'No Data found.';
								}
									?>
									
										
									</div><!-- /.span -->
								</div><!-- /.row -->

								<div class="hr hr-18 dotted hr-double"></div>

							
								<!-- PAGE CONTENT ENDS -->
							</div><!-- /.col -->
						</div><!-- /.row -->
					</div><!-- /.page-content -->
					
					<!-- Modal -->
<div id="myModal" class="modal fade" role="dialog">
  <div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Member Information</h4>
      </div>
      <div class="modal-body" id="member_display">
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
      </div>
    </div>

  </div>
</div>
					
<script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
	
    <script type="text/javascript">
	
	function openMember(mid)
	{
	 	$.ajax({url: "product_review_report?mid="+mid, success: function(result){
							document.getElementById('member_display').innerHTML = result;
							$('#myModal').modal('show'); 
                            }});
	}
	
       google.charts.load("current", {packages:["corechart"]});
	   
	   
	   <?php
	if(!empty($firstGraphAvg))  {
	   for($i=1;$i<$graphCount;$i++)
	   { 
	   
	   $count = count($firstGraphAvg[$i]);
	   
	   ?>
       
	    google.charts.setOnLoadCallback(drawChart<?php echo $i; ?>);
	  
	   
	     function drawChart<?php echo $i; ?>() {
        var data = google.visualization.arrayToDataTable([
          ['Task', 'Hours per Day'],
		  
		  
		  <?php for($a=1;$a<=$count;$a++) { 
		  // echo $firstGraph[$i][$a].' %'.' | '.
		  if($firstGraph[$i][$a]<1)	  { $firstGraph[$i][$a] = 1; }
		  ?>
          [' <?php echo $firstGraphAvg[$i][$a]; ?>', <?php echo $firstGraph[$i][$a]; ?>]
		  
		  <?php if($a!=$count) { echo ','; }  } ?>
        ]);

        var options = {
          title: '<?php // echo $label[$i]; ?>',
          is3D: true,
		   backgroundColor: 'none',
		  titleTextStyle: { color: '#4c8fbd' },
		  legendTextStyle: { color: '#4c8fbd' },
		  sliceVisibilityThreshold: .001,
        };

        var chart = new google.visualization.PieChart(document.getElementById('graphDiv<?php echo $i; ?>'));
		// console.log(data);
		if(data != '')  {
        chart.draw(data, options);
		}
      }
	  <?php } 
	  
	 } ?>
	  </script>

@endsection 