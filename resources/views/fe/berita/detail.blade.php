@extends('fe.master')
@section('title', $berita->judul . ' - WisataLokal.com')

@section('navbar')
    @include('fe.navbar')
@endsection

@section('content')
<div class="container py-5">
    <div class="row">
        <!-- Main Content -->
        <div class="col-lg-8">
            <article class="news-detail">
                <!-- Breadcrumb -->
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('home') }}">Beranda</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('berita') }}">Berita</a></li>
                        <li class="breadcrumb-item active" aria-current="page">{{ Str::limit($berita->judul, 30) }}</li>
                    </ol>
                </nav>

                <!-- News Header -->
                <header class="mb-4">
                    <h1 class="fw-bold mb-3">{{ $berita->judul }}</h1>
                    
                    <div class="d-flex align-items-center text-muted mb-3">
                        <div class="d-flex align-items-center me-3">
                            <i class="fas fa-calendar-alt me-2"></i>
                            <span>{{ \Carbon\Carbon::parse($berita->tgl_post)->translatedFormat('d F Y') }}</span>
                        </div>
                        <div class="d-flex align-items-center me-3">
                            <i class="fas fa-eye me-2"></i>
                            <span>{{ number_format($berita->views ?? 0) }}x dilihat</span>
                        </div>
                        <div class="d-flex align-items-center">
                            <i class="fas fa-tag me-2"></i>
                            <span>{{ $berita->kategori->nama_kategori_berita ?? 'Umum' }}</span>
                        </div>
                    </div>
                </header>

                <!-- Featured Image -->
                <div class="featured-image mb-4">
                    <img src="{{ $berita->foto ? asset('storage/'.$berita->foto) : asset('fe/images/default-news.jpg') }}" alt="{{ $berita->judul }}" class="img-fluid rounded-3 w-100" style="max-height: 500px; object-fit: cover;">
                    {{-- <div class="text-end mt-2">
                        <small class="text-muted">Sumber: {{ $berita->sumber_gambar }}</small>
                    </div> --}}
                </div>

                <!-- News Content -->
                <div class="news-content mb-5">
                    {!! $berita->berita !!}
                </div>

                <!-- Tags -->
                @if($berita->tags)
                <div class="tags mb-5">
                    <h5 class="mb-3">Tags:</h5>
                    <div class="d-flex flex-wrap gap-2">
                        @foreach(explode(',', $berita->tags) as $tag)
                        <a href="#" class="btn btn-sm btn-outline-secondary">{{ trim($tag) }}</a>
                        @endforeach
                    </div>
                </div>
                @endif

                <!-- Author Box -->
                <div class="author-box bg-light p-4 rounded-3 mb-5">
                    <div class="d-flex align-items-center">
                        <img src="{{ asset('/images/default-user.jpg') }}" 
                             alt="Penulis" 
                             class="rounded-circle me-3"
                             width="80"
                             height="80">
                        <div>
                            <h5 class="mb-1">Admin WisataLokal</h5>
                            <p class="text-muted mb-2">Tim Konten WisataLokal.com</p>
                            <p class="mb-0">Berbagi informasi terbaru seputar wisata, tips perjalanan, dan rekomendasi destinasi menarik di Indonesia.</p>
                        </div>
                    </div>
                </div>

                <!-- Share Buttons -->
                <div class="share-buttons mb-5">
                    <h5 class="mb-3">Bagikan:</h5>
                    <div class="d-flex gap-2">
                        <a href="https://www.facebook.com/sharer/sharer.php?u={{ urlencode(url()->current()) }}" 
                           target="_blank" 
                           class="btn btn-primary btn-sm rounded-circle"
                           title="Share on Facebook">
                            <i class="fab fa-facebook-f"></i>
                        </a>
                        <a href="https://twitter.com/intent/tweet?text={{ urlencode($berita->judul) }}&url={{ urlencode(url()->current()) }}" 
                           target="_blank" 
                           class="btn btn-info btn-sm rounded-circle text-white"
                           title="Share on Twitter">
                            <i class="fab fa-twitter"></i>
                        </a>
                        <a href="https://wa.me/?text={{ urlencode($berita->judul . ' ' . url()->current()) }}" 
                           target="_blank" 
                           class="btn btn-success btn-sm rounded-circle"
                           title="Share on WhatsApp">
                            <i class="fab fa-whatsapp"></i>
                        </a>
                        <a href="mailto:?subject={{ rawurlencode($berita->judul) }}&body={{ rawurlencode(url()->current()) }}" 
                           class="btn btn-secondary btn-sm rounded-circle"
                           title="Share via Email">
                            <i class="fas fa-envelope"></i>
                        </a>
                    </div>
                </div>

                <!-- Related News -->
                @if($related->count() > 0)
                <section class="related-news">
                    <h4 class="mb-4 fw-bold">Berita Terkait</h4>
                    <div class="row">
                        @foreach($related as $item)
                        <div class="col-md-6 mb-4">
                            <div class="card border-0 h-100">
                                <a href="{{ route('detail-berita', $item->id) }}">
                                    <img src="{{ $item->foto ? asset('storage/'.$item->foto) : asset('fe/images/default-news.jpg') }}" 
                                         class="card-img-top" 
                                         alt="{{ $item->judul }}"
                                         style="height: 180px; object-fit: cover;">
                                </a>
                                <div class="card-body">
                                    <div class="mb-2">
                                        <span class="badge bg-dark">{{ $item->kategori->nama_kategori_berita ?? 'Umum' }}</span>
                                        <small class="text-muted ms-2">{{ $item->tgl_post }}</small>
                                    </div>
                                    <h5 class="card-title">
                                        <a href="{{ route('detail-berita', $item->id) }}" class="text-dark text-decoration-none">
                                            {{ Str::limit($item->judul, 60) }}
                                        </a>
                                    </h5>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </section>
                @endif
            </article>
        </div>

        <!-- Sidebar -->
        <div class="col-lg-4">
            <aside class="sidebar">
                <!-- Popular News -->
                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-header bg-dark text-white">
                        <h5 class="mb-0">Berita Populer</h5>
                    </div>
                    <div class="card-body">
                        @foreach($popularNews as $news)
                        <div class="mb-3 pb-3 border-bottom">
                            <h6 class="mb-1">
                                <a href="{{ route('detail-berita', $news->id) }}" class="text-decoration-none">
                                    {{ Str::limit($news->judul, 50) }}
                                </a>
                            </h6>
                            <span>{{ $berita->tanggal_post }}</span>
                        </div>
                        @endforeach
                    </div>
                </div>

                <!-- Categories -->
                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-header bg-dark text-white">
                        <h5 class="mb-0">Kategori Berita</h5>
                    </div>
                    <div class="card-body">
                        <ul class="list-group list-group-flush">
                            @foreach($newsCategories as $category)
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                <a href="{{ route('berita') }}?kategori={{ $category->id }}" class="text-decoration-none">
                                    {{ $category->kategori_berita }}
                                </a>
                                <span class="badge bg-dark rounded-pill">{{ $category->berita_count }}</span>
                            </li>
                            @endforeach
                        </ul>
                    </div>
                </div>

                <!-- Newsletter -->
                <div class="card border-0 shadow-sm">
                    <div class="card-header bg-dark text-white">
                        <h5 class="mb-0">Newsletter</h5>
                    </div>
                    <div class="card-body">
                        <p>Dapatkan update berita terbaru langsung ke email Anda</p>
                        <form action="#" method="POST">
                            @csrf
                            <div class="mb-3">
                                <input type="email" class="form-control" placeholder="Alamat Email" required>
                            </div>
                            <button type="submit" class="btn btn-dark w-100">Berlangganan</button>
                        </form>
                    </div>
                </div>
            </aside>
        </div>
    </div>
