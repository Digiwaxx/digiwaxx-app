<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Clients\ClientRegisterController;
use App\Providers\RouteServiceProvider;
use Illuminate\Support\Facades\Artisan;
use App\Http\Controllers\AdminAddTracksController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
| php artisan config:cache
| php artisan config:clear
| php artisan cache:clear
|
*/

// SECURITY FIX: Protected cache clearing route - requires admin session
Route::get('/clear-cache', function() {
    // Check if admin is logged in
    if (!session()->has('admin_id') && !session()->has('admin_logged_in')) {
        abort(403, 'Unauthorized access. Admin login required.');
    }

    $exitCode1 = \Artisan::call('config:cache');
    $exitCode2 = \Artisan::call('config:clear');
    $exitCode3 = \Artisan::call('cache:clear');
    $exitCode4 = \Artisan::call('view:clear');
    $exitCode = \Artisan::call('optimize:clear');

    return response()->json([
        'status' => 'success',
        'message' => 'Cache cleared successfully',
        'results' => [
            'config:cache' => $exitCode1,
            'config:clear' => $exitCode2,
            'cache:clear' => $exitCode3,
            'view:clear' => $exitCode4,
            'optimize:clear' => $exitCode
        ]
    ]);
});

Route::get('/', 'App\Http\Controllers\Auth\LoginController@login')->name('home');



// Client Upload Track 
Route::get('/client_add_new_track', 'App\Http\Controllers\Clients\ClientsTrackController@client_add_new_track')->name('client_add_new_track');
Route::post('/client_add_new_track/save', 'App\Http\Controllers\Clients\ClientsTrackController@save_client_add_track_new')->name('client.save.add.track');
Route::post('/client_add_new_track/delete_duplicate', 'App\Http\Controllers\Clients\ClientsTrackController@delete_duplicate')->name('client.delete.duplicate.track');

// Client Step-2
Route::get('/manage_client_track_audios/{id}', 'App\Http\Controllers\Clients\ClientsTrackController@client_track_step2')->name('manage_client_track_audios');
Route::post('/client_add_track_mp3/save/{track_id}', 'App\Http\Controllers\Clients\ClientsTrackController@save_client_mp3_track')->name('save.client.mp3.track');

//fileupload

Route::any('client_mp3_upload/{track_id}', 'App\Http\Controllers\Clients\ClientsTrackController@upload')->name('client_mp3_upload');

// audio-stream-pcloud, remove
Route::get('audio_stream_pcloud_client/{id}', 'App\Http\Controllers\Clients\ClientsTrackController@pcloudStreamAudioUri')->name('audio_stream_pcloud_client');
Route::get('remove_track_pcloud_client/{id}/{track_id}', 'App\Http\Controllers\Clients\ClientsTrackController@delete')->name('remove_track_pcloud_client');

//step-3
Route::get('/manage_client_track_meta_info/{id}', 'App\Http\Controllers\Clients\ClientsTrackController@client_add_track_step3')->name('manage_client_track_meta_info');
Route::post('/client_add_track_step3/save/{id}', 'App\Http\Controllers\Clients\ClientsTrackController@save_client_add_track_step3')->name('client.save.add.track.step3');

//client_submitted_tracks
Route::any('/client_submitted_tracks', 'App\Http\Controllers\Clients\ClientsTrackController@client_submitted_tracks')->name('client_submitted_tracks');

// image-pcloud
Route::get('client_pcloud_fetch_image/{id}', 'App\Http\Controllers\Clients\ClientsTrackController@pcloudFetchImageUri')->name('client_pcloud_fetch_image');

//Route::get('/', 'App\Http\Controllers\Auth\LoginController@login');

Route::get('register', 'App\Http\Controllers\Auth\RegisterController@register');
Route::post('/register', 'App\Http\Controllers\Auth\RegisterController@storeUser')->name('register');
Route::get('login', 'App\Http\Controllers\Auth\LoginController@login')->name('login');
Route::post('login', 'App\Http\Controllers\Auth\LoginController@authenticate');
Route::get('logout', 'App\Http\Controllers\Auth\LoginController@logout')->name('logout');

Route::any('Member_resubmission_step2', 'App\Http\Controllers\Auth\LoginController@MemberResubmissionStep2');
Route::any('Member_resubmission_step3', 'App\Http\Controllers\Auth\LoginController@MemberResubmissionStep3');
Route::any('Member_resubmission_step4', 'App\Http\Controllers\Auth\LoginController@MemberResubmissionStep4');

// Route::get('home', 'App\Http\Controllers\PagesController@homePage')->name('home');

// Display news
Route::any('/view_news/{id}','App\Http\Controllers\PagesController@displayNews')->name('display-news');
Route::any('/news','App\Http\Controllers\PagesController@all_news')->name('all_news');

//video
Route::any('/videos','App\Http\Controllers\PagesController@all_videos')->name('all_videos');

//expiry mail
Route::any('subscription_expiry_confirmation_mail','App\Http\Controllers\PagesController@subscription_expiry_confirmation_mail')->name('subscription_expiry_confirmation_mail');


//Forums
Route::any('forums','App\Http\Controllers\PagesController@list_forums')->name('list_forum');
Route::any('add-article','App\Http\Controllers\PagesController@addArticle')->name('add-article');
Route::post('add-article-details','App\Http\Controllers\PagesController@addArticleDetails')->name('add-article-details');
Route::any('single-forum/{id}','App\Http\Controllers\PagesController@single_forum')->name('single-forum');
Route::post('add-comment','App\Http\Controllers\PagesController@add_comment')->name('add_comment');
Route::post('like-article','App\Http\Controllers\PagesController@like_article')->name('like_article');
Route::post('dislike-article','App\Http\Controllers\PagesController@dislike_article')->name('dislike_article');

Route::any('single-forum-dev','App\Http\Controllers\PagesController@single_forumDev')->name('single-forum-dev');

// SECURITY: testWebLogin route removed - debug code should never be in production

Route::get('forgot-password', 'App\Http\Controllers\Auth\ForgotPasswordController@getEmail');
Route::post('forgot-password', 'App\Http\Controllers\Auth\ForgotPasswordController@postEmail');

Route::get('reset-password/{token}', 'App\Http\Controllers\Auth\ResetPasswordController@getPassword');
Route::post('reset-password', 'App\Http\Controllers\Auth\ResetPasswordController@updatePassword');

Route::get('password-reset/{token}', 'App\Http\Controllers\Auth\ResetPasswordController@getResetPassword')->name('password-reset-list');
Route::any('password-reset', 'App\Http\Controllers\Auth\ResetPasswordController@updateResetPassword')->name('password-update');

Route::get('Client_registration_step1', 'App\Http\Controllers\Clients\ClientRegisterController@register')->name('client_register');
Route::post('Client_registration_step1', 'App\Http\Controllers\Clients\ClientRegisterController@register');
Route::get('Client_registration_step1/getCountries', 'App\Http\Controllers\Clients\ClientRegisterController@getCountries');
Route::get('Client_registration_step1/getStatesById', 'App\Http\Controllers\Clients\ClientRegisterController@getStatesById');

Route::get('Client_registration_step2', 'App\Http\Controllers\Clients\ClientRegisterController@registerstep2');
Route::post('Client_registration_step2', 'App\Http\Controllers\Clients\ClientRegisterController@registerstep2');
Route::get('Client_registration_step3', 'App\Http\Controllers\Clients\ClientRegisterController@registerstep3');
Route::post('Client_registration_step3', 'App\Http\Controllers\Clients\ClientRegisterController@registerstep3');
Route::get('Client_registration_step4', 'App\Http\Controllers\Clients\ClientRegisterController@registerstep4');

//client package
Route::any('client_subscriptions', 'App\Http\Controllers\Clients\ClientRegisterController@client_package_selection_registration')->name('client_package_selection_registration');
Route::any('package_payment_client/checkout', 'App\Http\Controllers\StripeDigiPaymentController@package_payment_client')->name('package_payment_client');

Route::get('Member_registration_step1', 'App\Http\Controllers\Members\MemberRegisterController@register')->name('register1');
Route::post('Member_registration_step1', 'App\Http\Controllers\Members\MemberRegisterController@register');

