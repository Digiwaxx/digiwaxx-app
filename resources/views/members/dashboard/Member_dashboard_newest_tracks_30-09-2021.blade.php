@extends('layouts.member_dashboard')

@section('content')
	<section class="main-dash">
		@include('layouts.include.sidebar-left')
	 <div class="dash-container">
       <div class="container">
         <div class="row">
           <div class="col-12">
            <div class="dash-heading">
                <h2>MY DASHBOARD</h2>
              </div>
              <div class="tabs-section">
                <ul class="nav nav-tabs" id="myTab" role="tablist">
                  <li class="nav-item" role="presentation">
                    <button class="nav-link active" id="new-tracks-tab" data-bs-toggle="tab" data-bs-target="#new-tracks" type="button" role="tab" aria-controls="new-tracks" aria-selected="true">Newest Tracks</button>
                  </li>
                  <li class="nav-item" role="presentation">
                    <button class="nav-link" id="all-tracks-tab" data-bs-toggle="tab" data-bs-target="#all-tracks" type="button" role="tab" aria-controls="all-tracks" aria-selected="false">All Tracks</button>
                  </li>
                  <li class="nav-item" role="presentation">
                    <button class="nav-link" id="priority-tracks-tab" data-bs-toggle="tab" data-bs-target="#priority-tracks" type="button" role="tab" aria-controls="priority-tracks" aria-selected="false">Priority Tracks</button>
                  </li>
                  <li class="nav-item" role="presentation">
                    <button class="nav-link" id="top-streaming-tab" data-bs-toggle="tab" data-bs-target="#top-streaming" type="button" role="tab" aria-controls="top-streaming" aria-selected="false">Top Streaming</button>
                  </li>
                </ul>
                <div class="tab-content" id="myTabContent">
                  <div class="tab-top-sec">
                    <div class="search-list">
                      <div class="input-group">
                          <input type="search" class="form-control rounded" placeholder="Search" aria-label="Search"
                            aria-describedby="search-addon" />
                          <button type="button" class="btn btn-theme btn-gradient">search</button>
                        </div>
                    </div>
                    <div class="list-pagination">
                      <nav aria-label="Page navigation">
                        <ul class="pagination">
                          <li class="page-item">
                            <a class="page-link" href="#" aria-label="Previous">
                              <span aria-hidden="true">&laquo;</span>
                              <span class="sr-only">Previous</span>
                            </a>
                          </li>
                          <li class="page-item"><a class="page-link" href="#">1</a></li>                          
                          <li class="page-item">
                            <a class="page-link" href="#" aria-label="Next">
                              <span aria-hidden="true">&raquo;</span>
                              <span class="sr-only">Next</span>
                            </a>
                          </li>
                        </ul>
                      </nav>
                    </div>
                  </div>
                  <div class="tab-pane fade show active" id="new-tracks" role="tabpanel" aria-labelledby="new-tracks-tab">
                    <!---tracklist--->
                    <div class="track-list">
                      <div class="tack-img"><a href="#"><img src="{{ asset('public/images/track-img.jpg') }}" alt="track" class="img-fluid"> </a></div>
                      <div class="track-details">
                        <h5 class="track-name">No Rookie <span class="badge badge-custom">Priority</span></h5> 
                        <div class="track-info">
                          <ul>
                            <li>Artist: Priceless Scott</li>
                            <li> Album: No Rookie (Single) </li>
                            <li>Producer: Illeagle Ent.</li>
                            
                          </ul>
                        </div>
                        <div class="track-info-2">
                          <ul>
                            <li>16 Digital Llc</li>
                            <li>04/01/2021</li>
                          </ul>
                          <div class="track-download">
                            <a href="#"><i class="fas fa-cloud-download-alt"></i>  LEAVE REVIEW TO UNLOCK DOWNLOAD</a>
                          </div>
                        </div>
                      </div>
                    </div>
                    <!---tracklist--->
                    <div class="track-list">
                      <div class="tack-img"><a href="#"><img src="{{ asset('public/images/track-img.jpg') }}" alt="track" class="img-fluid"> </a></div>
                      <div class="track-details">
                        <h5 class="track-name">No Rookie <span class="badge badge-custom">Priority</span></h5> 
                        <div class="track-info">
                          <ul>
                            <li>Artist: Priceless Scott</li>
                            <li> Album: No Rookie (Single) </li>
                            <li>Producer: Illeagle Ent.</li>
                            
                          </ul>
                        </div>
                        <div class="track-info-2">
                          <ul>
                            <li>16 Digital Llc</li>
                            <li>04/01/2021</li>
                          </ul>
                          <div class="track-download">
                            <a href="#"><i class="fas fa-cloud-download-alt"></i>  LEAVE REVIEW TO UNLOCK DOWNLOAD</a>
                          </div>
                        </div>
                      </div>
                    </div>
                    <!---tracklist--->
                    <div class="track-list">
                      <div class="tack-img"><a href="#"><img src="{{ asset('public/images/track-img.jpg') }}" alt="track" class="img-fluid"> </a></div>
                      <div class="track-details">
                        <h5 class="track-name">No Rookie <span class="badge badge-custom">Priority</span></h5> 
                        <div class="track-info">
                          <ul>
                            <li>Artist: Priceless Scott</li>
                            <li> Album: No Rookie (Single) </li>
                            <li>Producer: Illeagle Ent.</li>
                            
                          </ul>
                        </div>
                        <div class="track-info-2">
                          <ul>
                            <li>16 Digital Llc</li>
                            <li>04/01/2021</li>
                          </ul>
                          <div class="track-download">
                            <a href="#"><i class="fas fa-cloud-download-alt"></i>  LEAVE REVIEW TO UNLOCK DOWNLOAD</a>
                          </div>
                        </div>
                      </div>
                    </div>
                    <!---tracklist--->
                    <div class="track-list">
                      <div class="tack-img"><a href="#"><img src="{{ asset('public/images/track-img.jpg') }}" alt="track" class="img-fluid"> </a></div>
                      <div class="track-details">
                        <h5 class="track-name">No Rookie <span class="badge badge-custom">Priority</span></h5> 
                        <div class="track-info">
                          <ul>
                            <li>Artist: Priceless Scott</li>
                            <li> Album: No Rookie (Single) </li>
                            <li>Producer: Illeagle Ent.</li>
                            
                          </ul>
                        </div>
                        <div class="track-info-2">
                          <ul>
                            <li>16 Digital Llc</li>
                            <li>04/01/2021</li>
                          </ul>
                          <div class="track-download">
                            <a href="#"><i class="fas fa-cloud-download-alt"></i>  LEAVE REVIEW TO UNLOCK DOWNLOAD</a>
                          </div>
                        </div>
                      </div>
                    </div>
                    <!---tracklist--->
                    <div class="track-list">
                      <div class="tack-img"><a href="#"><img src="{{ asset('public/images/track-img.jpg') }}" alt="track" class="img-fluid"> </a></div>
                      <div class="track-details">
                        <h5 class="track-name">No Rookie <span class="badge badge-custom">Priority</span></h5> 
                        <div class="track-info">
                          <ul>
                            <li>Artist: Priceless Scott</li>
                            <li> Album: No Rookie (Single) </li>
                            <li>Producer: Illeagle Ent.</li>
                            
                          </ul>
                        </div>
                        <div class="track-info-2">
                          <ul>
                            <li>16 Digital Llc</li>
                            <li>04/01/2021</li>
                          </ul>
                          <div class="track-download">
                            <a href="#"><i class="fas fa-cloud-download-alt"></i>  LEAVE REVIEW TO UNLOCK DOWNLOAD</a>
                          </div>
                        </div>
                      </div>
                    </div>

                  </div>
                  <div class="tab-pane fade" id="all-tracks" role="tabpanel" aria-labelledby="all-tracks-tab">content2</div>
                  <div class="tab-pane fade" id="priority-tracks" role="tabpanel" aria-labelledby="priority-tracks-tab">content3</div>
                  <div class="tab-pane fade" id="top-streaming" role="tabpanel" aria-labelledby="top-streaming-tab">content3</div>
                </div>

              </div>
              <!---tab section end--->
              <!--album-download-->
              <div class="album-d-sec">
                <div class="heading-border">
                  <h4>STAFF PICKS</h4>
                </div>
                
                <div class="row">
                  <div class="col-md-5-cols col-sm-6 col-12">
                    <!--download tracks-->
                    <div class="album-download-box">
                      <div class="album-img">
                        <img src="{{ asset('public/images/album9.jpg') }}" class="img-fluid" alt="album">
                      </div>
                      <div class="download-detail">
                        <h3>Say less (Tikk</h3>
                        <h5>syce wavy</h5>
                        <div class="download-btn">
                        <a href="#"><i class="fas fa-cloud-download-alt"></i></a>
                      </div>
                      </div>                      
                    </div>
                  </div>
                  <div class="col-md-5-cols col-sm-6 col-12">
                    <!--download tracks-->
                    <div class="album-download-box">
                      <div class="album-img">
                        <img src="{{ asset('public/images/album9.jpg') }}" class="img-fluid" alt="album">
                      </div>
                      <div class="download-detail">
                        <h3>Say less (Tikk</h3>
                        <h5>syce wavy</h5>
                        <div class="download-btn">
                        <a href="#"><i class="fas fa-cloud-download-alt"></i></a>
                      </div>
                      </div>                      
                    </div>
                  </div>
                  <div class="col-md-5-cols col-sm-6 col-12">
                    <!--download tracks-->
                    <div class="album-download-box">
                      <div class="album-img">
                        <img src="{{ asset('public/images/album9.jpg') }}" class="img-fluid" alt="album">
                      </div>
                      <div class="download-detail">
                        <h3>Say less (Tikk</h3>
                        <h5>syce wavy</h5>
                        <div class="download-btn">
                        <a href="#"><i class="fas fa-cloud-download-alt"></i></a>
                      </div>
                      </div>                      
                    </div>
                  </div>
                  <div class="col-md-5-cols col-sm-6 col-12">
                    <!--download tracks-->
                    <div class="album-download-box">
                      <div class="album-img">
                        <img src="{{ asset('public/images/album9.jpg') }}" class="img-fluid" alt="album">
                      </div>
                      <div class="download-detail">
                        <h3>Say less (Tikk</h3>
                        <h5>syce wavy</h5>
                        <div class="download-btn">
                        <a href="#"><i class="fas fa-cloud-download-alt"></i></a>
                      </div>
                      </div>                      
                    </div>
                  </div>
                  <div class="col-md-5-cols col-sm-6 col-12">
                    <!--download tracks-->
                    <div class="album-download-box">
                      <div class="album-img">
                        <img src="{{ asset('public/images/album9.jpg') }}" class="img-fluid" alt="album">
                      </div>
                      <div class="download-detail empty-heading">
                        <!-- <h3>Say less (Tikk</h3> -->
                        <h5>syce wavy</h5>
                        <div class="download-btn">
                        <a href="#"><i class="fas fa-cloud-download-alt"></i></a>
                      </div>
                      </div>                      
                    </div>
                  </div>

                </div>
                <div class="album-d-more">
                  <a href="#">See more</a>
                </div>
              </div>
              <!--album-download-->
              <div class="album-d-sec">
                <div class="heading-border">
                  <h4>Selected For You</h4>
                </div>
                
                <div class="row">
                  <div class="col-md-5-cols col-sm-6 col-12">
                    <!--download tracks-->
                    <div class="album-download-box">
                      <div class="album-img">
                        <img src="{{ asset('public/images/album9.jpg') }}" class="img-fluid" alt="album">
                      </div>
                      <div class="download-detail">
                        <h3>Say less (Tikk</h3>
                        <h5>syce wavy</h5>
                        <div class="download-btn">
                        <a href="#"><i class="fas fa-cloud-download-alt"></i></a>
                      </div>
                      </div>                      
                    </div>
                  </div>
                  <div class="col-md-5-cols col-sm-6 col-12">
                    <!--download tracks-->
                    <div class="album-download-box">
                      <div class="album-img">
                        <img src="{{ asset('public/images/album9.jpg') }}" class="img-fluid" alt="album">
                      </div>
                      <div class="download-detail">
                        <h3>Say less (Tikk</h3>
                        <h5>syce wavy</h5>
                        <div class="download-btn">
                        <a href="#"><i class="fas fa-cloud-download-alt"></i></a>
                      </div>
                      </div>                      
                    </div>
                  </div>
                  <div class="col-md-5-cols col-sm-6 col-12">
                    <!--download tracks-->
                    <div class="album-download-box">
                      <div class="album-img">
                        <img src="{{ asset('public/images/album9.jpg') }}" class="img-fluid" alt="album">
                      </div>
                      <div class="download-detail">
                        <h3>Say less (Tikk</h3>
                        <h5>syce wavy</h5>
                        <div class="download-btn">
                        <a href="#"><i class="fas fa-cloud-download-alt"></i></a>
                      </div>
                      </div>                      
                    </div>
                  </div>
                  <div class="col-md-5-cols col-sm-6 col-12">
                    <!--download tracks-->
                    <div class="album-download-box">
                      <div class="album-img">
                        <img src="{{ asset('public/images/album9.jpg') }}" class="img-fluid" alt="album">
                      </div>
                      <div class="download-detail">
                        <h3>Say less (Tikk</h3>
                        <h5>syce wavy</h5>
                        <div class="download-btn">
                        <a href="#"><i class="fas fa-cloud-download-alt"></i></a>
                      </div>
                      </div>                      
                    </div>
                  </div>
                  <div class="col-md-5-cols col-sm-6 col-12">
                    <!--download tracks-->
                    <div class="album-download-box">
                      <div class="album-img">
                        <img src="{{ asset('public/images/album9.jpg') }}" class="img-fluid" alt="album">
                      </div>
                      <div class="download-detail empty-heading">
                        <!-- <h3>Say less (Tikk</h3> -->
                        <h5>syce wavy</h5>
                        <div class="download-btn">
                        <a href="#"><i class="fas fa-cloud-download-alt"></i></a>
                      </div>
                      </div>                      
                    </div>
                  </div>

                </div>
                <div class="album-d-more">
                  <a href="#">See more</a>
                </div>
              </div>
                         
           </div>
         </div>
       </div>
     </div>
	 </section>
	 
@endsection