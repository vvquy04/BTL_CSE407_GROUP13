<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use App\Http\Requests;
use Illuminate\Support\Facades\Redirect;
use App\Models\Product;
use App\Models\Category;
use App\Models\Brand;
use App\Factories\MenProductFactory;
use App\Factories\WomenProductFactory;
use App\Factories\SmartProductFactory;
use App\Factories\SportProductFactory;

class ProductController extends Controller
{
    private $productFactory;
    private $menProductFactory;
    private $womenProductFactory;
    private $smartProductFactory;
    private $sportProductFactory;

    public function __construct(
        MenProductFactory $productFactory,
        WomenProductFactory $menProductFactory,
        WomenProductFactory $womenProductFactory,
        SmartProductFactory $smartProductFactory,
        SportProductFactory $sportProductFactory
    ) {
        $this->productFactory = $productFactory;
        $this->menProductFactory = $menProductFactory;
        $this->womenProductFactory = $womenProductFactory;
        $this->smartProductFactory = $smartProductFactory;
        $this->sportProductFactory = $sportProductFactory;
    }

    public function AuthLogin() {
        if(Session::get('admin_id') != null) {
            return Redirect::to('admin.dashboard');
        } else {
            return Redirect::to('admin')->send();
        }
    }

    public function add_product() {
        $this->AuthLogin();
        $cate_product = Category::orderby('category_id','DESC')->get();
        
        // $cate_product = DB::table('tbl_category_product')->orderby('category_id','desc')->get();
        $branch_product = Brand::orderby('branch_id','desc')->get();
        
        // $branch_product = DB::table('tbl_branch_product')->orderby('branch_id','desc')->get();

        return view('admin.add_product')->with('category_product',$cate_product)->with('branch_product',$branch_product);
    }

    public function all_product() {
        $this->AuthLogin();
        $all_product = Product::join('tbl_branch_product','tbl_product.branch_id','=','tbl_branch_product.branch_id')
        ->join('tbl_category_product','tbl_product.category_id','=','tbl_category_product.category_id')
        ->orderby('product_id','desc')->get();
        // $all_product = DB::table('tbl_product')->join('tbl_branch_product','tbl_product.branch_id','=','tbl_branch_product.branch_id')
        // ->join('tbl_category_product','tbl_product.category_id','=','tbl_category_product.category_id')
        // ->orderby('product_id','desc')->get();
        return view('admin.all_product')->with('all_product',$all_product);
    }

    public function save_product(Request $request) {
        $this->AuthLogin();
        $product = new Product();
        $data = $request->all();
        $product->category_id = $data['selectCategory'];
        $product->branch_id = $data['selectBranch'];
        $product->product_content = $data['product_content'];
        $product->product_keywords = $data['product_keywords'];
        $product->product_name = $data['product_name'];
        $product->product_desc = $data['product_desc'];
        $product->product_price = $data['product_price'];
        $product->product_status = $data['selectProductStatus'];
        
        $validated = $request->validate([
            // 'product_name' => 'required|unique:posts|max:255',
            'product_name' => 'required|min:5',
            'product_price' => 'required|numeric',
            'product_image' => 'required|file',
            'product_desc' => 'required',
            'product_content' => 'required',
            'product_keywords' => 'required',
        ]);
        $file_select = $request->file('product_image');
        if($file_select != null) {
            $split = explode('.',$file_select->getClientOriginalName());
            $get_image_name = current($split); // chi lay ten - 0 lay duoi
            $get_extension = end($split); // lay extension
            $new_image_file = $get_image_name.rand(0,99).'.'.$get_extension; // tao ten moi ket hop random va lay duoi.
            $file_select->move('upload/product/',$new_image_file);
            $data['product_image'] = $new_image_file;
            $product->product_image = $data['product_image'];

        }
        else {$product->product_image = '';}
        // DB::table('tbl_product')->insert($data);
        $product->save();
        Session::put('message','Thêm sản phẩm thành công');
        
        return Redirect::to('all-product');
    }

    public function unactive_product($product_id) {
        $this->AuthLogin();
        Product::find($product_id)->update(['product_status'=>0]);
        // DB::table('tbl_product')->where('product_id',$product_id)->update(['product_status'=>0]);
        Session::put('message', 'Hủy kích hoạt sản phẩm thành công');
        return Redirect::to('all-product');

    }

