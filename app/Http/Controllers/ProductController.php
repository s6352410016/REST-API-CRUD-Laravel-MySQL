<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProductRequest;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rules\Exists;

class ProductController extends Controller
{
    public function index(){
        $products = Product::all();
        if($products->count() === 0){
            return response()->json([
                'msg' => 'products is empty.'
            ] , 200);
        }
        return response()->json($products , 200);
    }

    public function store(ProductRequest $request){
        try{
            $file_ext = strtolower($request->product_image->getClientOriginalExtension());
            $file_gen = md5(uniqid()) . '.' . $file_ext;
            Storage::disk('public')->put($file_gen , file_get_contents($request->product_image));

            Product::create([
                'product_name' => $request->product_name,
                'product_price' => $request->product_price,
                'product_image' => $file_gen,
            ]);

            return response()->json([
                'msg' => 'product created successfully.'
            ] , 201);
        }catch(\Exception $e){
            return response()->json([
                'errMsg' => $e->getMessage()
            ] , 500);
        }
    }

    public function show($id){
        $product = Product::find($id);
        if(!$product){
            return response()->json([
                'msg' => 'product not found.'
            ] , 404);
        }
        return response()->json($product , 200);
    }

    public function update(ProductRequest $request , $id){
        try{
            $product = Product::find($id);
            if(!$product){
                return response()->json([
                    'msg' => 'product not found.'
                ] , 404);
            }

            if(!$request->product_image){
                $product->product_name = $request->product_name;
                $product->product_price = $request->product_price;
            }else{
                $storage = Storage::disk('public');
                // เช็คว่ามีรูปเดิมอยู่ใน โฟลเดอร์ public ไหมถ้ามีลบออกและอัพโหลดรูปใหม่เข้าไปแทน
                if($storage->exists($product->product_image)){
                    $storage->delete($product->product_image);

                    $file_ext = strtolower($request->product_image->getClientOriginalExtension());
                    $file_gen = md5(uniqid()) . '.' . $file_ext;
                    Storage::disk('public')->put($file_gen , file_get_contents($request->product_image));

                    if(!$request->product_name){
                        $product->product_name = $product->product_name;
                    }else{
                        $product->product_name = $request->product_name;
                    }
                    if(!$request->product_price){
                        $product->product_price = $product->product_price;
                    }else{
                        $product->product_price = $request->product_price;
                    }
                    $product->product_image = $file_gen;
                }
            }
            $product->save();
            return response()->json([
                'msg' => 'product updated successfully.'
            ] , 200);
        }catch(\Exception $e){
            return response()->json([
                'errMsg' => $e->getMessage()
            ] , 500);
        }
    }

    public function delete($id){
        $product = Product::find($id);
        if(!$product){
            return response()->json([
                'msg' => 'product not found.'
            ] , 404);
        }
        $storage = Storage::disk('public');
        // เช็คว่ามีรูปเดิมอยู่ใน โฟลเดอร์ public ไหมถ้ามีลบออก
        if($storage->exists($product->product_image)){
            $storage->delete($product->product_image);
            $product->delete();
            return response()->json([
                'msg' => 'product deleted successfully.'
            ] , 200);
        }
    }
}
