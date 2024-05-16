<?php

namespace App\Http\Controllers\DTS;

use App\Http\Controllers\Controller;
use App\Models\DTSDocs;
use App\Models\DTSDocsHistory;
use App\Models\DTSStatus;
use App\Models\Users;
use App\Services\DTSServices;
use Carbon\Carbon;
use Exception;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Validator;
use Intervention\Image\Facades\Image;
use PDF;
use PDOException;
use Webklex\PDFMerger\Facades\PDFMergerFacade as PDFMerger;

class NewController extends Controller
{
    public function create(Request $request)
    {
        // Validate the incoming request data
        $validator = $this->createValidateRequest($request);

        // Check if validation fails
        if ($validator->fails()) {
            return  response()->json(['result' => 'error']);
        }

        $type_id = $request->type_id;
        $office_id = $request->office_id;
        $particulars = $request->particulars;
        $description = $request->description;
        $amount = $request->amount;
        $remarks = $request->remarks;
        $total_files = $request->total_files;
        $dts = DTSDocs::where('particulars',$particulars)
            ->where('type_id',$type_id)
            ->first();

        // Check if dts exists
        if ($dts) {
            return  response()->json(['result' => 'error']);
        }

        $user = Auth::user();
        $user_id = $user->id;
        $user = Users::with('employee_default')->where('id',$user_id)->first();
        $user_office_id = $user->employee_default->office_id;

        $dts_services = new DTSServices;
        $dts_id = $dts_services->dts_id();

       try{

            $insert = new DTSDocs();
            $insert->dts_id = $dts_id;
            $insert->type_id = $type_id;
            $insert->particulars = $particulars;
            $insert->description = $description;
            $insert->amount = $amount;
            $insert->remarks = $remarks;
            $insert->office_id = $user_office_id;
            $insert->status_id = 1;
            $insert->created_by = $user_id;
            $insert->updated_by = $user_id;
            $insert->save();

            $doc_id = $insert->id;

            if($total_files>0){
                $doc = $this->getDoc($request,$total_files,$dts_id);
                DTSDocs::where('id', $doc_id)
                    ->update([
                        'docs' => $doc
                    ]);
            }

            $insert = new DTSDocsHistory();
            $insert->doc_id = $doc_id;
            $insert->office_id = $office_id;
            $insert->option_id = 2;
            $insert->remarks = '';
            $insert->is_return = 'N';
            $insert->action_office_id = $user_office_id;
            $insert->dhm = '0-0-0';
            $insert->action = 1;
            $insert->created_by = $user_id;
            $insert->updated_by = $user_id;
            $insert->save();

            return  response()->json([
                'result' => 'success',
                'dts_id' => $dts_id,
            ]);

        } catch (QueryException $e) {
            // Handle database query exceptions
            return $this->handleDatabaseError($e);
        } catch (PDOException $e) {
            // Handle PDO exceptions
            return $this->handleDatabaseError($e);
        } catch (Exception $e) {
            // Handle other exceptions
            return $this->handleOtherError($e);
        }
    }