    public function active_product($product_id) {
        $this->AuthLogin();
        Product::find($product_id)->update(['product_status'=>1]);
        // DB::table('tbl_product')->where('product_id',$product_id)->update(['product_status'=>1]);
        Session::put('message', 'Kích hoạt sản phẩm thành công');
        return Redirect::to('all-product');

    }

    public function edit_product($product_id) {
        $this->AuthLogin();
        $cate_product = Category::orderBy('category_id','desc')->get();
        $branch_product = Brand::orderBy('branch_id','desc')->get();
        // $cate_product = DB::table('tbl_category_product')->orderby('category_id','desc')->get();
        // $branch_product = DB::table('tbl_branch_product')->orderby('branch_id','desc')->get();

        $edit_product = Product::find($product_id);
        // $edit_product = DB::table('tbl_product')->where('product_id',$product_id)->get();
        $manager_branch_product = view('admin.edit_product')->with('edit_product',$edit_product)
        ->with('category_product',$cate_product)->with('branch_product',$branch_product);

        return view('admin_layout')->with('admin.edit_product',$manager_branch_product);
    }

    public function update_product(Request $request, $product_id) {
        $this->AuthLogin();
        $data = array();
        $data['product_keywords'] = $request->product_keywords;
        $data['product_name'] = $request->product_name;
        $data['branch_id'] = $request->selectBranch;
        $data['category_id'] = $request->selectCategory;
        $data['product_content'] = $request->product_content;
        $data['product_desc'] = $request->product_desc;
        $data['product_price'] = $request->product_price;
        $data['product_status'] = $request->selectProductStatus;
        
        $validated = $request->validate([
            // 'product_name' => 'required|unique:posts|max:255',
            'product_name' => 'required|min:5',
            'product_price' => 'required|numeric',
            'product_desc' => 'required',
            'product_content' => 'required',
            'product_keywords' => 'required',
        ]);

        $file_select = $request->file('product_image');
        if($file_select != null) {
            $split = explode('.',$file_select->getClientOriginalName());
            $get_image_name = current($split); // chi lay ten - 0 lay duoi
            $get_extension = end($split); // lay extension
            $new_image_file = $get_image_name.rand(0,99).'.'.$get_extension; // tao ten moi ket hop random va lay duoi.
            $file_select->move('public/upload/product/',$new_image_file);
            $data['product_image'] = $new_image_file;
        }
        DB::table('tbl_product')->where('product_id',$product_id)->update($data);
        Session::put('message', 'Cập nhật sản phẩm thành công');
        return Redirect::to('all-product');
    }

    public function delete_product($product_id) {
        $this->AuthLogin();
        DB::table('tbl_product')->where('product_id',$product_id)->delete();
        Session::put('message','Xóa sản phẩm thành công');
        return Redirect::to('all-product');
    }

    // End Admin Page
    public function detail_product($product_id, Request $request) {
            

        $cate_product = DB::table('tbl_category_product')->where('category_status','1')->orderby('category_id','desc')->get();
        $branch_product = DB::table('tbl_branch_product')->where('branch_status','1')->orderby('branch_id','desc')->get();

        $product_by_id = DB::table('tbl_product')->join('tbl_branch_product','tbl_product.branch_id','=','tbl_branch_product.branch_id')
        ->join('tbl_category_product','tbl_product.category_id','=','tbl_category_product.category_id')->where('tbl_product.product_id',$product_id)->get();
    
        foreach($product_by_id as $key => $product) {
            $category_id = $product->category_id;
        }
        
        $relate_product = DB::table('tbl_product')->join('tbl_branch_product','tbl_product.branch_id','=','tbl_branch_product.branch_id')
        ->join('tbl_category_product','tbl_product.category_id','=','tbl_category_product.category_id')
        ->where('tbl_product.category_id',$category_id)->whereNotIn('tbl_product.product_id',[$product_id])->get();
    
        foreach($product_by_id as $key => $val) {
            // seo meta
            $meta_title = $val->product_name;
           $meta_desc = $val->product_desc;
           $meta_keywords = $val->product_keywords;
           $meta_canonical = $request->url();
           $image_og = url('/').'/public/upload/product/'.$val->product_image;
           // end seo meta
       }

        return view('pages.product.detail-product')->with('category_product',$cate_product)->with('branch_product',$branch_product)
        ->with('product_by_id',$product_by_id)
        ->with('relate_product',$relate_product)
        ->with('meta_title',$meta_title)
        ->with('meta_desc',$meta_desc)
        ->with('meta_keywords',$meta_keywords)
        ->with('meta_canonical',$meta_canonical)
        ->with('image_og',$image_og);
    }

