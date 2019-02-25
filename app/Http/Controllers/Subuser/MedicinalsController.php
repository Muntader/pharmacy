<?php

namespace App\Http\Controllers\Subuser;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Validator;
use App\Medicinal;
use DB;
use Auth;
class MedicinalsController extends Controller
{

    /*
     *  Get all medicinal of user
     *  @return json response 
     * **********************************/

    public function get(){

        $getAllMedicinal = DB::table('medicinals')
                         ->select('m_id AS id', 'm_gname As gname', 'm_bname AS bname', 'm_desc AS descripition', 'm_country AS country', 'm_idnumber AS product_number','m_price AS price', 'm_imdate AS importer_date', 'm_exdate AS expire_date', 'm_seffect AS side_effect', 'm_quantity AS product_quantity', 'm_imname AS supplier_name','m_imprice AS original_price','m_discount AS product_discount', 'm_image AS product_image', 'm_icon AS icon', 'm_barcodeg AS product_barcode','categories.name AS category' )
                         ->join('categories','categories.id', '=' ,'medicinals.m_cat')
                         ->where('medicinals.uid', Auth::user()->pharmacy_id)
                         ->paginate(15);
        if(count($getAllMedicinal->all()) > 0 ) {
            return response()->json(['status' => 'success', 'data' => $getAllMedicinal],201);
        }else{
            return response()->json(['status' => 'empty'],204);
        }
    }
    

    /*
     *  Search 
     *  @return json response 
     * **********************************/

    public function search(Request $request){
        $request->validate([
            'query' => 'required|max:70|regex:/^[a-z0-9 .\-]+$/i',
        ]);
        
        $getAllMedicinal = DB::table('medicinals')
                         ->select('m_id AS id', 'm_gname As gname', 'm_bname AS bname', 'm_desc AS descripition', 'm_country AS country', 'm_idnumber AS product_number','m_price AS price', 'm_imdate AS importer_date', 'm_exdate AS expire_date', 'm_seffect AS side_effect', 'm_quantity AS product_quantity', 'm_imname AS supplier_name','m_imprice AS original_price','m_discount AS product_discount', 'm_image AS product_image', 'm_icon AS icon', 'm_barcodeg AS product_barcode','categories.name AS category' )
                         ->join('categories','categories.id', '=' ,'medicinals.m_cat')
                         ->where('medicinals.uid', Auth::id())
                         ->Where('m_bname', 'LIKE', $request->input('query').'%')
                         ->orWhere('m_gname', 'LIKE', $request->input('query').'%')
                         ->orWhere('m_idnumber', 'LIKE', $request->input('query').'%')
                         ->orWhere('m_barcodeg', 'LIKE', $request->input('query').'%')
                         ->limit(15)
                         ->get();
        
       
        if(count($getAllMedicinal->all()) > 0 ) {
            return response()->json(['status' => 'success', 'data' => $getAllMedicinal],201);
        }else{
            return response()->json(['status' => 'empty'],204);
        }
    }


    /*
     *  Sort By
     *  @return json response 
     * **********************************/

