<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Mail;

class MailController extends Controller
{
    public function basic_email() {
      $data = array('name'=>"DigiWaxx");
   
      Mail::send(['text'=>'mail'], $data, function($message) {
         $message->to('gurpreet@orientaloutsourcing.com', 'DigiWaxx Dev')->subject
            ('Laravel Basic Testing Mail');
         $message->from('business@digiwaxx.com','DigiWaxx');
      });
      echo "Basic Email Sent. Check your inbox.";
   }
   public function html_email() {
      $data = array('name'=>"DigiWaxx");
      Mail::send('mails.demo', $data, function($message) {
         $message->to('gurpreet@orientaloutsourcing.com', 'DigiWaxx')->subject
            ('DigiWaxx Testing Mail');
         $message->from('business@digiwaxx.com','DigiWaxx');
      });
      echo "HTML Email Sent. Check your inbox.";
   }
   public function attachment_email() {
      $data = array('name'=>"DigiWaxx");
      Mail::send('mail', $data, function($message) {
         $message->to('gurpreet@orientaloutsourcing.com', 'DigiWaxx Dev')->subject
            ('Laravel Testing Mail with Attachment');
         $message->attach('C:\laravel-master\laravel\public\uploads\image.png');
         $message->attach('C:\laravel-master\laravel\public\uploads\test.txt');
         $message->from('business@digiwaxx.com','DigiWaxx');
      });
      echo "Email Sent with attachment. Check your inbox.";
   }
}
