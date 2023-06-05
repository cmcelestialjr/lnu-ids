<?php

namespace App\Http\Controllers\FMS\Accounting\Fund;
use App\Http\Controllers\Controller;
use App\Models\FundCluster;
use App\Models\FundSource;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SourceController extends Controller
{
    public function table(Request $request){
        return $this->_table($request);
    }
    public function newModal(Request $request){
        return $this->_newModal($request);
    }
    public function newSubmit(Request $request){
        return $this->_newSubmit($request);
    }
    public function updateModal(Request $request){
        return $this->_updateModal($request);
    }
    public function updateSubmit(Request $request){
        return $this->_updateSubmit($request);
    }
    private function _table($request){
        $data = array();
        $query = FundSource::orderBy('code')->get()
            ->map(function($query) {
                return [
                    'id' => $query->id,
                    'name' => $query->name,
                    'shorten' => $query->shorten,
                    'uacs' => $query->uacs,
                    'code' => $query->code
                ];
            })->toArray();
        if(count($query)>0){
            $x = 1;
            foreach($query as $r){
                $data_list['f1'] = $x;
                $data_list['f2'] = $r['name'];
                $data_list['f3'] = $r['shorten'];
                $data_list['f4'] = $r['code'];
                $data_list['f5'] = $r['uacs'];
                $data_list['f6'] = '<button class="btn btn-primary btn-primary-scan btn-sm update"
                                        data-id="'.$r['id'].'"
                                        <span class="fa fa-eye"></span> View
                                    </button>';
                array_push($data,$data_list);
                $x++;
            }
        }
        return  response()->json($data);
    }
    private function _newModal($request){
        $fund_cluster = FundCluster::get();
        $data = array(
            'fund_cluster' => $fund_cluster
        );
        return view('fms/accounting/fund/source/newModal',$data);
    }
    private function _newSubmit($request){
        $user_access_level = $request->session()->get('user_access_level');
        $result = 'error';
        if($user_access_level==1 || $user_access_level==2 || $user_access_level==3){
            $name = $request->name;
            $shorten = $request->shorten;
            $fund_cluster = $request->fund_cluster;
            $uacs = $request->uacs;
            $check = FundSource::where('name',$name)
                ->orWhere('shorten',$shorten)->first();
            if($check==NULL){
                $cluster = FundCluster::where('id',$fund_cluster)->first();
                $cluster_code = $cluster->code;
                $code = $cluster_code.mb_strtoupper($cluster->shorten).mb_strtoupper($shorten);
                $user = Auth::user();
                $updated_by = $user->id;
                $insert = new FundSource(); 
                $insert->fund_cluster_id = $fund_cluster;
                $insert->name = $name;
                $insert->shorten = $shorten;
                $insert->code = $code;
                $insert->uacs = $uacs;
                $insert->updated_by = $updated_by;
                $insert->save();
                $result = 'success';
            }
        }
        $response = array('result' => $result
                        );
        return response()->json($response);
    }
    private function _updateModal($request){
        $id = $request->id;
        $query = FundSource::where('id',$id)->first();
        $fund_cluster = FundCluster::get();
        $data = array(
            'query' => $query,
            'fund_cluster' => $fund_cluster
        );
        return view('fms/accounting/fund/source/updateModal',$data);
    }
    private function _updateSubmit($request){
        $user_access_level = $request->session()->get('user_access_level');
        $result = 'error';
        if($user_access_level==1 || $user_access_level==2 || $user_access_level==3){
            $id = $request->id;
            $name = $request->name;
            $shorten = $request->shorten;
            $fund_cluster = $request->fund_cluster;
            $uacs = $request->uacs;
            $check = FundSource::where('id','!=',$id)
                ->where(function ($query) use ($name,$shorten) {
                    $query->where('name',$name)
                    ->orWhere('shorten',$shorten);
                })->first();
            if($check==NULL){
                $cluster = FundCluster::where('id',$fund_cluster)->first();
                $cluster_code = $cluster->code;
                $code = $cluster_code.mb_strtoupper($cluster->shorten).mb_strtoupper($shorten);
                $user = Auth::user();
                $updated_by = $user->id;
                FundSource::where('id', $id)
                ->update(['fund_cluster_id' => $fund_cluster,
                    'name' => $name,
                    'shorten' => $shorten,
                    'code' => $code,
                    'uacs' => $uacs,
                    'updated_by' => $updated_by,
                    'updated_at' => date('Y-m-d H:i:s'),
                ]);
                $result = 'success';
            }
        }
        $response = array('result' => $result
                        );
        return response()->json($response);
    }
}