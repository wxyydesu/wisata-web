<div class="related-news-section" style="background: #f8f9fa; padding: 40px 0 30px 0; margin-top: 0;">
        <div class="container">
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 30px;">
                <div style="text-align:left;">
                    <span style="display: inline-block; border-top: 2px solid #28a745; width: 50px; vertical-align: middle;"></span>
                    <i class="fas fa-newspaper" style="color: #007bff; margin: 0 12px; font-size: 22px; vertical-align: middle;"></i>
                    <span style="font-size: 22px; font-weight: 700; color: #333; vertical-align: middle;">Related Berita</span>
                    <span style="display: inline-block; border-top: 2px solid #28a745; width: 50px; vertical-align: middle;"></span>
                </div>
                <a href="{{ route('berita') }}" class="btn btn-dark btn-sm rounded-pill" style="font-weight:600;">Lihat Semua</a>
            </div>
            <div class="row" style="gap: 0 0;">
                @if(isset($relatedBerita) && count($relatedBerita))
                    @foreach($relatedBerita as $berita)
                        <div class="col-md-4 mb-4">
                            <div class="card h-100 shadow-sm border-0">
                                <img src="{{ $berita->foto ? asset('storage/'.$berita->foto) : 'https://source.unsplash.com/400x200/?news,travel' }}" class="card-img-top" alt="{{ $berita->judul }}" style="object-fit:cover; height:180px;">
                                <div class="card-body">
                                    <h5 class="card-title" style="font-weight:600;">{{ $berita->judul }}</h5>
                                    <p class="card-text" style="color:#555;">{{ \Illuminate\Support\Str::limit(strip_tags($berita->berita), 80) }}</p>
                                    <a href="{{ route('detail-berita', $berita->id) }}" class="btn btn-outline-primary btn-sm rounded-pill">Baca Selengkapnya</a>
                                </div>
                            </div>
                        </div>
                    @endforeach
                @else
                    <div class="col-12 text-center text-muted">Belum ada berita terbaru.</div>
                @endif
            </div>
        </div>
    </div>