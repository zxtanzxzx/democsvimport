<div class="flex flex-col" wire:poll.10000ms='refreshCsvFileUploadList'>
    <div class="overflow-x-auto sm:-mx-6 lg:-mx-8">
        <div class="inline-block min-w-full py-2 sm:px-6 lg:px-8">
            <div class="overflow-hidden">
                @if (count($csvFileUploadList) > 0)

                    <table class="min-w-full text-left text-sm font-light">
                        <thead class="border-b font-medium dark:border-neutral-500">
                            <tr>
                                <th scope="col" class="px-6 py-4">Time</th>
                                <th scope="col" class="px-6 py-4">File Name</th>
                                <th scope="col" class="px-6 py-4">Status</th>
                                <th scope="col" class="px-6 py-4">Progress</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($csvFileUploadList as $csvFileUpload)
                                <tr class="border-b dark:border-neutral-500">
                                    <td class="whitespace-nowrap px-6 py-4 font-medium">{{ $csvFileUpload->created_at }}
                                    </td>
                                    <td class="whitespace-nowrap px-6 py-4 font-medium">{{ $csvFileUpload->file_path }}
                                    </td>
                                    <td class="whitespace-nowrap px-6 py-4 font-medium">{{ $csvFileUpload->status }}
                                    </td>
                                    <td class="whitespace-nowrap px-6 py-4 font-medium">
                                        @if ($csvFileUpload->status == 'pending' && $csvFileUpload->batch_id != null)
                                            @livewire('csv-import-progress-bar', ['batchId' => $csvFileUpload->batch_id])
                                        @else
                                            All record saved.
                                        @endif
                                    </td>
                                </tr>
                            @endforeach

                        </tbody>
                    </table>
                @else
                    <div>No record found.</div>
                @endif
            </div>
        </div>
    </div>
</div>
