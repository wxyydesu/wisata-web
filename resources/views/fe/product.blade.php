{{-- filepath: c:\xampp\htdocs\LSP\wisata-web\resources\views\fe\product.blade.php --}}
<div class="shop_top" style="animation: fadeInUp 0.8s ease-out;">
    <div class="container">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 30px; animation: slideInDown 0.6s ease-out;">
            <div style="text-align:left;">
                <span style="display: inline-block; border-top: 3px solid #05C3FB; width: 50px; vertical-align: middle;"></span>
                <i class="fas fa-hand-holding-heart" style="color: #05C3FB; margin: 0 16px; font-size: 24px; vertical-align: middle;"></i>
                <span style="font-size: 24px; font-weight: 800; color: #1F1F1F; vertical-align: middle;">Paket Wisata</span>
                <span style="display: inline-block; border-top: 3px solid #05C3FB; width: 50px; vertical-align: middle;"></span>
            </div>
            <a href="{{ route('paket') }}" class="btn btn-sm rounded-pill" style="font-weight:700; background: linear-gradient(135deg, #1F3BB3 0%, #05C3FB 100%); color: white; padding: 10px 25px; transition: all 0.3s ease; box-shadow: 0 4px 12px rgba(31, 59, 179, 0.25);" onmouseover="this.style.transform='translateY(-2px)'; this.style.boxShadow='0 8px 20px rgba(31, 59, 179, 0.35)'" onmouseout="this.style.transform='translateY(0)'">Lihat Semua →</a>
        </div>
        <div class="row shop_box-top">
            @foreach($paketWisata as $paket)
            @php
                $diskon = $paket->diskonAktif ?? null;
                $hargaNormal = $paket->harga_per_pack;
                $hargaDiskon = $hargaNormal;
                if ($diskon && $diskon->persen > 0) {
                    $hargaDiskon = $hargaNormal - ($hargaNormal * $diskon->persen / 100);
                }
                $isNew = \Carbon\Carbon::parse($paket->created_at)->gt(now()->subDays(7));
            @endphp
            <div class="col-md-3 mb-4" style="animation: scaleIn 0.6s ease-out;">
                <div class="card h-100 shadow-sm border-0 position-relative product-card modern-card" style="border-radius: 16px; overflow: hidden; border: 1px solid rgba(5, 195, 251, 0.1);">
                    @if($isNew)
                        <span class="badge" style="position: absolute; top: 12px; left: 12px; z-index: 2; font-size: 12px; background: linear-gradient(135deg, #34B1AA 0%, #2a9a93 100%); padding: 6px 12px; border-radius: 8px; font-weight: 700;">🆕 NEW</span>
                    @endif
                    @if($diskon && $diskon->persen > 0)
                        <span class="badge" style="position: absolute; top: 12px; right: 12px; z-index: 2; font-size: 12px; background: linear-gradient(135deg, #F95F53 0%, #d63c2d 100%); color: white; padding: 6px 12px; border-radius: 8px; font-weight: 700;">{{ $diskon->persen }}% 💰</span>
                    @endif
                    <a href="{{ route('paket.detail', $paket->id) }}" style="overflow: hidden; display: block;">
                        <img src="{{ asset('storage/' . $paket->foto1) }}" class="card-img-top" alt="{{ $paket->nama_paket }}" style="width:100%; height:200px; object-fit:cover; border-radius:16px 16px 0 0; transition: transform 0.4s ease;">
                    </a>
                    <div class="card-body d-flex flex-column p-3">
                        <h5 class="card-title mb-2" style="font-weight:700; min-height:48px; color: #1F1F1F; font-size: 1.05rem;">
                            <a href="{{ route('paket.detail', $paket->id) }}" class="text-decoration-none text-dark">{{ $paket->nama_paket }}</a>
                        </h5>
                        <p class="card-text mb-2" style="color:#666; min-height:40px; font-size: 0.95rem;">{{ \Illuminate\Support\Str::limit($paket->deskripsi, 50) }}</p>
                        <div class="mb-3">
                            @if($diskon && $diskon->persen > 0)
                                <span class="reducedfrom me-2" style="text-decoration:line-through; color:#999; font-size: 0.95rem;">Rp{{ number_format($hargaNormal, 0, ',', '.') }}</span>
                                <span class="actual" style="color: #F95F53; font-weight:800; font-size: 1.1rem;">Rp{{ number_format($hargaDiskon, 0, ',', '.') }}</span>
                            @else
                                <span class="actual" style="color: #05C3FB; font-weight:800; font-size: 1.1rem;">Rp{{ number_format($hargaNormal, 0, ',', '.') }}</span>
                            @endif
                        </div>
                        <div class="mt-auto">
                            <a href="{{ route('paket.detail', $paket->id) }}" class="btn btn-sm rounded-pill w-100" style="font-weight:700; background: linear-gradient(135deg, #1F3BB3 0%, #0a9fd4 100%); color: white; padding: 10px; transition: all 0.3s ease; box-shadow: 0 4px 12px rgba(31, 59, 179, 0.25);" onmouseover="this.style.transform='translateY(-2px)'; this.style.boxShadow='0 8px 20px rgba(31, 59, 179, 0.35)'" onmouseout="this.style.transform='translateY(0)'">Pesan Sekarang →</a>
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
        <div class="row">
            <div class="col-12">
                <div class="d-flex justify-content-center mt-3" style="animation: fadeInUp 0.8s ease-out;">
                    {{ $paketWisata->links() }}
                </div>
            </div>
        </div>
    </div>
</div>
<style>
    .modern-card {
        transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1) !important;
    }

    .product-card:hover {
        transform: translateY(-10px) scale(1.03);
        box-shadow: 0 12px 40px rgba(5, 195, 251, 0.2) !important;
        border-color: #05C3FB !important;
    }

    .product-card:hover .card-img-top {
        transform: scale(1.08);
    }

    .product-card .card-title a:hover {
        color: #05C3FB !important;
    }
</style>