<!DOCTYPE HTML>
<html>
<head>
<title>WisataLokal.com</title>
{{-- <link href="{{ asset('fe/css/bootstrap.css') }}" rel='stylesheet' type='text/css' /> --}}
<link href="{{ asset('fe/css/style.css') }}" rel='stylesheet' type='text/css' />
<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
<link href='http://fonts.googleapis.com/css?family=Open+Sans:400,300,600,700,800' rel='stylesheet' type='text/css'>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
<link rel="stylesheet" href="{{ asset('fe/css/fwslider.css') }}" media="all">
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
<!-- Bootstrap 5 JS Bundle with Popper -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
<!-- Font Awesome untuk ikon -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
<script type="application/x-javascript"> addEventListener("load", function() { setTimeout(hideURLbar, 0); }, false); function hideURLbar(){ window.scrollTo(0,1); } </script>
<script src="{{ asset('fe/js/jquery.min.js') }}"></script>
<script src="{{ asset('fe/js/jquery-ui.min.js') }}"></script>
<script src="{{ asset('fe/js/fwslider.js') }}"></script>
<!-- Toastify CSS -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/toastify-js/src/toastify.min.css">

<!-- Toastify JS -->
<script src="https://cdn.jsdelivr.net/npm/toastify-js"></script>
<style>
    .discount-badge {
        background: #ff5722;
        color: white;
        padding: 2px 8px;
        border-radius: 4px;
        font-size: 12px;
        display: inline-block;
        margin-left: 10px;
    }
    
    .itinerary {
        background: #f9f9f9;
        padding: 15px;
        border-radius: 5px;
        margin-top: 15px;
    }
    
    .itinerary h5 {
        margin-top: 15px;
        color: #333;
    }
    
    .card {
        margin-bottom: 20px;
        border: none;
        box-shadow: 0 2px 10px rgba(0,0,0,0.1);
    }
    
    .form-control {
        padding: 10px;
        border-radius: 4px;
    }
    
    .btn-primary {
        background-color: #007bff;
        border-color: #007bff;
        padding: 10px 20px;
    }
    
    .alert-info {
        background-color: #f8f9fa;
        border-color: #ddd;
    }
    
    .success-icon {
        font-size: 80px;
        color: #28a745;
        margin-bottom: 20px;
    }
    
    .booking-code {
        font-size: 24px;
        font-weight: bold;
        color: #007bff;
    }
    .dropdown-menu {
        background-color: #fff !important;
    }
    
    .dropdown-item {
        color: #000 !important;
    }
    
    .dropdown-item:hover {
        background-color: #f8f9fa !important;
        color: #000 !important;
    }
    
    /* Style untuk gambar profil */
    .profile-img {
        width: 30px;
        height: 30px;
        border-radius: 50%;
        object-fit: cover;
    }
    .profile-img {
        width: 30px;
        height: 30px;
        border-radius: 50%;
        object-fit: cover;
    }
    .package-container {
        display: flex;
        flex-wrap: wrap;
        gap: 20px;
        margin-top: 30px;
    }
    .package-card {
        width: calc(33.33% - 20px);
        border: 1px solid #ddd;
        border-radius: 8px;
        overflow: hidden;
        box-shadow: 0 2px 8px rgba(0,0,0,0.1);
        transition: transform 0.3s;
    }
    .package-card:hover {
        transform: translateY(-5px);
    }
    .package-image {
        height: 200px;
        overflow: hidden;
    }
    .package-image img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }
    .package-details {
        padding: 15px;
    }
    .package-title {
        font-size: 18px;
        font-weight: 600;
        margin-bottom: 10px;
    }
    .package-location {
        color: #666;
        margin-bottom: 10px;
        display: flex;
        align-items: center.
    }
    .package-location i {
        margin-right: 5px;
    }
    .package-price {
        font-size: 20px;
        font-weight: 700;
        color: #007bff;
        margin: 10px 0;
    }
    .package-meta {
        display: flex;
        justify-content: space-between;
        color: #666;
        font-size: 14px;
    }
    .package-rating {
        color: #ffc107;
    }
    .book-now {
        display: block;
        width: 100%;
        padding: 10px;
        background: #28a745;
        color: white;
        text-align: center;
        border: none;
        border-radius: 4px;
        margin-top: 15px;
        font-weight: 600;
        cursor: pointer.
    }
    .section-title {
        text-align: center;
        margin: 40px 0 20px;
        font-size: 28px;
        font-weight: 700.
    }
    .search-filters {
        background: #f8f9fa;
        padding: 20px;
        border-radius: 8px;
        margin-bottom: 30px.
    }
    .filter-group {
        margin-bottom: 15px.
    }
    .filter-title {
        font-weight: 600;
        margin-bottom: 8px.
    }
        /* Tambahkan di file CSS Anda */
    .dropdown-reservasi {
        width: 350px;
        max-height: 400px;
        overflow-y: auto;
        padding: 0.
    }

    .dropdown-header {
        padding: 0.75rem 1rem;
        background-color: #f8f9fa.
    }

    .reservasi-item {
        padding: 0.75rem 1rem;
        transition: background-color 0.2s.
    }

    .reservasi-item:hover {
        background-color: #f8f9fa.
    }

    .dropdown-footer {
        padding: 0.75rem 1rem;
        background-color: #f8f9fa.
    }

    @media (max-width: 767.98px) {
        .dropdown-reservasi {
            width: 300px.
        }
    }

    /* Fix SweetAlert2 z-index */
    .swal-top-container {
        z-index: 999999 !important.
    }

    .swal-top-popup {
        margin-top: 70px !important; /* Adjust based on your navbar height */
        z-index: 999999 !important.
    }

    /* Ensure navbar stays below */
    .navbar {
        z-index: 1030 !important; /* Bootstrap default is 1030 */
    }

    /* Fix dropdown menus */
    .dropdown-menu {
        z-index: 1040 !important.
    }
    
    .reducedfrom {
    text-decoration: line-through;
    color: #999.
    }
    
    .actual {
        color: #f00;
        font-weight: bold.
    }
    #flexiselDemo3 {
    padding: 0;
    list-style: none.
    }
    #flexiselDemo3 li {
        padding: 0 10px;
        text-align: center.
    }
