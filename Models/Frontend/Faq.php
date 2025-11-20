<?php

namespace App\Models\Frontend;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use Illuminate\Support\Facades\DB;

class Faq extends Model
{
    use HasFactory;
	//protected $table="faqs";
	
	public static function getFaqs(){
		
		$queryRes = DB::select("SELECT * FROM faqs where status = '1' order by faq_id desc");

        $result['numRows'] = count($queryRes);

		$result['data']  = $queryRes;

		return $result;
	} 
	
}
