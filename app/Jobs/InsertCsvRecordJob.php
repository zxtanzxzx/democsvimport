<?php

namespace App\Jobs;

use App\Models\Product;
use App\Models\CsvFileUpload;
use Illuminate\Bus\Batchable;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Contracts\Queue\ShouldBeUnique;

class InsertCsvRecordJob implements ShouldQueue
{
    use Batchable, Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public CsvFileUpload $csv;

    public $interestedColumns;
    public $colMap;
    public $record;
    /**
     * Create a new job instance.
     */
    public function __construct($interestedColumns, $colMap, $record)
    {
        $this->interestedColumns = $interestedColumns;
        $this->colMap = $colMap;
        $this->record = $record;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        Product::updateOrCreate(
            [
                $this->interestedColumns[0] => $this->record[$this->interestedColumns[0]],
            ],
            [
                $this->interestedColumns[1] => iconv("UTF-8", "UTF-8//IGNORE", $this->record[$this->interestedColumns[1]]),
                $this->interestedColumns[2] => iconv("UTF-8", "UTF-8//IGNORE", $this->record[$this->interestedColumns[2]]),
                $this->interestedColumns[3] => iconv("UTF-8", "UTF-8//IGNORE", $this->record[$this->interestedColumns[3]]),
                $this->interestedColumns[4] => iconv("UTF-8", "UTF-8//IGNORE", $this->record[$this->interestedColumns[4]]),
                $this->interestedColumns[5] => iconv("UTF-8", "UTF-8//IGNORE", $this->record[$this->interestedColumns[5]]),
                $this->interestedColumns[6] => iconv("UTF-8", "UTF-8//IGNORE", $this->record[$this->interestedColumns[6]]),
                $this->interestedColumns[7] => iconv("UTF-8", "UTF-8//IGNORE", $this->record[$this->interestedColumns[7]]),
            ]
        );
    }
}
