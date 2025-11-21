<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class TrackReviewNotification extends Mailable
{
    use Queueable, SerializesModels;

    public $track;
    public $review;
    public $djName;
    public $clientName;
    public $unsubscribeToken;
    public $reportDownloadUrl;

    /**
     * Create a new message instance.
     *
     * @param  object  $track
     * @param  object  $review
     * @param  string  $djName
     * @param  string  $clientName
     * @param  string  $unsubscribeToken
     * @param  string  $reportDownloadUrl
     * @return void
     */
    public function __construct($track, $review, $djName, $clientName, $unsubscribeToken, $reportDownloadUrl)
    {
        $this->track = $track;
        $this->review = $review;
        $this->djName = $djName;
        $this->clientName = $clientName;
        $this->unsubscribeToken = $unsubscribeToken;
        $this->reportDownloadUrl = $reportDownloadUrl;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $rating = $this->review->whatrate;
        $stars = str_repeat('⭐', $rating);

        return $this
            ->subject("New {$rating}-Star Review on \"{$this->track->title}\" from DJ {$this->djName}")
            ->view('emails.track_review_notification')
            ->text('emails.track_review_notification_plain')
            ->with([
                'trackTitle' => $this->track->title,
                'trackArtist' => $this->track->artist,
                'rating' => $rating,
                'stars' => $stars,
                'comment' => $this->review->additionalcomments,
                'djName' => $this->djName,
                'clientName' => $this->clientName,
                'reviewDate' => date('F j, Y \a\t g:i A', strtotime($this->review->added)),
                'trackUrl' => url('/track/' . $this->track->id),
                'reportDownloadUrl' => $this->reportDownloadUrl,
                'unsubscribeUrl' => url('/unsubscribe-reviews/' . $this->unsubscribeToken),
            ]);
    }
}
