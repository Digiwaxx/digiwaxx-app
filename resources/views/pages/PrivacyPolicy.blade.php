@extends('layouts.app')

  <?php 
  $ban_img='';
  if(is_numeric($banner[0]->pCloudFileID)){
      $ban_img= url('/pCloudImgDownload.php?fileID='.$banner[0]->pCloudFileID);
  }else{
      $ban_img=  url('public/images/'.$banner[0]->banner_image);
  }
  
  ?>


<?php if(!empty($ban_img)){ ?>
<style>
	.privacy-bg{
	    <?php if(is_numeric($banner[0]->pCloudFileID)){?>
                 background-image: url(<?php echo $ban_img;?>);
      <?php }else{ ?>
                    background-image: url(<?php echo $ban_img;?>);
         <?php }?>
		background-size: cover;
		background-repeat: no-repeat;
		padding: 100px;
	}
</style>
<?php } ?>
@section('content')
<section class="top-banner privacy-bg">
    <div class="container">
      <div class="row">
        <div class="col-md-5 col-sm-12">
         <div class="banner-text">
            <h2><?php echo stripslashes($bannerText); ?></h2>
          </div>
       </div>
      </div>
    </div>     
   </section>

<section class="content-area">
     <div class="container">      
        <div class="row">
          <div class="col-md-10 col-sm-12 mx-auto">          	
			<?php echo stripslashes(urldecode($content[0]->page_content)); ?>          	
          </div>
      </div>  
</div>
</section>


<!-- <div class="charts-block">
        	<div class="container">
	            <div class="row">
                    <div class="col-lg-12 col-md-12 col-sm-12">
					   
					   <strong></strong>
