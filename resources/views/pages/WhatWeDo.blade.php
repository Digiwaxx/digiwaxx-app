@extends('layouts.app')

@section('content')


 <section class="content-area why-bg-login">
     <div class="container">      
      <div class="member-area">
        <?php echo stripslashes(urldecode($content[0]->page_content)); ?>
        
      </div> 
     </div> 
       
   </section>


<!-- <div class="digi-radio-block" style="height:1000px;">
	   
	<div class="col-sm-offset-2 col-sm-8  page_content">
	  <p>&nbsp;</p>

		<h1>&nbsp;</h1>

		<h1><strong>WHAT WE DO?</strong></h1>

		<p>&nbsp;</p>

		<p><span style="font-size:20px">The DJ has always influenced culture. From EDM, Rock, Country, Afrobeats, Drum'N Bass, Hip-Hop, R&amp;B, Reggae or Funk music,&nbsp; DJ captures the hearts, minds, and bodies of the masses.</span></p>

		<p><span style="font-size:20px">Thus, building a network of DJs worldwide became an integral part of Digiwaxx's culture.&nbsp;Digiwaxx Media is a premier marketing and promotions agency serving all corners of the market with an unsurpassed niche in urban lifestyle. With music at the core of this culture, Digiwaxx is situated where things really happen now: at the intersection of recording studios, radio stations, DJ booths, concert venues and online sites where creative innovators, cultural influencers, entrepreneurs and fans converge.&nbsp;<br>
		<br>
		We are centered on a network of tight-knit local relationships with a global platform. Our ability to activate and integrate these dynamic relationships with agility, efficiency, and credibility knows no equal in the industry. Highly revered as a leader in the industry, Digiwaxx converts years of experience at the forefront of urban culture and lifestyle into a dynamic hub of information and resources. Our diverse platforms and services provide custom solutions that connect music, technology, people and culture in a way that is unique to the Digiwaxx experience.&nbsp;</span></p>
			</div>      		

</div> -->
@endsection