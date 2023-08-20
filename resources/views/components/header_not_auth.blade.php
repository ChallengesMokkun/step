<header class="l-header">
  <div class="l-header__wrapper">
    <div class="l-header__logo-wrapper">
      <a href="{{route('step.index')}}" class="l-header__logo-link">
        <img src="{{ asset('logo.png') }}" alt="" class="l-header__logo">
      </a>
    </div>
    <div class="l-header__menu-wrapper l-header__menu-wrapper--not-login">
      <nav class="l-header__menu-list-wrapper l-header__menu-list-wrapper--not-login">
        <ul class="l-header__menu-list l-header__menu-list--not-login">
          <li class="l-header__menu-item l-header__menu-item--not-login">
            <a href="/" class="l-header__menu-link l-header__menu-link--not-login">トップページ</a>
          </li>
          <li class="l-header__menu-item l-header__menu-item--not-login">
            <a href="{{route('step.index')}}" class="l-header__menu-link l-header__menu-link--not-login">STEPを探す</a>
          </li>
        </ul>
      </nav>
    </div>
  </div>
</header>