<br>
Digiwaxx' s privacy policy covers the collection and use of personal information that may be collected by Digiwaxx anytime you interact with Digiwaxx, such as when you visit our website, when you purchase Digiwaxx products and services, or when you call our sales or support associates. Please take a moment to read the following to learn more about our information practices, including what type of information is gathered, how the information is used and for what purposes, to whom we disclose the information, and how we safeguard your personal information. Your privacy is a priority at Digiwaxx, and we go to great lengths to protect it.
<p>
<strong>Digiwaxx Collects Personal Information</strong>
<br>
Digiwaxx collects personal information because it helps us deliver a superior level of customer service. It enables us to give you convenient access to our products and services and focus on categories of greatest interest to you. In addition, your personal information helps us keep you posted on the latest product announcements, software updates, special offers, and events that you might like to hear about.  If you do not want Digiwaxx to keep you up to date with Digiwaxx news, updates and the latest information on products and services follow the unsubscribe list located at the bottom of the email.
</p><p>
<strong>The Information Digiwaxx Collects</strong>
<br>
There are a number of situations in which your personal information may help us give you better service. For example, we may ask for your personal information when you�re discussing a service issue on the phone with an associate, downloading a software update, registering for a seminar, participating in an online survey, registering your products, or purchasing a product. At such times, we may collect personal information relevant to the situation, such as your name, mailing address, phone number, email address, and contact preferences; your credit card information and information about the Digiwaxx products you own, such as their serial numbers, and date of purchase; and information relating to a support or service issue. We collect information for market research purposes - such as your occupation and where you use your computer - to gain a better understanding of our customers and thus provide more valuable service. We also collect information regarding customer activities on our website, the Digital Waxx Service, the Digital Waxx Record Pool, and on related websites. This helps us to determine how best to provide useful information to customers and to understand which parts of our websites and Internet services are of most interest to them.
The Digiwaxx website, as well as Digiwaxx services such as the Digital Waxx Service, the Digital Waxx Record Pool and our related services, allows you to create an "Digiwaxx ID" based on your personal information. This convenient service saves you time and allows for easier use of our web services. Here's how it works: You create a personal profile - providing your name, phone number, email address, and in some cases your mailing address or a credit card number - and choose a password and password hint (such as the month and day of your birth) for security. The system saves your information and assigns you a personal Digiwaxx ID - in many cases simply your email address, because it's unique and easy to remember. The next time you order from the Digiwaxx Store or register a new product, all you need to do is enter your Digiwaxx ID and password; the system looks up the information it needs to assist you. In addition, if you update the information associated with your Digiwaxx ID it will be available for all your transactions with Digiwaxx globally.
</p><p>
If you use a bulletin board or chat room on an Digiwaxx website you should be aware that any information you share is visible to other users. Personally identifiable information you submit to one of these forums can be read, collected, or used by other individuals to send you unsolicited messages. Digiwaxx is not responsible for the personally identifiable information you choose to submit in these forums.
</p><p>
<strong>Digiwaxx Disclosure of Information</strong>
<br>
Digiwaxx takes your privacy very seriously. Be assured that Digiwaxx does not sell or rent your contact information to other marketers. To help us provide superior service, your personal information may be shared with legal entities within the Digiwaxx group globally who will safeguard it in accordance with Digiwaxx's privacy policy. There are also times when it may be advantageous for Digiwaxx to make certain personal information about you available to companies that Digiwaxx has a strategic relationship with or that perform work for Digiwaxx to provide products and services to you on our behalf. These companies may help us process information, extend credit, fulfill customer orders, deliver products to you, manage and enhance customer data, provide customer service, assess your interest in our products and services, or conduct customer research or satisfaction surveys. These companies are also obligated to protect your personal information in accordance with Digiwaxx's policies. Without such information being made available, it would be difficult for you to purchase products, have products delivered to you, receive customer service, provide us feedback to improve our products and services, or access certain services, offers, and content on the Digiwaxx website.
At times we may be required by law or litigation to disclose your personal information. We may also disclose information about you if we determine that for national security, law enforcement, or other issues of public importance, disclosure is necessary.
</p><p>
<strong>Digiwaxx Protects Your Personal Information</strong>
<br>
Digiwaxx takes precautions - including administrative, technical, and physical measures - to safeguard your personal information against loss, theft, and misuse, as well as unauthorized access, disclosure, alteration, and destruction.
We utilize secure servers, and do our best to protect any information we collect over the internet.  You can help us by also taking precautions to protect your personal data when you are on the Internet. Change your passwords often using a combination of letters and numbers, and make sure you use a secure web browser like Safari.
</p><p>
<strong>Integrity of your personal information</strong>
<br>
Digiwaxx has safeguards in place to keep your personal information accurate, complete, and up to date for the purposes for which it is used. Naturally, you always have the right to access and correct the personal information you have provided. You can help us ensure that your contact information and preferences are accurate, complete, and up to date by checking at www.Digiwaxx.com/Members. And you can request a copy of your personal information, your download history, and your interactions with our sales and support agents by contacting us at the email address below.
</p><p>
<strong>Cookies and other technologies</strong>
<br>
As is standard practice on many corporate websites, Digiwaxx's website uses "cookies" and other technologies to help us understand which parts of our websites are the most popular, where our visitors are going, and how much time they spend there. We also use cookies and other technologies to make sure that our online advertising is bringing customers to our products and services, such as the Digital Waxx Service. We use cookies and other technologies to study traffic patterns on our website, to make it even more rewarding as well as to study the effectiveness of our customer communications. And we use cookies to customize your experience and provide greater convenience each time you interact with us.
</p><p>
Information such as your country and language - and if you're an disc jockey, your station call letters - helps us provide a more useful online experience. And your contact information and information about your computer helps us register your products, personalize your experience, and set up your Internet Service and accounts and provide you with customer service.
</p><p>
As is true of most Web sites, we gather certain information automatically and store it in log files. This information includes internet protocol (IP) addresses, browser type, internet service provider (ISP), referring/exit pages, operating system, date/time stamp, and clickstream data.
We use this information, which does not identify individual users, to analyze trends, to administer the site, to track users' movements around the site and to gather demographic information about our user base as a whole. Digiwaxx will not use the information collected to market directly to that person.
</p><p>
In some of our email messages we use a "click-through URL" linked to content on the Digiwaxx website. When a customer clicks one of these URLs, they pass through our web server before arriving at the destination web page. We track this click-through data to help us determine interest in particular topics and measure the effectiveness of our customer communications. If you prefer not to be tracked simply avoid clicking text or graphic links in the email.
In addition we use pixel tags - tiny graphic images - to tell us what parts of our website customers have visited or to measure the effectiveness of searches customers perform on our site. 
Pixel tags also enable us to send email messages in a format customers can read. And they tell us whether emails have been opened to assure that we're only sending messages that are of interest to our customers. We store all of this information in a secure database located in New York, New York, in the United States.
</p><p>
<strong>Digiwaxx is committed to your privacy</strong>
<br>
As we said, Digiwaxx takes protecting your privacy very seriously. To make sure your personal information is secure, we communicate these guidelines to Digiwaxx employees and strictly enforce privacy safeguards within the company. In addition, Digiwaxx supports industry initiatives - such as the Online Privacy Alliance and TRUSTe - to preserve privacy rights on the Internet and in all aspects of electronic commerce. And we do not knowingly solicit personal information from minors or send them requests for personal information.
Digiwaxx abides by the safe harbor framework set forth by the U.S. Department of Commerce regarding the collection, use, and retention of personal information collected from the European Union. You'll find more information about the U.S. Department of Commerce Safe Harbor Program. 
</p><p>
Digiwaxx's website has links to the sites of other companies. Digiwaxx is not responsible for their privacy practices. We encourage you to learn about the privacy policies of those companies.
</p><p>
<strong>Privacy questions</strong>
<br>
If you have questions or concerns about Digiwaxx�s Customer Privacy Policy or data processing, please contact us at info@digiwaxx.com
</p><p>
Digiwaxx may update its privacy policy from time to time. When we change the policy in a material way a notice will be posted on our website along with the updated privacy policy.              </p>
                        
                    </div> --><!-- eof col -->
                    









                    <!-- eof col
				</div> eof row 
            </div> eof container 
        </div>-->
@endsection