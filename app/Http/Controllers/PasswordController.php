<?php

namespace App\Http\Controllers;
use App\Http\Controllers\Controller;
use App\Jobs\ForgotPasswordJob;
use App\Models\Users;
use App\Models\UsersResetPassword;
use App\Services\NameServices;
use App\Services\TokenServices;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class PasswordController extends Controller
{
    public function change(Request $request)
    {
        $user = Auth::user();
        $user_id = $user->id;
        $update_password = $user->update_password;
        $data = array(
            'update_password' => $update_password
            );
        return view('index/change_password',$data);
    }
    public function update(Request $request)
    {
        $user = Auth::user();
        $user_id = $user->id;

        $request->validate([
            'password' => ['required', 'string', 'min:8', 'regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[!@#$%^&*(),.?":{}|<>]).*$/'],
        ]);

        $password = $request->password;

        $update_password = $user->update_password;
        if($update_password==NULL){
            $update_password = 0;
        }

        $user = Users::find($user_id);
        $user->password = Hash::make($password);
        $user->update_password = $update_password+1;
        $user->forgot_password = NULL;
        $user->save();

        return response()->json(['result' => 'success']);
    }

    public function forgot(Request $request)
    {
        $request->validate([
            'id_no' => ['required', 'string'],
        ]);

        $user = Users::with('personal_info')
            ->where('username',$request->id_no)
            ->first();

        if(!$user){
            return response()->json(['result' => 'Error',
                'message' => "ID No. doesn't match in our system."]);
        }

        if(!$user->personal_info->email_official){
            return response()->json(['result' => 'Error',
                'message' => "Your account doesn't have email in our system. please visit the IT Support Office for assistance."]);
        }

        $token_services = new TokenServices;
        $name_services = new NameServices;
        $temporary_password = $token_services->token_w_upper_special_char(12);
        $name = $name_services->firstname($user->lastname,$user->firstname,$user->middlename,$user->extname);
        $email = $user->personal_info->email_official;
        $dateTime = date('Y-m-d H:i:s');
        $reference_no = $this->getReferenceNo($dateTime);
        $details = [
            'name' => $name,
            'email' => $email,
            'dateTime' => $dateTime,
            'reference_no' => $reference_no,
            'temporary_password' => $temporary_password,
        ];

        dispatch(new ForgotPasswordJob($details));

        $update = Users::find($user->id);
        $update->password = Hash::make($temporary_password);
        $update->forgot_password = 1;
        $update->save();

        $insert = new UsersResetPassword;
        $insert->user_id = $user->id;
        $insert->email = $email;
        $insert->reference_no = $reference_no;
        $insert->temporary_password = $temporary_password;
        $insert->save();

        $email = explode('@',$email);
        $hiddenEmail = substr($email[0], 0, 3).str_repeat('*', strlen($email[0]) - 3).'@'.$email[1];
        return response()->json(['result' => 'success',
                'message' => "Your temporary password had been sent to your email ".$hiddenEmail]);
    }

    private function getReferenceNo($dateTime){
        $date = date('Y-m-d',strtotime($dateTime));
        $year = date('Y',strtotime($dateTime));
        $month = date('m',strtotime($dateTime));
        $day = date('d',strtotime($dateTime));
        $query = UsersResetPassword::whereDate('created_at', $date)
            ->orderBy('reference_no','DESC')
            ->first();
        if($query){
            $existingNumber = intval(substr($query->reference_no, -6));
            $nextNumber = str_pad($existingNumber + 1, 6, '0', STR_PAD_LEFT);

            return 'TP' .$year.$month.$day. $nextNumber;
        }else{
            return 'TP'.$year.$month.$day.'000001';
        }

    }
}
