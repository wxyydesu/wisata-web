@extends('fe.master')
@section('navbar')
    @include('fe.navbar')
@endsection
@section('slider')
    @include('fe.slider')
@endsection
@section('content')
<div class="main">
    <div class="container">
        <h2 class="section-title">Snow Adventure Packages</h2>
        
        <!-- Search Filters -->
        <div class="search-filters">
            <div class="row">
                <div class="col-md-3">
                    <div class="filter-group">
                        <div class="filter-title">Destination</div>
                        <select class="form-control">
                            <option>All Destinations</option>
                            <option>Swiss Alps</option>
                            <option>Rocky Mountains</option>
                            <option>Japanese Alps</option>
                            <option>Andes</option>
                        </select>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="filter-group">
                        <div class="filter-title">Duration</div>
                        <select class="form-control">
                            <option>Any Duration</option>
                            <option>Weekend (2-3 days)</option>
                            <option>1 Week</option>
                            <option>2 Weeks</option>
                            <option>Month+</option>
                        </select>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="filter-group">
                        <div class="filter-title">Difficulty</div>
                        <select class="form-control">
                            <option>Any Level</option>
                            <option>Beginner</option>
                            <option>Intermediate</option>
                            <option>Advanced</option>
                            <option>Expert</option>
                        </select>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="filter-group">
                        <div class="filter-title">Price Range</div>
                        <select class="form-control">
                            <option>Any Price</option>
                            <option>Under $500</option>
                            <option>$500 - $1000</option>
                            <option>$1000 - $2000</option>
                            <option>Over $2000</option>
                        </select>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Packages Grid -->
        <div class="package-container">
            <!-- Package 1 -->
            <div class="package-card">
                <div class="package-image">
                    <img src="{{ asset('fe/images/package1.jpg') }}" alt="Swiss Alps Adventure">
                </div>
                <div class="package-details">
                    <div class="package-title">Swiss Alps Adventure</div>
                    <div class="package-location">
                        <i class="fas fa-map-marker-alt"></i> Zermatt, Switzerland
                    </div>
                    <div class="package-price">$1,299</div>
                    <div class="package-meta">
                        <span><i class="far fa-calendar-alt"></i> 7 days</span>
                        <span class="package-rating">
                            <i class="fas fa-star"></i> 4.8
                        </span>
                    </div>
                    <button class="book-now">Book Now</button>
                </div>
            </div>
            
            <!-- Package 2 -->
            <div class="package-card">
                <div class="package-image">
                    <img src="{{ asset('fe/images/package2.jpg') }}" alt="Rocky Mountain Extreme">
                </div>
                <div class="package-details">
                    <div class="package-title">Rocky Mountain Extreme</div>
                    <div class="package-location">
                        <i class="fas fa-map-marker-alt"></i> Banff, Canada
                    </div>
                    <div class="package-price">$899</div>
                    <div class="package-meta">
                        <span><i class="far fa-calendar-alt"></i> 5 days</span>
                        <span class="package-rating">
                            <i class="fas fa-star"></i> 4.6
                        </span>
                    </div>
                    <button class="book-now">Book Now</button>
                </div>
            </div>
            
            <!-- Package 3 -->
            <div class="package-card">
                <div class="package-image">
                    <img src="{{ asset('fe/images/package3.jpg') }}" alt="Japanese Powder Experience">
                </div>
                <div class="package-details">
                    <div class="package-title">Japanese Powder Experience</div>
                    <div class="package-location">
                        <i class="fas fa-map-marker-alt"></i> Niseko, Japan
                    </div>
                    <div class="package-price">$1,599</div>
                    <div class="package-meta">
                        <span><i class="far fa-calendar-alt"></i> 10 days</span>
                        <span class="package-rating">
                            <i class="fas fa-star"></i> 4.9
                        </span>
                    </div>
                    <button class="book-now">Book Now</button>
                </div>
            </div>
            
            <!-- Package 4 -->
            <div class="package-card">
                <div class="package-image">
                    <img src="{{ asset('fe/images/package4.jpg') }}" alt="Beginner's Paradise">
                </div>
                <div class="package-details">
                    <div class="package-title">Beginner's Paradise</div>
                    <div class="package-location">
                        <i class="fas fa-map-marker-alt"></i> Aspen, USA
                    </div>
                    <div class="package-price">$699</div>
                    <div class="package-meta">
                        <span><i class="far fa-calendar-alt"></i> 3 days</span>
                        <span class="package-rating">
                            <i class="fas fa-star"></i> 4.5
                        </span>
                    </div>
                    <button class="book-now">Book Now</button>
                </div>
            </div>
            
            <!-- Package 5 -->
            <div class="package-card">
                <div class="package-image">
                    <img src="{{ asset('fe/images/package5.jpg') }}" alt="Family Snow Vacation">
                </div>
                <div class="package-details">
                    <div class="package-title">Family Snow Vacation</div>
                    <div class="package-location">
                        <i class="fas fa-map-marker-alt"></i> Whistler, Canada
                    </div>
                    <div class="package-price">$1,199</div>
                    <div class="package-meta">
                        <span><i class="far fa-calendar-alt"></i> 7 days</span>
                        <span class="package-rating">
                            <i class="fas fa-star"></i> 4.7
                        </span>
                    </div>
                    <button class="book-now">Book Now</button>
                </div>
            </div>
            
            <!-- Package 6 -->
            <div class="package-card">
                <div class="package-image">
                    <img src="{{ asset('fe/images/package6.jpg') }}" alt="Backcountry Expedition">
                </div>
                <div class="package-details">
                    <div class="package-title">Backcountry Expedition</div>
                    <div class="package-location">
                        <i class="fas fa-map-marker-alt"></i> Chamonix, France
                    </div>
                    <div class="package-price">$1,899</div>
                    <div class="package-meta">
                        <span><i class="far fa-calendar-alt"></i> 14 days</span>
                        <span class="package-rating">
                            <i class="fas fa-star"></i> 5.0
                        </span>
                    </div>
                    <button class="book-now">Book Now</button>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Resort Section -->
