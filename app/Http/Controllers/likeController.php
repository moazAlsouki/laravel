<?php

namespace App\Http\Controllers;

use App\Models\LikeView;
use App\Models\Product;
use App\Models\User;
use Facade\FlareClient\Http\Response;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class likeController extends Controller
{
    function addLike(Request $request){
        $user=User::find(Auth::user()->id);
        $allProduct=$user->getlikes;
        foreach($allProduct as $p){
            if($p->id==$request->id)
                {
                    Db::table('likes')->where('product_id',$p->id)->where('user_id',$user->id)->delete();
                    return Response([
                        'message' => false
                    ],201);
                }
        }
        $product=Product::find($request->id);
        $user->getLikes()->attach($product);
        return Response([
            'message' => true
        ],201);
    }
    function removeLike(Request $request){
        $like=DB::table('like_views')->
        where('user_id','=',Auth::user()->id)->
        where('product_id','=',$request->product_id)->first();
        $like->delete();
    }
}
