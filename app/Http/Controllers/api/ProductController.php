<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Product;
class ProductController extends Controller
{
    public function show(Request $request){
        $productShow=Product::select('id','name','image','cate_id')->where('cate_id',$request->id)->where('display','1')->orderBy('order','desc')->take(6)->get()->toArray();
    foreach($productShow as $data){
        echo "<div class=' col-md-6 col-sm-12  col-lg-4 grid-item col-xs-12 col-sm-6'  >
                <div class='single_protfolio kc-elm kc-css-298222'>
                <div class='prot_thumb'><a href='/product/".$data['id']."'><img width='707' height='913' src='/upload/".$data['image']."' class='attachment-post-thumbnail size-post-thumbnail wp-post-image' alt=' loading='lazy' srcset='/upload/".$data['image']." 707w, /upload/".$data['image']." 232w' sizes='(max-width: 707px) 100vw, 707px' /></a>
                    <div class='prot_content em_port_content '>
                    <div class='prot_content_inner'>
                        <div class='porttitle_inner'>
                        <h3><a href='/product/".$data['id']."'>".$data['name']."</a></h3>
                        <p><span class='category-item-p'></span></p>
                        </div>
                    </div>
                    </div>
                </div>
                </div>
            </div>";
    }
    }
}
