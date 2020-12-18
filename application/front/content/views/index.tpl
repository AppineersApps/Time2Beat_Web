<!doctype html>
<html class="no-js" lang="zxx">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="x-ua-compatible" content="ie=edge">
        <title>Time2Beat</title>
        <meta name="description" content="">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="manifest" href="site.webmanifest">
		<link rel="shortcut icon" type="image/x-icon" href="<%$this->config->item('site_url')%>public/upload/website_images/favicon.ico">

		<!-- CSS here -->
            <link rel="stylesheet" href="<%$this->config->item('site_url')%>public/styles/website/bootstrap.min.css">
            <link rel="stylesheet" href="<%$this->config->item('site_url')%>public/styles/website/owl.carousel.min.css">
            <link rel="stylesheet" href="<%$this->config->item('site_url')%>public/fonts/website/flaticon.css">
            <link rel="stylesheet" href="<%$this->config->item('site_url')%>public/styles/website/slicknav.css">
            <link rel="stylesheet" href="<%$this->config->item('site_url')%>public/styles/website/animate.min.css">
            <link rel="stylesheet" href="<%$this->config->item('site_url')%>public/styles/website/magnific-popup.css">
            <link rel="stylesheet" href="<%$this->config->item('site_url')%>public/styles/website/fontawesome-all.min.css">
            <link rel="stylesheet" href="<%$this->config->item('site_url')%>public/fonts/website/themify-icons.css">
            <link rel="stylesheet" href="<%$this->config->item('site_url')%>public/fonts/website/themify.ttf">
            <link rel="stylesheet" href="<%$this->config->item('site_url')%>public/styles/website/slick.css">
            <link rel="stylesheet" href="<%$this->config->item('site_url')%>public/styles/website/nice-select.css">
            <link rel="stylesheet" href="<%$this->config->item('site_url')%>public/styles/website/style.css">
            <link rel="stylesheet" href="<%$this->config->item('site_url')%>public/styles/website/index_style.css">
   </head>
<style type="text/css">
.logo
{
    width: 70%;
}

