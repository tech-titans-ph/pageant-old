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
  <link rel="stylesheet"
    href="{{ mix('css/app.css') }}">
</head>

<body class="p-4 text-sm text-gray-700">
  <div id="app">
    <div class="flex items-center justify-center">
      <div>
        <div class="flex items-center justify-center">
          <img src="{{ $contest->logo_url }}"
            class="object-contain object-center w-32 h-32 border rounded">
        </div>
        <div class="px-4 text-center">
          <div class="text-2xl font-medium">{{ $contest->name }}</div>
        </div>
      </div>
    </div>
    <div class="px-1 mb-4">
      <p class="mb-4 text-2xl font-bold">Category Scores</p>

      @include('admin.scores.category')
    </div>
  </div>
  <script src="{{ mix('js/app.js') }}"></script>
  <script>
    window.print();
  </script>
</body>

</html>
