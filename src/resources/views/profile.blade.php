@extends('layouts.app')

@section('css')
    <link rel="stylesheet" href="{{ asset('css/profile.css') }}">
@endsection

@section('content')
    <div class="main-inner">
        <h1 class="main-title">
            プロフィール設定
        </h1>
        <form action="/profile" class="main-form" method="post" enctype="multipart/form-data">
        @csrf
            <div class="main-form__image">
                 @if (!empty($account) && !empty($account->image))
                    <img src="{{ asset('storage/' . $account->image) }}" 
                        alt="プロフィール画像" 
                        class="img-content" width="300px" height="300px">
                @else
                    <img src="{{ asset('storage/noimage.png') }}" 
                        class="img-content">
                    <p class="noimage-cont"></p>
                @endif
                <input type="file" name="image" accept="image/*" hidden id="imageInput">
                <button type="button" class="form__input-image" onclick="document.getElementById('imageInput').click();">
                    画像を選択する
                </button>
                <div class="main-form__error">
                    @error('image')
                        {{ $errors->first('image') }}
                    @enderror
                </div>
            </div>

            <label class="main-form__label">ユーザー名</label>
            @if (!empty($account) && !empty($account->image))
                <input type="text" class="main-form__input" name="name" value="{{$account->name}}   ">
            @else
                <input type="text" class="main-form__input" name="name">
            @endif
            <div class="main-form__error">
                @error('name')
                    {{ $errors->first('name') }}
                @enderror
            </div>

            <label class="main-form__label">郵便番号</label>
            @if (!empty($account) && !empty($account->image))
                <input type="text" class="main-form__input" name="post_code" value="{{$account->post_code}}">
            @else
                <input type="text" class="main-form__input" name="post_code">
            @endif
            <div class="main-form__error">
                @error('post_code')
                    {{ $errors->first('post_code') }}
                @enderror
            </div>

            <label for="" class="main-form__label"> 住所</label>
            @if (!empty($account) && !empty($account->image))
                <input type="text" class="main-form__input" name="address" value="{{$account->address}}">
            @else
                <input type="text" class="main-form__input" name="address">
            @endif
            <div class="main-form__error">
                @error('address')
                    {{ $errors->first('address') }}
                @enderror
            </div>

            <label class="main-form__label"> 建物名</label>
             @if (!empty($account) && !empty($account->image))
                <input type="text" class="main-form__input" name="building" value="{{$account->building}}">
            @else
                <input type="text" class="main-form__input" name="building">
            @endif
            <input type="submit" class="main-dorm__submit" value="更新する">
        </form>
    </div>
@endsection