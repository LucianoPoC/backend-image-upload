<?php

namespace App\Domains\Uploads\Controllers;

use App\Core\Http\Controllers\Controller;
use App\Domains\Uploads\Models\Uploads;
use Maatwebsite\Excel\Facades\Excel;

class ExportController extends Controller
{
    public function index()
    {
        $items = Uploads::all();

        Excel::create('uploads', function($excel) use ($items) {
            $excel->sheet('ExportFile', function($sheet) use ($items) {
                $sheet->fromArray($items);
            });
        })->download('csv');
    }
}
