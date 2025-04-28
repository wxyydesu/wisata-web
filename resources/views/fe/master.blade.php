<!--A Design by W3layouts
Author: W3layout
Author URL: http://w3layouts.com
License: Creative Commons Attribution 3.0 Unported
License URL: http://creativecommons.org/licenses/by/3.0/
-->
<!DOCTYPE HTML>
<html>
<head>
<title>Free Snow Bootstrap Website Template | Home :: w3layouts</title>
<link href="{{asset('fe/css/bootstrap.css')}}" rel='stylesheet' type='text/css' />
<link href="{{asset('fe/css/style.css')}}" rel='stylesheet' type='text/css' />
<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link href='http://fonts.googleapis.com/css?family=Open+Sans:400,300,600,700,800' rel='stylesheet' type='text/css'>
<script type="application/x-javascript"> addEventListener("load", function() { setTimeout(hideURLbar, 0); }, false); function hideURLbar(){ window.scrollTo(0,1); } </script>
<script src="{{asset('fe/js/jquery.min.js')}}"></script>
<!--<script src="js/jquery.easydropdown.js"></script>-->
<!--start slider -->
<link rel="stylesheet" href="{{asset('fe/css/fwslider.css')}}" media="all">
<script src="{{asset('fe/js/jquery-ui.min.js')}}"></script>
<script src="{{asset('fe/js/fwslider.js')}}"></script>
<!--end slider -->
<script type="text/javascript">
        $(document).ready(function() {
            $(".dropdown img.flag").addClass("flagvisibility");

            $(".dropdown dt a").click(function() {
                $(".dropdown dd ul").toggle();
            });
                        
            $(".dropdown dd ul li a").click(function() {
                var text = $(this).html();
                $(".dropdown dt a span").html(text);
                $(".dropdown dd ul").hide();
                $("#result").html("Selected value is: " + getSelectedValue("sample"));
            });
                        
            function getSelectedValue(id) {
                return $("#" + id).find("dt a span.value").html();
            }

            $(document).bind('click', function(e) {
                var $clicked = $(e.target);
                if (! $clicked.parents().hasClass("dropdown"))
                    $(".dropdown dd ul").hide();
            });


            $("#flagSwitcher").click(function() {
                $(".dropdown img.flag").toggleClass("flagvisibility");
            });
        });
     </script>
