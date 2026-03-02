@extends('layouts.app')

@section('css')
    <link rel="stylesheet" href="{{ asset('css/mypage.css') }}" />
@endsection

@section('content')
    <div class="mypage">
        <div class="mypage-inner">
            <img src="{{ asset('storage/' . $account->image) }}" alt="プロフィール画像" class="mypage__image" width="100px" height="100px">
            <p class="mypage__user-name">{{$account->name}}</p>
        </div>
        <a href="/mypage/profile" class="mypage__link">プロフィールを編集</a>
    </div>
    <div class="page-link__group">
        <a href="/mypage?page=sell" class="page-link {{ $page === 'sell' ? 'active' : '' }}">出品した商品</a>
        <a href="/mypage?page=buy" class="page-link {{ $page === 'buy' ? 'active' : '' }}">購入した商品</a>
        <a href="/mypage?page=trading" class="page-link {{ $page === 'trading' ? 'active' : '' }}">取引中の商品</a>
        @if($unread_count > 0)
            <span class="badge">
                {{ $unread_count }}
            </span>
        @endif
    </div>

@if($page === 'sell')
    <div class="item-list" id="pageContent01">
        @foreach($items as $item)
            <div class="item-card">
                <a href="/item/{{ $item->id }}" class="item-link">
                    <img src="{{ asset('storage/' . $item->image) }}" alt="商品画像" class="img-content" width=100%/>
                </a>
                <p class="item-card__name">{{$item->name}}</p>
            </div>
        @endforeach
    </div>
@elseif($page === 'buy')
    @auth
        <div class="item-list" id="pageContent02">
            @foreach($items as $item)
                <div class="item-card">
                    <a href="/item/{{ $item->id }}" class="item-link">
                        <img src="{{ asset('storage/' . $item->image) }}" alt="商品画像" class="img-content" width=100%/>
                    </a>
                    <p class="item-card__name">{{$item->name}}</p>
                </div>
            @endforeach
        </div>
    @endauth
@elseif($page === 'trading')
    @auth
        <div class="item-list" id="pageContent02">
            @foreach($trading as $order)
                <div class="item-card">
                    <a href="/chat/{{ $order->id }}" class="item-link">
                        <img src="{{ asset('storage/' . $order->item->image) }}" alt="商品画像" class="img-content" width=100%/>
                    </a>
                    <p class="item-card__name">{{$order->item->name}}</p>
                    @if($order->unread_count > 0)
                        <span class="badge">
                            {{ $order->unread_count }}
                        </span>
                    @endif
                </div>
            @endforeach
        </div>
    @endauth
@endif

@endsection