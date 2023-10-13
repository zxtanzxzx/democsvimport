<?php

namespace App\Livewire;

use App\Models\CsvFileUpload;
use Livewire\Component;

class CsvUploadTable extends Component
{
    public $csvFileUploadList = [];

    public function render()
    {
        return view('livewire.csv-upload-table');
    }

    public function refreshCsvFileUploadList()
    {
        $this->csvFileUploadList = CsvFileUpload::all();
    }
}
