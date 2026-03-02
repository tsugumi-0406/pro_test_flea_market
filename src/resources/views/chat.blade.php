<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Flea_market</title>
    <link rel="stylesheet" href="{{ asset('css/sanitize.css') }}" />
    <link rel="stylesheet" href="{{ asset('css/common.css') }}" />
    <link rel="stylesheet" href="{{ asset('css/chat.css') }}">
</head>

<body>
    <header class="header">
        <img src="{{ asset('storage/logo.svg') }}" alt="アプリロゴ" class="header__logo">
    </header>

    <main>
        <div class="main-inner">
            <div class="side">
                <p class="side-title">その他の取引</p>
                
                @foreach($tradings as $trading)
                    <div class="side-link">
                        <a class="side-link__name" href="/chat/{{ $trading->id }}">
                            {{ $trading->item->name }}
                        </a>
                    </div>
                @endforeach
            </div> 


            <div class="content">
                <div class="title">
                    <img src="" alt="" class="title-icon">
                    @if($status == 'buyer')
                        <h1 class="title__user">
                            「{{ $order->item->account->name }}」さんとの取引画面
                        </h1>
                    @else
                        <h1 class="title__user">
                            「{{ $order->account->name }}」さんとの取引画面
                        </h1>
                    @endif
                    @if($status == 'buyer')
                        <form action="" class="form-tradingend">
                            <button class="form-tradingend__button">取引を完了する</button>
                        </form>
                    @else
                    @endif
                </div>

                <div class="item">
                    <img src="{{ asset('storage/' . $order->item->image) }}" alt="商品画像" class="item-image">
                    <div class="item-data">
                        <p class="item-name">{{ $order->item->name }}</p>
                        <p class="price">{{ $order->item->price }}</p>
                    </div>
                </div>

                <div class="chat">
                    <div class="partner">
                        <img src="" alt="" class="partner-icon">
                        <p class="partner-name"></p>
                        <p class="partner-message"></p>
                    </div>
                    <div class="myself">
                        <img src="" alt="" class="myself-icon">
                        <p class="myself-name"></p>
                        <p class="myself-message"></p>
                    </div>
                </div>
                <form action="/chat/send" method="post" class="form" id="form" enctype="multipart/form-data">
                    @csrf
                    <input type="text" name="order_id" value="{{ $order->id }}" hidden>
                    <textarea class="form__text" name="message" id="message" placeholder="取引メッセージを記入してください"></textarea>
                    <label for="image" class="form__input-image">画像を追加</label>
                    <input type="file" name="image" accept="image/*" hidden id="image">
                    <button class="form__button">
                        <img class="form-button__image" src="{{ asset('storage/inputbutton.jpg') }}" alt="">
                    </button>
                </form>
            </div>
        </div>
    </main>
</body>

<script>
  const key = 'chat_draft_' + location.pathname; // /chat/1 みたいにページごと保存
  const textarea = document.getElementById('message');

  // 復元
  const saved = localStorage.getItem(key);
  if (saved !== null) textarea.value = saved;

  // 入力のたびに保存
  textarea.addEventListener('input', () => {
    localStorage.setItem(key, textarea.value);
  });

  // 送信成功したら消したい場合（フォーム送信に合わせて）
  document.getElementById('form').addEventListener('submit', () => {
    localStorage.removeItem(key);
  });
</script>

</html>