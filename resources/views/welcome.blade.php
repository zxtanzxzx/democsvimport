<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Laravel</title>

    @vite('resources/css/app.css')
    @livewireStyles
</head>

<body class="antialiased">
    <div class="relative flex flex-col items-center min-h-screen bg-dots-darker bg-center bg-gray-100 pt-10">
        <x-custom-container>
            @livewire('csv-upload-form')
        </x-custom-container>
        <x-custom-container>
            @livewire('csv-upload-table')
        </x-custom-container>
        <x-custom-container>
            Table 2 here
        </x-custom-container>
    </div>
    @livewireScripts
</body>

</html>
