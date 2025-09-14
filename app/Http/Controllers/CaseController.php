<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\DataTables\CaseDataTable;

class CaseController extends Controller
{
    public function index(CaseDataTable $dataTable)
    {
        return $dataTable->render('case.index');
    }
}
