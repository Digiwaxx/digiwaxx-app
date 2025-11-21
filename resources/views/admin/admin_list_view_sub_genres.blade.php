<table id="sample-table-1" class="table table-striped table-bordered table-hover">
   <thead>
      <tr>
         <th class="center" width="100">
            S. No.
         </th>
         <th>Sub Genre</th>
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
      </tr>
      <?php $i++; } ?>
      <?php } else { ?>
      <tr>
         <td colspan="2">No Data found.</td>
      </tr>
      <?php } ?>
   </tbody>
</table>