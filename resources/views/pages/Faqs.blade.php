@extends('layouts.app')

@section('content')

<section class="top-banner banner-contact">
    <div class="container">
      <div class="row">
        <div class="col-md-5 col-sm-12">
         <div class="banner-text">
            <h2>FAQ</h2>
          </div>
       </div>
      </div>
    </div>     
   </section>

<!---content-section-->
   <section class="content-area">
     <div class="container">
      <div class="faq-section">
        <div class="row">
        	<div class="col-md-8 col-sm-12 mx-auto">
				@if(count($faqs['data']) > 0)
        		<div class="accordion" id="accordionExample">
				 @php($count=1)
				 @foreach($faqs['data'] as $key => $faq)
					@if(!empty($faq->question))					
					  <div class="accordion-item {{ $count }}">
						<h2 class="accordion-header" id="heading{{ $faq->faq_id }}">
						  <button class="accordion-button @if($count !== 1) collapsed @endif" type="button" data-bs-toggle="collapse" data-bs-target="#collapse{{ $faq->faq_id }}" @if($count === 1) aria-expanded="true" @else aria-expanded="false" @endif aria-controls="collapseOne">
							{{ $faq->question }}
						  </button>
						</h2>
						<div id="collapse{{ $faq->faq_id }}" class="accordion-collapse collapse @if($count === 1) show @endif" aria-labelledby="heading{{ $faq->faq_id }}" data-bs-parent="#accordionExample">
						  <div class="accordion-body">
							<strong>{{ $faq->answer }}</strong>
						  </div>
						</div>
					  </div>
					   @php($count++)
					@endif
				  @endforeach
				</div>
				@endif
        	</div>
        	
        </div>
    </div>
</div>
</section>

@endsection