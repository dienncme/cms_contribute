<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ProductsModel;
use App\Models\CategoryModel;
use App\Models\StatusProductModel;

class ProductsController extends Controller {
  public function index(Request $request) {
    $user_id = session('contributor_login', false)['id'];
    $sort = $request->query('product_sort', "");
    $searchKeyword = $request->query('product_name', "");
    $category_id = (int) $request->query('category_id', 0);
    $status_id = (int) $request->query('product_status', 0);
    $queryORM = ProductsModel::where('product_name', "LIKE", "%".$searchKeyword."%")
                ->where('user_id',$user_id)
                ->join('status_products','status','status_products.id')
                ->select('products.id','product_name','product_quantity','product_enpiry','product_image','product_desc','date_contribute','status_products.status_name');
    if($category_id != 0) {
      $queryORM = $queryORM->where('category_id', $category_id);
    }
    if($status_id != 0) {
      $queryORM = $queryORM->where('status_products.id',  $status_id);
    }

    if ($sort == "name_asc") {
      $queryORM->orderBy('product_name', 'asc');
    }
    if ($sort == "name_desc") {
      $queryORM->orderBy('product_name', 'desc');
    }
    if ($sort == "quantity_asc") {
      $queryORM->orderBy('product_quantity', 'asc');
    }
    if ($sort == "quantity_desc") {
      $queryORM->orderBy('product_quantity', 'desc');
    }
    $products = $queryORM->paginate(10);
    $data = [];
    $data['products'] = $products;
    $data["searchKeyword"] = $searchKeyword;
    $data["category_id"] = $category_id;
    $data["sort"] = $sort;
    $categories = CategoryModel::all();
    $data["categories"] = $categories;
    $status = StatusProductModel::all();
    $data["status"] = $status;
    $data['stt'] = 0;
    return view('products.index', $data);
  }

  public function create() {
    $data = [];
    $categories = CategoryModel::all();
    $data["categories"] = $categories;
    return view("products.create", $data);
  }

  public function store(Request $request) {
  //   $validatedData = $request->validate([
  //     'product_name' => 'required',
  //     'category_id' => 'required',
  //     'product_expiry' => 'required',
  //     'product_desc' => 'required',
  //     'product_quantity' => 'required',
  //  ]);
    $user_id = session('contributor_login', false)['id'];
    $product_name = $request->input('product_name', '');
    $category_id = (int) $request->input('category_id', 0);
    $product_quantity = $request->input('product_quantity', 0);
    $product_desc = $request->input('product_desc', '');
    $product_enpiry = $request->input('product_enpiry');
    $date_contribute = $request->input('date_contribute');
    if(!$product_enpiry) {
        $product_enpiry = 'unlimited';
    }
    if(!$date_contribute) {
      $date_contribute =date("Y-m-d H:i:s");
    }
    $product = new ProductsModel();
    $product->user_id = $user_id;
    $product->product_name = $product_name;
    $product->category_id = $category_id;
    $product->product_desc = $product_desc;
    $product->product_enpiry = $product_enpiry;
    $product->product_quantity = $product_quantity;
    $product->date_contribute = $date_contribute;
    $product->status = 1;
    $product->save();
    return redirect("/product/index")->with('status', 'th??m s???n ph???m th??nh c??ng !');
  }

  public function edit($id) {
    $product = ProductsModel::findOrFail($id);
    $data = [];
    $data["product"] = $product;
    $categories = CategoryModel::all();
    $data["categories"] = $categories;
    return view("products.edit", $data);
  }

  public function update(Request $request, $id) {
    $product_name = $request->input('product_name', '');
    $category_id = (int) $request->input('category_id', 0);
    $product_quantity = $request->input('product_quantity', 0);
    $product_desc = $request->input('product_desc', '');
    $product_enpiry = $request->input('product_enpiry');
    $date_contribute = $request->input('date_contribute');
    if(!$product_enpiry) {
        $product_enpiry = 'unlimited';
    }
    if(!$date_contribute) {
      $date_contribute =date("Y-m-d H:i:s");
    }
    $product = ProductsModel::findOrFail($id);
    $product->product_name = $product_name;
    $product->category_id = $category_id;
    $product->product_desc = $product_desc;
    $product->product_enpiry = $product_enpiry;
    $product->product_quantity = $product_quantity;
    $product->date_contribute = $date_contribute;
    $product->save();
    return redirect("/product/index")->with('status', 'c???p nh???t s???n ph???m th??nh c??ng !');
  }

  public function delete($id) {
    $product = ProductsModel::findOrFail($id);
    // truy???n d??? li???u xu???ng view
    $data = [];
    $data["product"] = $product;
    return view("products.delete", $data);
  }

  // x??a s???n ph???m th???t s??? trong CSDL
  public function destroy($id) {
    // l???y ?????i t?????ng model d???a tr??n bi???n $id
    $product = ProductsModel::findOrFail($id);
    $product->delete();
    return redirect("/product/index")->with('status', 'x??a s???n ph???m th??nh c??ng !');
  }

  public function productContribute($category_id, $recipient_id) {
    $user_id = session('contributor_login', false)['id'];
    $products = ProductsModel::where('user_id', $user_id)->where('category_id', $category_id)
                            ->join('status_products','status','status_products.id')
                            ->where('status_products.id', 1)
                            ->select('products.id','product_name','product_quantity','product_enpiry','product_image','product_desc','date_contribute','status_products.status_name')
                            ->get();
    $data = [];
    $data['products'] = $products;
    $data['recipient_id'] = $recipient_id;
    $data['stt'] = 0;
    return view('products.contribute', $data);
  }
}
