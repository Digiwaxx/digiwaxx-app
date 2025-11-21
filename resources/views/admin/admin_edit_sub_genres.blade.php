<div class="col-xs-12" id="<?php echo 'subGenresList'.$genreId; ?>">
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
            {
            
            
            
            
            ?>
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
</div>
<form class="searchForm" autocomplete="off">
   <div class="col-xs-12 col-sm-8 col-sm-offset-2">
      <div class="form-group">
         <label>Sub Genre Name</label>
         <input type="text" class="form-control" id="<?php echo 'subGenre'.$genreId; ?>" placeholder="Sub Genre" style="width: 100%" />
      </div>
      <div class="form-group">
          <input type="button" value="Add Sub Genre" style="width:100%;" onclick="addSubGenre('<?php echo $genreId; ?>')" />
      </div>
   </div>
  
</form>