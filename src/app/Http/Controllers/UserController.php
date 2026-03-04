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
        // 購入した場合の未読メッセージ数
        $unread_count_buy = 0;
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

        // 販売した場合の未読メッセージ数
        $unread_count_sell = 0;
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

        // タグごとの表示の変更
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

            // 評価の平均を求める
            $done_buy_orders = Order::where('status', 'done')
                            ->where('account_id', $account->id)
                            ->with('assessment')->get();
            $done_sell_orders = Order::where('status', 'done')
                            ->whereHas('item', function ($q) use ($account){
                                $q->where('account_id', $account->id);
                            })
                            ->with('assessment')->get();

            $assessment_sum_buy = 0;
            $assessment_count_buy = 0;
            foreach($done_buy_orders as $done_buy_order){
                if(!$done_buy_order->assessment){
                }else{
                    $assessment_buy = $done_buy_order->assessment->buyer_assessment;
                    if(!$assessment_buy){
                    }else{
                        $assessment_sum_buy += $assessment_buy;
                        $assessment_count_buy += 1;
                    }
                }
            }

            $assessment_sum_sell = 0;
            $assessment_count_sell = 0;
            foreach($done_sell_orders as $done_sell_order){
                if(!$done_sell_order->assessment){
                }else{
                    $assessment_sell = $done_sell_order->assessment->seller_assessment;
                    if(!$assessment_sell){
                    }else{
                        $assessment_sum_sell += $assessment_sell;
                        $assessment_count_sell += 1;
                    }
                }
            }

            if($assessment_count_buy + $assessment_count_sell == 0){
                $assessment_average = null;
            }else{
                $assessment_average = round(($assessment_sum_buy + $assessment_sum_sell) / ($assessment_count_buy + $assessment_count_sell), 0);
            }

        return view('mypage', compact('items', 'page', 'account', 'trading', 'unread_count', 'assessment_average'));
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
