<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Tracks extends Model
{
    protected $table = 'tracks';
	
	
    protected $primaryKey = 'id';

	
    protected $fillable = ['artist', 'title', 'contact_email', 'contact_phone', 'created_at', 'updated_at'];
	

    protected $hidden = [];

}
