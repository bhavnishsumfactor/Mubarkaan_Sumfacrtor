<?php

namespace App\Imports;

use Maatwebsite\Excel\Concerns\ToArray;

class LanguagesImport implements ToArray
{
    protected $languages;

    public function array(array $languages)
    {
        return $languages;
    }
}