</style>
   <body>
       
    <!-- Preloader Start -->
    <div id="preloader-active">
        <div class="preloader d-flex align-items-center justify-content-center">
            <div class="preloader-inner position-relative">
                <div class="preloader-circle"></div>
                <div class="preloader-img pere-text">
                    <img src="<%$this->config->item('site_url')%>public/upload/website_images/logo/logo.png" alt="">
                </div>
            </div>
        </div>
    </div>
    <!-- Preloader Start -->

    <header>
        <!-- Header Start -->
       <div class="header-area header-transparrent ">
            <div class="main-header header-sticky">
                <div class="container">
                    <div class="row align-items-center">
                        <!-- Logo -->
                        <div class="col-xl-2 col-lg-2 col-md-2">
                            <div class="logo">
                                <a href="index.html"><img class="logo" src="<%$this->config->item('site_url')%>public/upload/website_images/logo/logo.png" alt="">
                                </a>
                            </div>
                        </div>
                        <div class="col-xl-10 col-lg-10 col-md-10">
                            <!-- Main-menu -->
                            <div class="main-menu f-right d-none d-lg-block">
                                <nav>
                                    <ul id="navigation">    
                                        <!-- <li class="active"><a href="#home"> Home</a></li> -->
                                        <li class="menu-item menu-item-type-post_type menu-item-object-page menu-item-home current-menu-item page_item page-item-6 current_page_item menu-item-61 et-show-dropdown et-hover"><a href="#home" aria-current="page">Home</a></li>
                                        <!-- <li class="menu-item menu-item-type-post_type menu-item-object-page menu-item-home current-menu-item page_item page-item-6 current_page_item menu-item-61 et-show-dropdown et-hover"><a href="#aboutUs" aria-current="page">About us</a></li> -->

                                        <li><a href="#features">Features</a></li>
                                        <li><a href="#ThisServices">Video</a></li>
                                       <li><a href="mailto:time2beatapp@gmail.com">Contact</a></li>
                                    </ul>
                                </nav>
                            </div>
                        </div>
                        <!-- Mobile Menu -->
                        <div class="col-12">
                            <div class="mobile_menu d-block d-lg-none"></div>
                        </div>
                    </div>
                </div>
            </div>
       </div>
        <!-- Header End -->
    </header>

    <main>

        <!-- Slider Area Start-->
        <div class="slider-area " id="home">
            <div class="slider-active">
                <div class="single-slider slider-height slider-padding sky-blue d-flex align-items-center">
                    <div class="container">
                        <div class="row d-flex align-items-center">
                            <div class="col-lg-6 col-md-9 ">
                                <div class="hero__caption">
                                    <!-- <span data-animation="fadeInUp" data-delay=".4s">Home</span> -->
                                    <h1 data-animation="fadeInUp" data-delay=".6s">Get things done<br>with Time2Beat</h1>
                                    <p data-animation="fadeInUp" data-delay=".8s">Time2Beat makes commuting fun by gamifying your daily commute! compete against yourself or your friends by trying to beat the record time.</p>
                                    <!-- Slider btn -->
                                   <div class="slider-btns">
                                        <!-- Hero-btn -->
                                        <a data-animation="fadeInLeft" data-delay="1.0s" href="#" class="btn radius-btn">Download</a>
                                        <!-- Video Btn -->
                                        <!-- <a data-animation="fadeInRight" data-delay="1.0s" class="popup-video video-btn ani-btn" href="#"><i class="fas fa-play"></i></a> -->
                                   </div>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="hero__img d-none d-lg-block f-right" data-animation="fadeInRight" data-delay="1s">
                                    <img src="<%$this->config->item('site_url')%>public/upload/website_images/hero/hero_right.png" alt="">
                                </div>
                            </div>
                        </div>
                    </div>
                </div> 
                <div class="single-slider slider-height slider-padding sky-blue d-flex align-items-center">
                    <div class="container">
                        <div class="row d-flex align-items-center">
                            <div class="col-lg-6 col-md-9 ">
                                <div class="hero__caption">
                                    <!-- <span data-animation="fadeInUp" data-delay=".4s">App Landing Page</span> -->
                                   <h1 data-animation="fadeInUp" data-delay=".6s">Get things done<br>with Time2Beat</h1>
                                    <p data-animation="fadeInUp" data-delay=".8s">Time2Beat makes commuting fun by gamifying your daily commute! compete against yourself or your friends by trying to beat the record time.</p>
                                    <!-- Slider btn -->
                                   <div class="slider-btns">
                                        <!-- Hero-btn -->
                                        <a data-animation="fadeInLeft" data-delay="1.0s" href="#" class="btn radius-btn">Download</a>
                                        <!-- Video Btn -->
                                        <!-- <a data-animation="fadeInRight" data-delay="1.0s" class="popup-video video-btn ani-btn" href="#"><i class="fas fa-play"></i></a> -->
                                   </div>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="hero__img d-none d-lg-block f-right" data-animation="fadeInRight" data-delay="1s">
                                    <img src="<%$this->config->item('site_url')%>public/upload/website_images/hero/hero_right.png" alt="">
                                </div>
                            </div>
                        </div>
                    </div>
                </div> 
            </div>
        </div>
        <!-- Slider Area End -->


        <!-- Best Features Start -->
        <section class="best-features-area section-padd4" id="features">
            <div class="container">
                <div class="row justify-content-end">
                    <div class="col-xl-7 col-lg-9">
                        <!-- Section Tittle -->
                        <div class="row">
                            <div class="col-lg-10 col-md-10">
                                <div class="section-tittle">
                                    <h2>Some of the best features Of Our App!</h2>
                                </div>
                            </div>
                        </div>
                        <!-- Section caption -->
                        <div class="row">
                            <div class="col-xl-6 col-lg-6 col-md-6">
                                <div class="single-features mb-70">
                                    <div class="features-icon">
                                        <span class="flaticon-support"></span>
                                    </div>
                                    <div class="features-caption">
                                         <h3>Explore Rides</h3>
                                        <p>Explore rides for locations near you </p>
                                    </div>
                                </div>
                            </div>
                             <div class="col-xl-6 col-lg-6 col-md-6">
                                <div class="single-features mb-70">
                                    <div class="features-icon">
                                        <span class="flaticon-support"></span>
                                    </div>
                                    <div class="features-caption">
                                    	 <h3>Share Ride</h3>
                                        <p>Share your best rides with friends and challenge them.</p>
                                        
                                    </div>
                                </div>
                            </div> 
                            <div class="col-xl-6 col-lg-6 col-md-6">
                                <div class="single-features mb-70">
                                    <div class="features-icon">
                                        <span class="flaticon-support"></span>
                                    </div>
                                    <div class="features-caption">
                                       <h3>Leaderboards</h3>
                                        <p>Beat the best time to reach top of leaderboard.</p>
                                    </div>
                                </div>
                            </div>
                             <div class="col-xl-6 col-lg-6 col-md-6">
                                <div class="single-features mb-70">
                                    <div class="features-icon">
                                        <span class="flaticon-support"></span>
                                    </div>
                                    <div class="features-caption">
                                    	<h3>Add Friend</h3>
                                        <p>Make new friends who share your passion</p>
                                        
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Shpe -->
            <div class="features-shpae d-none d-lg-block" >
                <img src="<%$this->config->item('site_url')%>public/upload/website_images/shape/best-features.png" alt="">
            </div>
        </section>
        <!-- Best Features End -->
        <!-- Services Area Start -->
        <section class="service-area sky-blue" id="ThisServices">
            <div class="container" style="padding: 50px 0;">
                <!-- Section Tittle -->
               <div class="row d-flex justify-content-center">
                    <div class="col-lg-6">
                        <div class="section-tittle text-center">
                            <h2>How Can We HelpYour<br>with Time2Beat!</h2>
                        </div>
                    </div>
                </div>
                <!-- Section caption -->
                <div class="row">
                    <div class="col-lg-6">
                        <div class=" text-center mb-30">
                           <div class="et_pb_code_inner"><iframe width="560" height="315" src="https://www.youtube.com/embed/P1JtuxdG-7A" frameborder="0" allow="accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe></div>
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="et_pb_text_6 text-center mb-30">
                            <div class="et_pb_text_inner"><h1 style="color:#000!important;font-size: 45px;font-weight: 600;border: unset;">Video</h1>
