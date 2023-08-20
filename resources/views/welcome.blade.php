<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
  @include('components.head', ['title' => config('app.catchphrase')])
  <body>
    <div class="p-top-page">
      <div class="p-top-page__pic-title-area">
        <h1 class="p-top-page__title c-title c-title--top-page">
          あなたの人生の<br class="p-top-page__nl">STEPを共有しよう
        </h1>
        <div class="p-top-page__pic-wrapper">
          <img class="p-top-page__picture c-picture" src="{{asset('top-page.jpg')}}" alt="">
        </div>
      </div>
      <div class="p-top-page__msg-btn-area">
        <div class="p-top-page__msg-wrapper">
          <h2 class="p-top-page__sub-title c-title c-title--top-page-sub">成功が新たな成功を生みだす</h2>
          <p class="p-top-page__plain-text c-plain-text">
            STEPとは、何かを成功させるための筋道です。
          </p>
          <p class="p-top-page__plain-text c-plain-text">
            あなたがSTEPをシェアすることで、<br class="p-top-page__nl">他の人を成功へと導くことができます。
          </p>
          <p class="p-top-page__plain-text c-plain-text">
            あるいはあなたが他の人のSTEPを辿り、<br class="p-top-page__nl">成功を手にすることができます。
          </p>
        </div>
        <a href="{{route('step.index')}}" class="c-btn c-btn--m c-btn--active p-top-page__btn">アクセス</a>
      </div>
    </div>
  </body>
</html>