Route::get('Member_registration_step2', 'App\Http\Controllers\Members\MemberRegisterController@registerstep2');
Route::post('Member_registration_step2', 'App\Http\Controllers\Members\MemberRegisterController@registerstep2');
Route::get('Member_registration_step3', 'App\Http\Controllers\Members\MemberRegisterController@registerstep3');
Route::post('Member_registration_step3', 'App\Http\Controllers\Members\MemberRegisterController@registerstep3');
Route::get('Member_registration_step4', 'App\Http\Controllers\Members\MemberRegisterController@registerstep4');
Route::post('Member_registration_step4', 'App\Http\Controllers\Members\MemberRegisterController@registerstep4');
Route::get('Member_registration_step5', 'App\Http\Controllers\Members\MemberRegisterController@registerstep5');

// member select package registration
Route::any('member_subscriptions', 'App\Http\Controllers\Members\MemberRegisterController@package_selection_registration')->name('package_selection_registration');
Route::any('package_payment/checkout', 'App\Http\Controllers\StripeDigiPaymentController@package_payment')->name('package_payment');


//verify mail
Route::get('verify_mail/{mtoken}', 'App\Http\Controllers\Members\MemberRegisterController@verify_mail')->name('verify_mail');




// Route::get('Member_dashboard_newest_tracks', 'App\Http\Controllers\Members\MemberDashboardController@viewMemberNewestTracks')->name('member-newest-tracks');

//announcment on member dashboard

Route::any('member_list_announcement', 'App\Http\Controllers\Members\MemberDashboardController@member_list_announcement')->name('member-list-announcement');
Route::any('member_single_announcement/{id}', 'App\Http\Controllers\Members\MemberDashboardController@member_single_announcement')->name('member-single-announcement');

//upload media member
Route::any('member_uploadmedia', 'App\Http\Controllers\Members\MemberDashboardController@member_uploadmedia')->name('member_uploadmedia');
Route::any('member_upload_media_preview', 'App\Http\Controllers\Members\MemberDashboardController@member_upload_media_preview')->name('member_upload_media_preview');
Route::any('upload_media_edit', 'App\Http\Controllers\Members\MemberDashboardController@upload_media_edit')->name('upload_media_edit');
Route::any('/checkTrackExists', 'App\Http\Controllers\Members\MemberDashboardController@checkTrackExists')->name('checkTrackExists');
Route::any('/delete_track', 'App\Http\Controllers\Members\MemberDashboardController@delete_track')->name('delete_track');

// my tracks
Route::any('member_my_tracks', 'App\Http\Controllers\Members\MemberDashboardController@member_my_tracks')->name('member_my_tracks');


// R-s new member routes starts
Route::any('Member_dashboard_newest_tracks', 'App\Http\Controllers\Members\MemberDashboardController@viewMemberNewestTracks')->name('member-newest-tracks');
Route::any('Notifications', 'App\Http\Controllers\Members\MemberDashboardController@viewNotifications')->name('view-notifications');

Route::any('Member_track_review', 'App\Http\Controllers\Members\MemberDashboardController@Member_track_review')->name('Member_track_review');
Route::any('Member_dashboard_top_priority', 'App\Http\Controllers\Members\MemberDashboardController@Member_dashboard_top_priority')->name('Member_dashboard_top_priority');
Route::any('Member_dashboard_top_streaming', 'App\Http\Controllers\Members\MemberDashboardController@Member_dashboard_top_streaming')->name('Member_dashboard_top_streaming');

Route::any('Member_track_label', 'App\Http\Controllers\Members\MemberDashboardController@Member_track_label')->name('Member_track_label');

Route::any('Member_send_message', 'App\Http\Controllers\Members\MemberDashboardController@Member_send_message')->name('Member_send_message');

Route::any('Member_dashboard_all_tracks', 'App\Http\Controllers\Members\MemberDashboardController@Member_dashboard_all_tracks')->name('Member_dashboard_all_tracks');

Route::any('Member_change_password', 'App\Http\Controllers\Members\MemberDashboardController@Member_change_password')->name('Member_change_password');

Route::any('Member_edit_profile', 'App\Http\Controllers\Members\MemberDashboardController@Member_edit_profile')->name('Member_edit_profile');

Route::any('Buy_digicoins', 'App\Http\Controllers\Members\MemberDashboardController@Buy_digicoins')->name('Buy_digicoins');

Route::any('Buy_digicoin_options', 'App\Http\Controllers\Members\MemberDashboardController@Buy_digicoin_options')->name('Buy_digicoin_options');
Route::any('Stripe_digicoins_payment/checkout', 'App\Http\Controllers\StripeDigiPaymentController@stripePostCheckout')->name('stripePostCheckout');
Route::any('Buy_digicoins_stripe_response', 'App\Http\Controllers\StripeDigiPaymentController@buyDigicoinsStripeResponse')->name('buyDigicoinsStripeResponse');
Route::any('Paypal/buy_digicoins', 'App\Http\Controllers\Members\MemberDashboardController@PaypalBuyDigicoins')->name('Paypal_BuyDigicoins');

Route::any('Member_track_review_view', 'App\Http\Controllers\Members\MemberDashboardController@Member_track_review_view')->name('Member_track_review_view');

Route::any('/Member_track_download_front_end/{tid?}', 'App\Http\Controllers\Members\MemberDashboardController@Member_track_download_front')->name('Member_track_download_front');
Route::any('/Download_member_track', 'App\Http\Controllers\Members\MemberDashboardController@Download_member_track')->name('Download_member_track');


Route::any('/Member_track_review_edit', 'App\Http\Controllers\Members\MemberDashboardController@Member_track_review_edit')->name('Member_track_review_edit');

Route::any('/Member_track_review/share', 'App\Http\Controllers\Members\MemberDashboardController@Member_track_review_share')->name('Member_track_review_share');

  // package for member view
  Route::any('member_manage_subscription', 'App\Http\Controllers\Members\MemberDashboardController@members_view_package')->name('members_view_package'); 
  Route::any('payment_history','App\Http\Controllers\Members\MemberDashboardController@package_history')->name('package_history');
  
  //upgrade package
  Route::any('upgrade_package','App\Http\Controllers\Members\MemberDashboardController@upgrade_package')->name('upgrade_package');
  Route::any('upgrade_package_payment_member/checkout', 'App\Http\Controllers\StripeDigiPaymentController@upgrade_package_payment_member')->name('upgrade_package_payment_member');

//BPM

Route::any('/get_audio_bitrate','App\Http\Controllers\Clients\ClientDashboardController@get_audio_bitrate')->name('get_audio_bitrate');

Route::any('/Client_track_review', 'App\Http\Controllers\Clients\ClientDashboardController@viewClientTrackReview')->name('viewClientTrackReview');
Route::any('/client_track_review/track_review_members_list', 'App\Http\Controllers\Clients\ClientDashboardController@track_review_members_list')->name('track_review_members_list');
Route::any('/Client_track_review_member', 'App\Http\Controllers\Clients\ClientDashboardController@Client_track_review_member')->name('Client_track_review_member');

Route::any('/Client_track_download', 'App\Http\Controllers\Clients\ClientDashboardController@Client_track_download')->name('Client_track_download');

Route::any('/Client_track_data', 'App\Http\Controllers\Clients\ClientDashboardController@Client_track_data')->name('Client_track_data');

Route::any('/Client_track_feedback_video', 'App\Http\Controllers\Clients\ClientDashboardController@Client_track_feedback_video')->name('Client_track_feedback_video');

Route::any('/Client_edit_track', 'App\Http\Controllers\Clients\ClientDashboardController@Client_edit_track')->name('Client_edit_track');

Route::post('/delete-client-submitted-track', 'App\Http\Controllers\Clients\ClientDashboardController@deleteClientSubmittedTrack')
    ->name('delete.client.submitted.track');

Route::post('/delete-client-track', 'App\Http\Controllers\Clients\ClientDashboardController@deleteClientTrack')
    ->name('delete.client.track');