</div>
@endsection

@section('styles')
<style>
    .news-content {
        font-size: 1.1rem;
        line-height: 1.8;
    }
    
    .news-content img {
        max-width: 100%;
        height: auto;
        margin: 1.5rem 0;
        border-radius: 8px;
    }
    
    .news-content iframe {
        max-width: 100%;
        margin: 1.5rem 0;
    }
    
    .news-content h2, 
    .news-content h3, 
    .news-content h4 {
        margin-top: 2rem;
        margin-bottom: 1rem;
        font-weight: 600;
    }
    
    .news-content ul, 
    .news-content ol {
        padding-left: 2rem;
        margin-bottom: 1.5rem;
    }
    
    .news-content blockquote {
        border-left: 4px solid #0d6efd;
        padding-left: 1.5rem;
        margin: 1.5rem 0;
        font-style: italic;
        color: #555;
    }
    
    .btn-rounded-circle {
        width: 40px;
        height: 40px;
        border-radius: 50%;
        display: inline-flex;
        align-items: center;
        justify-content: center;
    }
    
    @media (max-width: 768px) {
        .news-content {
            font-size: 1rem;
        }
        
        .author-box {
            flex-direction: column;
            text-align: center;
        }
        
        .author-box img {
            margin-bottom: 1rem;
            margin-right: 0;
        }
    }
</style>
@endsection