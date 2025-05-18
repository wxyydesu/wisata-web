@extends('fe.master')
@section('navbar')
    @include('fe.navbar')
@endsection
@section('slider')
    @include('fe.slider')
@endsection

@section('content')
<style>
    .top_box {
        margin-bottom: 30px;
    }
    .card-penginapan {
        background: #fff;
        border-radius: 12px;
        box-shadow: 0 2px 12px rgba(0,0,0,0.08);
        overflow: hidden;
        transition: box-shadow 0.2s;
        position: relative;
        min-height: 410px;
        display: flex;
        flex-direction: column;
        justify-content: space-between;
    }
    .card-penginapan:hover {
        box-shadow: 0 8px 24px rgba(0,0,0,0.16);
        transform: translateY(-4px) scale(1.01);
    }
    .card-penginapan .img-responsive {
        border-top-left-radius: 12px;
        border-top-right-radius: 12px;
        transition: filter 0.2s;
    }
    .card-penginapan:hover .img-responsive {
        filter: brightness(0.92);
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
        background: #000000;
        color: #fff;
        border: none;
        border-radius: 6px;
        padding: 7px 18px;
        font-size: 0.98rem;
        transition: background 0.2s;
        margin-bottom: 12px;
        margin-top: 4px;
        text-decoration: none;
        display: inline-block;
    }
    .card-penginapan .btn-detail:hover {
        background: #1565c0;
        color: #fff;
        text-decoration: none;
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
            <a href="{{ route('penginapan') }}" class="btn btn-dark btn-sm rounded-pill" style="font-weight:600;">Lihat Semua</a>
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