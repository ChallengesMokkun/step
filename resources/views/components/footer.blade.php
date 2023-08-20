<footer class="l-footer">
  <div class="l-footer__wrapper">
    <nav class="l-footer__menu-list-wrapper">
      <ul class="l-footer__menu-list">
        <li class="l-footer__menu-item">
          <a href="{{ route('tos') }}" class="l-footer__menu-link">利用規約</a>
        </li>
        <li class="l-footer__menu-item">
          <a href="{{ route('privacy') }}" class="l-footer__menu-link">プライバシーポリシー</a>
        </li>
        <li class="l-footer__menu-item">
          <a href="{{ route('inquiry') }}" class="l-footer__menu-link">お問い合わせ</a>
        </li>
      </ul>
    </nav>
    <p class="l-footer__plain-text">&copy; {{ config('app.corp') }} Co., Ltd.</p>
  </div>
</footer>