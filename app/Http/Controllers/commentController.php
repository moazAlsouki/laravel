<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use App\Models\Product;
use Facade\FlareClient\Http\Response;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use PDO;

class commentController extends Controller
{
    //
    public function addComment(Request $request){
        $comment=new Comment();
        $comment->user_id=Auth::user()->id;
        $comment->product_id=$request->product_id;
        $comment->commentData=$request->comment;
        $comment->save();
        return Response($comment,201);
    }
    public function getComments($productId){
        $product=Product::find($productId);
        $comments=$product->getcomments;
        if($comments){
            return Response($comments,201);
        }
        else return Response([
            "message"=> "No Comments"
        ],401);
    }
}