    private function getDoc($request,$total_files,$dts_id)
    {
        $doc = '';
        $oMerger = PDFMerger::init();
        $path = 'storage\dts/'.date('Y').'/'.date('m');
        $public_path = public_path($path);
        $x = 0;
        $getFiles = [];
        $getFilesPdf = [];

        if (!File::isDirectory($path)) {
            File::makeDirectory($path, 0755, true, true);
        }

        for ($x = 0; $x < $total_files; $x++){
            if ($request->hasFile('files'.$x)){
                $file = $request->file('files'.$x);
                $fileExtension = strtolower($file->extension());
                $fileName = $dts_id.$x.'.'.$fileExtension;
                if($fileExtension=='pdf'){
                    $file->move($path, $fileName);
                    $getFilesPdf[] = $path.'/'.$fileName;
                    $oMerger->addPDF($path.'/'.$fileName, 'all','P');
                }else{
                    $img = Image::make($file->path());
                    $height = $img->height();
                    $width = $img->width();
                    if($width>$height){
                        $img->orientate()
                            ->rotate(-90)
                            ->save($public_path.'/'.$fileName);
                    }else{
                        $file->move($public_path, $fileName);
                    }
                    $getFiles[] = $public_path.'/'.$fileName;
                    $x++;
                }
            }
        }

        $fileName = $dts_id.'.pdf';

        if(File::exists($path.'/'.$fileName)){
            File::delete($path.'/'.$fileName);
        }

        if(count($getFiles)>0 && count($getFilesPdf)>0){
            $pdf = new PDF('P', 'mm', array(215.9, 330.2), true, 'UTF-8', false);
            foreach($getFiles as $row){
                $headertext = '<div><img src="'.$row.'"
                style="height:1150px;width:800px"></div>';
                $pdf::AddPage();
                $pdf::writeHTML($headertext, true, false, true, false, '');
            }
            $pdf::Output(public_path($path.'/img-'.$fileName), 'F');

            $oMerger->addPDF($path.'/img-'.$fileName, 'all','P');
            $oMerger->merge();
            $oMerger->save($path.'/'.$fileName);

            File::delete($path.'/img-'.$fileName);
            foreach($getFiles as $row){
                File::delete($row);
            }
            foreach($getFilesPdf as $row){
                File::delete($row);
            }
        }

        if(count($getFiles)>0 && count($getFilesPdf)<=0){
            $pdf = new PDF('P', 'mm', array(215.9, 330.2), true, 'UTF-8', false);
            foreach($getFiles as $row){
                $headertext = '<div><img src="'.$row.'"
                style="height:1150px;width:800px"></div>';
                $pdf::AddPage();
                $pdf::writeHTML($headertext, true, false, true, false, '');
            }
            $pdf::Output(public_path($path.'/'.$fileName), 'F');

            foreach($getFiles as $row){
                File::delete($row);
            }
        }

        if(count($getFiles)<=0 && count($getFilesPdf)>0){
            $oMerger->merge();
            $oMerger->save($path.'/'.$fileName);

            foreach($getFilesPdf as $row){
                File::delete($row);
            }
        }

        return $path.'/'.$fileName;
    }

    /**
     * Validate the request data.
     *
     * @param Request $request The HTTP request instance.
     * @return \Illuminate\Contracts\Validation\Validator The validation validator instance.
     */
    private function createValidateRequest($request)
    {
        $rules = [
            'type_id' => 'required|numeric',
            'office_id' => 'required|numeric',
            'particulars' => 'required|string',
            'description' => 'required|string',
            'amount' => 'nullable|numeric',
            'remarks' => 'nullable|string',
            //'files.*' => 'nullable|mimes:jpeg,png,jpg,gif,pdf',
        ];

        $customMessages = [
            'type_id.required' => 'Type ID is required',
            'type_id.numeric' => 'Type ID must be a number',
            'office_id.required' => 'Office ID is required',
            'office_id.numeric' => 'Office ID must be a number',
            'particulars.required' => 'Particulars is required',
            'particulars.string' => 'Particulars must be a string',
            'description.required' => 'Description is required',
            'description.string' => 'Description must be a string',
            'amount.numeric' => 'Amount must be a number',
            'remarks.string' => 'Remarks must be a string',
            //'files.*.mimes' => 'The uploaded file must be a PDF or an image (JPEG, PNG, GIF).',
        ];

        return Validator::make($request->all(), $rules, $customMessages);
    }

     /**
     * Handle database errors during the transaction.
     *
     * @param Exception $e The exception object.
     * @return \Illuminate\Http\JsonResponse The JSON response with error details.
     */
    private function handleDatabaseError($e)
    {
        return response()->json(['result' => $e->getMessage()], 400);
    }

    /**
     * Handle other errors during the transaction.
     *
     * @param Exception $e The exception object.
     * @return \Illuminate\Http\JsonResponse The JSON response with error details.
     */
    private function handleOtherError($e)
    {
        return response()->json(['result' => $e->getMessage()], 500);
    }

}
