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
    <script type="module" src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.esm.js"></script>
    <script nomodule src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.js"></script>
</head>

<body>
    <header class="header">
        <img src="{{ asset('storage/logo.svg') }}" alt="アプリロゴ" class="header__logo">
    </header>

    <main class="main">
        <!-- サイドバー -->
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

            <!-- 取引相手の表示 -->
            <div class="content">
                <div class="title">
                    <img src="{{ asset('storage/' . $order->item->account->image) }}" alt="アイコン" class="title-icon">
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
                        <a href="#modal1" class="open">取引を完了する</a>
                    @endif

                    <!-- 評価のモーダル -->
                    <div id="modal1" class="modal" role="dialog" aria-modal="true" aria-labelledby="modal1-title">
                    <a href="#" class="overlay" aria-hidden="true"></a>
                        <div class="content" role="document">
                            <div class="content__inner">
                                <div class="content__inner-title">
                                    <p class="modal__title">取引が完了しました。</p>
                                </div>
                                    <p class="modal__sentence">今回の取引相手はどうでしたか？</p>
                                <form action="/assessment" method="POST">
                                @csrf
                                    <div class="assessment" id="assessment">
                                        <ion-icon class="star" data-value="1" name="star"></ion-icon>
                                        <ion-icon class="star" data-value="2" name="star"></ion-icon>
                                        <ion-icon class="star" data-value="3" name="star"></ion-icon>
                                        <ion-icon class="star" data-value="4" name="star"></ion-icon>
                                        <ion-icon class="star" data-value="5" name="star"></ion-icon>
                                    </div>
                                    <input type="hidden" name="assessment" id="assessmentValue" value="0">
                                    <input type="text" name="order_id" value="{{ $order->id }}" hidden>
                                    <div class="modal__button">
                                        <button class="modal__button-dassessment">送信する</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>

                    @if($open_modal)
                        <script>
                            if (location.hash !== '#modal1') location.hash = 'modal1';
                        </script>
                    @endif
                </div>

                <!-- 商品情報の表示 -->
                <div class="item">
                    <img src="{{ asset('storage/' . $order->item->image) }}" alt="商品画像" class="item-image">
                    <div class="item-data">
                        <p class="item-name">{{ $order->item->name }}</p>
                        <p class="price">{{ $order->item->price }}</p>
                    </div>
                </div>
                
                <!-- メッセージの表示 -->
                @foreach($messages as $message)
                    @php
                        $sender = $message->send_account_id
                    @endphp
                    <div class="chat">
                        @if($sender != $account->id)
                            <div class="partner">
                                <div class="chat__account-partner">
                                    <img src="{{ asset('storage/' . $message->sender->image) }}" alt="" class="partner-icon">
                                    <p class="partner-name">{{ $message->sender->name }}</p>
                                </div>
                                <p class="partner-message">{{ $message->message }}</p>
                                <div>
                                    @if($message->image != null)
                                        <label for="image-update__{{ $message->id }}">
                                            <img src="{{ asset('storage/' . $message->image) }}" alt="添付画像" class="product-card__content-image" name="image">
                                        </label>
                                        <input type="file" name="image" accept="image/*" hidden id="image-update__{{ $message->id }}"> 
                                    @endif
                                </div>
                            </div>
                        @elseif($sender == $account->id)
                            <div class="myself">
                                <div class="chat__account-myself">
                                    <p class="myself-name">{{ $message->sender->name }}</p>
                                    <img src="{{ asset('storage/' . $message->sender->image) }}" alt="" class="myself-icon">
                                </div>
                                <div class="message-form">

                                <!-- メッセージ編集フォーム -->
                                    <form action="/chat/update" method="post" class="message-update" enctype="multipart/form-data">
                                    @csrf
                                        <input type="text" name="order_id" value="{{ $order->id }}" hidden>
                                        <div>
                                            <textarea type="text" class="myself-message" name="message">{{ $message->message }}</textarea>
                                        </div>
                                        <div>
                                            @if($message->image != null)
                                            <label for="image-update__{{ $message->id }}">
                                                <img src="{{ asset('storage/' . $message->image) }}" alt="添付画像" class="product-card__content-image" name="image">
                                            </label>
                                            <input type="file" name="image" accept="image/*" hidden id="image-update__{{ $message->id }}"> 
                                            @endif
                                        </div>
                                        <input type="text" value="{{ $message->id }}" name="message_id" hidden>
                                        <button class="message-update__button">編集</button>
                                    </form>

                                    <!-- メッセージ削除フォーム -->
                                    <form action="/chat/delete" method="post" class="message-delete" enctype="multipart/form-data">
                                    @csrf
                                        <input type="text" value="{{ $message->id }}" name="message_id" hidden>
                                        <input type="text" name="order_id" value="{{ $order->id }}" hidden>
                                        <button class="message-delete__button">削除</button>
                                    </form>
                                </div>
                            </div>
                        @endif
                    </div>
                @endforeach

                <!-- メッセージ送信フォーム -->
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
                <div class="main-form__error">
                    @error('message')
                        {{ $errors->first('message') }}
                    @enderror
                </div>
                <div class="main-form__error">
                    @error('image')
                        {{ $errors->first('image') }}
                    @enderror
                </div>
            </div>
        </div>
    </main>
</body>

<script>
  const key = 'chat_draft_' + location.pathname;
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


// 評価の星の色の変更   
    const stars = document.querySelectorAll("#assessment .star");
    const assessmentValue = document.getElementById("assessmentValue");

    function paint(assessment) {
    stars.forEach((star) => {
    const v = Number(star.dataset.value);
    const on = v <= assessment;

    // 色も切り替え（CSSクラス）
    star.classList.toggle("is-on", on);
    });
    }

    stars.forEach((star) => {
    star.addEventListener("click", () => {
    const assessment = Number(star.dataset.value);
    assessmentValue.value = String(assessment);
    paint(assessment);
    });

    // ついで：ホバーでプレビューしたい
    star.addEventListener("mouseenter", () => paint(Number(star.dataset.value)));
    });

    document.getElementById("assessment").addEventListener("mouseleave", () => {
    paint(Number(assessmentValue.value || 0));
    });
</script>

</html>