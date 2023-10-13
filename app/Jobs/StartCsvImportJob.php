<?php

namespace App\Jobs;

use Illuminate\Bus\Batch;
use App\Models\CsvFileUpload;
use Illuminate\Bus\Queueable;
use Illuminate\Support\Facades\Bus;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Contracts\Queue\ShouldBeUnique;

class StartCsvImportJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public string $filePath;
    public CsvFileUpload $csvFileUpload;
    /**
     * Create a new job instance.
     */
    public function __construct($filePath, $csvFileUpload)
    {
        $this->filePath = $filePath;
        $this->csvFileUpload = $csvFileUpload;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $batch = Bus::batch([
            new GenerateCsvImportBatchJob($this->filePath)
        ])->then(
            function (Batch $batch) {
                $csvFileUpload = CsvFileUpload::where('batch_id', $batch->id)->first();
                $csvFileUpload->status = 'completed';
                $csvFileUpload->save();
            }
        )->dispatch();

        $this->csvFileUpload->batch_id = $batch->id;
        $this->csvFileUpload->save();
    }
}
