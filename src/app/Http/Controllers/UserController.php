<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Item;
use Illuminate\Support\Facades\Auth;
use App\Models\Account;
use App\Models\Order;
use App\Models\Like;
use App\Models\Message;
use App\Http\Requests\AddressRequest;
use App\Http\Requests\ProfileRequest;

class UserController extends Controller
{
    public function address($item_id)
    {
        $item = Item::findOrFail($item_id);
        $user = Auth::user();
        $account = Account::where('user_id', $user->id)->first();

        return view('address', compact('item', 'account'));
    }

     // 商品購入の際の送付先の変更
    public function updateAddress(AddressRequest $request, $item_id)
    {
        $item = Item::find($item_id);
        $user = Auth::user();
        $account = \App\Models\Account::where('user_id', $user->id)->first();
        $account->post_code = $request->input('post_code');
        $account->address   = $request->input('address');
        $account->building  = $request->input('building');
        $account->save();
        
        return redirect('/purchase/' . $item_id);
    }

    public function mypage(Request $request)
    {
        $user = Auth::user();
        $account = \App\Models\Account::where('user_id', $user->id)->first();   
        $page = $request->query('page', 'sell');
        $trading_buy_orders = Order::where('status', 'trading')
                        ->where('account_id', $account->id)
                        ->with('item')->get();
        $trading_sell_orders = Order::where('status', 'trading')
                        ->whereHas('item', function ($q) use ($account){
                            $q->where('account_id', $account->id);
                        })
                        ->with('item')->get();
        $unread_count_buy = 0;
        // 購入した場合の未読メッセージ数
        foreach($trading_buy_orders as $trading_buy_order){
            $otherId = $trading_buy_order->item->account_id;
            if($trading_buy_order->buyer_last_read_at === null){
                $message_buy = Message::where('order_id', $trading_buy_order->id)
                        ->where('send_account_id', $otherId)
                        ->count();
            }else{
                $message_buy = Message::where('order_id', $trading_buy_order->id)
                        ->where('created_at','>',$trading_buy_order->buyer_last_read_at)
                        ->where('send_account_id', $otherId)
                        ->count();
            }
            $trading_buy_order->unread_count = $message_buy;
            $unread_count_buy += $message_buy;
        }
        $unread_count_sell = 0;
        // 販売した場合の未読メッセージ数
        foreach($trading_sell_orders as $trading_sell_order){
            $otherId = $trading_sell_order->account_id;
            if($trading_sell_order->seller_last_read_at === null){
                $message_sell = Message::where('order_id', $trading_sell_order->id)
                        ->where('send_account_id', $otherId)
                        ->count();
            }else{
                $message_sell = Message::where('order_id', $trading_sell_order->id)
                        ->where('created_at','>',$trading_sell_order->seller_last_read_at)
                        ->where('send_account_id', $otherId)
                        ->count();
            }
            $trading_sell_order->unread_count = $message_sell;
            $unread_count_sell += $message_sell;
        }
        $unread_count = $unread_count_buy + $unread_count_sell;

        switch ($page) {
            case 'trading':
                $trading = $trading_buy_orders
                        ->concat($trading_sell_orders)
                        ->unique('id')
                        ->sortByDesc('last_message_at');
                $items = $trading->pluck('item');
            break;

            case 'buy':
                $orders = Order::where('account_id', $account->id)->with('item')->get();
                $items = $orders->pluck('item');
                $trading = collect();
            break;

            case 'sell':
            default:
                $items = Item::where('account_id', $account->id)->get();
                $trading = collect();
            break;
        }
        return view('mypage', compact('items', 'page', 'account', 'trading', 'unread_count'));
    }

    // プロフィール画面にアカウントテーブルにデータがあれば表示する
    public function profile()
    {
        $user = Auth::user();
        $account = \App\Models\Account::where('user_id', $user->id)->first();
        if (!$account) {
            return view('profile', ['account' => null]);
        }

        return view('profile', compact('account', 'user'));
    }

    public function update(ProfileRequest $request)
    {
        $user = Auth::user();
        $data = $request->only(['name', 'post_code', 'address', 'building', 'image']);

        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('account_image', 'public');
            $data['image'] = $path;
        }
        if ($user->account) {
            $user->account->update($data);
        } else {
            $user->account()->create($data);
        }

        return redirect('/');
    } 
}
