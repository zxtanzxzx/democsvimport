<div wire:poll.1000ms='checkBatchJobStatus'>
    Saving records. {{abs($batchTotalJob - $batchPendingJob)}} / {{$batchTotalJob}}
     Saved.
</div>
