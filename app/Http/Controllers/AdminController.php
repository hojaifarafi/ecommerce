<?php

namespace App\Http\Controllers;

use App\Models\Brand;
use App\Models\Category;
use App\Models\Coupon;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\Transaction;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;
use Intervention\Image\Laravel\Facades\Image;

class AdminController extends Controller
{
    public function index(){
        return view('admin.index');
    }
    public function brands(){
        $brands = Brand::orderBy('id','DESC')->paginate(10);
        return view('admin.brands',compact('brands'));
    }
    public function add_brand(){
        return view('admin.brand-add');
    }
    public function brand_store(Request $req){
        $req->validate([
            'name' => 'required',
            'slug' => 'required|unique:brands,slug',
            'image' => 'mimes:png,jpg,jpeg|max:2048'
        ]);

        $brand = new Brand();
        $brand->name = $req->name;
        $brand->slug = Str::slug($req->slug)."_".rand(1,1000);
        $image = $req->file("image");
        $file_extention = $image->extension();
        $file_name = Carbon::now()->timestamp."_".rand(1,100).'.'.$file_extention;
        $brand->image = $file_name;
        $this->GenerateBrandThumbnailImage($image,$file_name,"brands");
        $brand->save();
        return redirect()->route('admin.brands')->with('status','Brand has been added successfully');
    }
    public function brand_edit($id){
        $brand = Brand::find($id);
        return view('admin.brand-edit',compact('brand'));
    }
    public function brand_update(Request $request){
        $request->validate([
            'name' => 'required',
            'slug' => 'required|unique:brands,slug,'.$request->edit_id,
            'image' => 'mimes:png,jpg,jpeg|max:2048'
        ]);
        $brand = Brand::find($request->edit_id);
        $brand->name = $request->name;
        $brand->slug = Str::slug($request->slug);
        if($request->hasFile('image')){
            if(File::exists(public_path('uploads/brands').'/'.$brand->image)){
                File::delete(public_path('uploads/brands').'/'.$brand->image);
            }
            $image = $request->file("image");
            $file_extention = $image->extension();
            $file_name = Carbon::now()->timestamp."_".rand(1,100).'.'.$file_extention;
            $brand->image = $file_name;
            $this->GenerateBrandThumbnailImage($image,$file_name,"brands");
        }
        $brand->save();
        return redirect()->route('admin.brands')->with('status','Brand has been updated successfully');

    }
    public function brand_delete($id){
        $brand = Brand::find($id);
        if(File::exists(public_path('uploads/brands').'/'.$brand->image)){
            File::delete(public_path('uploads/brands').'/'.$brand->image);
        }
        $brand->delete();
        return redirect()->route('admin.brands')->with('status','Brand has been deleted successfully');
    }
    public function GenerateBrandThumbnailImage($image,$image_name,$path){
        $destinationPath = public_path("uploads/$path");
        $img = Image::read($image->path());
        $img->cover(124,124,"top");
        $img->resize(124,124,function ($constraint){
            $constraint->aspectRatio();
        })->save($destinationPath."/".$image_name);
    }
    public function categories(){
        $categories = Category::orderBy('id','DESC')->paginate(10);
        return view('admin.categories',compact('categories'));
    }
    public function category_add(){
        return view('admin.category_add');
    }
    public function category_store(Request $request){
        $request->validate([
            'name' => 'required',
            'slug' => 'required|unique:categories,slug',
            'image' => 'mimes:png,jpg,jpeg|max:2048'
        ]);

        $category = new Category();
        $category->name = $request->name;
        $category->slug = Str::slug($request->slug)."_".rand(1,1000);
        $image = $request->file("image");
        $file_extention = $image->extension();
        $file_name = Carbon::now()->timestamp."_".rand(1,100).'.'.$file_extention;
        $category->image = $file_name;
        $this->GenerateBrandThumbnailImage($image,$file_name,"categories");
        $category->save();
        return redirect()->route('admin.categories')->with('status','Category has been added successfully');
    }
    public function category_edit($id){
        $category = Category::find($id);
        return view('admin.category_edit',compact('category'));
    }
    public function category_update(Request $request){
        $request->validate([
            'name' => 'required',
            'slug' => 'required|unique:categories,slug,'.$request->edit_id,
            'image' => 'mimes:png,jpg,jpeg|max:2048'
        ]);
        $category = Category::find($request->edit_id);
        $category->name = $request->name;
        $category->slug = Str::slug($request->slug);
        if($request->hasFile('image')){
            if(File::exists(public_path('uploads/categories').'/'.$category->image)){
                File::delete(public_path('uploads/categories').'/'.$category->image);
            }
            $image = $request->file("image");
            $file_extention = $image->extension();
            $file_name = Carbon::now()->timestamp."_".rand(1,100).'.'.$file_extention;
            $category->image = $file_name;
            $this->GenerateBrandThumbnailImage($image,$file_name,"categories");
        }
        $category->save();
        return redirect()->route('admin.categories')->with('status','Brand has been updated successfully');
    }
    public function category_delete($id){
        $category = Category::find($id);
        if(File::exists(public_path('uploads/categories').'/'.$category->image)){
            File::delete(public_path('uploads/categories').'/'.$category->image);
        }
        $category->delete();
        return redirect()->route('admin.categories')->with('status','Category has been deleted successfully');
    }
    public function products(){
        $products = Product::orderBy('created_at','DESC')->paginate(10);
        return view('admin.products',compact('products'));
    }
    public function product_add(){
        $categories = Category::select('id','name')->orderBy('name')->get();
        $brands = Brand::select('id','name')->orderBy('name')->get();
        return view('admin.product-add',compact('categories','brands'));
   }
    public function product_store(Request $request){
        $request->validate([
            'name' => 'required',
            'slug' => 'required|unique:products,slug',
            'short_description' => 'required',
            'description' => 'required',
            'regular_price' => 'required',
            'sale_price' => 'required',
            'SKU' => 'required',
            'stock_status' => 'required',
            'featured' => 'required',
            'quantity' => 'required',
            'image' => 'required|mimes:png,jpg,jpeg|max:2048',
            'category_id' => 'required',
            'brand_id' => 'required'
        ]);
        $product = new Product();
        $product->name = $request->name;
        $product->slug = Str::slug($request->name);
        $product->short_description = $request->short_description;
        $product->description = $request->description;
        $product->regular_price = $request->regular_price;
        $product->sale_price = $request->sale_price;
        $product->SKU = $request->SKU;
        $product->stock_status = $request->stock_status;
        $product->featured = $request->featured;
        $product->quantity = $request->quantity;
        $product->category_id = $request->category_id;
        $product->brand_id = $request->brand_id;
        $current_timestamps = Carbon::now()->timestamp;
        if($request->hasFile('image')){
            $image = $request->file('image');
            $imageName = $current_timestamps.".".$image->extension();
            $this->GenerateProductThumbnailImage($image,$imageName);
            $product->image = $imageName;
        }
        $gallary_arr = array();
        $gallary_images = "";
        $counter = 1;
        if($request->hasFile('images')){
            $allowedFileExtention = ['jpg','png','jpeg'];
            $files = $request->file('images');
            foreach($files as $file){
                $gextention = $file->getClientOriginalExtension();
                $gcheck = in_array($gextention,$allowedFileExtention);
                if($gcheck){
                    $gFileName = $current_timestamps."-".$counter.".".$gextention;
                    $this->GenerateProductThumbnailImage($file,$gFileName);
                    array_push($gallary_arr,$gFileName);
                    $counter += 1;
                }
            }
            $gallary_images = implode(',',$gallary_arr);
        }
        $product->images = $gallary_images;
        $product->save();
        return redirect()->route('admin.products')->with('status','Product has been added successfully');
    }
    public function GenerateProductThumbnailImage($image,$image_name){
        $destinationThumbnailPath = public_path("uploads/products/thumbnails");
        $destinationPath = public_path("uploads/products");
        $img = Image::read($image->path());
        $img->cover(540,689,"top");
        $img->resize(540,689,function ($constraint){
            $constraint->aspectRatio();
        })->save($destinationPath."/".$image_name);
        $img->resize(124,124,function ($constraint){
            $constraint->aspectRatio();
        })->save($destinationThumbnailPath."/".$image_name);
    }
    public function product_edit($id) {
        $product = Product::find($id);
        $brands = Brand::select('id','name')->orderBy('name')->get();
        $categories = Category::select('id','name')->orderBy('name')->get();
        return view('admin.product-edit',compact('product','brands','categories'));
    }
    public function product_update(Request $request){
        $request->validate([
            'name' => 'required',
            'slug' => 'required|unique:products,slug,'.$request->id,
            'short_description' => 'required',
            'description' => 'required',
            'regular_price' => 'required',
            'sale_price' => 'required',
            'SKU' => 'required',
            'stock_status' => 'required',
            'featured' => 'required',
            'quantity' => 'required',
            'image' => 'mimes:png,jpg,jpeg|max:2048',
            'category_id' => 'required',
            'brand_id' => 'required'
        ]);
        $product = Product::find($request->id);
        $product->name = $request->name;
        $product->slug = Str::slug($request->name);
        $product->short_description = $request->short_description;
        $product->description = $request->description;
        $product->regular_price = $request->regular_price;
        $product->sale_price = $request->sale_price;
        $product->SKU = $request->SKU;
        $product->stock_status = $request->stock_status;
        $product->featured = $request->featured;
        $product->quantity = $request->quantity;
        $product->category_id = $request->category_id;
        $product->brand_id = $request->brand_id;
        $current_timestamps = Carbon::now()->timestamp;
        if($request->hasFile('image')){
                if(File::exists(public_path('uploads/products').'/'.$product->image)){
                    File::delete(public_path('uploads/products').'/'.$product->image);
                }
                if(File::exists(public_path('uploads/products/thumbnails').'/'.$product->image)){
                    File::delete(public_path('uploads/products/thumbnails').'/'.$product->image);
                }
            $image = $request->file('image');
            $imageName = $current_timestamps.".".$image->extension();
            $this->GenerateProductThumbnailImage($image,$imageName);
            $product->image = $imageName;
        }
        $gallary_arr = array();
        $gallary_images = "";
        $counter = 1;
        if($request->hasFile('images')){
            foreach(explode(',',$product->images) as $ofFile){
                if(File::exists(public_path('uploads/products').'/'.$ofFile)){
                    File::delete(public_path('uploads/products').'/'.$ofFile);
                }
                if(File::exists(public_path('uploads/products/thumbnails').'/'.$ofFile)){
                    File::delete(public_path('uploads/products/thumbnails').'/'.$ofFile);
                }
            }
            $allowedFileExtention = ['jpg','png','jpeg'];
            $files = $request->file('images');
            foreach($files as $file){
                $gextention = $file->getClientOriginalExtension();
                $gcheck = in_array($gextention,$allowedFileExtention);
                if($gcheck){
                    $gFileName = $current_timestamps."-".$counter.".".$gextention;
                    $this->GenerateProductThumbnailImage($file,$gFileName);
                    array_push($gallary_arr,$gFileName);
                    $counter += 1;
                }
            }
            $gallary_images = implode(',',$gallary_arr);
            $product->images = $gallary_images;
        }
        $product->save();
        return redirect()->route('admin.products')->with('status','Product has been updated successfully');
    }
    public function product_delete($id){
        $product = Product::find($id);
        if(File::exists(public_path('uploads/products').'/'.$product->image)){
            File::delete(public_path('uploads/products').'/'.$product->image);
        }
        if(File::exists(public_path('uploads/products/thumbnails').'/'.$product->image)){
            File::delete(public_path('uploads/products/thumbnails').'/'.$product->image);
        }
        foreach(explode(',',$product->images) as $ofFile){
                if(File::exists(public_path('uploads/products').'/'.$ofFile)){
                    File::delete(public_path('uploads/products').'/'.$ofFile);
                }
                if(File::exists(public_path('uploads/products/thumbnails').'/'.$ofFile)){
                    File::delete(public_path('uploads/products/thumbnails').'/'.$ofFile);
                }
            }
        $product->delete();
        return redirect()->route('admin.products')->with('status','Product has been deleted successfully');
    }
    public function coupons(){
        $coupons = Coupon::orderBy('expiry_date','DESC')->paginate(12);
        return view('admin.coupons',compact('coupons'));
    }
    public function coupon_add(){
        return view('admin.coupon-add');
    }
    public function coupon_store(Request $request){
        $request->validate([
            'code' => 'required',
            'type' => 'required',
            'value' => 'required|numeric',
            'cart_value' => 'required|numeric',
            'expiry_date' => 'required|date'
        ]);
        $coupon = new Coupon();
        $coupon->code = $request->code;
        $coupon->type = $request->type;
        $coupon->value = $request->value;
        $coupon->cart_value = $request->cart_value;
        $coupon->expiry_date = $request->expiry_date;
        $coupon->save();
        return redirect()->route('admin.coupons')->with('status','Coupon has been added successfully');
    }
    public function coupon_edit($id){
        $coupon = Coupon::find($id);
        return view('admin.coupon-edit',compact('coupon'));
    }
    public function coupon_update(Request $request){
        $request->validate([
            'code' => 'required',
            'type' => 'required',
            'value' => 'required|numeric',
            'cart_value' => 'required|numeric',
            'expiry_date' => 'required|date'
        ]);
        $coupon = Coupon::find($request->id);
        $coupon->code = $request->code;
        $coupon->type = $request->type;
        $coupon->value = $request->value;
        $coupon->cart_value = $request->cart_value;
        $coupon->expiry_date = $request->expiry_date;
        $coupon->save();
        return redirect()->route('admin.coupons')->with('status','Coupon has been updated successfully');
    }
    public function coupon_delete($id){
        Coupon::find($id)->delete();
        return redirect()->route('admin.coupons')->with('status','Coupon has been deleted successfully');
    }
    public function orders(){
        $orders = Order::orderBy('created_at','DESC')->paginate(12);
        return view('admin.orders',compact('orders'));
    }
    public function order_details($order_id){
        $order = Order::find($order_id);
        $orderItems = OrderItem::where('order_id',$order_id)->orderBy('id')->paginate(12);
        $transaction = Transaction::where('order_id',$order_id)->first();
        return view('admin.order-details',compact('order','orderItems','transaction'));
    }
    public function update_order_status(Request $request){
        $order = Order::find($request->order_id);
        $order->status = $request->order_status;
        $transaction = Transaction::where('order_id',$request->order_id)->first();
        if($request->order_status == 'delivered'){
            $order->delivered_date = Carbon::now();
            $transaction->status = 'approved';
            $transaction->save();
        }elseif($request->order_status == 'canceled'){
            $order->canceled_date = Carbon::now();
            $transaction->status = 'declined';
            $transaction->save();
        }
        $order->save();
        return back()->with('status','Status changed successfully');
    }
}