//package for memmber  view
Route::any('/client_my_package', 'App\Http\Controllers\Clients\ClientDashboardController@client_my_package')->name('client_my_package');
  Route::any('client_payment_history','App\Http\Controllers\Clients\ClientDashboardController@client_payment_history')->name('client_payment_history');

//
Route::any('/checkTrackExistsclient', 'App\Http\Controllers\Clients\ClientDashboardController@checkTrackExistsclient')->name('checkTrackExistsclient');
Route::any('/delete_track_client', 'App\Http\Controllers\Clients\ClientDashboardController@delete_track_client')->name('delete_track_client');

// R-s new member routes ends here

	// paypal routes member

	Route::any('/Paypal/buy_digicoins/{usertype?}', 'App\Http\Controllers\Members\MemberDashboardController@paypal_buy_digicoins')->name('paypal_buy_digicoins');

	Route::any('/Paypal/cancel', 'App\Http\Controllers\Members\MemberDashboardController@Paypal_cancel')->name('Paypal_cancel');

	Route::any('/paypal/member/success', 'App\Http\Controllers\Members\MemberDashboardController@paypal_member_success')->name('paypal_member_success');

	Route::any('/ipn/notify', 'App\Http\Controllers\Members\MemberDashboardController@ipn_notify_url')->name('ipn_notify_url');

	// paypal routes client
	Route::any('/Paypal/buy/{type?}', 'App\Http\Controllers\Members\MemberDashboardController@paypal_buy_client')->name('paypal_buy_client');
	Route::any('/paypal/client/success', 'App\Http\Controllers\Members\MemberDashboardController@paypal_client_success')->name('paypal_client_success');

	// paypal routes member ends 

// R-s new client routes starts here

Route::any('/Client_tracks', 'App\Http\Controllers\Clients\ClientDashboardController@Client_tracks')->name('Client_tracks');

// submitted track versions routes 
Route::any('/uploaded_tracks_versions', 'App\Http\Controllers\Clients\ClientDashboardController@client_submitted_tracks_versions')->name('client_submitted_tracks_versions');

Route::any('/view_uploaded_tracks_versions/{tid?}', 'App\Http\Controllers\Clients\ClientDashboardController@view_uploaded_tracks_versions')->name('view_uploaded_tracks_versions');


Route::any('/Client_submit_track', 'App\Http\Controllers\Clients\ClientDashboardController@Client_submit_track')->name('Client_submit_track');

Route::any('/Tag_your_music', 'App\Http\Controllers\Clients\ClientDashboardController@Tag_your_music')->name('Tag_your_music');

Route::any('/Client_my_digicoins', 'App\Http\Controllers\Clients\ClientDashboardController@Client_my_digicoins')->name('Client_my_digicoins');

Route::any('/Client_label_reps', 'App\Http\Controllers\Clients\ClientDashboardController@Client_label_reps')->name('Client_label_reps');

Route::any('/Client_add_label_reps', 'App\Http\Controllers\Clients\ClientDashboardController@Client_add_label_reps')->name('Client_add_label_reps');

Route::any('/Client_edit_label_reps', 'App\Http\Controllers\Clients\ClientDashboardController@Client_edit_label_reps')->name('Client_edit_label_reps');

Route::any('/Client_info', 'App\Http\Controllers\Clients\ClientDashboardController@Client_info')->name('Client_info');

Route::any('/Client_messages', 'App\Http\Controllers\Clients\ClientDashboardController@Client_messages')->name('Client_messages');
Route::any('/Client_messages_unread', 'App\Http\Controllers\Clients\ClientDashboardController@Client_messages_unread')->name('Client_messages_unread');
Route::any('/Client_messages_starred', 'App\Http\Controllers\Clients\ClientDashboardController@Client_messages_starred')->name('Client_messages_starred');
Route::any('/Client_message_starred', 'App\Http\Controllers\Clients\ClientDashboardController@Client_message_starred')->name('Client_message_starred');
Route::any('/Client_messages_archived', 'App\Http\Controllers\Clients\ClientDashboardController@Client_messages_archived')->name('Client_messages_archived');
Route::any('/Client_message_archived', 'App\Http\Controllers\Clients\ClientDashboardController@Client_message_archived')->name('Client_message_archived');
Route::any('/Client_messages_members', 'App\Http\Controllers\Clients\ClientDashboardController@Client_messages_members')->name('Client_messages_members');
Route::any('/Client_messages_conversation', 'App\Http\Controllers\Clients\ClientDashboardController@Client_messages_conversation')->name('Client_messages_conversation');

Route::any('/Client_payment1', 'App\Http\Controllers\Clients\ClientDashboardController@Client_payment1')->name('Client_payment1');

Route::any('/Client_payment4', 'App\Http\Controllers\Clients\ClientDashboardController@Client_payment4')->name('Client_payment4');

Route::any('/client_submit_track_preview', 'App\Http\Controllers\Clients\ClientDashboardController@client_submit_track_preview')->name('client_submit_track_preview');

Route::any('/Client_submit_track_edit', 'App\Http\Controllers\Clients\ClientDashboardController@Client_submit_track_edit')->name('Client_submit_track_edit');


// R-s new client routes ends here

Route::get('Member_info', 'App\Http\Controllers\Members\MemberDashboardController@viewMemberInfo')->name('member-info');
Route::get('Member_messages', 'App\Http\Controllers\Members\MemberDashboardController@viewMemberMessages')->name('member-messages');
Route::get('Member_messages_unread', 'App\Http\Controllers\Members\MemberDashboardController@viewMemberMessagesUnread')->name('member-messages-unread');
Route::get('Member_messages_starred', 'App\Http\Controllers\Members\MemberDashboardController@viewMemberMessagesStarred')->name('member-messages-starred');
Route::get('Member_messages_archived', 'App\Http\Controllers\Members\MemberDashboardController@viewMemberMessagesArchived')->name('member-messages-archived');
Route::get('Member_message_archived', 'App\Http\Controllers\Members\MemberDashboardController@viewMemberMessageArchived')->name('member-message-archived');
Route::get('Member_message_starred', 'App\Http\Controllers\Members\MemberDashboardController@viewMemberMessageStarred')->name('member-message-starred');

Route::get('Member_tracks_archives', 'App\Http\Controllers\Members\MemberDashboardController@viewMemberTracksArchives')->name('member-tracks-archives');
Route::get('Member_track_own_archives', 'App\Http\Controllers\Members\MemberDashboardController@viewMemberTracksOwnArchives')->name('member-tracks-own-archives');
Route::get('Member_my_digicoins', 'App\Http\Controllers\Members\MemberDashboardController@viewMemberMyDigicoins')->name('member-my-digicoins');
Route::get('member_staff_picks', 'App\Http\Controllers\Members\MemberDashboardController@viewMemberStaffPicks')->name('member-staff-picks');
Route::get('member_selected_for_you', 'App\Http\Controllers\Members\MemberDashboardController@viewMemberSelectedForYou')->name('member-selected-for-you');
Route::get('Member_orders', 'App\Http\Controllers\Members\MemberDashboardController@viewMemberOrders')->name('member-orders');
Route::get('Products', 'App\Http\Controllers\Members\MemberDashboardController@viewProductsCallback')->name('products-callback');
Route::any('product', 'App\Http\Controllers\Members\MemberDashboardController@viewProductCallback')->name('product-callback');

Route::get('Contactus', 'App\Http\Controllers\PagesController@viewContactPage')->name('Contactus');
Route::post('Contactus', 'App\Http\Controllers\PagesController@viewContactPage')->name('Contactus');
Route::get('faq', 'App\Http\Controllers\PagesController@viewFaqPage')->name('faq');
Route::get('DigiwaxxRadio', 'App\Http\Controllers\PagesController@viewDigiwaxxRadioPage')->name('DigiwaxxRadio');
Route::get('WhatWeDo', 'App\Http\Controllers\PagesController@viewWhatWeDoPage')->name('WhatWeDo');
Route::get('WhyJoin', 'App\Http\Controllers\PagesController@viewWhyJoinPage')->name('WhyJoin');
Route::get('Privacy_policy', 'App\Http\Controllers\PagesController@viewPrivacyPolicyPage');
Route::get('SponsorAdvertise', 'App\Http\Controllers\PagesController@viewSponsorAdvertisePage')->name('SponsorAdvertise');
Route::post('SponsorAdvertise', 'App\Http\Controllers\PagesController@viewSponsorAdvertisePage');

