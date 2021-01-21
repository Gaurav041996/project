<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use phpDocumentor\Reflection\Types\Array_;

class ProductController extends Controller
{


    public function add(Request $request){
         $name=$request->product_name;
            $price=$request->price;
            $stock=$request->stock;
            $desc=$request->desc;
            $parray=array();
        if($request->hasFile('pimages'))
        {
            $pfiles=$request->file('pimages');
            foreach($pfiles as $pfile)
            {
               $filename=$pfile->getClientOriginalName();
               $pfile->move(public_path().'/product',$filename);
               array_push($parray,$filename);
            }
//            for($i=0;$i<$request->file('pimages').length;$i++){
//                $imgname=time().'.'.$request->file('pimages'.$i)->extension();
//            }

//            return $imgname;
        }
        $p=new Product();

        $p->prodcut_name=$name;
        $p->product_price=$price;
        $p->stock=$stock;
        $p->product_description=$desc;
        $p->images=json_encode($parray);
        $p->save();

        return response()->json(['status'=>true,'msg'=>"Record Added"]);

//        for ($x=0;$x<$request->images ; $x++){
//            $type=$request->file('images'.$x)->extension();
//            $filename=time()."_".$type;
//            return $filename;
//            $request->file('images'.$x)->storeAs(public_path('product'),$filename);
//        }

    }

    public  function  get_data(){
        $data= Product::all();
        return response()->json(['status'=>true,'data'=>$data]);
    }
    public  function  deletep(Request $request){
        $id=$request->id;
        $res=Product::where('id',$id)->delete();
        return response()->json(['status'=>true,'msg'=>"Record Delete"]);
    }
    public  function  editp(Request $request){
        $id=$request->id;
        $res=Product::where('id',$id)->get();
        return response()->json(['status'=>true,'response'=>$res]);
    }
    public function  update(Request $request){
        $id=$request->id;
        $name=$request->name;
        $price=$request->price;
        $stock=$request->stock;
        $desc=$request->desc;
        $parray=array();
        if($request->hasFile('pimages'))
        {
            $pfiles=$request->file('pimages');
            foreach($pfiles as $pfile)
            {
                $filename=$pfile->getClientOriginalName();
                $pfile->move(public_path().'/product',$filename);
                array_push($parray,$filename);
            }
//            for($i=0;$i<$request->file('pimages').length;$i++){
//                $imgname=time().'.'.$request->file('pimages'.$i)->extension();
//            }

//            return $imgname;
        }
        $p= Product::find($id);

        $p->prodcut_name=$name;
        $p->product_price=$price;
        $p->stock=$stock;
        $p->product_description=$desc;
        $p->images=json_encode($parray);
        $p->save();

        return response()->json(['status'=>true,'msg'=>"Record Updated"]);

    }
}
