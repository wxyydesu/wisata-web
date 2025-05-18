{{-- filepath: c:\xampp\htdocs\LSP\wisata-web\resources\views\fe\slider.blade.php --}}
<div class="banner">
    <!-- start slider -->
    <div id="fwslider">
        @php
            if (!function_exists('slider_image_path')) {
                function slider_image_path($foto) {
                    if (!$foto) return null;
                    // Jika sudah ada 'storage/' di path, langsung pakai asset()
                    if (str_starts_with($foto, 'storage/')) {
                        return asset($foto);
                    }
                    // Jika hanya nama file, asumsikan di storage/app/public
                    return asset('storage/' . $foto);
                }
            }
        @endphp
        <div class="slider_container">
            @foreach($sliderItems as $item)
            <div class="slide">
                <!-- Slide image -->
                @php
                    $image = '';
                    $title = 'Explore';
                    $route = '#';

                    if ($item->type == 'package') {
                        $image = slider_image_path($item->foto1) ?: asset('fe/images/default-slider.jpg');
                        $title = $item->nama_paket ?? 'Paket Wisata';
                        $route = route('paket.detail', $item->id);
                    } elseif ($item->type == 'accommodation') {
                        $image = slider_image_path($item->foto1) ?: asset('fe/images/default-slider.jpg');
                        $title = $item->nama_penginapan ?? 'Penginapan';
                        $route = route('detail.penginapan', $item->id);
                    } elseif ($item->type == 'attraction') {
                        $image = slider_image_path($item->foto1) ?: asset('fe/images/default-slider.jpg');
                        $title = $item->nama_obyek_wisata ?? 'Objek Wisata';
                        $route = route('detail.objekwisata', $item->id);
                    }
                @endphp

                <div style="position:relative; width:100%; aspect-ratio: 19/9; overflow:hidden;">
                    <img src="{{ $image }}" class="img-responsive" alt="{{ $title }}"
                        style="width:100%; height:100%; object-fit:cover; object-position:center; display:block;"/>
                </div>

                <!-- Texts container -->
                <div class="slide_content">
                    <div class="slide_content_wrap">
                        <!-- Text title -->
                        <h1 class="title">{{ $title }}</h1>
                        <!-- /Text title -->
                        <div class="button"><a href="{{ $route }}">Book Now</a></div>
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