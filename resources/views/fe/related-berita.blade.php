<div class="related-news-section" style="background: linear-gradient(135deg, #f0f7ff 0%, rgba(5, 195, 251, 0.05) 100%); padding: 60px 0 40px 0; margin-top: 0; animation: fadeInUp 0.8s ease-out;">
        <div class="container">
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 40px; animation: slideInDown 0.6s ease-out;">
                <div style="text-align:left;">
                    <span style="display: inline-block; border-top: 3px solid #05C3FB; width: 50px; vertical-align: middle;"></span>
                    <i class="fas fa-newspaper" style="color: #05C3FB; margin: 0 16px; font-size: 24px; vertical-align: middle;"></i>
                    <span style="font-size: 24px; font-weight: 800; color: #1F1F1F; vertical-align: middle;">Berita Terbaru</span>
                    <span style="display: inline-block; border-top: 3px solid #05C3FB; width: 50px; vertical-align: middle;"></span>
                </div>
                <a href="{{ route('berita') }}" class="btn btn-sm rounded-pill" style="font-weight:700; background: linear-gradient(135deg, #1F3BB3 0%, #05C3FB 100%); color: white; padding: 10px 25px; transition: all 0.3s ease; box-shadow: 0 4px 12px rgba(31, 59, 179, 0.25);" onmouseover="this.style.transform='translateY(-2px)'; this.style.boxShadow='0 8px 20px rgba(31, 59, 179, 0.35)'" onmouseout="this.style.transform='translateY(0)'">Lihat Semua →</a>
            </div>
            <div class="row" style="gap: 0 0;">
                @if(isset($relatedBerita) && count($relatedBerita))
                    @foreach($relatedBerita as $berita)
                        <div class="col-md-4 mb-4" style="animation: scaleIn 0.6s ease-out;">
                            <div class="card h-100 shadow-sm border-0 modern-card" style="border-radius: 16px; overflow: hidden; border: 1px solid rgba(5, 195, 251, 0.1); transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);">
                                <img src="{{ $berita->foto ? asset('storage/'.$berita->foto) : 'https://source.unsplash.com/400x200/?news,travel' }}" class="card-img-top" alt="{{ $berita->judul }}" style="object-fit:cover; height:200px; transition: transform 0.4s ease;">
                                <div class="card-body d-flex flex-column p-4">
                                    <h5 class="card-title mb-2" style="font-weight:700; color: #1F1F1F; font-size: 1.05rem; min-height: 50px;">{{ $berita->judul }}</h5>
                                    <p class="card-text mb-3" style="color:#666; font-size: 0.95rem;">{{ \Illuminate\Support\Str::limit(strip_tags($berita->berita), 80) }}</p>
                                    <a href="{{ route('detail-berita', $berita->id) }}" class="btn btn-outline-primary btn-sm rounded-pill mt-auto" style="font-weight:700; border: 2px solid #05C3FB; color: #05C3FB; transition: all 0.3s ease;" onmouseover="this.style.backgroundColor='#05C3FB'; this.style.color='white'" onmouseout="this.style.backgroundColor='transparent'; this.style.color='#05C3FB'">Baca Selengkapnya →</a>
                                </div>
                            </div>
                        </div>
                    @endforeach
                @else
                    <div class="col-12 text-center text-muted py-5" style="font-size: 1.1rem;">
                        <i class="fas fa-inbox mb-3" style="font-size: 3rem; color: #ccc;"></i>
                        <p>Belum ada berita terbaru.</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
    <style>
        .modern-card:hover {
            transform: translateY(-12px);
            box-shadow: 0 15px 50px rgba(5, 195, 251, 0.2) !important;
            border-color: #05C3FB !important;
        }

        .modern-card:hover .card-img-top {
            transform: scale(1.08);
        }

        .modern-card .card-title:hover {
            color: #05C3FB !important;
        }
    </style>