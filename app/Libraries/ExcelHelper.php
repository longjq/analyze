<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2016/6/2
 * Time: 17:45
 */

namespace App\Libraries;

use Storage;
use Excel;
class ExcelHelper
{
    private $fileName;
    public function __construct($fielName)
    {
        if(!is_dir(storage_path('app/exports/'.date('Y-m-d')))){
            Storage::makeDirectory('exports/'.date('Y-m-d'));
        }
        $this->fileName = iconv('UTF-8', 'GBK', date('Y-m-d').DIRECTORY_SEPARATOR.$fielName);
    }

    private function createExcel($cellData, $sheetName){
        return Excel::create($this->fileName,function($excel) use ($cellData, $sheetName){
            $excel->sheet($sheetName, function($sheet) use ($cellData){
                $sheet->rows($cellData);
            });
        });
    }

    public function exportToDownload($cellData,$sheetName = 'sheet', $ext = 'xls'){
        $this->createExcel($cellData, $sheetName)->download($ext);
    }

    public function exportToStore($cellData,$sheetName = 'sheet', $ext = 'xls'){
        return $this->createExcel($cellData, $sheetName)->store($ext, false, true);
    }
}