<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, viewport-fit=cover">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <title>{{ isset($title) ? $title.' - '.config('app.name') : config('app.name') }}</title>

  @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="min-h-screen font-sans antialiased bg-base-200/50 dark:bg-base-200">
  <x-toast />
{{-- MAIN --}}
  <x-main full-width>
    {{-- The `$slot` goes here --}}
    <x-slot:content>
      <div class="min-h-screen flex flex-col justify-center items-center pt-6 sm:pt-0">
        {{ $slot }}
      </div>
    </x-slot:content>
  </x-main>
</body>

</html>
