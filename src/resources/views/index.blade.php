@extends('layouts.app')

@section('css')
    <link rel="stylesheet" href="{{ asset('css/index.css') }}" />
@endsection

@section('content')
    <div class="page-link">
        <a href="/?tab=recommendation&keyword={{ request('keyword') }}" class="tab-link {{ $tab === 'recommendation' ? 'active' : '' }}">おすすめ</a>
        <a href="/?tab=mylist&keyword={{ request('keyword') }}" class="tab-link {{ $tab === 'mylist' ? 'active' : '' }}">マイリスト</a>
    </div>

@if($tab === 'recommendation')
    <div class="recomendation-list" id="tabContent01">
        @foreach($items as $item)
            <div class="item-card">
                <a href="{{ route('item.detail', ['item_id' => $item->id]) }}" class="item-link">
                    <img src="{{ asset('storage/' . $item->image) }}" alt="商品画像" class="img-content" width=100%/>
                    <p class="item-card__name">{{$item->name}}</p>
                    @if ($item->order)
                        <span class="sold-label">SOLD</span>
                    @endif
                </a>
            </div>
        @endforeach
    </div>
@elseif($tab === 'mylist')
    @auth
        <div class="mylist-list" id="tabContent02">
            @foreach($items as $item)
                <div class="item-card">
                    <a href="{{ route('item.detail', ['item_id' => $item->id]) }}" class="item-link">
                        <img src="{{ asset('storage/' . $item->image) }}" alt="商品画像" class="img-content" width=100%/>
                        <p class="item-card__name">{{$item->name}}</p>
                        @if ($item->order)
                            <span class="sold-label">SOLD</span>
                        @endif
                    </a>
                </div>
            @endforeach
        </div>
    @endauth
@endif

@endsection