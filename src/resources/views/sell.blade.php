@extends('layouts.app')

@section('css')
    <link rel="stylesheet" href="{{ asset('css/sell.css') }}">
@endsection

@section('content')
    <div class="main-inner">
        <h1 class="main-title">商品の出品</h1>
        <form action="/listing" method="post" class="main-form" enctype="multipart/form-data">
        @csrf
            <div class="main-form__image">
                <h2 class="main-form__titile">商品画像</h2>
                <div class="main-form__image-submit">
                    <input type="file" name="image" accept="image/*" hidden id="imageInput">
                    <button type="button" class="form__input-image" onclick="document.getElementById('imageInput').click();">
                    画像を選択する
                    </button>
                </div>
                <div class="main-form__error">
                    @error('image')
                        {{ $errors->first('image') }}
                    @enderror
                </div>
            </div>

            <div class="subtitle">
                <h3 class="subtitle__text">商品の詳細</h3>
                <h2 class="main-form__titile">カテゴリー</h2>
                <div class="main-form__category">
                    @foreach($categories as $category)
                        <div class="main-form__category-list">
                            <input type="checkbox"  id="{{$category->id}}" class="main-dorm__category-checkbox" name="category_id[]" value="{{$category->id}}">
                            <label class="main-form__category-label" for="{{$category->id}}">{{$category->name}}</label>
                        </div>  
                    @endforeach
                </div>
                <div class="main-form__error">
                    @error('category_id')
                        {{ $errors->first('category_id') }}
                    @enderror
                </div>
                <h2 class="main-form__titile">商品の状態</h2>
                <select class="main-form__condition-select" name="condition" value="{{request('gender')}}">
                    <option disabled selected value="">選択してください</option>
                    <option value="1" class="main-form__condition-option">良好</option>
                    <option value="2" class="main-form__condition-option">目立った傷や汚れなし</option>
                    <option value="3" class="main-form__condition-option">やや傷や汚れあり</option>
                    <option value="4" class="main-form__condition-option">状態が悪い</option>
                </select>
                <span class="select__triangle">▼</span>
                <div class="main-form__error">
                    @error('condition')
                        {{ $errors->first('condition') }}
                    @enderror
                </div>
            </div>

            <div class="subtitle">
                <h3 class="subtitle__text">商品名と説明</h3>
            </div>
            <label for="" class="main-form__label">商品名</label>
            <input type="text" class="main-form__input" name="name">
            <div class="main-form__error">
                @error('name')
                    {{ $errors->first('name') }}
                @enderror
            </div>
            <label class="main-form__label">ブランド名</label>
            <input type="text" class="main-form__input" name="brand">
            <label class="main-form__label-description"> 商品の説明</label>
            <textarea class="main-form__text" name="description"></textarea>
            <div class="main-form__error">
                @error('description')
                    {{ $errors->first('description') }}
                @enderror
            </div>
            <label class="main-form__label"> 販売価格</label>
            <div class="price-wrap">
                <span class="yen-mark">¥</span>
                <input type="text" class="main-form__input-price" name="price">
            </div>
            <div class="main-form__error">
                @error('price')
                    {{ $errors->first('price') }}
                @enderror
            </div>
            <input type="submit" class="main-dorm__submit" value="出品する">
        </form>
    </div>
@endsection