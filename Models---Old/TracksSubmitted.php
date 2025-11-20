<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class TracksSubmitted extends Model
{
    protected $table = 'tracks_submitted';
	
	
    protected $primaryKey = 'id';

	
    protected $fillable = ['artist','title', 'imgpage', 'pCloudFileID', 'pCloudParentFolderID', 'created_at', 'updated_at'];
	

    protected $hidden = [];

}
