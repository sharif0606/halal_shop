<?php

namespace App\Http\Controllers;

use App\Models\Faq;
use App\Models\Frontend;
use App\Models\Category;
use App\Models\SubCategory;
use App\Models\ChildCategory;
use App\Models\Product;
use App\Models\HeaderSlider;
use App\Models\OurOffer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class FrontendController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $faq=Faq::all();
        $slide=HeaderSlider::all();
        $offer=OurOffer::all();
        $product = DB::table('db_items')->where('is_feature', '1')->select('id','item_name','sales_price','item_image','is_feature')->get();
        $offer_product = DB::table('db_items')->where('is_top', '1')->select('id','item_name','sales_price','item_image','is_top')->get();
        return view('home',compact('faq','slide','offer','product','offer_product'));
    }

    public function subCategory($category_id)
    {
        $show_subcategory = SubCategory::where('category_id',$category_id)->get();
        $cat=Category::find($category_id);
        // return $show_subcategory;
        $subcategorys = DB::table('db_subcategory')->where('category_id',$category_id)->orderBy('id', 'desc')->where('is_advertise', '1')->select('is_advertise','advertise_image')->limit(6)->get();
        // return $show_subcategory;
        return view('product.subcategory',compact('show_subcategory','subcategorys','cat'));

    }

    public function childCategory($category_id,$subcategory_id)
    {
        $show_childcategory = ChildCategory::where('subcategory_id',$subcategory_id)->get();
        $sub_cat=SubCategory::find($subcategory_id);
        $cat=Category::find($category_id);
        $child_advertise = DB::table('db_childcategory')->where('subcategory_id',$subcategory_id)->orderBy('id', 'desc')->where('is_advertise', '1')->select('is_advertise','advertise_image')->limit(6)->get();
        return view('product.childcategory',compact('show_childcategory','child_advertise','cat','sub_cat'));

    }

    public function childCategoryProductList($category_id,$subcategory_id=false,$childcategory_id=false)
    {
        $child_cat=$sub_cat=false;
        $product =Product::select('id','item_name','sales_price','item_image','is_feature');
        if($childcategory_id){
            $product =$product->where('childcategory_id', $childcategory_id);
            $child_cat=ChildCategory::find($childcategory_id);
            $sub_cat=SubCategory::find($subcategory_id);
        }else if($subcategory_id){
            $product =$product->where('subcategory_id', $subcategory_id);
            $sub_cat=SubCategory::find($subcategory_id);
        }else{
            $product =$product->where('category_id', $category_id);
        }

        $cat=Category::find($category_id);
        $product =$product->paginate(12);

        // return $product;
        return view('product.childcatproduct',compact('product','cat','sub_cat','child_cat'));
    }
}
