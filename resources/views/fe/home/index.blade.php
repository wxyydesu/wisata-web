@extends('fe.master')
@section('navbar')
    @include('fe.navbar')
@endsection
@section('slider')
    @include('fe.slider')
@endsection

@section('wisata')
    @include('fe.wisata')
@endsection
@section('content')
<style>
    .top_box {
        margin-bottom: 30px;
    }
    .card-penginapan {
        background: #fff;
        border-radius: 15px;
        box-shadow: 0 4px 15px rgba(0,0,0,0.08);
        overflow: hidden;
        transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
        position: relative;
        min-height: 410px;
        display: flex;
        flex-direction: column;
        justify-content: space-between;
        border: 1px solid rgba(5, 195, 251, 0.1);
    }
    .card-penginapan:hover {
        box-shadow: 0 12px 40px rgba(5, 195, 251, 0.2);
        transform: translateY(-8px) scale(1.02);
        border-color: #05C3FB;
    }
    .card-penginapan .img-responsive {
        border-top-left-radius: 15px;
        border-top-right-radius: 15px;
        transition: all 0.4s ease;
    }
    .card-penginapan:hover .img-responsive {
        filter: brightness(0.85) saturate(1.1);
        transform: scale(1.05);
    }
    .card-penginapan .content {
        padding: 18px 16px 10px 16px;
    }
    .card-penginapan h4.m_4 {
        margin: 0 0 8px 0;
        font-weight: 600;
        font-size: 1.15rem;
        color: #2d3a4b;
    }
    .card-penginapan .m_5 {
        color: #6c757d;
        font-size: 0.98rem;
        margin-bottom: 8px;
    }
    .star-rating {
        color: #ffc107;
        font-size: 1.1rem;
        margin-bottom: 8px;
    }
    .card-penginapan .btn-detail {
        background: linear-gradient(135deg, #1F3BB3 0%, #0a9fd4 100%);
        color: #fff;
        border: none;
        border-radius: 10px;
        padding: 10px 20px;
        font-size: 0.98rem;
        transition: all 0.3s ease;
        margin-bottom: 12px;
        margin-top: 4px;
        text-decoration: none;
        display: inline-block;
        font-weight: 600;
        box-shadow: 0 4px 12px rgba(31, 59, 179, 0.25);
    }
    .card-penginapan .btn-detail:hover {
        background: linear-gradient(135deg, #0a9fd4 0%, #05C3FB 100%);
        color: #fff;
        text-decoration: none;
        transform: translateY(-2px);
        box-shadow: 0 8px 20px rgba(5, 195, 251, 0.35);
    }
</style>
	<div class="container">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 30px;">
            <div style="text-align:left;">
                <span style="display: inline-block; border-top: 2px solid #28a745; width: 50px; vertical-align: middle;"></span>
                <i class="fas fa-house-user" style="color: #007bff; margin: 0 12px; font-size: 22px; vertical-align: middle;"></i>
                <span style="font-size: 22px; font-weight: 700; color: #333; vertical-align: middle;">Penginapan</span>
                <span style="display: inline-block; border-top: 2px solid #28a745; width: 50px; vertical-align: middle;"></span>
            </div>
            <a href="{{ route('penginapan') }}" class="btn btn-sm rounded-pill" style="font-weight:700; background: linear-gradient(135deg, #1F3BB3 0%, #05C3FB 100%); color: white; padding: 10px 25px; transition: all 0.3s ease; box-shadow: 0 4px 12px rgba(31, 59, 179, 0.25);" onmouseover="this.style.transform='translateY(-2px)'; this.style.boxShadow='0 8px 20px rgba(31, 59, 179, 0.35)'" onmouseout="this.style.transform='translateY(0)'">Lihat Semua →</a>
        </div>
		<div class="row">
			@foreach($topPenginapan as $penginapan)
			<div class="col-md-4 top_box">
				<div class="card-penginapan">
					<a href="{{ route('detail.penginapan', $penginapan->id) }}">
						<img src="{{ $penginapan->foto1 ? asset('storage/' . $penginapan->foto1) : asset('fe/images/default-slider.jpg') }}" class="img-responsive" alt="{{ $penginapan->nama_penginapan }}" style="width:100%;height:220px;object-fit:cover;"/>
					</a>
					<div class="content">
						<h4 class="m_4">
							<a href="{{ route('detail.penginapan', $penginapan->id) }}" style="color:#1e88e5;text-decoration:none;">
								{{ $penginapan->nama_penginapan }}
							</a>
						</h4>
						<div class="star-rating">
							@for($i=0; $i<5; $i++)
								@if($i < rand(3,5))
									<i class="fa fa-star"></i>
								@else
									<i class="fa fa-star-o"></i>
								@endif
							@endfor
						</div>
						<p class="m_5">
							<i class="fa fa-bed"></i>
							{{ \Illuminate\Support\Str::limit($penginapan->fasilitas, 40) }}
						</p>
						<p style="color:#444;font-size:0.97rem;">
							{{ \Illuminate\Support\Str::limit($penginapan->deskripsi, 60) }}
						</p>
						<a href="{{ route('detail.penginapan', $penginapan->id) }}" class="btn-detail">
							Lihat Detail <i class="fa fa-arrow-right"></i>
						</a>
					</div>
				</div>
			</div>
			@endforeach
		</div>
	</div>
@endsection

@section('related-berita')
@include('fe.related-berita')
@endsection

@section('product')
    @include('fe.product')
@endsection