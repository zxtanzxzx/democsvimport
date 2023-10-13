<x-custom-container>
    @if ($uploadStatus == 'uploading')
        <div class="p-2 my-1 bg-yellow-200">Uploading file... Please wait while file is uploading</div>
    @endif

    @if ($uploadStatus == 'completed')
        <div class="p-2 my-1 bg-green-200">Upload Completed. Importing data now.</div>
    @endif

    <form wire:submit.prevent="submit">
        @csrf

        <label class="block mb-2 text-sm font-medium text-gray-900 d" for="file_input">Upload file</label>
        <input
            class="block w-full text-sm text-gray-900 border border-gray-300 rounded-lg cursor-pointer bg-gray-50 focus:outline-none "
            id="csv_file" type="file" name="csv_file">

        <div class="mt-2 p-2 w-full flex item-center justify-center">
            <button type="button" id="submit" onclick="uploadChunks()">Submit</button>
        </div>

    </form>
    @error('import_file')
    @enderror
</x-custom-container>
<script>
    function uploadChunks() {
        const file = document.querySelector('#csv_file').files[0];

        @this.set('fileName', Date.now() + '-' + file.name, true);
        @this.set('fileSize', file.size, true);
        console.log('starting...')

        livewireUploadChunk(file, 0);
    }

    function livewireUploadChunk(file, start) {
        try {
            const chunkEnd = Math.min(start + @js($chunkSize), file.size);
            const chunk = file.slice(start, chunkEnd);

            @this.upload('fileChunk', chunk, (uName) => {}, () => {}, (event) => {
                if (event.detail.progress == 100) {
                    setTimeout(3000)

                    start = chunkEnd;
                    if (start < file.size) {
                        livewireUploadChunk(file, start);
                    }
                }
            });
        } catch (error) {

        }

    }
</script>