    public function sortby(Request $request){
        $request->validate([
            'type' => 'max:70|regex:/^[a-z0-9 .\-]+$/i',
            'category_id' => 'numeric',
        ]);        

        if($request->input('type') === 'category'){

          $getAllMedicinal = DB::table('medicinals')
                         ->select('m_id AS id', 'm_gname As gname', 'm_bname AS bname', 'm_desc AS descripition', 'm_country AS country', 'm_idnumber AS product_number','m_price AS price', 'm_imdate AS importer_date', 'm_exdate AS expire_date', 'm_seffect AS side_effect', 'm_quantity AS product_quantity', 'm_imname AS supplier_name','m_imprice AS original_price','m_discount AS product_discount', 'm_image AS product_image', 'm_icon AS icon', 'm_barcodeg AS product_barcode','categories.name AS category' )
                         ->join('categories','categories.id', '=' ,'medicinals.m_cat')
                         ->where('medicinals.uid', Auth::id())
                         ->where('m_cat','=', $request->input('category_id'))
                         ->paginate(15);

        } elseif ($request->input('type') === 'outstock') {

         $getAllMedicinal = DB::table('medicinals')
                         ->select('m_id AS id', 'm_gname As gname', 'm_bname AS bname', 'm_desc AS descripition', 'm_country AS country', 'm_idnumber AS product_number','m_price AS price', 'm_imdate AS importer_date', 'm_exdate AS expire_date', 'm_seffect AS side_effect', 'm_quantity AS product_quantity', 'm_imname AS supplier_name','m_imprice AS original_price','m_discount AS product_discount', 'm_image AS product_image', 'm_icon AS icon', 'm_barcodeg AS product_barcode','categories.name AS category' )
                         ->join('categories','categories.id', '=' ,'medicinals.m_cat')
                         ->where('medicinals.uid', Auth::id())
                         ->where('m_quantity','=','0')
                         ->paginate(15);

        } elseif ($request->input('type') === 'expire') {

         $getAllMedicinal = DB::table('medicinals')
                         ->select('m_id AS id', 'm_gname As gname', 'm_bname AS bname', 'm_desc AS descripition', 'm_country AS country', 'm_idnumber AS product_number','m_price AS price', 'm_imdate AS importer_date', 'm_exdate AS expire_date', 'm_seffect AS side_effect', 'm_quantity AS product_quantity', 'm_imname AS supplier_name','m_imprice AS original_price','m_discount AS product_discount', 'm_image AS product_image', 'm_icon AS icon', 'm_barcodeg AS product_barcode','categories.name AS category' )
                         ->join('categories','categories.id', '=' ,'medicinals.m_cat')
                         ->where('medicinals.uid', Auth::id())
                         ->where('m_exdate','<=','NOW()')
                         ->paginate(15);
        }



        if(count($getAllMedicinal->all()) > 0 ) {
            return response()->json(['status' => 'success', 'data' => $getAllMedicinal],201);
        }else{
            return response()->json(['status' => 'empty'],204);
        }
    }

    /*
     *  Create new medicinal
     *  @return json response 
     * **********************************/

    public function create(Request $request) {
        
        // User role
        Auth::user()->checkPermission();
        
        //validate data
        $data = json_decode($request['data'], true);
        Validator::make($data, [
            'gname' => 'required|max:70|regex:/^[a-z0-9 .\-]+$/i',
            'bname' => 'required|nullable|max:70|regex:/^[a-z0-9 .\-]+$/i',
            'description' => 'nullable',
            'country' => 'nullable|alpha_dash|max:70',
            'productNumber' => 'required|nullable|max:70|regex:/^[a-z0-9 .\-]+$/i',
            'importerDate' => 'required|nullable|date',
            'expireDate' => 'required|nullable|date',
            'sideEffect' => 'nullable',
            'category' => 'required|max:70|regex:/^[a-z0-9 .\-]+$/i',
            'quantity' => 'required|numeric|max:100000000',
            'salePrice' => 'numeric|max:100000000',
            'discount' => 'nullable|numeric|max:100',
            'suppliedName' => 'max:70|regex:/^[a-z0-9 .\-]+$/i',
            'originalPrice' => 'nullable|numeric|max:100000000',

        ])->validate();

        //store data
        $data = json_decode($request['data']);
        $product = new Medicinal;
        $product->m_gname = ucfirst($data->gname);
        $product->m_bname = ucfirst($data->bname);
        $product->m_desc = $data->description;
        $product->m_country = $data->country;
        $product->m_idnumber = $data->productNumber;
        $product->m_imdate = date('Y-m-d H:i:s', strtotime($data->importerDate));
        $product->m_exdate = date('Y-m-d H:i:s', strtotime($data->expireDate));
        $product->m_seffect = $data->sideEffect;
        $product->m_cat = $data->category;
        $product->m_quantity = $data->quantity;
        $product->m_price = $data->salePrice;
        $product->m_discount = $data->discount;
        $product->m_imname = $data->suppliedName;
        $product->m_imprice = $data->originalPrice;
        $product->m_barcodeg = $data->barcode;
        $product->uid = Auth::user()->pharmacy_id;

        //upload image
        if ($request->hasFile('image')) {
            $photoName = str_random(15) . '.' . $request->image->getClientOriginalExtension();
            $makeDir = File::makeDirectory(public_path('upload/'.Auth::user()->name), $mode = 0777, true, true);
            $request->image->move('upload/'.Auth::user()->name, $photoName);
            $product->m_image = $photoName;
        }
       // $product->m_icon = $request->icon;
        $product->save();

        return response()->json(['status' => 'success','data' => $product ],201);
    }




        
    /*
     *  Get some medicinal 
     *  @return json response 
     * **********************************/

