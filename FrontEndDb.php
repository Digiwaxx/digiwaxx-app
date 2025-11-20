<?php  
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
	
function pArr( $array ) {
	if ( ! empty( $array ) ) {
		echo '<pre>';
			print_r( $array );
		echo '</pre>';
	}
}

function curl_getFileContents($URL)
{
    $c = curl_init();
    curl_setopt($c, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($c, CURLOPT_URL, $URL);
    $contents = curl_exec($c);
    curl_close($c);

    if($contents) return $contents;
    else return FALSE;
}

/* function mail()
{
   $name = 'DigiWaxx';
   Mail::to('Cloudways@Cloudways.com')->send(new SendMailable($name));
   
   return 'Email sent Successfully';
} */

function getYoutubeEmbedUrl($url)
{
     $shortUrlRegex = '/youtu.be\/([a-zA-Z0-9_-]+)\??/i';
     $longUrlRegex = '/youtube.com\/((?:embed)|(?:watch))((?:\?v\=)|(?:\/))([a-zA-Z0-9_-]+)/i';

    if (preg_match($longUrlRegex, $url, $matches)) {
        $youtube_id = $matches[count($matches) - 1];
    }

    if (preg_match($shortUrlRegex, $url, $matches)) {
        $youtube_id = $matches[count($matches) - 1];
    }
    return 'https://www.youtube.com/embed/' . $youtube_id ;
}