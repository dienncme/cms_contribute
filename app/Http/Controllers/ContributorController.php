<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ContributorModel;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class ContributorController extends Controller
{
  public function index(Request $request)
  {   
    $sort = $request->query('contributor_sort', "");
    $searchKeyword = $request->query('contributor_name', "");
    $queryORM = ContributorModel::where('name', "LIKE", "%".$searchKeyword."%");
    if ($sort == "name_asc") {
      $queryORM->orderBy('name', 'asc');
    }
    if ($sort == "name_desc") {
      $queryORM->orderBy('name', 'desc');
    }
    $contributors = $queryORM->paginate(10);
    $data = [];
    $data['contributors'] = $contributors;
    $data["searchKeyword"] = $searchKeyword;
    $data["sort"] = $sort;
    return view('contributors.index', $data);
  }

  public function create(){
      return view('contributors.create');
  }
  public function store(Request $request) {
    // validate dữ liệu
    $validatedData = $request->validate([
        'name' => 'required',
        'email' => 'required|unique:contributors',
        'password' => 'required|min:6|required_with:password_confirmation|same:password_confirmation',
        'password_confirmation' => 'required|min:6',
        'desc' => 'required',
    ]);
    $name = $request->input('name', '');
    $email = $request->input('email', '');
    $password = $request->input('password', '');
    $desc = $request->input('desc', '');
    $address = $request->input('address', '');
    $number_phone = $request->input('desc', '');
    $contributor = new ContributorModel();
    $contributor->name = $name;
    $contributor->email = $email;
    $contributor->password = Hash::make($password);
    $contributor->desc = $desc;
    $contributor->address = $address;
    $contributor->number_phone = $number_phone;
    $contributor->save();
    return redirect("/contributor/login")->with('status', 'Đăng kí người đóng góp thành công !');
  }

  public function edit($id) {
    $contributor = ContributorModel::findOrFail($id);
    $data = [];
    $data["contributor"] = $contributor;
    return view("contributors.edit", $data);
  }

  public function update(Request $request, $id) {
    $name = $request->input('name', '');
    $email = $request->input('email', '');
    $desc = $request->input('desc', '');
    $address = $request->input('address', '');
    $number_phone = $request->input('number_phone', '');
    $contributor = ContributorModel::findOrFail($id);
    $contributor->name = $name;
    $contributor->email = $email;
    $contributor->desc = $desc;
    $contributor->address = $address;
    $contributor->number_phone = $number_phone;
    $contributor->save();
    return redirect("/contributor/index")->with('status', 'cập nhật sản phẩm thành công !');
  }

  public function delete($id) {
    $contributor = ContributorModel::findOrFail($id);
    // truyền dữ liệu xuống view
    $data = [];
    $data["contributor"] = $contributor;
    return view("contributors.delete", $data);
  }

  // xóa sản phẩm thật sự trong CSDL
  public function destroy($id) {
    // lấy đối tượng model dựa trên biến $id
    $contributor = ContributorModel::findOrFail($id);
    $contributor->delete();
    return redirect("/contributor/index")->with('status', 'xóa sản phẩm thành công !');
  }
}