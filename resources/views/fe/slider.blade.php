<div class="banner">
    <!-- start slider -->
    <div id="fwslider">
        <div class="slider_container">
            @foreach($sliderItems as $item)
            <div class="slide">
                <!-- Slide image -->
                @php
                    $image = $item->foto1 ?? 'images/default-slider.jpg';
                    $title = $item->name_wisata ?? $item->name_penginapan ?? $item->nama_obyek_wisata ?? 'Explore';
                    $route = $item->type == 'package' ? route('paket.detail', $item->id) : 
                            ($item->type == 'accommodation' ? route('detail.penginapan', $item->id) : 
                            route('detail.obyek-wisata', $item->id));
                @endphp
                
                <img src="{{ asset($image) }}" class="img-responsive" alt="{{ $title }}" style="object-fit: cover; height: 100%; width: 100%;"/>
                
                <!-- Texts container -->
                <div class="slide_content">
                    <div class="slide_content_wrap">
                        <!-- Text title -->
                        <h1 class="title">{{ $title }}</h1>
                        <!-- /Text title -->
                        <div class="button"><a href="{{ $route }}">Explore Now</a></div>
                    </div>
                </div>
                <!-- /Texts container -->
            </div>
            @endforeach
        </div>
        <div class="timers"></div>
        <div class="slidePrev"><span></span></div>
        <div class="slideNext"><span></span></div>
    </div>
    <!--/slider -->
</div>
<style>
    /* Slider Styles */
.slider_container .slide img {
    width: 100%;
    height: 80vh;
    object-fit: cover;
    object-position: center;
}

.slide_content {
    position: absolute;
    bottom: 20%;
    left: 10%;
    color: white;
    text-shadow: 1px 1px 3px rgba(0,0,0,0.8);
}

.slide_content .title {
    font-size: 3rem;
    margin-bottom: 1rem;
}

.button a {
    background: #ff6b00;
    color: white;
    padding: 10px 20px;
    border-radius: 5px;
    text-decoration: none;
    transition: all 0.3s ease;
}

.button a:hover {
    background: #e05d00;
}

/* Responsive adjustments */
@media (max-width: 992px) {
    .slider_container .slide img {
        height: 60vh;
    }
    
    .slide_content .title {
        font-size: 2.5rem;
    }
}

@media (max-width: 768px) {
    .slider_container .slide img {
        height: 50vh;
    }
    
    .slide_content {
        bottom: 15%;
    }
    
    .slide_content .title {
        font-size: 2rem;
    }
}

@media (max-width: 576px) {
    .slider_container .slide img {
        height: 40vh;
    }
    
    .slide_content .title {
        font-size: 1.5rem;
    }
    
    .button a {
        padding: 8px 15px;
        font-size: 0.9rem;
    }
}
</style>