Route::get('PromoteYourProject', 'App\Http\Controllers\PagesController@viewPromoteYourProjectPage')->name('PromoteYourProject');
Route::get('Help', 'App\Http\Controllers\PagesController@viewHelpPage')->name('Help');
Route::get('FreePromo', 'App\Http\Controllers\PagesController@viewFreePromoPage')->name('FreePromo');
Route::get('PressPage', 'App\Http\Controllers\PagesController@viewPressPage')->name('PressPage');
Route::get('WallOfScratch', 'App\Http\Controllers\PagesController@viewWallOfScratchPage')->name('WallOfScratch');

// Route::get('Charts', 'App\Http\Controllers\PagesController@viewChartsPage');
Route::get('ClientsPage', 'App\Http\Controllers\PagesController@viewClientsPage');
//Route::get('Client_tracks', 'App\Http\Controllers\PagesController@viewClientTracksPage');
Route::get('Events', 'App\Http\Controllers\PagesController@viewEventsPage')->name('Events');
Route::get('testimonials', 'App\Http\Controllers\PagesController@viewTestimonialsPage')->name('testimonials');
Route::get('ImDj', 'App\Http\Controllers\PagesController@viewImDjPage')->name('ImDj');
Route::get('ImArtist', 'App\Http\Controllers\PagesController@viewImArtistPage');

Route::any('Client_dashboard', 'App\Http\Controllers\Clients\ClientDashboardController@viewClient_dashboard')->name('Client_dashboard');
Route::any('Client_dashboard_rated', 'App\Http\Controllers\Clients\ClientDashboardController@viewClienDashboardRated')->name('ClienDashboardRated');
Route::any('Client_dashboard_downloaded', 'App\Http\Controllers\Clients\ClientDashboardController@viewClienDashboardDownloaded')->name('ClienDashboardDownloaded');
Route::any('Client_dashboard_commented', 'App\Http\Controllers\Clients\ClientDashboardController@viewClienDashboardCommented')->name('ClienDashboardCommented');
Route::any('Client_dashboard_played', 'App\Http\Controllers\Clients\ClientDashboardController@viewClienDashboardPlayed')->name('ClienDashboardPlayed');
Route::any('Client_edit_profile', 'App\Http\Controllers\Clients\ClientDashboardController@viewClientEditProfile')->name('ClientEditProfile');
Route::any('Client_change_password', 'App\Http\Controllers\Clients\ClientDashboardController@viewClientChangePasssword')->name('ClientChangePasssword');
//Route::any('Buy_digicoins', 'App\Http\Controllers\Clients\ClientDashboardController@viewClientBuyDigicoins')->name('BuyDigicoins');

// Route for reset admin password
Route::any('email/ad/forget', 'App\Http\Controllers\Auth\AdminLoginController@AdminForgetNotification_function')->name('AdminForgetNotification_function');
Route::get('mail/ad/pass/form/redirect/{ad_mail?}', 'App\Http\Controllers\Auth\AdminLoginController@admin_reset_password_mail')->name('admin_reset_password_mail');
Route::get('mail/ad/password/form/submit//{ad_id?}', 'App\Http\Controllers\Auth\AdminLoginController@submit_reset_admin_password')->name('submit_reset_admin_password');

// File export 
Route::get('/excel/tracks.csv')->name('sample_file');

//Website pages
    Route::any('/view_website_pages', 'App\Http\Controllers\AdminController@view_website_pages')->name('view_website_pages');

// Route::any('/admin/listing', 'App\Http\Controllers\Admin\AdminController@admins_listing')->name('admins_list');

	Route::prefix('admin')->group(function(){
	 Route::get('/', 'App\Http\Controllers\Auth\AdminLoginController@admin_showloginPage')->name('admin_showloginPage');
	 Route::any('/admin/check', 'App\Http\Controllers\Auth\AdminLoginController@validate_admin_login')->name('validate_admin_login');
	 Route::any('/devpcloudupload','App\Http\Controllers\AdminController@testPcloudImgUpload')->name('devpcloudupload');
	 Route::get('/dashboard', 'App\Http\Controllers\AdminController@admin_dashboard')->name('admin_dashboard');
   
	 ##Route::get('/dashboard-oo-test', 'App\Http\Controllers\AdminController@admin_dashboard_oo')->name('admin_dashboard_oo');
	 Route::any('/admin/logout', 'App\Http\Controllers\Auth\AdminLoginController@admin_logout')->name('admin_logout');
	 
	Route::any('/get_artist_list','App\Http\Controllers\AdminController@emptyCallBack')->name('get_artist_list');
	Route::any('/get_c_name_list','App\Http\Controllers\AdminController@emptyCallBack')->name('get_c_name_list');
	Route::any('/get_c_relation_list','App\Http\Controllers\AdminController@emptyCallBack')->name('get_c_relation_list');
	Route::any('/get_c_email_list','App\Http\Controllers\AdminController@emptyCallBack')->name('get_c_email_list');
	Route::any('/get_c_phone_list','App\Http\Controllers\AdminController@emptyCallBack')->name('get_c_phone_list');
	
	Route::get('/', 'App\Http\Controllers\Auth\AdminLoginController@admin_showloginPage')->name('admin_showloginPage');
	Route::any('/admin/check', 'App\Http\Controllers\Auth\AdminLoginController@validate_admin_login')->name('validate_admin_login');
	Route::get('/dashboard', 'App\Http\Controllers\AdminController@admin_dashboard')->name('admin_dashboard');
	Route::any('/admin/logout', 'App\Http\Controllers\Auth\AdminLoginController@admin_logout')->name('admin_logout');
	Route::get('/track_review_members_list', 'App\Http\Controllers\AdminController@track_review_members_list')->name('track_review_members_list');
	Route::get('/track_member_review', 'App\Http\Controllers\AdminController@track_member_review')->name('track_member_review');

	//  Route::get('/add_track_new', 'App\Http\Controllers\AdminAddTracksController@admin_add_track_new')->name('add_track_new');
	Route::get('/add_new_track', 'App\Http\Controllers\AdminAddTracksController@admin_add_track_new')->name('add_track_new');
	Route::post('/add_track_new/save', 'App\Http\Controllers\AdminAddTracksController@save_admin_add_track_new')->name('admin.save.add.track');
	Route::post('/add_track_new/delete_duplicate', 'App\Http\Controllers\AdminAddTracksController@delete_duplicate')->name('admin.delete.duplicate.track');

	//edit track
	Route::get('/edit_track/{id}', 'App\Http\Controllers\AdminAddTracksController@admin_edit_track')->name('edit_track');
	Route::post('/edit_track/save', 'App\Http\Controllers\AdminAddTracksController@save_admin_edit_track')->name('admin.save.edit.track');
	
	Route::get('/manage_track_audios/{id}', 'App\Http\Controllers\AdminAddTracksController@admin_add_audio_files')->name('add_audio_files');

	//step-3
	// Route::get('/add_track_step3/{id}', 'App\Http\Controllers\AdminAddTracksController@admin_add_track_step3')->name('add_track_step3');
	Route::get('/manage_track_meta_info/{id}', 'App\Http\Controllers\AdminAddTracksController@admin_add_track_step3')->name('manage_track_meta_info');
	Route::post('/add_track_step3/save/{id}', 'App\Http\Controllers\AdminAddTracksController@save_admin_add_track_step3')->name('admin.save.add.track.step3');
	

	// mp3
	Route::post('multiple-file-upload', 'App\Http\Controllers\AdminAddTracksController@uploadMultipleFile');
	Route::any('mp3_upload/{track_id}', 'App\Http\Controllers\AdminAddTracksController@upload')->name('mp3_upload');
	Route::get('remove_track_pcloud/{id}', 'App\Http\Controllers\AdminAddTracksController@delete')->name('remove_track_pcloud');

	Route::post('/add_mp3/save', 'App\Http\Controllers\AdminAddTracksController@save_mp3_track')->name('save.mp3.track');


	// audio-stream-pcloud
	Route::get('audio_stream_pcloud/{id}', 'App\Http\Controllers\AdminAddTracksController@pcloudStreamAudioUri')->name('audio_stream_pcloud');

	// image-pcloud
	Route::get('pcloud_fetch_image/{id}', 'App\Http\Controllers\AdminAddTracksController@pcloudFetchImageUri')->name('pcloud_fetch_image');
	
	//delete album track 
	Route::post('/delete_Track', 'App\Http\Controllers\AlbumsController@delete_Track')->name('delete_Track');

	//Add Albums
	Route::get('/add_new_album', 'App\Http\Controllers\AlbumsController@admin_add_new_album')->name('add_new_album');
	Route::post('/add_new_album/save', 'App\Http\Controllers\AlbumsController@save_admin_add_new_album')->name('admin.save.add.album');
	Route::post('/add_new_album/delete_duplicate', 'App\Http\Controllers\AlbumsController@delete_duplicate')->name('admin.delete.duplicate.album');

	Route::get('/manage_album_audios/{id}', 'App\Http\Controllers\AlbumsController@add_album_audio_files')->name('manage_album_audios');
	Route::post('/add_album_mp3/save', 'App\Http\Controllers\AlbumsController@save_mp3_album')->name('save.mp3.album');

	// Album track audio
	Route::get('/manage_album_track_audios/{id}/{album_id}', 'App\Http\Controllers\AlbumsController@admin_add_track_audio_files')->name('manage_album_track_audios');
	Route::post('/add_album_track_mp3/save', 'App\Http\Controllers\AlbumsController@save_mp3_album_track')->name('save.mp3.album.track');
	
	//edit album
	Route::get('/edit_album/{id}', 'App\Http\Controllers\AlbumsController@admin_edit_album')->name('edit_album');
	Route::post('/edit_album/save', 'App\Http\Controllers\AlbumsController@save_admin_edit_album')->name('admin.save.edit.album');

	// Add Album Step-3
	Route::get('/manage_album_meta_info/{id}', 'App\Http\Controllers\AlbumsController@admin_add_album_step3')->name('manage_album_meta_info');
	Route::post('/add_album_step3/save/{id}', 'App\Http\Controllers\AlbumsController@save_admin_add_album_step3')->name('admin.save.add.album.step3');
	 
 //news
	Route::any('/news', 'App\Http\Controllers\AdminController@list_news')->name('list_news');
	Route::any('/add_news', 'App\Http\Controllers\AdminController@add_news_view')->name('add_news_view');
	Route::post('/addnews','App\Http\Controllers\AdminController@add_news')->name('add_news');
	Route::post('/news_delete','App\Http\Controllers\AdminController@delete_news')->name('delete_news');
	Route::post('/news_approve','App\Http\Controllers\AdminController@approve_news')->name('approve_news');
	Route::any('/edit_news/{id}','App\Http\Controllers\AdminController@edit_news')->name('edit_news');
	Route::any('/news_edit','App\Http\Controllers\AdminController@news_edit')->name('news_edit');
	Route::any('/view_news/{id}','App\Http\Controllers\AdminController@view_news')->name('view_news');
	Route::post('/news_disable','App\Http\Controllers\AdminController@news_disable')->name('news_disable');
	
	//videos
	Route::any('/add_video', 'App\Http\Controllers\AdminController@add_video')->name('add_video');
	Route::post('/addvideo','App\Http\Controllers\AdminController@addvideo')->name('addvideo');
	Route::any('/videos', 'App\Http\Controllers\AdminController@videos')->name('videos');
	Route::post('/video_delete','App\Http\Controllers\AdminController@video_delete')->name('video_delete');
	Route::post('/video_approve','App\Http\Controllers\AdminController@video_approve')->name('video_approve');
	Route::post('/video_disable','App\Http\Controllers\AdminController@video_disable')->name('video_disable');
	Route::any('/update_video/{id}','App\Http\Controllers\AdminController@update_video')->name('update_video');
	Route::any('/video_edit','App\Http\Controllers\AdminController@video_edit')->name('video_edit');
	Route::any('/view_video/{id}','App\Http\Controllers\AdminController@view_video')->name('view_video');
	
	Route::get('/export-members', 'App\Http\Controllers\AdminController@csvExportDjMember')->name('admin_djmemberCsvExport');

	//
	
	Route::any('/TestPcloudFolderCreate','App\Http\Controllers\AdminController@testPcloudFolderCreate')->name('testPcloudFolderCreate');
	
	## For LOGOS Mapping
	Route::any('/localMediaToPcloudMapping','App\Http\Controllers\AdminController@localMediaToPcloudMapping')->name('localMediaToPcloudMapping');
	## For MEMBER IMAGES Mapping
	Route::any('/member_localMediaToPcloudMapping','App\Http\Controllers\AdminController@member_localMediaToPcloudMapping')->name('member_localMediaToPcloudMapping');
	## For PAGE IMAGE Mapping
	Route::any('/pageimage_localMediaToPcloudMapping','App\Http\Controllers\AdminController@pageimage_localMediaToPcloudMapping')->name('pageimage_localMediaToPcloudMapping');
	## For COVER IMAGES Mapping
	Route::any('/coverimage_localMediaToPcloudMapping','App\Http\Controllers\AdminController@coverimage_localMediaToPcloudMapping')->name('coverimage_localMediaToPcloudMapping');
	## Testing delete image from p cloud
	Route::any('/testdeletefile','App\Http\Controllers\AdminController@testdeletefile')->name('testdeletefile');

	
	
	
//sneakers	
		Route::any('/add_sneaker', 'App\Http\Controllers\AdminController@add_sneaker')->name('add_sneaker');
		Route::post('/addsneaker','App\Http\Controllers\AdminController@addsneaker')->name('addsneaker');
		Route::any('/sneaker', 'App\Http\Controllers\AdminController@sneaker')->name('sneaker');
		Route::post('/sneaker_approve','App\Http\Controllers\AdminController@sneaker_approve')->name('sneaker_approve');
	    Route::post('/sneaker_disable','App\Http\Controllers\AdminController@sneaker_disable')->name('sneaker_disable');
	    Route::post('/sneaker_delete','App\Http\Controllers\AdminController@sneaker_delete')->name('sneaker_delete');
	    Route::any('/update_sneaker/{id}','App\Http\Controllers\AdminController@update_sneaker')->name('update_sneaker');
	    Route::any('/sneaker_edit','App\Http\Controllers\AdminController@sneaker_edit')->name('sneaker_edit');
	    Route::any('/sneaker_view/{id}','App\Http\Controllers\AdminController@sneaker_view')->name('sneaker_view');
	
 //announcements
    Route::any('/announcement', 'App\Http\Controllers\AdminController@list_announcement')->name('list_announcement');
    Route::any('/add_announcement', 'App\Http\Controllers\AdminController@add_announcement_view')->name('add_announcement_view');
    Route::post('/addannouncement','App\Http\Controllers\AdminController@add_announcement')->name('add_announcement');
    Route::post('/announcement_approve','App\Http\Controllers\AdminController@approve_announcement')->name('approve_announcement');
    Route::post('/announcement_disable','App\Http\Controllers\AdminController@announcement_disable')->name('announcement_disable');
    Route::post('/announcement_delete','App\Http\Controllers\AdminController@delete_announcement')->name('announcement_delete');
    Route::any('/edit_announcement/{id}','App\Http\Controllers\AdminController@edit_announcement')->name('edit_announcement');
    Route::any('/announcement_edit','App\Http\Controllers\AdminController@announcement_edit')->name('announcement_edit');
	Route::any('/view_announcement/{id}','App\Http\Controllers\AdminController@view_announcement')->name('view_announcement');
	Route::post('/fetch_mem','App\Http\Controllers\AdminController@fetch_mem')->name('fetch_mem');
	
// forums
 Route::any('/forums', 'App\Http\Controllers\AdminController@list_forums')->name('list_forums');
 Route::post('/forum_approve','App\Http\Controllers\AdminController@approve_forum')->name('approve_forum');
 Route::post('/forum_disable','App\Http\Controllers\AdminController@forum_disable')->name('forum_disable');
 Route::post('/forum_delete','App\Http\Controllers\AdminController@delete_forum')->name('forum_delete');
 Route::any('/edit_forum/{id}','App\Http\Controllers\AdminController@edit_forum')->name('edit_forum');
 Route::any('/forum_edit','App\Http\Controllers\AdminController@forum_edit')->name('forum_edit');
 Route::any('/view_forum/{id}','App\Http\Controllers\AdminController@view_forum')->name('view_forum');
 Route::any('/list_comment/{id}', 'App\Http\Controllers\AdminController@list_comment')->name('list_comment');
 Route::post('/comment_approve','App\Http\Controllers\AdminController@comment_approve')->name('comment_approve');
 Route::post('/comment_disapprove','App\Http\Controllers\AdminController@comment_disapprove')->name('comment_disapprove');
 Route::post('/comment_delete','App\Http\Controllers\AdminController@comment_delete')->name('comment_delete');
 Route::post('/comment_undelete','App\Http\Controllers\AdminController@comment_undelete')->name('comment_undelete');
 Route::any('/list_single_comment/{id}', 'App\Http\Controllers\AdminController@list_single_comment')->name('list_single_comment_admin');
 
 // package
  Route::any('/add_package', 'App\Http\Controllers\AdminController@add_package')->name('add_package');
  Route::any('/insert_package', 'App\Http\Controllers\AdminController@insert_package')->name('insert_package');
  Route::any('/member_packages', 'App\Http\Controllers\AdminController@member_packages')->name('member_packages');
  Route::any('/edit_package_view/{id}', 'App\Http\Controllers\AdminController@edit_package_view')->name('edit_package_view');
  Route::any('/update_package', 'App\Http\Controllers\AdminController@update_package')->name('update_package');
  Route::post('/approve_package','App\Http\Controllers\AdminController@approve_package')->name('approve_package');
  Route::post('/package_disable','App\Http\Controllers\AdminController@package_disable')->name('package_disable');
  Route::any('/user_packages_details', 'App\Http\Controllers\AdminController@user_packages_details')->name('user_packages_details');
  Route::any('view_single_user_package', 'App\Http\Controllers\AdminController@view_single_user_package')->name('view_single_user_package');
   Route::post('update_package_user', 'App\Http\Controllers\AdminController@update_package_user')->name('update_package_user');
  


	 

	// admin module listing
	Route::any('/listing', 'App\Http\Controllers\AdminController@admin_listing')->name('admin_listing');
	Route::any('/add/admin', 'App\Http\Controllers\AdminController@add_admin')->name('add_admin');
	Route::any('submit/add/admin', 'App\Http\Controllers\AdminController@add_admin')->name('submit_add_admin');

	//  Labels routes starts
	Route::any('/labels', 'App\Http\Controllers\AdminController@admin_labels_listing')->name('admin_labels_listing');
	Route::any('/add/label', 'App\Http\Controllers\AdminController@admin_add_labels')->name('admin_add_labels');
	Route::any('submit/add/label', 'App\Http\Controllers\AdminController@admin_add_labels')->name('submit_add_label');

	// Albums routes starts 

	Route::any('/albums', 'App\Http\Controllers\AdminController@admin_albums_listing')->name('admin_albums_listing');
	Route::any('/album/edit/{aid?}', 'App\Http\Controllers\AdminController@admin_albums_edit')->name('admin_albums_edit');
	Route::any('submit/edit/album/{aid?}', 'App\Http\Controllers\AdminController@admin_albums_edit')->name('submit_edit_album');
	Route::any('/add/album', 'App\Http\Controllers\AdminController@admin_add_album')->name('admin_add_album');
	Route::any('/album/album_add', 'App\Http\Controllers\AdminController@admin_add_album')->name('admin_add_album');
	Route::any('/add/album_add', 'App\Http\Controllers\AdminController@admin_add_album')->name('admin_add_album');

	// Tracks routes starts 
	Route::any('/tracks', 'App\Http\Controllers\AdminController@admin_tracks_listing')->name('admin_tracks_listing');
	Route::any('/tracks_sort', 'App\Http\Controllers\AdminController@admin_sort_tracks')->name('admin_sort_tracks');
	Route::any('/track_review/{tid?}', 'App\Http\Controllers\AdminController@track_review')->name('track_review');
	Route::any('/track_view/{tid?}', 'App\Http\Controllers\AdminController@track_view')->name('track_view');
	Route::any('/track_manage_mp3/{tid?}', 'App\Http\Controllers\AdminController@track_manage_mp3')->name('track_manage_mp3');
	Route::any('/track_edit', 'App\Http\Controllers\AdminController@track_edit')->name('track_edit');

    // submitted track versions routes 
Route::any('/submitted_tracks_versions', 'App\Http\Controllers\AdminController@submitted_tracks_versions')->name('submitted_tracks_versions');

Route::post('/admin/version/approve', 'App\Http\Controllers\AdminController@approveVersion')->name('approveVersion');
  Route::post('/admin/version/approveAll', 'App\Http\Controllers\AdminController@approveAllVersions')->name('approveAllVersions');
  Route::post('/admin/version/delete', 'App\Http\Controllers\AdminController@deleteVersion')->name('deleteVersion');
  Route::post('/admin/version/deleteAll', 'App\Http\Controllers\AdminController@deleteAllVersions')->name('deleteAllVersions');

Route::get('/submitted_tracks_versions_get', 'App\Http\Controllers\AdminController@submitted_tracks_versions_get')->name('submitted_tracks_versions_get');

Route::any('/submitted_tracks_versions_edit/{tid?}', 'App\Http\Controllers\AdminController@submitted_tracks_versions_edit')->name('submitted_tracks_versions_edit');
	
	Route::any('/add_track', 'App\Http\Controllers\AdminController@admin_add_track')->name('admin_add_track');  // track submit step 1
	Route::any('/checkClientTrackExists', 'App\Http\Controllers\AdminController@checkClientTrackExists')->name('checkClientTrackExists');
	Route::any('/add_track1', 'App\Http\Controllers\AdminController@admin_add_track1')->name('admin_add_track1');  // track submit step 2
	Route::any('/submitted_tracks', 'App\Http\Controllers\AdminController@submitted_tracks')->name('submitted_tracks');
	Route::any('/submitted_track_edit', 'App\Http\Controllers\AdminController@submitted_track_edit')->name('submitted_track_edit');

	Route::any('/top_streaming', 'App\Http\Controllers\AdminController@top_streaming')->name('top_streaming');
	Route::any('/top_priority', 'App\Http\Controllers\AdminController@top_priority')->name('top_priority');
	Route::any('/export_tracks', 'App\Http\Controllers\AdminController@export_tracks')->name('export_tracks');

	Route::any('/download_tracks_data', 'App\Http\Controllers\AdminController@download_tracks_data')->name('download_tracks_data');
	Route::any('/manage_releasetype', 'App\Http\Controllers\AdminController@manage_releasetype')->name('manage_releasetype');
	Route::any('/add_releasetype', 'App\Http\Controllers\AdminController@add_releasetype')->name('add_releasetype');


	// client admin module 

	Route::any('/clients', 'App\Http\Controllers\AdminController@admin_clients_listing')->name('admin_clients_listing');
	Route::any('/client_change_password', 'App\Http\Controllers\AdminController@client_change_password')->name('client_change_password');
	Route::any('/client_view', 'App\Http\Controllers\AdminController@client_view')->name('client_view');
	Route::any('/client_edit', 'App\Http\Controllers\AdminController@client_edit')->name('client_edit');
	Route::any('/client_payments', 'App\Http\Controllers\AdminController@client_payments')->name('client_payments');
	Route::any('/client_payment_view', 'App\Http\Controllers\AdminController@client_payment_view')->name('client_payment_view');
	Route::any('/add_client', 'App\Http\Controllers\AdminController@add_client')->name('add_client');
	Route::any('/Exportclients', 'App\Http\Controllers\AdminController@Exportclients')->name('Exportclients_file');
	Route::any('/pending_clients', 'App\Http\Controllers\AdminController@pending_clients')->name('pending_clients');
	Route::any('/manage_pending_client', 'App\Http\Controllers\AdminController@manage_pending_client')->name('manage_pending_client');
	
	Route::any('/client_packages', 'App\Http\Controllers\AdminController@client_packages')->name('client_packages');

	// member admin module 

	Route::any('/add_member', 'App\Http\Controllers\AdminController@add_member')->name('add_member');
	Route::any('/member_payments', 'App\Http\Controllers\AdminController@member_payments')->name('member_payments');
	Route::any('/member_payment_view', 'App\Http\Controllers\AdminController@member_payment_view')->name('member_payment_view');
	Route::any('/pending_members', 'App\Http\Controllers\AdminController@pending_members')->name('pending_members');
	Route::any('/manage_pending_member', 'App\Http\Controllers\AdminController@manage_pending_member')->name('manage_pending_member');
	Route::any('/add_multiple_member', 'App\Http\Controllers\AdminController@add_multiple_member')->name('add_multiple_member');


	// Digicoins

	Route::any('/digicoins', 'App\Http\Controllers\AdminController@Digicoins')->name('Digicoins');


	// Products/store module

	Route::any('/store', 'App\Http\Controllers\AdminController@products_lisitng')->name('products_lisitng');
	Route::any('/store/product_review_report', 'App\Http\Controllers\AdminController@product_review_report')->name('product_review_report');
	Route::any('/store/product_digicoins', 'App\Http\Controllers\AdminController@product_digicoins')->name('product_digicoins');
	Route::any('/store/product_review_options', 'App\Http\Controllers\AdminController@product_review_options')->name('product_review_options');
	Route::any('/store/view_question', 'App\Http\Controllers\AdminController@view_question')->name('view_question');
	Route::any('/store/edit_question', 'App\Http\Controllers\AdminController@edit_question')->name('edit_question');
	Route::any('/store/edit_product', 'App\Http\Controllers\AdminController@edit_product')->name('edit_product');

	Route::any('/store/add_product', 'App\Http\Controllers\AdminController@add_product')->name('add_product');
	Route::any('/store/product_orders', 'App\Http\Controllers\AdminController@product_orders')->name('product_orders');
	Route::any('/store/add_product_question', 'App\Http\Controllers\AdminController@add_product_question')->name('add_product_question');
	
	// Members Module
	
	 Route::any('/members', 'App\Http\Controllers\AdminController@adminMembersListing')->name('adminMembersListing');
	 Route::any('/member_digicoins', 'App\Http\Controllers\AdminController@adminMemberDigicoins')->name('adminMemberDigicoins');
	 Route::any('/member_view', 'App\Http\Controllers\AdminController@adminMemberViewInfo')->name('adminMemberViewInfo');
	 Route::any('/member_edit', 'App\Http\Controllers\AdminController@adminMemberEditInfo')->name('adminMemberEditInfo');
	 Route::any('/member_change_password', 'App\Http\Controllers\AdminController@adminMemberChangePassword')->name('adminMemberChangePassword');
	
	// @GS Logos Module Routes 
	 Route::any('/logos', 'App\Http\Controllers\AdminController@admin_listCompanyLogos')->name('admin_listCompanyLogos');
	 Route::any('/logo_view', 'App\Http\Controllers\AdminController@admin_viewCompanyLogo')->name('admin_viewCompanyLogo');
	 Route::any('/logo_edit', 'App\Http\Controllers\AdminController@admin_editCompanyLogo')->name('admin_editCompanyLogo');
	 Route::any('/logo_add', 'App\Http\Controllers\AdminController@admin_addCompanyLogo')->name('admin_addCompanyLogo');
	 
	// @GS Genres Module Routes
	 Route::any('/genres', 'App\Http\Controllers\AdminController@admin_listGenres')->name('admin_listGenres');
	 
	 	// Additional Request Admin Module
	Route::any('/requests', 'App\Http\Controllers\AdminController@admin_additionalRequests')->name('additionalRequests');
	 
	// @GS Logos DjTools Routes
	 Route::any('/tools/tools', 'App\Http\Controllers\AdminController@admin_listTools')->name('admin_listTools');
	 Route::any('/tools/edit_tool', 'App\Http\Controllers\AdminController@admin_editDjTool')->name('admin_editDjTool');
	 Route::any('/tools/add_tool', 'App\Http\Controllers\AdminController@admin_addDjTool')->name('admin_addDjTool');
	 
	// @GS FAQs Routes
	 Route::any('/faqs', 'App\Http\Controllers\AdminController@admin_listFaqs')->name('admin_listFaqs');
	 Route::any('/faq_edit', 'App\Http\Controllers\AdminController@admin_editFaqs')->name('admin_editFaqs');
	 Route::any('/faq_view', 'App\Http\Controllers\AdminController@admin_viewFaq')->name('admin_viewFaq');
	 Route::any('/faq_add', 'App\Http\Controllers\AdminController@admin_addNewFaq')->name('admin_addNewFaq');
	 
	// @GS Mails Routes
	 Route::any('/mails', 'App\Http\Controllers\AdminController@admin_listMails')->name('admin_listMails');
	 Route::any('/mail_view', 'App\Http\Controllers\AdminController@admin_viewMail')->name('admin_viewMail');
	 Route::any('/send_mail', 'App\Http\Controllers\AdminController@admin_sendMail')->name('admin_sendMail');
	 
	// @GS Subscribers Routes
	 Route::any('/subscribers', 'App\Http\Controllers\AdminController@admin_listSubscribers')->name('admin_listSubscribers');
	 Route::any('/newsletters', 'App\Http\Controllers\AdminController@admin_listNewsletters')->name('admin_listNewsletters');
	 Route::any('/send_newsletter', 'App\Http\Controllers\AdminController@admin_sendNewsletter')->name('admin_sendNewsletter');
	 Route::any('/newsletter_view', 'App\Http\Controllers\AdminController@admin_NewsletterView')->name('admin_NewsletterView');
	 
	// @GS Staff Selected Routes
	 Route::any('/admin_staff_selected', 'App\Http\Controllers\AdminController@admin_ListStaffSelected')->name('admin_ListStaffSelected');
	 
	 // @GS Countries/States Routes
	 Route::any('/countries', 'App\Http\Controllers\AdminController@admin_ListCountries')->name('admin_ListCountries');
	 Route::any('/states', 'App\Http\Controllers\AdminController@admin_ListStates')->name('admin_ListStates');
	 Route::any('/youtube', 'App\Http\Controllers\AdminController@admin_ListYoutube')->name('admin_ListYoutube');
	 Route::any('/seo', 'App\Http\Controllers\AdminController@admin_ListSEO')->name('admin_ListSEO');
	 
	 // @GS Pages Routes	 
	 Route::any('/pages', 'App\Http\Controllers\AdminController@admin_ListPages')->name('admin_ListPages');
	 Route::any('/pages/what_is_digiwaxx', 'App\Http\Controllers\AdminController@admin_PageWhatIsDigiwaxx')->name('admin_PageWhatIsDigiwaxx');
	 Route::any('/pages/charts', 'App\Http\Controllers\AdminController@admin_PageCharts')->name('admin_PageCharts');
	 Route::any('/pages/digiwaxx_radio', 'App\Http\Controllers\AdminController@admin_PageDigiwaxxRadio')->name('admin_PageDigiwaxxRadio');
	 Route::any('/pages/promote_your_project', 'App\Http\Controllers\AdminController@admin_PagePromoteYourProject')->name('admin_PagePromoteYourProject');
	 Route::any('/pages/contact_us', 'App\Http\Controllers\AdminController@admin_PageContactUs')->name('admin_PageContactUs');
	 Route::any('/pages/press_page', 'App\Http\Controllers\AdminController@admin_PagePress')->name('admin_PagePress');
	 Route::any('/pages/clients_page', 'App\Http\Controllers\AdminController@admin_PageClientsPage')->name('admin_PageClientsPage');
	 Route::any('/pages/wall_of_scratch', 'App\Http\Controllers\AdminController@admin_PageWallOfScratch')->name('admin_PageWallOfScratch');
	 Route::any('/pages/what_we_do', 'App\Http\Controllers\AdminController@admin_PageWhatWeDo')->name('admin_PageWhatWeDo');
	 Route::any('/pages/free_promo', 'App\Http\Controllers\AdminController@admin_PageFreePromo')->name('admin_PageFreePromo');
	 Route::any('/pages/events', 'App\Http\Controllers\AdminController@admin_PageEvents')->name('admin_PageEvents');
	 Route::any('/pages/testimonials', 'App\Http\Controllers\AdminController@admin_PageTestimonials')->name('admin_PageTestimonials');
	 Route::any('/pages/why_join', 'App\Http\Controllers\AdminController@admin_PageWhyJoin')->name('admin_PageWhyJoin');
	 Route::any('/pages/sponsor_advertise', 'App\Http\Controllers\AdminController@admin_PageSponsorAdvert')->name('admin_PageSponsorAdvert');
	 Route::any('/pages/help', 'App\Http\Controllers\AdminController@admin_PageHelp')->name('admin_PageHelp');
	 Route::any('/pages/im_dj', 'App\Http\Controllers\AdminController@admin_PageImDj')->name('admin_PageImDj');
	 Route::any('/pages/privacy_policy', 'App\Http\Controllers\AdminController@admin_PagePrivacyPolicy')->name('admin_PagePrivacyPolicy');
	});




	Route::any('/Member_track_download/{tid?}', 'App\Http\Controllers\AdminController@Member_track_download')->name('Member_track_download');

	Route::get('WhatIsDigiwaxx', 'App\Http\Controllers\AdminController@WhatIsDigiwaxx');
	//Route::any('Charts', 'App\Http\Controllers\AdminController@viewChartsPage');   // ***** remove previous charts route
	Route::any('Charts', 'App\Http\Controllers\PagesController@viewChartsPage')->name('Charts');   // ***** remove previous charts route
	Route::post('/ai/ask', [App\Http\Controllers\AIController::class, 'ask']);

/*
|--------------------------------------------------------------------------
| Subscription & Pricing Routes (New Pricing Tiers)
|--------------------------------------------------------------------------
*/

use App\Http\Controllers\SubscriptionController;
use App\Http\Controllers\StripeWebhookController;
use App\Http\Controllers\SubscriptionErrorTestController;

// Public pricing page - shows all tiers with monthly/annual toggle
Route::get('/pricing', [SubscriptionController::class, 'pricing'])->name('pricing');

// Subscription checkout routes (requires authentication)
Route::middleware(['auth'])->group(function () {
    Route::get('/subscribe/{tier}/{billing}', [SubscriptionController::class, 'checkout'])
        ->name('subscribe.checkout')
        ->where(['tier' => 'free|artist|label', 'billing' => 'monthly|annual']);
    Route::get('/subscribe/success', [SubscriptionController::class, 'success'])->name('subscribe.success');
    Route::get('/subscribe/cancel', [SubscriptionController::class, 'cancel'])->name('subscribe.cancel');
    Route::get('/subscription', [SubscriptionController::class, 'manage'])->name('subscription.manage');
    Route::post('/subscription/upgrade', [SubscriptionController::class, 'upgrade'])->name('subscription.upgrade');
    Route::post('/subscription/cancel', [SubscriptionController::class, 'cancelSubscription'])->name('subscription.cancel');
    Route::post('/subscription/resume', [SubscriptionController::class, 'resume'])->name('subscription.resume');
    Route::get('/billing-portal', [SubscriptionController::class, 'billingPortal'])->name('billing.portal');
    Route::get('/subscription/upload-usage', [SubscriptionController::class, 'uploadUsage'])->name('subscription.upload-usage');
});

