<?php

namespace App\Http\Controllers\DTS;
use App\Http\Controllers\Controller;
use App\Models\DTSType;
use App\Models\Office;
use App\Models\Users;
use App\Services\ValidateAccessServices;
use Illuminate\Support\Facades\Auth;

class PageController extends Controller
{
    private $page;
    private $validate;
    public function __construct()
    {
        $this->page = 'dts';
        $this->validate = new ValidateAccessServices;
    }
    public function inbox($data)
    {
        return view($this->page.'/inbox',$data);
    }
    public function receive($data)
    {
        return view($this->page.'/receive',$data);
    }
    public function forward($data)
    {
        return view($this->page.'/forward',$data);
    }
    public function search($data)
    {
        return view($this->page.'/search',$data);
    }
    public function new($data)
    {
        $user = Auth::user();
        $user_id = $user->id;
        $user = Users::with('employee_default')->where('id',$user_id)->first();
        $office_id = $user->employee_default->office_id;

        $documents = DTSType::get();
        $offices = Office::whereNotIn('id',[$office_id])->get();

        $data['documents'] = $documents;
        $data['offices'] = $offices;
        return view($this->page.'/new',$data);
    }
}
?>
