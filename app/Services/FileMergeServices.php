<?php
namespace App\Services;
use PDF;
use Illuminate\Support\Facades\File;
use Intervention\Image\Facades\Image;
use Webklex\PDFMerger\Facades\PDFMergerFacade as PDFMerger;

class FileMergeServices
{
    public function getDoc($request,$total_files,$id_no,$path)
    {
        $oMerger = PDFMerger::init();

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
                $fileName = $id_no.$x.'.'.$fileExtension;
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

        $fileName = $id_no.'-'.date('Y-m-d-H-i-s').'.pdf';

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
}
