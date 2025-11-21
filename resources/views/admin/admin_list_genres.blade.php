
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
				Genres
			</li>

		</ul><!-- /.breadcrumb -->
		<a href="javascript:void" onclick="openAddBox()" title="Add Genres" class="btn btn-xs btn-info">
		   Add Genres
		</a>
		
		
		<div class="nav-search" id="nav-search">
			<form class="form-search">
			@csrf
			<label class="hidden-md hidden-sm hidden-xs">Sort By</label>
				<span class="input-icon">
				<select class="nav-search-input" id="sortBy" onchange="get_selected_data()">
					<option <?php if(strcmp($sortBy,'genre')==0) { ?> selected="selected" <?php } ?> value="genre">Genre</option>
					<option <?php if(strcmp($sortBy,'genreId')==0) { ?> selected="selected" <?php } ?> value="genreId">Added</option>
										</select>
									
									</span>
									<label class="hidden-md hidden-sm hidden-xs">Order By</label>
									
									<span class="input-icon">
										<select class="nav-search-input" id="sortOrder" onchange="get_selected_data()">
										<option <?php if($sortOrder==1) { ?> selected="selected" <?php } ?> value="1">ASC</option>
										<option <?php if($sortOrder==2) { ?> selected="selected" <?php } ?> value="2">DESC</option>
										</select>
										
									</span>
									
								
								 <label class="hidden-md hidden-sm hidden-xs"> No. Records</label>
									<span class="input-icon">
					<select class="nav-search-input" id="numRecords" onchange="get_selected_data()">
					<option <?php if($numRecords==10) { ?> selected="selected" <?php } ?> value="10">10</option>
					<option <?php if($numRecords==30) { ?> selected="selected" <?php } ?> value="30">30</option>
					<option <?php if($numRecords==50) { ?> selected="selected" <?php } ?> value="50">50</option>
					<option <?php if($numRecords==100) { ?> selected="selected" <?php } ?> value="100">100</option>
					<option <?php if($numRecords==500) { ?> selected="selected" <?php } ?> value="500">500</option>
					</select>
				</span>
			</form>
						</div><!-- /.nav-search -->

		<!-- /section:basics/content.searchbox -->

		
	</div>
    <!-- /section:basics/content.breadcrumbs -->
    <div class="page-content">
		<div class="searchDiv">
		<form class="form-inline searchForm" id="searchForm" action="" method="get" autocomplete="off">
		  @csrf
		  <input type="hidden" name="sortBy" value="<?php echo $sortBy; ?>" />
		  <input type="hidden" name="sortOrder" value="<?php echo $sortOrder; ?>" />
		  <input type="hidden" name="numRecords" value="<?php echo $numRecords; ?>" />
		   <div class="search-filters">
			<input type="text" placeholder="Genre" class="nav-search-input" id="genre" name="genre" value="<?php (!empty($searchGenre))?$searchGenre: ''; ?>" required /> 
			<input type="submit" value="Search" name="search" />
			<input type="button" value="Reset" onclick="window.location.href='{{ route('admin_listGenres') }}'"  />
		  </div>
		</form>
		
		</div>
		<div class="space-10"></div>
      <!-- PAGE CONTENT BEGINS -->
      <div class="row">
         <div class="col-xs-12">
            <?php 
               if(isset($alert_message)) 
               { 
               
                ?>
            <div class="<?php echo $alert_class; ?>"><?php echo $alert_message; ?>
               <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
            </div>
            <?php }
               ?>
            <div class="table-responsive">
            <table id="sample-table-1" class="table table-striped table-bordered table-hover">
               <thead>
                  <tr>
                     <th width="100" class="center">
                        S. No.
                     </th>
                     <th>Genre</th>
                     <th width="120">Action</th>
                  </tr>
               </thead>
               <tbody>
                  <?php 
                     $i = $start+1;
                     
                     if($genres['numRows']>0)
                     {
                     foreach($genres['data'] as $genre)
                     {
                     
                     
                     
                     
                     ?>
                  <tr>
                     <td>
                        <div class="checkbox">
                           <label>
                           <span class="lbl">&nbsp;&nbsp;<?php echo $i; ?></span>
                           </label>
                        </div>
                     </td>
                     <td id="<?php echo 'genreDisplay'.$genre->genreId; ?>"><?php echo $genre->genre;  ?></td>
                     <td>
                        <div class="btn-group">
                           <a href="javascript:void" onclick="openViewBox('viewGenreModal','<?php echo $genre->genreId; ?>')" title="View Logo" class="btn btn-xs btn-success">
                           <i class="ace-icon fa fa-align-justify bigger-120"></i>
                           </a>
                           <a href="javascript:void" onclick="openBox('editGenreModal','<?php echo $genre->genreId; ?>')" title="Edit Logo" class="btn btn-xs btn-info">
                           <i class="ace-icon fa fa-pencil bigger-120"></i>
                           </a>
                           <button title="Delete Genre" onclick="deleteRecord('<?php echo $currentPage; ?>','<?php echo $genre->genreId; ?>','Confirm delete <?php echo urldecode($genre->genre); ?> ')" class="btn btn-xs btn-danger">
                           <i class="ace-icon fa fa-trash-o bigger-120"></i>
                           </button>
                        </div>
                        <!--edit genre box-->
                        <!-- Modal -->
                        <div id="<?php echo 'editGenreModal'.$genre->genreId; ?>" class="modal fade" role="dialog">
                           <div class="modal-dialog">
                              <!-- Modal content-->
                              <div class="modal-content">
                                 <div class="modal-header">
                                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                                    <h4 class="modal-title" id="<?php echo 'genreTittle'.$genre->genreId; ?>"><?php echo 'Edit '.$genre->genre; ?></h4>
                                 </div>
                                 <div class="modal-body">
                                   
                                       <form class="searchForm" id="searchForm" action="" method="get" autocomplete="off">
                                       	 <div class="row">
									     @csrf
                                           <div class="col-xs-12">
    														  <div class="form-group">
    														  	 <label>Genre Name</label>
                                             	 <input type="text" class="form-control w-100" id="<?php echo 'editGenre'.$genre->genreId; ?>" value="<?php echo $genre->genre; ?>" />
                                          	</div>
                                          	<div class="form-group">
	                                             <input type="button" value="Update" onclick="editGenre('<?php echo $genre->genreId; ?>')" />
	                                          </div>
	                                       </div>
	                                       </div>
                                       </form>
                                    
                                    <span id="<?php echo 'genreResponse'.$genre->genreId; ?>"></span>
                                    <div class="row" id="<?php echo 'subGenres'.$genre->genreId; ?>">
                                    </div>
                                 </div>
                                 <!-- <div class="modal-footer">
                                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                                 </div> -->
                              </div>
                           </div>
                        </div>
                        <!--edit genre box-->
                        <!-- Modal -->
                        <div id="<?php echo 'viewGenreModal'.$genre->genreId; ?>" class="modal fade" role="dialog">
                           <div class="modal-dialog">
                              <!-- Modal content-->
                              <div class="modal-content">
                                 <div class="modal-header">
                                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                                    <h4 class="modal-title" id="<?php echo 'genreTittle'.$genre->genreId; ?>"><?php echo $genre->genre; ?></h4>
                                 </div>
                                 <div class="modal-body">
                                    <div class="row">
                                    </div>
                                    <div class="row">
                                       <div class="col-xs-12" id="<?php echo 'viewSubGenres'.$genre->genreId; ?>">
                                       </div>
                                    </div>
                                 </div>
                                <!--  <div class="modal-footer">
                                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                                 </div> -->
                              </div>
                           </div>
                        </div>
                     </td>
                  </tr>
                  <?php $i++; } if($numPages>1) { ?>
                  <tr>
                     <td colspan="3">
                        <ul class="pager pager-rounded" style="margin-bottom:10px; margin-right:10px;">
                           <li class="<?php echo $firstPageLink; ?>"><a href="javascript:void()" onclick="goToPage1('<?php echo $currentPage.$link_string; ?>','1')"><i class="fa fa-angle-double-left"></i></a></li>
                           <li class="<?php echo $preLink; ?>"><a href="javascript:void()" onclick="goToPage1('<?php echo $currentPage.$link_string; ?>','<?php echo $currentPageNo-1; ?>')"> <i class="fa fa-angle-left"></i> </a></li>
                           <li> &nbsp; Page <?php echo $currentPageNo; ?> of <?php echo $numPages; ?> &nbsp;  </li>
                           <li class="<?php echo $nextLink; ?>"><a href="javascript:void()" onclick="goToPage1('<?php echo $currentPage.$link_string; ?>','<?php echo $currentPageNo+1; ?>')"> <i class="fa fa-angle-right"></i> </a></li>
                           <li class="<?php echo $lastPageLink; ?>"><a href="javascript:void()" onclick="goToPage1('<?php echo $currentPage.$link_string; ?>','<?php echo $numPages; ?>')"><i class="fa fa-angle-double-right"></i></a></li>
                        </ul>
                     </td>
                  </tr>
                  <?php } } else { ?>
                  <tr>
                     <td colspan="3">No Data found.</td>
                  </tr>
                  <?php } ?>
               </tbody>
            </table>
        	</div>
         </div>
         <!-- /.span -->
      </div>
      <!-- /.row -->
      <div class="hr hr-18 dotted hr-double"></div>
      <!--add genre box-->
      <div id="addGenreModal" class="modal fade" role="dialog">
         <div class="modal-dialog">
            <!-- Modal content-->
            <div class="modal-content">
               <div class="modal-header">
                  <button type="button" class="close" data-dismiss="modal">&times;</button>
                  <h4 class="modal-title">Add Genre</h4>
               </div>
               <div class="modal-body">
                  <div class="row">
                     <form id="searchForm" action="" method="post" autocomplete="off">
						@csrf
                        <div class="col-xs-12">
      							<div class="form-group">
      								 <label>Genre Name</label>
                           <input type="text" style="width:100%;" class="nav-search-input" placeholder="Genre" name="genre" required />
                       	</div>
                       	<div class="form-group">
                           <input type="submit" name="addGenre" value="Add Genre" class="btn btn-sm" style="border: 1px solid #6fb3e0; border-radius: 4px !important; background-color: #FFB752 !important;  border-color: #FFB752; color: #FFF; line-height: 28px;"  />
                        </div>
                     </div>
                     </form>
                  </div>
               </div>
               <div class="modal-footer">
                  <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
               </div>
            </div>
         </div>
      </div>
      <!--add genre box-->
      <!-- PAGE CONTENT ENDS -->
  
    </div><!-- /.page-content -->
	<script>

	function get_selected_data() {
		var sortBy = document.getElementById('sortBy').value;
		var sortOrder = document.getElementById('sortOrder').value;
		var numRecords = document.getElementById('numRecords').value;
		var genre = document.getElementById('genre').value;

		window.location = "genres?sortBy=" + sortBy + "&sortOrder=" + sortOrder + "&numRecords=" + numRecords + "&genre=" + genre;
	}

	function deleteSubGenre(subGenreId, genreId, message) {
		if (confirm(message)) {
			$.ajax({
				url: "genres?subDid=" + subGenreId + "&deleteSubGenre=1&genreId=" + genreId,
				success: function(result) {

					document.getElementById('subGenresList' + genreId).innerHTML = result;
					/*row = JSON.parse(result);
								var responseMessage = '';
								var responseColor =  '';
							
								if(row.response==1)
								{
								responseColor = '#090';  						
								}
								else
								{
								responseColor = '#FF0000';  						
								}
								alert(row.message);
								document.getElementById('genreResponse'+genreId).style.color = responseColor;
								document.getElementById('genreResponse'+genreId).innerHTML = row.message;	 */
				}
			});

		}

	}

	function updateSubGenre(subGenreId) {
		var subGenre = document.getElementById('editSubGenre' + subGenreId).value;
		$.ajax({
			url: "genres?subGenreId=" + subGenreId + "&updateSubGenre=1&subGenre=" + subGenre,
			success: function(result) {

				document.getElementById('subGenreDisplay' + subGenreId).innerHTML = subGenre;
				document.getElementById('editLinkId' + subGenreId).setAttribute("onclick", "editSubGenreBox('" + subGenreId + "','" + subGenre + "')");


				/*row = JSON.parse(result);
								var responseMessage = '';
								var responseColor =  '';
							
								if(row.response==1)
								{
								responseColor = '#090';  						
								//document.getElementById('genreDisplay'+id).innerHTML = genre;
								//document.getElementById('genreTittle'+id).innerHTML = genre;
								}
								else
								{
								responseColor = '#FF0000';  						
								}
								
								alert(row.message);
								*/
				//	document.getElementById('genreResponse'+id).style.color = responseColor;
				//	document.getElementById('genreResponse'+id).innerHTML = row.message;
			}
		});

	}

	function editSubGenreBox(subGenreId, subGenre) {
		document.getElementById('subGenreDisplay' + subGenreId).innerHTML = '<input type="text" value="' + subGenre + '" id="editSubGenre' + subGenreId + '" /><input type="button" value="Update" onclick="updateSubGenre(' + subGenreId + ')" class="btn btn-sm btn-primary" style="width:80px; margin-left:10px;" />';

	}

	function openViewBox(tittle, id) {
		$("#" + tittle + id).modal();

		$.ajax({
			url: "genres?id=" + id + "&subGenres=1&view=1",
			success: function(result) {
				document.getElementById('viewSubGenres' + id).innerHTML = result;
			}
		});
	}

	function openAddBox() {

		$("#addGenreModal").modal();
	}

	function openBox(tittle, id) {
		$("#" + tittle + id).modal();

		$.ajax({
			url: "genres?id=" + id + "&subGenres=1&edit=1",
			success: function(result) {
				document.getElementById('subGenres' + id).innerHTML = result;
			}
		});
	}

	function editGenre(id) {
		document.getElementById('genreResponse' + id).innerHTML = '';
		var genre = document.getElementById('editGenre' + id).value;
		$.ajax({
			url: "genres?id=" + id + "&genre=" + genre + "&updateGenre=1",
			success: function(result) {

				row = JSON.parse(result);
				var responseMessage = '';
				var responseColor = '';

				if (row.response == 1) {
					responseColor = '#090';
					document.getElementById('genreDisplay' + id).innerHTML = genre;
					document.getElementById('genreTittle' + id).innerHTML = genre;
				} else {
					responseColor = '#FF0000';
				}

				document.getElementById('genreResponse' + id).style.color = responseColor;
				document.getElementById('genreResponse' + id).innerHTML = row.message;

			}
		});
	}

	function addSubGenre(genreId) {

		var subGenre = document.getElementById('subGenre' + genreId).value;
		$.ajax({
			url: "genres?genreId=" + genreId + "&subGenre=" + subGenre + "&addSubGenre=1",
			success: function(result) {
				document.getElementById('subGenresList' + genreId).innerHTML = result;
				document.getElementById('subGenre' + genreId).value = '';
			}
		});
	}
	</script>
    @endsection       