<?php

namespace App\Http\Controllers\SEARCH;
use App\Http\Controllers\Controller;
use App\Models\PSGCBrgys;
use App\Models\PSGCCityMuns;
use App\Models\PSGCProvinces;
use Illuminate\Http\Request;

class PSGCController extends Controller
{
    public function brgys(Request $request){
        $search = $request->input('search');
        $city_muns = $request->city_muns;
        $results = PSGCBrgys::where('name', 'LIKE', "%$search%");
        $result = $results->whereHas('city_muns', function ($query) use ($city_muns) {
            $query->where('id',$city_muns);
        });
        $results = $results->orderBy('name')
            ->limit(10)
            ->get();
        $data = [];
        if($results->count()>0){
            foreach ($results as $result) {
                $data[] = ['id' => $result->id, 'text' => $result->name];
            }
        }
        return response()->json($data);
    }
    public function cityMuns(Request $request){
        $search = $request->input('search');
        $province = $request->province;
        $results = PSGCCityMuns::where('name', 'LIKE', "%$search%");
        if($province!='place'){
            $result = $results->whereHas('provinces', function ($query) use ($province) {
                 $query->where('id',$province);
            });
        }
        $results = $results->orderBy('name')
            ->limit(10)
            ->get();
        $data = [];
        if($results->count()>0){
            foreach ($results as $result) {                
                if($province!='place'){
                    $data[] = ['id' => $result->id, 'text' => $result->name];
                }else{
                    if($result->type=='HUC'){
                        $province_ = '';
                    }else{
                        $province_ = ', '.$result->provinces->name;
                    }
                    $data[] = ['id' => $result->name.$province_, 'text' => $result->name.$province_];
                }
            }
        }
        return response()->json($data);
    }
    public function provinces(Request $request){
        $search = $request->input('search');
        $results = PSGCProvinces::where('name', 'LIKE', "%$search%");
        $results = $results->orderBy('name')
            ->limit(10)
            ->get();
        $data = [];
        if($results->count()>0){
            foreach ($results as $result) {
                $data[] = ['id' => $result->id, 'text' => $result->name];
            }
        }
        return response()->json($data);
    }
}