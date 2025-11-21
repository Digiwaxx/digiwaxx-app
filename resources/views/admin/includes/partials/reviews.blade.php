@if ($dashboard_weekly_tracks_reviews->count())
    @foreach ($dashboard_weekly_tracks_reviews as $weekly_reviews)
        <div class="itemdiv dialogdiv">
            <div class="user">
                <img alt="Digiwaxx Logo" src="{{ asset('assets/img/profile-pic.png') }}" />
            </div>

            <div class="body">
                <div class="time">
                    <i class="ace-icon fa fa-clock-o"></i>
                    <span class="green">{{ \Carbon\Carbon::parse($weekly_reviews->added)->diffForHumans() }}</span>
                </div>

                <div class="text">{{ urldecode($weekly_reviews->additionalcomments) }}</div>
                <div class="name">
                    <span>DJ: </span>
                    <a target="_blank" href="{{ url('/admin/member_view?mid=' . $weekly_reviews->member) }}">
                        {{ $weekly_reviews->fname . ' ' . $weekly_reviews->lname }}
                    </a>
                    | <span>Track: </span>
                    <a target="_blank" href="{{ url('/admin/track_review?tid=' . $weekly_reviews->track) }}">
                        {{ urldecode($weekly_reviews->title) }}
                    </a>
                </div>
            </div>
        </div>
    @endforeach
@endif