</style>
</head>
<body>
    @yield('navbar')
	@yield('slider')
    <div style="padding: 24px 0; text-align: center;">
        <span style="display: inline-block; border-top: 2px solid #007bff; width: 60px; vertical-align: middle;"></span>
        <i class="fas fa-star" style="color: #ffc107; margin: 0 16px; font-size: 22px; vertical-align: middle;"></i>
        <span style="display: inline-block; border-top: 2px solid #007bff; width: 60px; vertical-align: middle;"></span>
    </div>
    @yield('content')
    <hr>
    @yield('product')
    @yield('related-berita')
    <!-- END New Packages Section -->
    
    <div class="footer">
		<div class="container">
			<div class="row">
				<div class="col-md-3">
					<ul class="footer_box">
						<h4>Products</h4>
						<li><a href="#">Snowboards</a></li>
						<li><a href="#">Ski Equipment</a></li>
						<li><a href="#">Winter Gear</a></li>
						<li><a href="#">Apparel</a></li>
					</ul>
				</div>
				<div class="col-md-3">
					<ul class="footer_box">
						<h4>About</h4>
						<li><a href="#">About Us</a></li>
						<li><a href="#">Careers</a></li>
						<li><a href="#">Blog</a></li>
						<li><a href="#">Sustainability</a></li>
					</ul>
				</div>
				<div class="col-md-3">
					<ul class="footer_box">
						<h4>Customer Support</h4>
						<li><a href="#">Contact Us</a></li>
						<li><a href="#">FAQs</a></li>
						<li><a href="#">Shipping Info</a></li>
						<li><a href="#">Returns</a></li>
						<li><a href="#">Size Guide</a></li>
					</ul>
				</div>
				<div class="col-md-3">
					<ul class="footer_box">
						<h4>Newsletter</h4>
						<div class="footer_search">
				    		   <form>
				    			<input type="text" value="Enter your email" onfocus="this.value = '';" onblur="if (this.value == '') {this.value = 'Enter your email';}">
				    			<input type="submit" value="Subscribe">
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
			           <p>© 2023 Snow Adventure. All rights reserved.</p>
		            </div>
					  <dl id="sample" class="dropdown">
				        <dt><a href="#"><span>Change Region</span></a></dt>
				        <dd>
				            <ul>
				                <li><a href="#">United States<img class="flag" src="{{ asset('fe/images/us.png') }}" alt="" /><span class="value">US</span></a></li>
				                <li><a href="#">Canada<img class="flag" src="{{ asset('fe/images/ca.png') }}" alt="" /><span class="value">CA</span></a></li>
				                <li><a href="#">United Kingdom<img class="flag" src="{{ asset('fe/images/uk.png') }}" alt="" /><span class="value">UK</span></a></li>
				                <li><a href="#">Australia<img class="flag" src="{{ asset('fe/images/au.png') }}" alt="" /><span class="value">AU</span></a></li>
				                <li><a href="#">Japan<img class="flag" src="{{ asset('fe/images/jp.png') }}" alt="" /><span class="value">JP</span></a></li>
				            </ul>
				         </dd>
	   				  </dl>
   				</div>
			</div>
		</div>
</body>
</html>