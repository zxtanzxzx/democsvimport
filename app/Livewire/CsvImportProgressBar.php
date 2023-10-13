<?php

namespace App\Livewire;

use Livewire\Component;
use Illuminate\Support\Facades\Bus;

class CsvImportProgressBar extends Component
{
    public $batchId;
    public $batchTotalJob = 0;
    public $batchPendingJob = 0;

    public function checkBatchJobStatus()
    {

        if ($this->batchId != null) {
            $batch =  Bus::findBatch($this->batchId);
            $this->batchTotalJob = $batch->totalJobs;
            $this->batchPendingJob = $batch->pendingJobs;

            if ($batch->pendingJobs != 0) {


                return;
            }
        }
    }

    public function render()
    {
        return view('livewire.csv-import-progress-bar');
    }
}