<p style="color:#000!important;">Check out the exciting features in action!</div>
                        </div>
                    </div> 
                    
                </div>
            </div>
        </section>
        <!-- Services Area End -->
        <!-- Applic App Start -->
        <div class="applic-apps section-padding2">
            <div class="container-fluid">
                <div class="row">
                    <!-- slider Heading -->
                    <div class="col-xl-4 col-lg-4 col-md-8">
                        <div class="single-cases-info mb-30">
                            <h3>Time2Beat Apps<br> Screenshot</h3>
                            <p>Make it even more exciting by competing with friends in racing to your favourite locations.  </p>
                        </div>
                    </div>
                    <!-- OwL -->
                    <div class="col-xl-8 col-lg-8 col-md-col-md-7">
                        <div class="app-active owl-carousel"> 
                            <div class="single-cases-img">
                                <img src="<%$this->config->item('site_url')%>public/upload/website_images/gallery/App1.png" alt="">
                            </div>
                            <div class="single-cases-img">
                                <img src="<%$this->config->item('site_url')%>public/upload/website_images/gallery/App2.png" alt="">
                            </div>
                            <div class="single-cases-img">
                                <img src="<%$this->config->item('site_url')%>public/upload/website_images/gallery/App3.png" alt="">
                            </div>
                            <div class="single-cases-img">
                                <img src="<%$this->config->item('site_url')%>public/upload/website_images/gallery/App4.png" alt="">
                            </div>
                            <div class="single-cases-img">
                                <img src="<%$this->config->item('site_url')%>public/upload/website_images/gallery/App5.png" alt="">
                            </div>
                            <div class="single-cases-img">
                                <img src="<%$this->config->item('site_url')%>public/upload/website_images/gallery/App6.png" alt="">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- Applic App End -->
        <!-- Best Pricing Start -->
     
        <!-- Best Pricing End -->
        <!-- Pricing Card Start -->
        <!-- <div class="pricing-card-area">
            <div class="container">
                <div class="row">
                    <div class="col-xl-4 col-lg-4 col-md-6">
                        <div class="single-card text-center mb-30">
                            <div class="card-top">
                                <span>2 Years</span>
                                <h4>$05 <span>/ month</span></h4>
                            </div>
                            <div class="card-bottom">
                                <ul>
                                    <li>Increase traffic 50%</li>
                                    <li>E-mail support</li>
                                    <li>10 Free Optimization</li>
                                    <li>24/7  support</li>
                                </ul>
                                <a href="services.html" class="btn card-btn1">Get Started</a>
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-4 col-lg-4 col-md-6">
                        <div class="single-card  text-center mb-30">
                            <div class="card-top">
                                <span>2 Years</span>
                                <h4>$05 <span>/ month</span></h4>
                            </div>
                            <div class="card-bottom">
                                <ul>
                                    <li>Increase traffic 50%</li>
                                    <li>E-mail support</li>
                                    <li>10 Free Optimization</li>
                                    <li>24/7  support</li>
                                </ul>
                                <a href="services.html" class="btn card-btn1">Get Started</a>
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-4 col-lg-4 col-md-6">
                        <div class="single-card text-center mb-30">
                            <div class="card-top">
                                <span>2 Years</span>
                                <h4>$05 <span>/ month</span></h4>
                            </div>
                            <div class="card-bottom">
                                <ul>
                                    <li>Increase traffic 50%</li>
                                    <li>E-mail support</li>
                                    <li>10 Free Optimization</li>
                                    <li>24/7  support</li>
                                </ul>
                                <a href="services.html" class="btn card-btn1">Get Started</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>-->
        <!-- Pricing Card End -->
      
        <!-- Available App  Start-->
        <div class="available-app-area">
            <div class="container">
                <div class="row d-flex justify-content-between">
                    <div class="col-xl-5 col-lg-6">
                        <div class="app-caption">
                            <div class="section-tittle section-tittle3">
                                <h2>Our App Available For Any Device Download now</h2>
                                <p>Download Time2Beat  today!</p>
                                <div class="app-btn">
                                    <a href="#" class="app-btn1"><img src="<%$this->config->item('site_url')%>public/upload/website_images/shape/app_btn1.png" alt=""></a>
                                    <a href="#" class="app-btn2"><img src="<%$this->config->item('site_url')%>public/upload/website_images/shape/app_btn2.png" alt=""></a>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-6 col-lg-6">
                        <div class="app-img">
                            <img src="<%$this->config->item('site_url')%>public/upload/website_images/shape/available-app.png" alt="">
                        </div>
                    </div>
                </div>
            </div>
            <!-- Shape -->
            <div class="app-shape">
                <img src="<%$this->config->item('site_url')%>public/upload/website_images/shape/app-shape-top.png" alt="" class="app-shape-top heartbeat d-none d-lg-block">
                <img src="<%$this->config->item('site_url')%>public/upload/website_images/shape/app-shape-left.png" alt="" class="app-shape-left d-none d-xl-block">
                <!-- <img src="assets/img/shape/app-shape-right.png" alt="" class="app-shape-right bounce-animate "> -->
            </div>
        </div>
        <!-- Available App End-->
        <!-- Say Something Start -->
        <div class="say-something-aera pt-90 pb-90 fix">
            <div class="container">
                <div class="row justify-content-between align-items-center">
                    <div class="offset-xl-1 offset-lg-1 col-xl-5 col-lg-5">
                        <div class="say-something-cap">
                            <h2>Say Hello To The Collaboration Hub.</h2>
                        </div>
                    </div>
                    <div class="col-xl-2 col-lg-3">
                        <div class="say-btn">
                            <a href="mailto:time2beatapp@gmail.com" class="btn radius-btn">Contact Us</a>
                        </div>
                    </div>
                </div>
            </div>
            <!-- shape -->
            <div class="say-shape">
                <img src="<%$this->config->item('site_url')%>public/upload/website_images/shape/say-shape-left.png" alt="" class="say-shape1 rotateme d-none d-xl-block">
                <img src="<%$this->config->item('site_url')%>public/upload/website_images/shape/say-shape-right.png" alt="" class="say-shape2 d-none d-lg-block">
            </div>
        </div>
        <!-- Say Something End -->
     
    </main>


  
   
	<!-- JS here -->
	
		<!-- All JS Custom Plugins Link Here here -->
        <script src="<%$this->config->item('site_url')%>public/js/website/vendor/modernizr-3.5.0.min.js"></script>
		
		<!-- Jquery, Popper, Bootstrap -->
		<script src="<%$this->config->item('site_url')%>public/js/website/vendor/jquery-1.12.4.min.js"></script>
        <script src="<%$this->config->item('site_url')%>public/js/website/popper.min.js"></script>
        <script src="<%$this->config->item('site_url')%>public/js/website/bootstrap.min.js"></script>
	    <!-- Jquery Mobile Menu -->
        <script src="<%$this->config->item('site_url')%>public/js/website/jquery.slicknav.min.js"></script>

		<!-- Jquery Slick , Owl-Carousel Plugins -->
        <script src="<%$this->config->item('site_url')%>public/js/website/owl.carousel.min.js"></script>
        <script src="<%$this->config->item('site_url')%>public/js/website/slick.min.js"></script>
        <!-- Date Picker -->
        <script src="<%$this->config->item('site_url')%>public/js/website/gijgo.min.js"></script>
		<!-- One Page, Animated-HeadLin -->
        <script src="<%$this->config->item('site_url')%>public/js/website/wow.min.js"></script>
		<script src="<%$this->config->item('site_url')%>public/js/website/animated.headline.js"></script>
        <script src="<%$this->config->item('site_url')%>public/js/website/jquery.magnific-popup.js"></script>

		<!-- Scrollup, nice-select, sticky -->
        <script src="<%$this->config->item('site_url')%>public/js/website/jquery.scrollUp.min.js"></script>
        <script src="<%$this->config->item('site_url')%>public/js/website/jquery.nice-select.min.js"></script>
		<script src="<%$this->config->item('site_url')%>public/js/website/jquery.sticky.js"></script>
        
        <!-- contact js -->
        <script src="<%$this->config->item('site_url')%>public/js/website/contact.js"></script>
        <script src="<%$this->config->item('site_url')%>public/js/website/jquery.form.js"></script>
        <script src="<%$this->config->item('site_url')%>public/js/website/jquery.validate.min.js"></script>
        <script src="<%$this->config->item('site_url')%>public/js/website/mail-script.js"></script>
        <script src="<%$this->config->item('site_url')%>public/js/website/jquery.ajaxchimp.min.js"></script>
        
		<!-- Jquery Plugins, main Jquery -->	
        <script src="<%$this->config->item('site_url')%>public/js/website/plugins.js"></script>
        <script src="<%$this->config->item('site_url')%>public/js/website/main.js"></script>
        <script type="text/javascript">

 var initialSrc = "<%$this->config->item('site_url')%>public/upload/website_images/logo/logo.png";
var scrollSrc = "<%$this->config->item('site_url')%>public/upload/website_images/logo/white_logo.png";

$(window).scroll(function() {
   var value = $(this).scrollTop();
   if (value > 100)
      $(".logo").attr("src", scrollSrc);
   else
      $(".logo").attr("src", initialSrc);
});
    </script> 
    </body>
</html>