<?php

namespace App\Jobs;

use League\Csv\Reader;
use App\Models\Product;
use League\Csv\Statement;
use Illuminate\Support\Str;
use Illuminate\Bus\Batchable;
use Illuminate\Bus\Queueable;
use Illuminate\Support\Facades\Log;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Storage;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Contracts\Queue\ShouldBeUnique;

class GenerateCsvImportBatchJob implements ShouldQueue
{
    use Batchable, Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public string $filePath;

    /**
     * Create a new job instance.
     */
    public function __construct($filePath)
    {
        $this->filePath = $filePath;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $saveJobs = [];

        $csv = Reader::createFromPath(Storage::path($this->filePath));
        $csv->setDelimiter(',');
        $csv->setHeaderOffset(0);

        $maxRowSizePerStatement = 2;
        $currentChunkOffset = 0;
        $currentRowIndex = 1;

        $colMap = [];
        $interestedColumns = [
            'UNIQUE_KEY',
            'PRODUCT_TITLE',
            'PRODUCT_DESCRIPTION',
            'STYLE#',
            'SANMAR_MAINFRAME_COLOR',
            'SIZE',
            'COLOR_NAME',
            'PIECE_PRICE',
        ];

        foreach ($csv->getHeader() as $key => $value) {
            $value = preg_replace('/[\W]/', '', $value);

            if (in_array($value, $interestedColumns)) {
                $colMap[$value] = $key;
            }
        }

        $totalRow = $csv->count();
        while ($currentRowIndex <= $totalRow) {

            $stmt = Statement::create();
                // ->offset($currentChunkOffset)
                // ->limit($maxRowSizePerStatement);

            $records = $stmt->process($csv);

            foreach ($records as $record) {
                array_push($saveJobs, new InsertCsvRecordJob($interestedColumns, $colMap, $record));
                $currentRowIndex++;
            }
            // $currentChunkOffset += $maxRowSizePerStatement;
        }

        $this->batch()->add($saveJobs);

    }
}
