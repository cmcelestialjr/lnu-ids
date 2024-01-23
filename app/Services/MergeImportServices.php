<?php
namespace App\Services;

use Intervention\Image\Facades\Image;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use PDF;
use PDFMerger;

class MergeImportServices
{
    public function do($request,$path,$path_retrieve,$extension,$name,$imageNameNew){
        $getImage = array();
        $getPdf = array();
        $oMerger = PDFMerger::init();
        if ($request->hasFile('files')) {
            $public_path = public_path($path_retrieve);
            if(!File::exists($public_path)) {
                File::makeDirectory($public_path, $mode = 0777, true, true);
            }
            $files = $request->file('files');
            $x = 0;            
            foreach ($files as $file) {      
                $fileExtension = strtolower($file->extension());
                if(in_array($fileExtension, $extension) ){
                    $imageName = $name.$x.'.'.$fileExtension;  
                    if($fileExtension!='pdf'){                          
                        $img = Image::make($file->path());
                        $height = $img->height();
                        $width = $img->width();
                        if($width>$height){
                            $img->orientate()
                            ->rotate(-90);
                            Storage::putFileAs($path, $img->encode(), $imageName);
                        }else{
                            Storage::putFileAs($path, $file, $imageName);
                        }                            
                        $getImage[] = $public_path.$imageName;
                    }else{
                        Storage::putFileAs($path, $file, 'pdf-'.$x.$imageName);
                        $getPdf[] = $public_path.'pdf-'.$x.$imageName;
                        $oMerger->addPDF($public_path.'pdf-'.$x.$imageName, 'all','P');
                    }
                }
                $x++;
            }
        }
        if($getImage!=NULL){
            $pdf = new PDF('P', 'mm', array(215.9, 330.2), true, 'UTF-8', false);
            foreach($getImage as $row){
                $headertext = '<div><img src="'.$row.'"
                style="height:1150px;width:800px"></div>';
                $pdf::AddPage();
                $pdf::writeHTML($headertext, true, false, true, false, '');
            }
            $pdf::Output($public_path.'img-pdf', 'F');

            $oMerger->addPDF($public_path.'img-pdf', 'all','P');
        }
        $oMerger->merge();
        $imageNameNew = $imageNameNew.'.pdf';
        if(File::exists($public_path.$imageNameNew)){
            File::delete($public_path.$imageNameNew);
        }
        $oMerger->save($public_path.$imageNameNew);        
        if($getImage){
            File::delete($public_path.'img-pdf');
            $this->deleteFile($getImage);
        }
        if($getPdf){
            $this->deleteFile($getPdf);
        }
        $doc = $path_retrieve.$imageNameNew;
        return $doc; 
    }
    private function deleteFile($files){
        foreach($files as $row){
            File::delete($row);
        }
    }
}

?>