</head>
<body>
	<div class="header">
		<div class="container">
			<div class="row">
			  <div class="col-md-12">
				 <div class="header-left">
					 <div class="logo">
						<a href="index.html"><img src="{{asset('fe/images/logo.png')}}" alt=""/></a>
					 </div>
					 <div class="menu">
						  <a class="toggleMenu" href="#"><img src="{{asset('fe/images/nav.png')}}" alt="" /></a>
						    <ul class="nav" id="nav">
						    	<li><a href="shop.html">Shop</a></li>
						    	<li><a href="team.html">Team</a></li>
						    	<li><a href="experiance.html">Events</a></li>
						    	<li><a href="experiance.html">Experiance</a></li>
						    	<li><a href="shop.html">Company</a></li>
								<li><a href="contact.html">Contact</a></li>								
								<div class="clear"></div>
							</ul>
							<script type="text/javascript" src="js/responsive-nav.js"></script>
				    </div>							
	    		    <div class="clear"></div>
	    	    </div>
	            <div class="header_right" style="display: flex; align-items: center; justify-content: flex-end; gap: 15px;">
	    		  <!-- start search-->
				      	{{-- <div class="search-box">
							<div id="sb-search" class="sb-search">
								<form>
									<input class="sb-search-input" placeholder="Enter your search term..." type="search" name="search" id="search">
									<input class="sb-search-submit" type="submit" value="">
									<span class="sb-icon-search"> </span>
								</form>
							</div>
						</div> --}}
						<!----search-scripts---->
						{{-- <script src="{{asset('fe/js/classie.js')}}"></script>
						<script src="{{asset('fe/js/uisearch.js')}}"></script>
						<script>
							new UISearch( document.getElementById( 'sb-search' ) );
						</script> --}}
						<!----//search-scripts---->
				    <ul class="icon1 sub-icon1 profile_img">
					 <li><a class="active-icon c1" href="#"> </a>
						<ul class="sub-icon1 list">
						  <div class="product_control_buttons">
						  	<a href="#"><img src="{{asset('fe/images/edit.png')}}" alt=""/></a>
						  		<a href="#"><img src="{{asset('fe/images/close_edit.png')}}" alt=""/></a>
						  </div>
						   <div class="clear"></div>
						  <li class="list_img"><img src="{{asset('fe/images/1.jpg')}}" alt=""/></li>
						  <li class="list_desc"><h4><a href="#">velit esse molestie</a></h4><span class="actual">1 x
                          $12.00</span></li>
						  <div class="login_buttons">
							 <div class="check_button"><a href="checkout.html">Check out</a></div>
							 <div class="clear"></div>
						  </div>
						  <div class="clear"></div>
						</ul>
					 </li>
				   </ul>
				   <!-- Add Login and Sign Up buttons -->
				   <div class="auth_buttons" style="display: flex; gap: 10px;">
					@if(session('loginId'))
						<?php
							// Ambil data pengguna berdasarkan ID yang ada di session
							$user = \App\Models\User::find(session('loginId'));
						?>
						<form action="{{route('logout')}}" method="POST">					
							@csrf
								<button type="submit" class="btn">Logout</button>
						</form>
						@else
						<button onCLick="window.location.href='{{route('login')}}'" class="btn btn-primary">Login</button>
						<button onCLick="window.location.href='{{route('register')}}'" class="btn">Sign Up</button>
					@endif	
				   </div>
		        <div class="clear"></div>
	       </div>
	      </div>
		 </div>
	    </div>
	</div>
	<div class="banner">
	<!-- start slider -->
       @yield('slider')
    <!--/slider -->
      </div>
	  <div class="main">
		<div class="content-top">
			<h2>snowboards</h2>
			<p>hendrerit in vulputate velit esse molestie consequat, vel illum dolore</p>
			<div class="close_but"><i class="close1"> </i></div>
				<ul id="flexiselDemo3">
				<li><img src="{{asset('fe/images/board1.jpg')}}" /></li>
				<li><img src="{{asset('fe/images/board2.jpg')}}" /></li>
				<li><img src="{{asset('fe/images/board3.jpg')}}" /></li>
				<li><img src="{{asset('fe/images/board4.jpg')}}" /></li>
				<li><img src="{{asset('fe/images/board5.jpg')}}" /></li>
			</ul>
		<h3>SnowBoard Extreme Series</h3>
			<script type="text/javascript">
		$(window).load(function() {
			$("#flexiselDemo3").flexisel({
				visibleItems: 5,
				animationSpeed: 1000,
				autoPlay: true,
				autoPlaySpeed: 3000,    		
				pauseOnHover: true,
				enableResponsiveBreakpoints: true,
		    	responsiveBreakpoints: { 
		    		portrait: { 
		    			changePoint:480,
		    			visibleItems: 1
		    		}, 
		    		landscape: { 
		    			changePoint:640,
		    			visibleItems: 2
		    		},
		    		tablet: { 
		    			changePoint:768,
		    			visibleItems: 3
		    		}
		    	}
		    });
		    
		});
		</script>
		<script type="text/javascript" src="js/jquery.flexisel.js"></script>
		</div>
	</div>
	<div class="content-bottom">
		<div class="container">
			<div class="row content_bottom-text">
			  <div class="col-md-7">
				<h3>The Mountains<br>Snowboarding</h3>
				<p class="m_1">Lorem ipsum dolor sit amet, consectetuer adipiscing elit, sed diam nonummy nibh euismod tincidunt ut laoreet dolore magna aliquam erat volutpat. Ut wisi enim ad minim veniam, quis nostrud exerci tation ullamcorper suscipit lobortis nisl ut aliquip ex ea commodo consequat. Duis autem vel eum iriure dolor in hendrerit in vulputate velit esse molestie consequat, vel illum dolore eu feugiat nulla facilisis at vero eros et accumsan et iusto odio.</p>
				<p class="m_2">Lorem ipsum dolor sit amet, consectetuer adipiscing elit, sed diam nonummy nibh euismod tincidunt ut laoreet dolore magna aliquam erat volutpat. Ut wisi enim ad minim veniam, quis nostrud exerci tation ullamcorper suscipit lobortis nisl ut aliquip ex ea commodo consequat. Duis autem vel eum iriure dolor in hendrerit in vulputate velit esse molestie consequat, vel illum dolore eu feugiat nulla facilisis at vero eros et accumsan et iusto odio.</p>
			  </div>
			</div>
		</div>
	</div>
	<div class="features">
		<div class="container">
			<h3 class="m_3">Features</h3>
			<div class="close_but"><i class="close1"> </i></div>
			  <div class="row">
				<div class="col-md-3 top_box">
				  <div class="view view-ninth"><a href="single.html">
                    <img src="{{asset('fe/images/pic1.jpg')}}" class="img-responsive" alt=""/>
                    <div class="mask mask-1"> </div>
                    <div class="mask mask-2"> </div>
                      <div class="content">
                        <h2>Hover Style #9</h2>
                        <p>Lorem ipsum dolor sit amet, consectetuer adipiscing.</p>
                      </div>
                   </a> </div>
                  </div>
                  <h4 class="m_4"><a href="#">nostrud exerci ullamcorper</a></h4>
                  <p class="m_5">claritatem insitam</p>
                </div>
                <div class="col-md-3 top_box">
					<div class="view view-ninth"><a href="single.html">
                    <img src="{{asset('fe/images/pic2.jpg')}}" class="img-responsive" alt=""/>
                    <div class="mask mask-1"> </div>
                    <div class="mask mask-2"> </div>
                      <div class="content">
                        <h2>Hover Style #9</h2>
                        <p>Lorem ipsum dolor sit amet, consectetuer adipiscing.</p>
                      </div>
                    </a> </div>
                   <h4 class="m_4"><a href="#">nostrud exerci ullamcorper</a></h4>
                   <p class="m_5">claritatem insitam</p>
				</div>
				<div class="col-md-3 top_box">
					<div class="view view-ninth"><a href="single.html">
                    <img src="{{asset('fe/images/pic3.jpg')}}" class="img-responsive" alt=""/>
                    <div class="mask mask-1"> </div>
                    <div class="mask mask-2"> </div>
                      <div class="content">
                        <h2>Hover Style #9</h2>
                        <p>Lorem ipsum dolor sit amet, consectetuer adipiscing.</p>
                      </div>
                    </a> </div>
                   <h4 class="m_4"><a href="#">nostrud exerci ullamcorper</a></h4>
                   <p class="m_5">claritatem insitam</p>
				</div>
				<div class="col-md-3 top_box1">
					<div class="view view-ninth"><a href="single.html">
                    <img src="{{asset('fe/images/pic4.jpg')}}" class="img-responsive" alt=""/>
                    <div class="mask mask-1"> </div>
                    <div class="mask mask-2"> </div>
                      <div class="content">
                        <h2>Hover Style #9</h2>
                        <p>Lorem ipsum dolor sit amet, consectetuer adipiscing.</p>
                      </div>
                     </a> </div>
                   <h4 class="m_4"><a href="#">nostrud exerci ullamcorper</a></h4>
                   <p class="m_5">claritatem insitam</p>
				</div>
			</div>
		 </div>
	    </div>
		<div class="footer">
			<div class="container">
				<div class="row">
					<div class="col-md-3">
						<ul class="footer_box">
							<h4>Products</h4>
							<li><a href="#">Mens</a></li>
							<li><a href="#">Womens</a></li>
							<li><a href="#">Youth</a></li>
						</ul>
					</div>
					<div class="col-md-3">
						<ul class="footer_box">
							<h4>About</h4>
							<li><a href="#">Careers and internships</a></li>
							<li><a href="#">Sponserships</a></li>
							<li><a href="#">team</a></li>
							<li><a href="#">Catalog Request/Download</a></li>
						</ul>
					</div>
					<div class="col-md-3">
						<ul class="footer_box">
							<h4>Customer Support</h4>
							<li><a href="#">Contact Us</a></li>
							<li><a href="#">Shipping and Order Tracking</a></li>
							<li><a href="#">Easy Returns</a></li>
							<li><a href="#">Warranty</a></li>
							<li><a href="#">Replacement Binding Parts</a></li>
						</ul>
					</div>
					<div class="col-md-3">
						<ul class="footer_box">
							<h4>Newsletter</h4>
							<div class="footer_search">
				    		   <form>
				    			<input type="text" value="Enter your email" onfocus="this.value = '';" onblur="if (this.value == '') {this.value = 'Enter your email';}">
				    			<input type="submit" value="Go">
				    		   </form>
					        </div>
							<ul class="social">	
							  <li class="facebook"><a href="#"><span> </span></a></li>
							  <li class="twitter"><a href="#"><span> </span></a></li>
							  <li class="instagram"><a href="#"><span> </span></a></li>	
							  <li class="pinterest"><a href="#"><span> </span></a></li>	
							  <li class="youtube"><a href="#"><span> </span></a></li>										  				
						    </ul>
		   					
						</ul>
					</div>
				</div>
				<div class="row footer_bottom">
				    <div class="copy">
			           <p>© 2014 Template by <a href="http://w3layouts.com" target="_blank">w3layouts</a></p>
		            </div>
					  <dl id="sample" class="dropdown">
				        <dt><a href="#"><span>Change Region</span></a></dt>
				        <dd>
				            <ul>
				                <li><a href="#">Australia<img class="flag" src="{{asset('fe/images/as.png')}}" alt="" /><span class="value">AS</span></a></li>
				                <li><a href="#">Sri Lanka<img class="flag" src="{{asset('fe/images/srl.png')}}" alt="" /><span class="value">SL</span></a></li>
				                <li><a href="#">Newziland<img class="flag" src="{{asset('fe/images/nz.png')}}" alt="" /><span class="value">NZ</span></a></li>
				                <li><a href="#">Pakistan<img class="flag" src="{{asset('fe/images/pk.png')}}" alt="" /><span class="value">Pk</span></a></li>
				                <li><a href="#">United Kingdom<img class="flag" src="{{asset('fe/images/uk.png')}}" alt="" /><span class="value">UK</span></a></li>
				                <li><a href="#">United States<img class="flag" src="{{asset('fe/images/us.png')}}" alt="" /><span class="value">US</span></a></li>
				            </ul>
				         </dd>
	   				  </dl>
   				</div>
			</div>
		</div>
</body>	
</html>