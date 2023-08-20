<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
  @include('components.head', ['title' => $title])
  <body>
    <div id="js-flash-msg"></div>
    @include('components.header_not_auth')
    <main class="l-main" id="js-app">
      {{ $slot }}
    </main>
    @include('components.footer')
    <script src="{{ asset('build/assets/app.js') }}"></script>
  </body>
</html>