// Stripe Webhook
Route::post('/stripe/webhook', [StripeWebhookController::class, 'handleWebhook'])
    ->name('stripe.webhook')
    ->withoutMiddleware([\App\Http\Middleware\VerifyCsrfToken::class]);

/*
|--------------------------------------------------------------------------
| Subscription Error Testing Routes (Development/Testing)
|--------------------------------------------------------------------------
| These routes are for testing subscription error scenarios and verifying
| that error messages are properly translated in all supported languages.
*/

Route::prefix('subscription/test')->group(function () {
    Route::get('/', [SubscriptionErrorTestController::class, 'index'])->name('subscription.error-test');
    Route::get('/not-logged-in', [SubscriptionErrorTestController::class, 'testNotLoggedIn']);
    Route::get('/invalid-plan', [SubscriptionErrorTestController::class, 'testInvalidPlan']);
    Route::get('/invalid-tier', [SubscriptionErrorTestController::class, 'testInvalidTier']);
    Route::get('/stripe-not-configured', [SubscriptionErrorTestController::class, 'testStripeNotConfigured']);
    Route::get('/process-error', [SubscriptionErrorTestController::class, 'testProcessError']);
    Route::get('/invalid-session', [SubscriptionErrorTestController::class, 'testInvalidSession']);
    Route::get('/verify-error', [SubscriptionErrorTestController::class, 'testVerifyError']);
    Route::get('/checkout-canceled', [SubscriptionErrorTestController::class, 'testCheckoutCanceled']);
    Route::get('/invalid-upgrade', [SubscriptionErrorTestController::class, 'testInvalidUpgrade']);
    Route::get('/upgrade-error', [SubscriptionErrorTestController::class, 'testUpgradeError']);
    Route::get('/no-active-subscription', [SubscriptionErrorTestController::class, 'testNoActiveSubscription']);
    Route::get('/cancel-success', [SubscriptionErrorTestController::class, 'testCancelSuccess']);
    Route::get('/cancel-error', [SubscriptionErrorTestController::class, 'testCancelError']);
    Route::get('/no-subscription-resume', [SubscriptionErrorTestController::class, 'testNoSubscriptionToResume']);
    Route::get('/resume-success', [SubscriptionErrorTestController::class, 'testResumeSuccess']);
    Route::get('/resume-error', [SubscriptionErrorTestController::class, 'testResumeError']);
    Route::get('/billing-portal-error', [SubscriptionErrorTestController::class, 'testBillingPortalError']);
    Route::get('/free-plan-success', [SubscriptionErrorTestController::class, 'testFreePlanSuccess']);
    Route::get('/subscription-success', [SubscriptionErrorTestController::class, 'testSubscriptionSuccess']);
    Route::get('/upgrade-success', [SubscriptionErrorTestController::class, 'testUpgradeSuccess']);
});

