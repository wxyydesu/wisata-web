<div class="shop_top">
    <div class="container">
        <div class="row shop_box-top">
            @foreach($paketWisata as $paket)
            @php
                $diskon = $paket->diskonAktif ?? null;
                $hargaNormal = $paket->harga_per_pack;
                $hargaDiskon = $hargaNormal;
                if ($diskon && $diskon->persen > 0) {
                    $hargaDiskon = $hargaNormal - ($hargaNormal * $diskon->persen / 100);
                }
                // Paket dianggap baru jika dibuat dalam 7 hari terakhir
                $isNew = \Carbon\Carbon::parse($paket->created_at)->gt(now()->subDays(7));
            @endphp
            <div class="col-md-3">
                <div style="position:relative;">
                    @if($isNew)
                    <span class="new-box" style="position:absolute;top:10px;right:10px;z-index:2;">
                        <span class="new-label" style="background:#f00;color:#fff;padding:2px 8px;border-radius:4px;font-size:16px;font-weight:bold;">NEW</span>
                    </span>
                    @endif
                    <img src="{{ asset('storage/' . $paket->foto1) }}" class="img-responsive" alt="{{ $paket->nama_paket }}" style="width:100%; height:200px; object-fit:cover;" />
                </div>
                <div class="shop_desc">
                    <a href="{{ route('paket.detail', $paket->id) }}"></a>
                    <h3><a href="{{ route('paket.detail', $paket->id) }}"></a><a href="{{ route('paket.detail', $paket->id) }}">{{ $paket->nama_paket }}</a></h3>
                    <p>{{ \Illuminate\Support\Str::limit($paket->deskripsi, 50) }}</p>
                    @if($diskon && $diskon->persen > 0)
                        <span class="reducedfrom">Rp{{ number_format($hargaNormal, 0, ',', '.') }}</span>
                        <span class="actual">Rp{{ number_format($hargaDiskon, 0, ',', '.') }}</span>
                    @else
                        <span class="actual">Rp{{ number_format($hargaNormal, 0, ',', '.') }}</span>
                    @endif
                    <br>
                    <ul class="buttons">
                        <li class="cart"><a href="#">Checkout</a></li>
                        <li class="shop_btn"><a href="{{ route('paket.detail', $paket->id) }}">Read More</a></li>
                        <div class="clear"> </div>
                    </ul>
                </div>
            </div>
            @endforeach
        </div>
        <div class="row">
            <div class="col-12">
                {{ $paketWisata->links() }}
            </div>
        </div>
    </div>
</div>