    public function getMedicinal($id){
       
        $getMedicinal = DB::table('medicinals')
                         ->select('m_id AS id', 'm_gname As gname', 'm_bname AS bname', 'm_desc AS description', 'm_country AS country', 'm_idnumber AS product_number','m_price AS price', 'm_imdate AS importer_date', 'm_exdate AS expire_date', 'm_seffect AS side_effect', 'm_quantity AS product_quantity', 'm_imname AS importer_name','m_imprice AS original_price','m_discount AS product_discount', 'm_image AS product_image', 'm_icon AS icon', 'm_barcodeg AS product_barcode','categories.id AS category_id','categories.name AS category_name' )
                         ->join('categories','categories.id', '=' ,'medicinals.m_cat')
                         ->where('medicinals.uid', Auth::id())
                         ->where('m_id',$id)
                         ->first();
        if(! is_null($getMedicinal) ) {
            return response()->json(['status' => 'success', 'data' => $getMedicinal],200);
        }else{
            return response()->json(['status' => 'empty'],204);
        }
    }


    /*
     *  Create new medicinal
     *  @return json response 
     * **********************************/

    public function update(Request $request) {
        
        // User role
        Auth::user()->checkPermission();

        
        //validate data
        $data = json_decode($request['data'], true);
        Validator::make($data, [
            'gname' => 'required|max:70|regex:/^[a-z0-9 .\-]+$/i',
            'bname' => 'required|nullable|max:70|regex:/^[a-z0-9 .\-]+$/i',
            'description' => 'nullable',
            'country' => 'nullable|alpha_dash|max:70',
            'product_number' => 'required|nullable|max:70|regex:/^[a-z0-9 .\-]+$/i',
            'importer_date' => 'required|nullable|date',
            'expire_date' => 'required|nullable|date',
            'side_effect' => 'nullable',
            'category_id' => 'required|max:70|regex:/^[a-z0-9 .\-]+$/i',
            'product_quantity' => 'required|numeric|max:100000000',
            'price' => 'numeric|max:100000000',
            'product_discount' => 'nullable|numeric|max:100',
            'importer_name' => 'max:70|nullable|regex:/^[a-z0-9 .\-]+$/i',
            'original_price' => 'nullable|numeric|max:100000000',
            'product_barcode' => 'numeric'

        ])->validate();
      
      
        //store data

        $data = json_decode($request['data']);
        $product = Medicinal::find($data->id);
        $product->m_gname = ucfirst($data->gname);
        $product->m_bname = ucfirst($data->bname);
        $product->m_desc = $data->description;
        $product->m_country = $data->country;
        $product->m_idnumber = $data->product_number;
        $product->m_imdate = date('Y-m-d H:i:s', strtotime($data->importer_date));
        $product->m_exdate = date('Y-m-d H:i:s', strtotime($data->expire_date));
        $product->m_seffect = $data->side_effect;
        $product->m_cat = $data->category_id;
        $product->m_quantity = $data->product_quantity;
        $product->m_price = $data->price;
        $product->m_discount = $data->product_discount;
        $product->m_imname = $data->importer_name;
        $product->m_imprice = $data->original_price;
        $product->m_barcodeg = $data->product_barcode;
        $product->uid = Auth::user()->pharmacy_id;

        //upload image
        if ($request->hasFile('image')) {
            $photoName = str_random(15) . '.' . $request->image->getClientOriginalExtension();
            $makeDir = File::makeDirectory(public_path('upload/'.Auth::user()->name), $mode = 0777, true, true);
            $request->image->move('upload/'.Auth::user()->name, $photoName);
            $product->m_image = $photoName;
        }
       // $product->m_icon = $request->icon;
        $product->save();

        return response()->json(['status' => 'success','data' => $product ],201);
    }



    /*
     *  Create new medicinal
     *  @return json response 
     * **********************************/

    public function export(Request $request) {

        // User role
        Auth::user()->checkPermission();


       $name = str_random(20);
       \Excel::create($name, function($excel) {

            $excel->sheet('Medicinal product', function($sheet) {
                
                $data = Medicinal::where('uid', Auth::user()->pharmacy_id)->get();
                $sheet->fromArray($data);

            });

        })->store('xls', public_path('upload/files/'.Auth::user()->name.'/exports'));
       
        return response()->json(['status' => 'success', 'download_url' => url('/upload/files/'.Auth::user()->name.'/exports/'.$name.'.xls') ]);
    }

}
