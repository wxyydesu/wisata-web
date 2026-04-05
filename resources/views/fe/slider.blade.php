{{-- filepath: c:\xampp\htdocs\LSP\wisata-web\resources\views\fe\slider.blade.php --}}
<div class="banner" style="animation: fadeInDown 0.8s ease-out;">
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
                <div class="slide_content" style="background: linear-gradient(135deg, rgba(0,0,0,0.4) 0%, rgba(0,0,0,0.2) 100%);">
                    <div class="slide_content_wrap" style="animation: slideInUp 0.8s ease-out;">
                        <!-- Text title -->
                        <h1 class="title" style="font-size: 3.5rem; font-weight: 800; text-shadow: 0 4px 12px rgba(0,0,0,0.3); letter-spacing: -1px;">{{ $title }}</h1>
                        <!-- /Text title -->
                        <div class="button"><a href="{{ $route }}" style="background: linear-gradient(135deg, #05C3FB 0%, #0a9fd4 100%); padding: 14px 40px; border-radius: 12px; font-weight: 700; transition: all 0.3s ease; box-shadow: 0 8px 20px rgba(5, 195, 251, 0.3);" onmouseover="this.style.transform='translateY(-3px)'; this.style.boxShadow='0 12px 30px rgba(5, 195, 251, 0.4)'" onmouseout="this.style.transform='translateY(0)'">Jelajahi Sekarang ✨</a></div>
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