@extends('layouts.app')

@section('css')
    <link rel="stylesheet" href="{{ asset('css/purchase.css') }}">
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    <script src="https://js.stripe.com/v3/"></script>
@endsection

@section('content')
    <div class="main-inner">
        <div class="top">
            <div class="top-content">
                <div class="top-content__image">
                    <img src="{{ asset('storage/' . $item->image) }}" alt="商品画像" width="100%"/>
                </div>
                <div class="top-content__detail">
                    <h1 class="top-content__detail-name">{{ $item->name }}</h1>
                    <p class="top-content__detail-price">¥{{ $item->price }}</p>
                </div>
            </div>

            <table class="pay-table">
                <tr>
                    <td class="pay-table__td">商品代金</td>
                    <td class="pay-table__td">¥{{ $item->price }}</td>
                </tr>
                <tr>
                    <td class="pay-table__td">支払方法</td>
                    <td>
                        <div class="bl_selectCont" id="1">
                            <div class="bl_selectCont_body">
                                <p>コンビニ支払</p>
                            </div>
                        </div>
                        <div class="bl_selectCont" id="2">
                            <div class="bl_selectCont_body">
                                <p>カード支払い</p>
                            </div>
                        </div>
                    </td>
                </tr>
            </table>
        </div>

        <form action="/order" method="post" class="pay-form">
        @csrf
            <div class="select">
                <div class="center">
                    <h2 class="center__title">支払方法</h2>
                    <input type="hidden" name="item_id" value="{{ $item->id }}">
                    <select name="method" class="pay-form__select" id="js_selectToggle" novalidate>
                        <option selected hidden value="">選択してください</option>
                        <option value="1">1. コンビニ支払い</option>
                        <option value="2">2. カード支払い</option>
                    </select>
                    <span class="pay-form__select__triangle">▼</span>
                    <div class="comment-form__Error">
                        @error('method')
                            {{ $errors->first('method') }}
                        @enderror
                    </div>
                </div>
                <div class="bottum">
                    <div class="bottum-header">
                        <h2 class="buttum__title">配送先</h2>
                        <a href="{{ route('item.address', ['item_id' => $item->id]) }}" class="bottum__destination-link">変更する</a>
                    </div>
                    <div class="bottom__destination">
                        <div class="post_code">
                            <p class="post_code-mark">〒</p>
                            <input type="text" readonly class="bottom__destination--post_code" name="post_code" value="{{ $account->post_code }}">
                        </div>
                        <input type="text" readonly class="bottom__destination--address" name="address" value="{{ $account->address }}{{ $account->building }}">
                    </div>
                    @error('post_code')
                        {{ $errors->first('post_code') }}
                    @enderror
                    @error('address')
                        {{ $errors->first('address') }}
                    @enderror
                </div>
            </div>
            <button type="button" class="pay-form__submit" id="js-submit-btn">購入する</button>
        </form>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {

            const selectToggle = document.getElementById('js_selectToggle');
            const selectContainers = document.querySelectorAll('.bl_selectCont');
            const form = document.querySelector('.pay-form');
            const submitBtn = document.getElementById('js-submit-btn');

            // 初期状態：全て非表示
            selectContainers.forEach(c => c.style.display = 'none');

            // 支払方法切り替え
            selectToggle.addEventListener('change', function () {
                const selectedValue = this.value;

                selectContainers.forEach(container => {
                    container.style.display = (container.id === selectedValue) ? 'block' : 'none';
                });
            });

            // Stripe公開キー
            const stripe = Stripe("{{ config('services.stripe.key') }}");
            const createCheckoutUrl = "{{ route('checkout.session') }}";

            submitBtn.addEventListener('click', async () => {

                const method = selectToggle.value;

                if (method === "2") {
                    const itemId = document.querySelector('input[name="item_id"]').value;

                    const response = await fetch(createCheckoutUrl, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        },
                        body: JSON.stringify({ item_id: itemId }),
                    });

                    const session = await response.json();

                    await stripe.redirectToCheckout({
                        sessionId: session.id,
                    });

                } else if (method === "1") {
                    form.submit();

                } else {
                    alert("支払い方法を選択してください");
                }
            });
        });
    </script>

@endsection
