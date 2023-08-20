<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="viewport-extra" content="min-width=375">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <title>@isset($title) {{ $title }} | {{ config('app.name') }} @else {{ config('app.name') }} @endisset</title>
  <link rel="stylesheet" href="{{ asset('build/assets/app.css') }}">
</head>