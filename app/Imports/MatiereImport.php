<?php

namespace App\Imports;

use App\Matiere;
use Maatwebsite\Excel\Concerns\ToArray;
use Maatwebsite\Excel\Concerns\WithStartRow;

class MatiereImport implements ToArray
{
    public function array(array $row)
    {
        if(!empty(array_filter($row))){
            return $row;
        }
    }
}
