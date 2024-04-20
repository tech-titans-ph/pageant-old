<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">

<head>
  <meta charset="UTF-8">
  <meta name="viewport"
    content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible"
    content="ie=edge">
  <meta name="csrf-token"
    content="{{ csrf_token() }}">

  <title>{{ config('app.name') }}</title>

  <link rel="icon"
    href="{{ asset('images/favicon.png') }}"
    type="image/png" />

  <link rel="stylesheet"
    href="{{ mix('css/app.css') }}">

  <style>
    @page {
      margin: 0.75in 0;
    }
  </style>
</head>

<body class="px-6 py-4 text-gray-900 print:p-0 text-2xs">
  <div id="app">
    <div class="flex items-center justify-center mb-4">
      <div class="flex items-center space-x-4">
        <div class="text=center">
          <img src="{{ $contest->logo_url }}"
            class="object-contain object-center w-24 h-24 border rounded">
        </div>
        <h1 class="text-base font-bold text-center">{{ $contest->name }}</h1>
      </div>
    </div>

    @yield('content')
  </div>

  @include('admin.print-footer', ['model' => $contest])

  <script src="{{ mix('js/app.js') }}"></script>
  <script>
    // window.print();
  </script>
</body>
