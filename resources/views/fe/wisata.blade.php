{{-- filepath: c:\xampp\htdocs\LSP\wisata-web\resources\views\fe\product.blade.php --}}
<div class="shop_top">
    <div class="container">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 30px;">
            <div style="text-align:left;">
                <span style="display: inline-block; border-top: 2px solid #28a745; width: 50px; vertical-align: middle;"></span>
                <i class="fas fa-globe-asia" style="color: #007bff; margin: 0 12px; font-size: 22px; vertical-align: middle;"></i>
                <span style="font-size: 22px; font-weight: 700; color: #333; vertical-align: middle;">Wisata</span>
                <span style="display: inline-block; border-top: 2px solid #28a745; width: 50px; vertical-align: middle;"></span>
            </div>
            <a href="{{ route('objekwisata.front') }}" class="btn btn-dark btn-sm rounded-pill" style="font-weight:600;">Lihat Semua</a>
        </div>
        <div class="row shop_box-top">
            @foreach($obyekWisata as $wisata)
            @php
                $diskon = $wisata->diskonAktif ?? null;
                $hargaNormal = $wisata->harga_per_pack;
                $hargaDiskon = $hargaNormal;
                if ($diskon && $diskon->persen > 0) {
                    $hargaDiskon = $hargaNormal - ($hargaNormal * $diskon->persen / 100);
                }
                $isNew = \Carbon\Carbon::parse($wisata->created_at)->gt(now()->subDays(7));
            @endphp
            <div class="col-md-3 mb-4">
                <div class="card h-100 shadow-sm border-0 position-relative product-card" style="transition:transform .2s;">
                    @if($isNew)
                        <span class="badge bg-danger position-absolute" style="top:12px;left:12px;z-index:2;font-size:13px;">NEW</span>
                    @endif
                    @if($diskon && $diskon->persen > 0)
                        <span class="badge bg-warning text-dark position-absolute" style="top:12px;right:12px;z-index:2;font-size:13px;">{{ $diskon->persen }}% OFF</span>
                    @endif
                    <a href="{{ route('detail.objekwisata', $wisata->id) }}">
                        <img src="{{ asset('storage/' . $wisata->foto1) }}" class="card-img-top" alt="{{ $wisata->nama_wisata }}" style="width:100%; height:200px; object-fit:cover; border-radius:8px 8px 0 0;">
                    </a>
                    <div class="card-body d-flex flex-column">
                        <h5 class="card-title mb-2" style="font-weight:600; min-height:48px;">
                            <a href="{{ route('detail.objekwisata', $wisata->id) }}" class="text-decoration-none text-dark">{{ $wisata->nama_wisata }}</a>
                        </h5>
                        <p class="card-text mb-2" style="color:#555; min-height:40px;">{{ \Illuminate\Support\Str::limit($wisata->deskripsi, 50) }}</p>
                        <div class="mb-2">
                            @if($diskon && $diskon->persen > 0)
                                <span class="reducedfrom me-2" style="text-decoration:line-through; color:#999;">Rp{{ number_format($hargaNormal, 0, ',', '.') }}</span>
                                <span class="actual" style="color:#f00; font-weight:bold;">Rp{{ number_format($hargaDiskon, 0, ',', '.') }}</span>
                            @else
                                <span class="actual" style="color:#007bff; font-weight:bold;">Rp{{ number_format($hargaNormal, 0, ',', '.') }}</span>
                            @endif
                        </div>
                        <div class="mt-auto">
                            <a href="{{ route('detail.objekwisata', $wisata->id) }}" class="btn btn-dark btn-sm rounded-pill w-100" style="font-weight:600;">Order Now</a>
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
        <div class="row">
            <div class="col-12">
                <div class="d-flex justify-content-center mt-3">
                    {{ $obyekWisata->links() }}
                </div>
            </div>
        </div>
    </div>
</div>
<style>
    .product-card:hover {
        transform: translateY(-6px) scale(1.03);
        box-shadow: 0 6px 24px rgba(0,0,0,0.13);
    }
    .product-card .card-title a:hover {
        color: #28a745;
    }
</style>