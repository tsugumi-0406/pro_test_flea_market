<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Carbon\Carbon;
use Carbon\CarbonImmutable;
use App\Models\Order;
use Illuminate\Support\Facades\Auth;
use App\Models\Account;
use App\Models\Message;
use App\Models\Assessment;
use App\Http\Requests\ChatRequest;

class ChatController extends Controller
{
    // チャット画面を開く
    public function chat(Request $request, $order_id)
    {
        $now = CarbonImmutable::now();
        $user = Auth::user();
        $account = \App\Models\Account::where('user_id', $user->id)->first();
        $account_id = $account->id;
        $order = Order::where('id', $order_id)->with('item')->first();
        $order_account_id = $order->account_id;
        $seller_account_id = $order->item->account_id;

        // ログインアカウントが購入者の場合
        if($account_id === $order_account_id){
            $order->update(['buyer_last_read_at' => $now]);
            $status = 'buyer';
        // ログインアカウントが出品者の場合
        }elseif($account_id === $seller_account_id){
            $order->update(['seller_last_read_at' => $now]);
            $status = 'seller';
        }

        // サイドバー用の取引中情報の取得
        $trading_buy_orders = Order::where('status', 'trading')
                        ->where('account_id', $account->id)
                        ->with('item')->get();
        $trading_sell_orders = Order::where('status', 'trading')
                        ->whereHas('item', function ($q) use ($account){
                            $q->where('account_id', $account->id);
                        })
                        ->with(['item.account', 'account'])->get();
        $tradings = $trading_buy_orders
                        ->concat($trading_sell_orders)
                        ->unique('id')
                        ->sortByDesc('last_message_at');

        // チャットのメッセージの取得
        $messages = Message::with('sender')
                ->where('order_id', $order_id)
                ->get();
        
        $assessment = Assessment::where('order_id', $order_id)->first();
        
        $open_modal = false;

        if ($status === 'seller' && $assessment) {
            $open_modal = ($assessment->buyer_assessment === null);
        }

        return view('chat', compact('order', 'tradings', 'status', 'messages', 'account', 'open_modal'));
    }

    // メッセージを送信する
    public function send(ChatRequest $request){
        $user = Auth::user();
        $account = \App\Models\Account::where('user_id', $user->id)->first();
        $data = [];
        $data['order_id'] = $request->order_id;
        $data['send_account_id'] = $account->id;
        if ($request->filled('message')) {
            $data['message'] = $request->message;
        }

        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('chat_image', 'public');
            $data['image'] = $path;
        }

        Message::create($data);

        return redirect('/chat/' . $request->order_id);
    }

    // メッセージを編集する
    public function update(Request $request)
    {
        $form = $request->only(['message', 'image']);

        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('chat_image', 'public');
            $form['image'] = $path;
        }
        
        Message::where('id', $request->message_id)->update($form);

        return redirect('/chat/' . $request->order_id);
    }

    // メッセージを削除する
    public function delete(Request $request)
    {
        Message::where('id', $request->message_id)->delete();

        return redirect('/chat/' . $request->order_id);
    }

    // 評価をする
    public function assessment(Request $request)
    {
        $user = Auth::user();
        $account = \App\Models\Account::where('user_id', $user->id)->first();
        $account_id = $account->id;
        $order = Order::where('id', $request->order_id)->with('item')->first();
        $order_account_id = $order->account_id;
        $seller_account_id = $order->item->account_id;

        $data = [];
        // ログインアカウントが購入者の場合
        if($account_id === $order_account_id){
            $data['order_id'] = $request->order_id;
            $data['buyer_assessment'] = null;
            $data['seller_assessment'] = $request->assessment;

            Assessment::create($data);

        // ログインアカウントが出品者の場合
        }elseif($account_id === $seller_account_id){
            $assessment = Assessment::where('order_id', $request->order_id)->first();
            $data = $request->assessment;

            $assessment->update(['buyer_assessment' => $data]);

            Order::where('id', $request->order_id)->update(['status' => 'done']);
        }

        return redirect('/');
    }
}
