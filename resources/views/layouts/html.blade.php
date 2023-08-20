<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
  @include('components.head', ['title' => $title])
  <body>
    @include('components.header')
    <main class="l-main">
      {{ $slot }}
    </main>
    @include('components.footer')
    <script src="{{ asset('build/assets/viewport-extra.min.js') }}" async></script>
  </body>
</html>