<div class="content-bottom" style="background: #f8f9fa; padding: 50px 0;">
    <div class="container">
        <h2 class="section-title">Featured Resorts</h2>
        <div class="row">
            <div class="col-md-4">
                <div class="resort-card">
                    <img src="{{ asset('fe/images/resort1.jpg') }}" alt="Alpine Grand Resort" class="img-responsive">
                    <h3>Alpine Grand Resort</h3>
                    <div class="resort-meta">
                        <span class="resort-rating"><i class="fas fa-star"></i> 4.8</span>
                        <span class="resort-price">From $199/night</span>
                    </div>
                    <p>Luxury accommodation with direct slope access and spa facilities.</p>
                    <a href="#" class="btn btn-primary">View Rooms</a>
                </div>
            </div>
            <div class="col-md-4">
                <div class="resort-card">
                    <img src="{{ asset('fe/images/resort2.jpg') }}" alt="Mountain Peak Lodge" class="img-responsive">
                    <h3>Mountain Peak Lodge</h3>
                    <div class="resort-meta">
                        <span class="resort-rating"><i class="fas fa-star"></i> 4.6</span>
                        <span class="resort-price">From $149/night</span>
                    </div>
                    <p>Cozy lodge with breathtaking views and excellent dining options.</p>
                    <a href="#" class="btn btn-primary">View Rooms</a>
                </div>
            </div>
            <div class="col-md-4">
                <div class="resort-card">
                    <img src="{{ asset('fe/images/resort3.jpg') }}" alt="Snow Valley Chalets" class="img-responsive">
                    <h3>Snow Valley Chalets</h3>
                    <div class="resort-meta">
                        <span class="resort-rating"><i class="fas fa-star"></i> 4.7</span>
                        <span class="resort-price">From $179/night</span>
                    </div>
                    <p>Private chalets with modern amenities and ski-in/ski-out access.</p>
                    <a href="#" class="btn btn-primary">View Rooms</a>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Rental Equipment Section -->
<div class="features">
    <div class="container">
        <h2 class="section-title">Rental Equipment</h2>
        <div class="row">
            <div class="col-md-3">
                <div class="rental-item">
                    <img src="{{ asset('fe/images/rental1.jpg') }}" alt="Premium Snowboard" class="img-responsive">
                    <h4>Premium Snowboard</h4>
                    <p class="price">$35/day</p>
                    <p>High-performance boards for all levels</p>
                    <button class="btn btn-outline-primary">Add to Cart</button>
                </div>
            </div>
            <div class="col-md-3">
                <div class="rental-item">
                    <img src="{{ asset('fe/images/rental2.jpg') }}" alt="Ski Package" class="img-responsive">
                    <h4>Ski Package</h4>
                    <p class="price">$45/day</p>
                    <p>Skis, poles and boots included</p>
                    <button class="btn btn-outline-primary">Add to Cart</button>
                </div>
            </div>
            <div class="col-md-3">
                <div class="rental-item">
                    <img src="{{ asset('fe/images/rental3.jpg') }}" alt="Helmet & Goggles" class="img-responsive">
                    <h4>Helmet & Goggles</h4>
                    <p class="price">$15/day</p>
                    <p>Essential safety equipment</p>
                    <button class="btn btn-outline-primary">Add to Cart</button>
                </div>
            </div>
            <div class="col-md-3">
                <div class="rental-item">
                    <img src="{{ asset('fe/images/rental4.jpg') }}" alt="Winter Clothing" class="img-responsive">
                    <h4>Winter Clothing</h4>
                    <p class="price">$25/day</p>
                    <p>Jackets, pants and gloves</p>
                    <button class="btn btn-outline-primary">Add to Cart</button>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection