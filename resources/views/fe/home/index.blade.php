@extends('fe.master')
@section('navbar')
    @include('fe.navbar')
@endsection
@section('slider')
    @include('fe.slider')
@endsection

@section('content')
<div class="banner">
    <div class="main">
		<div class="content-top">
			<h2>Top Paket Wisata</h2>
			<p>Pilihan terbaik penginapan & objek wisata untuk Anda</p>
			<ul id="flexiselDemo3">
				@foreach($topPaket as $paket)
					<li>
						<img src="{{ $paket->foto1 ? asset('storage/' . $paket->foto1) : asset('images/default.jpg') }}" alt="{{ $paket->nama_paket }}" style="height:120px;object-fit:cover;">
						<div style="text-align:center;font-size:14px;margin-top:5px;">{{ $paket->nama_paket }}</div>
					</li>
				@endforeach
			</ul>
			<h3>Pilihan Favorit Pengunjung</h3>
			<script type="text/javascript">
			$(window).load(function() {
				$("#flexiselDemo3").flexisel({
					visibleItems: 5,
					animationSpeed: 1000,
					autoPlay: true,
					autoPlaySpeed: 3000,    		
					pauseOnHover: true,
					enableResponsiveBreakpoints: true,
					responsiveBreakpoints: { 
						portrait: { 
							changePoint:480,
							visibleItems: 1
						}, 
						landscape: { 
							changePoint:640,
							visibleItems: 2
						},
						tablet: { 
							changePoint:768,
							visibleItems: 3
						}
					}
				});
			});
			</script>
			<script type="text/javascript" src="js/jquery.flexisel.js"></script>
		</div>
	</div>
</div>
@endsection

@section('product')
    @include('fe.product')
@endsection