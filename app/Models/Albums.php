<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Albums extends Model
{
    protected $table = 'tracks_album';
	
	
    protected $primaryKey = 'id';

	
    protected $fillable = ['title', 'album_page_image', 'pCloudFileID_album', 'pCloudParentFolderID_album', 'created_at', 'updated_at'];
	

    protected $hidden = [];

}
