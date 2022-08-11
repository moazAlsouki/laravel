<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Facade\FlareClient\Http\Response;

class productController extends Controller
{
    //
    public function addProduct(Request $request)
    {
        if ($request->requestType=='add'){
            $product = new Product();
            $product->userEmail=Auth::user()->email;
            //$product->expired=$request->expired;
            $product->expired='2021-12-15';
            $product->views=0;
            $product->type=$request->type;
        }
        else{
            $product=Product::find($request->id);
            if(Auth::user()->email!=$product->userEmail)
                return Response([
                    "message"=>"Can't edit This Product " 
                ],401);
        }
        $product->name=$request->name;
        //$product->photo=$request->photo;
        //$product->type=$request->type;
        $product->quantity=$request->quantity;
        $product->price=$request->price;
        $product->price30=$request->price30;
        $product->price15=$request->price15;
        $product->priceOther=$request->priceOther;
        
        $product->photo='hello';
        $product->save();
        if (!$product)
        {
            return response([
                'message'=>'error in saving product'
            ],401);
        }
        else
        {
            return response([
                'message'=>sprintf("The Product %s Saved",$request->name)
            ],201);
        }
    }


    public function removeProduct(Request $request)
    {
        $product=Product::find($request->id);
        $user=Auth::user();
        if ($user->email==$product->userEmail)
        {
            $product->getViews()->detach();
            $product->getLikes()->detach();
            $product->delete();
            if (!$product)
            {
                return response([
                    'message'=>'error in Delete product'
                ],401);
            }
            else
            {
                return response([
                    'message'=>sprintf("The Product %s Deleted",$product->name)
                ],201);
            }
        }
        else {
            return response([
                'message'=>sprintf("You Cant Delete The Product %s",$product->name)
            ],401);
        }
    }
    public function sortProduct(Request $request)
    {
        $products=DB::table('products')
        ->orderBy($request->sort)
        ->get(); 
        return $products;
    }

    public function getAllProduct()
    {
        $products=Product::all();
        if ($products){
         return response($products,201);
        }
        else
        {
            return response(401,[
                'message'=>'error in getting all products'
            ]);

        }
    }
    
    public function searchProductByName(Request $request){
        $products=DB::table('products')
        ->where('name','=',$request->name)
        ->get();
        if ($products){
            return Response($products,201);
        }
        else
        {
            return response(401,[
                'message'=>sprintf("The Product With Name %s is not Exist",$request->name)
            ]);

        }
    }
    public function searchProductByType(Request $request){
        $products=DB::table('products')
        ->where('type','=',$request->type)
        ->get();
        return $products;
    }


    public function getProduct($productID){
    
        $product=Product::find($productID);
        if (!$product)
        {
            return response([
                'message'=>sprintf("The %s Is Invalid",$productID)
            ],401);
        }
        $viewed=false;
        $user=User::find(Auth::user()->id);
        $allProduct=$user->getViews;
        if($allProduct->count()>0)
        foreach($allProduct as $p){
            if($p->id==$product->id)
                {
                    $viewed=true;
                }
        }
        $like=DB::table('likes')->
        where('user_id',$user->id)->
        where('product_id',$product->id)->first();
        
        if ($like)
        {
            $youlikeIt=true;
        }else {
            $youlikeIt=false;
        }
        
        $date = Carbon::now();
        $da=Carbon::createFromFormat('Y-m-d', $product->expired);
        $interval = $date->diff($da);
        $days = $interval->format('%a');//now do whatever you like with $days
        $price=$product->price;
        if($days>=30){
            $percent=100-$product->price30;
            
        }
        else if($days>=15&&$days<30)
        {
            $percent=100-$product->price15;
        }
        else if($days<15)
        {
            $percent=100-$product->priceOthers;
        }
        $price=($product->price)*($percent/100);
        if(!$viewed){
            $product->getViews()->attach($user);
            $product->views++;
            $product->save();       
        }      
        
        
        if ($user->email==$product->userEmail)
        {
            return response([
                'name' => $product->name,
                'photo'=>$product->photo,
                'quantity'=>$product->quantity,
                'views'=>$product->views,
                'price'=>sprintf("%s",$price),
                'days'=>$days,
                'userEmail'=>$product->userEmail,
                'canEdit' => true ,
                'isLiked'=> $youlikeIt
            ],201);
        } else
        {
            return response([
                'name' => $product->name,
                'photo'=>$product->photo,
                'quantity'=>$product->quantity,
                'views'=>$product->views,
                'price'=>sprintf("%s",$price),
                'days'=>$days,
                'userEmail'=>$product->userEmail,
                'canEdit' => false ,
                'isLiked'=> $youlikeIt
            ],201);
        }
    }
    public function getProductlist(){
        $allproducts=Product::all();
        $foodproduct=Db::table('products')->
        where('type','=','food')->get();
        $clothproduct=Db::table('products')->
        where('type','=','cloth')->get();
        $otherproduct=Db::table('products')->
        where('type','!=','cloth')->
        where('type','!=','food')->get();
        if($allproducts)
        return response([
            'allproducts' => $allproducts,
            'foodproduct' => $foodproduct,
            'clothproduct' =>$clothproduct,
            'otherproduct' =>$otherproduct
        ],201);
        else return response([
            'message'=>'there is No product yet'
        ],401);
    }
    public function getfoodProduct(){
        $foodproduct=Db::table('products')->
        where('type','=','food')->get();
        if($foodproduct){
            return response($foodproduct,201);
        }else return response([
            'message'=>'there is No product yet'
        ],401);
    }
    public function getclothProduct(){
        $clothproduct=Db::table('products')->
        where('type','=','Clothes')->get();
        if($clothproduct){
            return response($clothproduct,201);
        }else return response([
            'message'=>'there is No product yet'
        ],401);
    }
    public function getOtherproduct(){
        $otherproduct=Db::table('products')->
        where('type','!=','Clothes')->
        where('type','!=','food')->get();
        if($otherproduct){
            return response($otherproduct,201);
        }else return response([
            'message'=>'there is No product yet'
        ],401);
    }
    public function getProducttoEdit($productID){
        $product=Product::find($productID);
        if($product->userEmail!=Auth::user()->email){
            return Response([
                "message"=>"cant Edit This Product"
            ],401);
        }
        else return Response($product,201);
    }
    public function searchMyProduct(Request $request){
        $email=Auth::user()->email;
        $products=DB::table('products')
        ->where('name','=',$request->name)
        ->where('userEmail','=',$email)
        ->get();
        if ($products){
            return Response($products,201);
        }
        else
        {
            return response(401,[
                'message'=>sprintf("The Product With Name %s is not Exist",$request->name)
            ]);

        }
    }
}
