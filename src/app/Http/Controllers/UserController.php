<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Item;
use Illuminate\Support\Facades\Auth;
use App\Models\Account;
use App\Models\Order;
use App\Models\Like;
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
        switch ($page) {
            case 'buy':
                $user = Auth::user();
                $account = \App\Models\Account::where('user_id', $user->id)->first();
                $orders = Order::where('account_id', $account->id)->with('item')->get();
                $items = $orders->pluck('item');
            break;

            case 'sell':
            default:
                $user = Auth::user();
                $account = \App\Models\Account::where('user_id', $user->id)->first();
                $items = Item::where('account_id', $account->id)->get();
            break;
        }
        return view('mypage', compact('items', 'page', 'account'));
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
