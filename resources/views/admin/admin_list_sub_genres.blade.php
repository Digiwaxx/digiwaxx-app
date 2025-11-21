<?php if(isset($status)) { ?><span style="color:<?php echo $textColor; ?>"><?php  echo $status; ?></span> <?php } ?>
<table id="sample-table-1" class="table table-striped table-bordered table-hover">
   <thead>
      <tr>
         <th class="center" width="100">
            S. No.
         </th>
         <th>Sub Genre</th>
         <th>Action</th>
      </tr>
   </thead>
   <tbody>
      <?php 
         $i = $start+1;
         
         if($subGenres['numRows']>0)
         {
         foreach($subGenres['data'] as $genre)
         { ?>
      <tr>
         <td class="center">
            <div class="checkbox">
               <label>
               <span class="lbl">&nbsp;&nbsp;<?php echo $i; ?></span>
               </label>
            </div>
         </td>
         <td id="<?php echo 'subGenreDisplay'.$genre->subGenreId; ?>"><?php echo $genre->subGenre;  ?></td>
         <td>
            <div class="btn-group">
               <a href="javascript:void" id="editLinkId<?php echo $genre->subGenreId; ?>" onclick="editSubGenreBox('<?php echo $genre->subGenreId; ?>','<?php echo $genre->subGenre; ?>')" title="Edit Sub Genre" class="btn btn-xs btn-info">
               <i class="ace-icon fa fa-pencil bigger-120"></i>
               </a>
               <button title="Delete Sub Genre" onclick="deleteSubGenre('<?php echo $genre->subGenreId; ?>','<?php echo $genreId; ?>','Confirm delete <?php echo $genre->subGenre; ?> ')" class="btn btn-xs btn-danger">
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
                        <div class="row">
                           <form class="form-inline searchForm" id="searchForm" action="" method="get" autocomplete="off">
                              <div class="col-sm-4">
                                 <input type="text" class="nav-search-input" id="<?php echo 'editGenre'.$genre->genreId; ?>" value="<?php echo $genre->genre; ?>" />
                              </div>
                              <div class="col-sm-3">
                                 <input type="button" value="Update" onclick="editGenre('<?php echo $genre->genreId; ?>')" />
                              </div>
                           </form>
                        </div>
                        <span id="<?php echo 'genreResponse'.$genre->genreId; ?>"></span>
                        <div class="row" id="<?php echo 'subGenres'.$genre->genreId; ?>">
                        </div>
                     </div>
                     <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                     </div>
                  </div>
               </div>
            </div>
         </td>
      </tr>
      <?php $i++; } ?>
      <?php } else { ?>
      <tr>
         <td colspan="3">No Data found.</td>
      </tr>
      <?php } ?>
   </tbody>
</table>