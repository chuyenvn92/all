<?php

namespace App\Http\Controllers\Admin\Category;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Model\Admin\Brand;
use DB;

class BrandController extends Controller
{
	public function __construct()
	{
		$this->middleware('auth:admin');
	}

	public function brand()
	{
		$brand = Brand::all();
		return view('admin.category.brand', compact('brand'));
	}

	public function storebrand(Request $request)
	{
		// đoạn này là validate dữ liệu thì hiểu rồi chứ gì, bắt lỗi k nhập với tên k được trùng thôi
		$validateData = $request->validate([
			'brand_name' => 'required|unique:brands|max:55'
		]);
		// đoạn này đổ dữ liệu ra ấy mà :))
		$data = array();
		$data['brand_name'] = $request->brand_name;
		$image = $request->file('brand_logo');
		// đoạn này khó nên phải comment lại
		// mục đích là lấy ảnh từ trong máy tính ra thôi, tên sẽ để theo kiểu convert từ hàm date
		// $ext là convert sang định dạng ảnh thôi, sau đó ghép lại được cái tên ảnh là biến $image_full_name
		// à rồi có đường dẫn là $upload_path. Xong inset vào DB thôi :))
		if ($image) {
			$image_name = date('dmy_H_s_i');
			$ext = strtolower($image->getClientOriginalExtension());
			$image_full_name = $image_name . '.' . $ext;
			$upload_path = 'media/brand/';
			$image_url = $upload_path . $image_full_name;
			$success = $image->move($upload_path, $image_full_name);

			$data['brand_logo'] = $image_url;
			$brand = DB::table('brands')->insert($data);
			$notification = array(
				'messege' => 'Thêm thương hiệu thành công',
				'alert-type' => 'success'
			);
			return Redirect()->back()->with($notification);
		} else {
			$brand = DB::table('brands')->insert($data);
			$notification = array(
				'messege' => 'Thành công',
				'alert-type' => 'success'
			);
			return Redirect()->back()->with($notification);
		}
	}

	public function Deletebrand($id)
	{
		$data = DB::table('brands')->where('id', $id)->first();
		$image = $data->brand_logo;
		unlink($image);
		$brand = DB::table('brands')->where('id', $id)->delete();
		$notification = array(
			'messege' => 'Xóa thương hiệu thành công',
			'alert-type' => 'success'
		);
		return Redirect()->back()->with($notification);
	}

	public function Editbrand($id)
	{
		$brand = DB::table('brands')->where('id', $id)->first();
		return view('admin.category.edit_brand', compact('brand'));
	}

	public function Updatebrand(Request $request, $id)
	{
		$oldlogo = $request->old_logo;
		$data = array();
		$data['brand_name'] = $request->brand_name;
		$image = $request->file('brand_logo');
		if ($image) {
			unlink($oldlogo);
			$image_name = date('dmy_H_s_i');
			$ext = strtolower($image->getClientOriginalExtension());
			$image_full_name = $image_name . '.' . $ext;
			$upload_path = 'media/brand/';
			$image_url = $upload_path . $image_full_name;
			$success = $image->move($upload_path, $image_full_name);

			$data['brand_logo'] = $image_url;
			$brand = DB::table('brands')->where('id', $id)->update($data);
			$notification = array(
				'messege' => 'Cập nhật thương hiệu thành công',
				'alert-type' => 'success'
			);
			return Redirect()->route('brands')->with($notification);
		} else {
			$brand = DB::table('brands')->where('id', $id)->update($data);
			$notification = array(
				'messege' => 'Cập nhật không có hình ảnh',
				'alert-type' => 'success'
			);
			return Redirect()->route('brands')->with($notification);
		}
	}
}