// Localized error test page
Route::get('/subscription/error-test', [SubscriptionErrorTestController::class, 'index'])->name('subscription.error-test.en');

// API Routes for Upload Limit Checking
Route::middleware(['auth'])->prefix('api')->group(function () {
    Route::get('/can-upload', [SubscriptionController::class, 'canUpload'])->name('api.can-upload');
    Route::get('/subscription-info', [SubscriptionController::class, 'subscriptionInfo'])->name('api.subscription-info');
});

/*
|--------------------------------------------------------------------------
| LOCALIZED ROUTES (Multi-Language Support)
|--------------------------------------------------------------------------
*/

// Localized routes (always enabled for error testing)
Route::prefix('{locale}')
    ->where(['locale' => 'es|pt|fr|de|ja|ko'])
    ->group(function () {
        Route::get('/pricing', [SubscriptionController::class, 'pricing'])->name('pricing.localized');
        Route::get('/subscription/error-test', [SubscriptionErrorTestController::class, 'index'])->name('subscription.error-test.localized');
        Route::middleware(['auth'])->group(function () {
            Route::get('/subscribe/{tier}/{billing}', [SubscriptionController::class, 'checkout'])
                ->name('subscribe.checkout.localized')
                ->where(['tier' => 'free|artist|label', 'billing' => 'monthly|annual']);
            Route::get('/subscribe/success', [SubscriptionController::class, 'success'])->name('subscribe.success.localized');
            Route::get('/subscribe/cancel', [SubscriptionController::class, 'cancel'])->name('subscribe.cancel.localized');
            Route::get('/subscription', [SubscriptionController::class, 'manage'])->name('subscription.manage.localized');
        });
    });

?>