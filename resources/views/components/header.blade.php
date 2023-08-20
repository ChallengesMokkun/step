<header class="l-header">
  <div class="l-header__wrapper">
    <div class="l-header__logo-wrapper">
      <a href="{{route('step.index')}}" class="l-header__logo-link">
        <img src="{{ asset('logo.png') }}" alt="" class="l-header__logo">
      </a>
    </div>

    @guest
      <div class="l-header__menu-wrapper l-header__menu-wrapper--not-login">
        <nav class="l-header__menu-list-wrapper l-header__menu-list-wrapper--not-login">
          <ul class="l-header__menu-list l-header__menu-list--not-login">
            <li class="l-header__menu-item l-header__menu-item--not-login">
              <a href="{{ route('user.register') }}" class="l-header__menu-link l-header__menu-link--not-login">ユーザー登録</a>
            </li>
            <li class="l-header__menu-item l-header__menu-item--not-login">
              <a href="{{ route('login') }}" class="l-header__menu-link l-header__menu-link--not-login">ログイン</a>
            </li>
          </ul>
        </nav>
      </div>
    @endguest

    @auth
      <div class="l-header__menu-wrapper l-header__menu-wrapper--login">
        <input type="checkbox" id="l-header__menu-switch">
        <label for="l-header__menu-switch" class="l-header__menu-btn-wrapper l-header__menu-btn-wrapper--close">
          <span class="l-header__menu-btn l-header__menu-btn--close"></span>
          <span class="l-header__menu-btn l-header__menu-btn--close"></span>
          <span class="l-header__menu-btn l-header__menu-btn--close"></span>
        </label>

        <nav class="l-header__menu-list-wrapper l-header__menu-list-wrapper--login">
          <label for="l-header__menu-switch" class="l-header__menu-btn-wrapper l-header__menu-btn-wrapper--open">
            <span class="l-header__menu-btn l-header__menu-btn--open"></span>
            <span class="l-header__menu-btn l-header__menu-btn--open"></span>
            <span class="l-header__menu-btn l-header__menu-btn--open"></span>
          </label>
          <ul class="l-header__menu-list l-header__menu-list--login">
            <li class="l-header__menu-item l-header__menu-item--login">
              <a href="{{ route('mypage') }}" class="l-header__menu-link l-header__menu-link--login">マイページ</a>
            </li>
            <li class="l-header__menu-item l-header__menu-item--login">
              <a href="{{ route('challenges') }}" class="l-header__menu-link l-header__menu-link--login">チャレンジ</a>
            </li>
            <li class="l-header__menu-item l-header__menu-item--login">
              <a href="{{ route('mystep') }}" class="l-header__menu-link l-header__menu-link--login">マイSTEP</a>
            </li>
            <li class="l-header__menu-item l-header__menu-item--login">
              <a href="{{ route('step.register') }}" class="l-header__menu-link l-header__menu-link--login">STEP登録</a>
            </li>
            <li class="l-header__menu-item l-header__menu-item--login">
              <form action="{{ route('logout') }}" method="post">
                @csrf
                <button type="submit" class="l-header__menu-link l-header__menu-link--login">ログアウト</button>
              </form>
            </li>
          </ul>
        </nav>
        
      </div>
    @endauth
  </div>
</header>