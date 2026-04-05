{{-- filepath: c:\xampp\htdocs\LSP\wisata-web\resources\views\fe\product.blade.php --}}
<div class="shop_top">
    <div class="container">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 30px; animation: slideInDown 0.6s ease-out;">
            <div style="text-align:left;">
                <span style="display: inline-block; border-top: 3px solid #05C3FB; width: 50px; vertical-align: middle;"></span>
                <i class="fas fa-globe-asia" style="color: #05C3FB; margin: 0 16px; font-size: 24px; vertical-align: middle;"></i>
                <span style="font-size: 24px; font-weight: 800; color: #1F1F1F; vertical-align: middle;">Objek Wisata</span>
                <span style="display: inline-block; border-top: 3px solid #05C3FB; width: 50px; vertical-align: middle;"></span>
            </div>
            <a href="{{ route('objekwisata.front') }}" class="btn btn-sm rounded-pill" style="font-weight:700; background: linear-gradient(135deg, #1F3BB3 0%, #05C3FB 100%); color: white; padding: 10px 25px; transition: all 0.3s ease; box-shadow: 0 4px 12px rgba(31, 59, 179, 0.25);" onmouseover="this.style.transform='translateY(-2px)'; this.style.boxShadow='0 8px 20px rgba(31, 59, 179, 0.35)'" onmouseout="this.style.transform='translateY(0)'">Lihat Semua →</a>
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
            <div class="col-md-3 mb-4" style="animation: scaleIn 0.6s ease-out;">
                <div class="modern-card" style="border-radius: 16px; border: 1px solid rgba(5, 195, 251, 0.1); overflow: hidden; background: white; box-shadow: 0 4px 15px rgba(0,0,0,0.08); transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1); height: 100%; display: flex; flex-direction: column; position: relative;">
                    <!-- Badge Section -->
                    <div style="position: absolute; top: 16px; left: 16px; right: 16px; display: flex; gap: 8px; z-index: 10; justify-content: space-between; align-items: flex-start;">
                        @if($isNew)
                            <span style="background: linear-gradient(135deg, #34B1AA 0%, #2a9a93 100%); color: white; padding: 6px 12px; border-radius: 50px; font-size: 11px; font-weight: 700; display: flex; align-items: center; gap: 4px; box-shadow: 0 4px 12px rgba(52, 177, 170, 0.25);">
                                <i class="fas fa-star-of-david"></i> 🆕 BARU
                            </span>
                        @endif
                        @if($diskon && $diskon->persen > 0)
                            <span style="background: linear-gradient(135deg, #F95F53 0%, #d63c2d 100%); color: white; padding: 6px 12px; border-radius: 50px; font-size: 11px; font-weight: 700; display: flex; align-items: center; gap: 4px; box-shadow: 0 4px 12px rgba(249, 95, 83, 0.25); margin-left: auto;">
                                <i class="fas fa-tag"></i> 💰 {{ $diskon->persen }}% OFF
                            </span>
                        @endif
                    </div>

                    <!-- Image Section -->
                    <a href="{{ route('detail.objekwisata', $wisata->id) }}" style="text-decoration: none; overflow: hidden; height: 200px; display: block;">
                        <img src="{{ asset('storage/' . $wisata->foto1) }}" alt="{{ $wisata->nama_wisata }}" style="width: 100%; height: 100%; object-fit: cover; transition: transform 0.4s ease;">
                    </a>

                    <!-- Content Section -->
                    <div style="padding: 20px; flex-grow: 1; display: flex; flex-direction: column;">
                        <!-- Title -->
                        <h5 style="font-size: 1.05rem; font-weight: 700; color: #1F1F1F; margin-bottom: 8px; min-height: 50px; display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden;">
                            <a href="{{ route('detail.objekwisata', $wisata->id) }}" style="text-decoration: none; color: #1F1F1F; transition: color 0.3s ease;">
                                {{ $wisata->nama_wisata }}
                            </a>
                        </h5>

                        <!-- Description -->
                        <p style="font-size: 0.95rem; color: #666; margin-bottom: 16px; min-height: 50px; display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden;">
                            {{ \Illuminate\Support\Str::limit($wisata->deskripsi, 60) }}
                        </p>

                        <!-- Price Section -->
                        <div style="margin-bottom: 16px;">
                            @if($diskon && $diskon->persen > 0)
                                <span class="reducedfrom" style="text-decoration: line-through; color: #999; font-size: 0.95rem; display: inline-block; margin-right: 8px;">Rp{{ number_format($hargaNormal, 0, ',', '.') }}</span>
                                <span class="actual" style="color: #F95F53; font-weight: bold; font-size: 1.1rem;">Rp{{ number_format($hargaDiskon, 0, ',', '.') }}</span>
                            @else
                                <span class="actual" style="color: #05C3FB; font-weight: bold; font-size: 1.1rem;">Rp{{ number_format($hargaNormal, 0, ',', '.') }}</span>
                            @endif
                        </div>

                        <!-- Button -->
                        <a href="{{ route('detail.objekwisata', $wisata->id) }}" style="width: 100%; padding: 12px 16px; background: linear-gradient(135deg, #1F3BB3 0%, #0a9fd4 100%); color: white; text-decoration: none; border: none; border-radius: 10px; font-weight: 700; text-align: center; cursor: pointer; transition: all 0.3s ease; box-shadow: 0 4px 12px rgba(31, 59, 179, 0.25); margin-top: auto;" onmouseover="this.style.transform='translateY(-2px)'; this.style.boxShadow='0 8px 20px rgba(31, 59, 179, 0.35)'" onmouseout="this.style.transform='translateY(0)'">
                            Lihat Detail ✨
                        </a>
                    </div>
                </div>

                <!-- Hover Effects Script -->
                <script>
                    document.addEventListener('DOMContentLoaded', function() {
                        const card = this.closest('.modern-card');
                        if (!card) return;
                        
                        card.addEventListener('mouseenter', function() {
                            this.style.transform = 'translateY(-10px) scale(1.03)';
                            this.style.borderColor = '#05C3FB';
                            this.style.boxShadow = '0 12px 40px rgba(5, 195, 251, 0.2)';
                        });
                        
                        card.addEventListener('mouseleave', function() {
                            this.style.transform = 'translateY(0) scale(1)';
                            this.style.borderColor = 'rgba(5, 195, 251, 0.1)';
                            this.style.boxShadow = '0 4px 15px rgba(0,0,0,0.08)';
                        });

                        const img = card.querySelector('img');
                        if (img) {
                            img.addEventListener('mouseenter', function() {
                                this.style.transform = 'scale(1.08)';
                            });
                            img.addEventListener('mouseleave', function() {
                                this.style.transform = 'scale(1)';
                            });
                        }
                    });
                </script>
            </div>
            @endforeach
        </div>
        <div class="row">
            <div class="col-12">
                <div class="d-flex justify-content-center mt-4" style="animation: fadeInUp 0.8s ease-out;">
                    {{ $obyekWisata->links() }}
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .modern-card {
        transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1) !important;
    }

    .modern-card:hover {
        transform: translateY(-10px) scale(1.03) !important;
        border-color: #05C3FB !important;
        box-shadow: 0 12px 40px rgba(5, 195, 251, 0.2) !important;
    }

    .modern-card img {
        transition: transform 0.4s ease;
    }

    .modern-card:hover img {
        transform: scale(1.08);
    }

    .modern-card .card-title a:hover {
        color: #05C3FB !important;
    }

    .product-card:hover {
        transform: translateY(-10px) scale(1.03);
        box-shadow: 0 12px 40px rgba(5, 195, 251, 0.2);
    }
</style>