    public function createWatch(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
            'description' => 'required|string',
            'category' => 'required|string',
            'stock' => 'required|integer|min:0',
            'image' => 'required|string',
            'brand' => 'required|string',
            'model' => 'required|string',
            'movement' => 'required|string',
        ]);

        // Tạo sản phẩm mới
        $product = $this->productFactory->createProduct($data);

        // Lưu vào database
        $savedProduct = \App\Models\Product::create([
            'product_name' => $product->getName(),
            'product_price' => $product->getPrice(),
            'product_desc' => $product->getDescription(),
            'product_content' => $product->getDescription(),
            'product_status' => $product->getStatus(),
            'product_image' => $product->getImage(),
            'category_id' => $this->getCategoryId($product->getCategory()),
            'brand_id' => $this->getBrandId($product->getBrand()),
            'product_quantity' => $product->getStock(),
            'product_model' => $product->getModel(),
            'product_movement' => $product->getMovement(),
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Sản phẩm đã được tạo thành công',
            'product' => $savedProduct
        ]);
    }

    private function getCategoryId(string $categoryName): int
    {
        $category = \App\Models\Category::where('category_name', $categoryName)->first();
        return $category ? $category->category_id : 1; // Default category ID if not found
    }

    private function getBrandId(string $brandName): int
    {
        $brand = \App\Models\Brand::where('brand_name', $brandName)->first();
        return $brand ? $brand->brand_id : 1; // Default brand ID if not found
    }

    public function createMenWatch(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
            'description' => 'required|string',
            'category' => 'required|string',
            'stock' => 'required|integer|min:0',
            'image' => 'required|string',
            'brand' => 'required|string',
            'model' => 'required|string',
            'movement' => 'required|string',
        ]);

        $product = $this->menProductFactory->createProduct($data);

        return response()->json([
            'success' => true,
            'message' => 'Đồng hồ nam đã được tạo thành công',
            'product' => [
                'name' => $product->getName(),
                'price' => $product->getPrice(),
                'gender' => $product->getGender(),
                'brand' => $product->getBrand(),
                'model' => $product->getModel()
            ]
        ]);
    }

    public function createWomenWatch(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
            'description' => 'required|string',
            'category' => 'required|string',
            'stock' => 'required|integer|min:0',
            'image' => 'required|string',
            'brand' => 'required|string',
            'model' => 'required|string',
            'movement' => 'required|string',
        ]);

        $product = $this->womenProductFactory->createProduct($data);

        return response()->json([
            'success' => true,
            'message' => 'Đồng hồ nữ đã được tạo thành công',
            'product' => [
                'name' => $product->getName(),
                'price' => $product->getPrice(),
                'gender' => $product->getGender(),
                'brand' => $product->getBrand(),
                'model' => $product->getModel()
            ]
        ]);
    }

    public function createSmartWatch(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
            'description' => 'required|string',
            'category' => 'required|string',
            'stock' => 'required|integer|min:0',
            'image' => 'required|string',
            'brand' => 'required|string',
            'model' => 'required|string',
            'movement' => 'required|string',
            'os' => 'required|string',
            'battery_life' => 'required|string',
            'features' => 'required|array'
        ]);

        $product = $this->smartProductFactory->createProduct($data);

        return response()->json([
            'success' => true,
            'message' => 'Đồng hồ thông minh đã được tạo thành công',
            'product' => [
                'name' => $product->getName(),
                'price' => $product->getPrice(),
                'type' => $product->getType(),
                'os' => $product->getOS(),
                'battery_life' => $product->getBatteryLife(),
                'features' => $product->getFeatures()
            ]
        ]);
    }

    public function createSportWatch(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
            'description' => 'required|string',
            'category' => 'required|string',
            'stock' => 'required|integer|min:0',
            'image' => 'required|string',
            'brand' => 'required|string',
            'model' => 'required|string',
            'movement' => 'required|string',
            'water_resistance' => 'required|string',
            'sport_features' => 'required|array',
            'material' => 'required|string'
        ]);

        $product = $this->sportProductFactory->createProduct($data);

        return response()->json([
            'success' => true,
            'message' => 'Đồng hồ thể thao đã được tạo thành công',
            'product' => [
                'name' => $product->getName(),
                'price' => $product->getPrice(),
                'type' => $product->getType(),
                'water_resistance' => $product->getWaterResistance(),
                'sport_features' => $product->getSportFeatures(),
                'material' => $product->getMaterial()
            ]
        ]);
    }
}
