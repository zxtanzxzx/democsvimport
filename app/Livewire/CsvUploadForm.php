<?php

namespace App\Livewire;

use App\Jobs\StartCsvImportJob;
use Livewire\Component;
use App\Models\CsvFileUpload;
use Livewire\WithFileUploads;
use App\Jobs\StartImportProductJob;
use Illuminate\Support\Facades\Bus;
use Illuminate\Support\Facades\Log;
use App\Jobs\StartProductBatchImport;
use Illuminate\Support\Facades\Storage;

class CsvUploadForm extends Component
{
    use WithFileUploads;

    const LIVEWIRETEMPDIR = '/livewire-tmp/';
    public $chunkSize = 2000000; // 2 MB
    public $fileChunk;

    public $fileName;
    public $fileSize;

    public $finalFile;

    public $uploadStatus = '';

    public function render()
    {
        return view('livewire.csv-upload-form');
    }

    public function updatedFileChunk()
    {
        $this->uploadStatus = 'uploading';
        // Open chunk file
        $chunkFileName = $this->fileChunk->getFileName();
        $finalFilePath = Storage::path($this::LIVEWIRETEMPDIR . $this->fileName);
        $tmpChunkFile   = Storage::path($this::LIVEWIRETEMPDIR . $chunkFileName);

        // Load chunk file data
        $chunkFile = fopen($tmpChunkFile, 'rb');
        $buff = fread($chunkFile, $this->chunkSize);
        fclose($chunkFile);

        // Append chunk file data into final file
        $finalFile = fopen($finalFilePath, 'ab');
        fwrite($finalFile, $buff);
        fclose($finalFile);

        // Delete tmp chunk file
        unlink($tmpChunkFile);

        $finalFileSize = Storage::size($this::LIVEWIRETEMPDIR . $this->fileName);

        if ($finalFileSize == $this->fileSize) {
            $this->uploadStatus = 'completed';
            $this->saveUploadedFile($this::LIVEWIRETEMPDIR . $this->fileName);
        } else {
        }
    }

    public function saveUploadedFile(string $finalFilePath)
    {
        $fileMd5 = md5_file(Storage::path($finalFilePath));
        $newFileName = now()->timestamp . $fileMd5 . '.csv';

        if (CsvFileUpload::where('md5_hash', $fileMd5)->first() == null) {

            $newFilePath = '/product_csv/' . $newFileName;
            if (Storage::move($finalFilePath, $newFilePath)) {

                $newUpload = new CsvFileUpload;

                $newUpload->original_file_name = $this->fileName;
                $newUpload->new_file_name = $newFileName;
                $newUpload->file_path = $newFilePath;
                $newUpload->status = 'pending';
                $newUpload->md5_hash = $fileMd5;

                $newUpload->save();

                StartCsvImportJob::dispatch($newFilePath, $newUpload)
                ->onConnection('redis')
                ->onQueue('default')
                ->delay(now()->addSeconds(5));
            }
        }
        Storage::delete($finalFilePath);
    }

    
}
