@extends('layouts.app')

@section('css')
    <link rel="stylesheet" href="{{ asset('css/address.css') }}">
@endsection

@section('content')
    <div class="main-inner">
        <h1 class="main-title">
            住所の変更
        </h1>
        <form action="{{ route('item.updateAddress', ['item_id' => $item->id]) }}" class="main-form" method="post" novalidate>
        @csrf
            <label class="main-form__label">郵便番号</label>
            <input type="text" class="main-form__input" name="post_code">
            <div class="main-form__error">
                @error('post_code')
                    {{ $errors->first('post_code') }}
                @enderror
            </div>
            <label class="main-form__label"> 住所</label>
            <input type="text" class="main-form__input" name="address">
            <div class="main-form__error">
                @error('address')
                    {{ $errors->first('address') }}
                @enderror
            </div>
            <label class="main-form__label"> 建物名</label>
            <input type="text" class="main-form__input" name="building">
            <div class="main-form__error">
                @error('build')
                    {{ $errors->first('build') }}
                @enderror
            </div>
            <input type="submit" class="main-dorm__submit" value="更新する">
        </form>
    </div>
@endsection