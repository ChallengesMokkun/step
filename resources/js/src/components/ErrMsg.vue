<template>
  <p class="c-err-msg" :class="'p-' + project + '__err-msg'">
    <template v-if="'columns' in data && data.columns.auth in errors">
      {{ errors[data.columns.auth][0] }}
    </template>
    <template v-else-if="Object.keys(errors).length">
      入力内容に誤りがございます。
    </template>
    <template v-else-if="'dbErr' in data && data.dbErr">
      ご不便をおかけして申し訳ございません。<br>
      接続エラーのため通信できませんでした。<br>
      時間を置いてから、再度お試しください。
    </template>
    <template v-else-if="'sendLinkErr' in data && data.sendLinkErr">
      ご不便をおかけして申し訳ございません。<br>
      メールを送信することができませんでした。<br>
      時間を置いてから、再度お試しください。
    </template>
    <template v-else-if="'sendEmailConfirmErr' in data && data.sendEmailConfirmErr">
      ご不便をおかけして申し訳ございません。<br>
      接続エラーにより送信できませんでした。<br>
      時間を置いてから、再度お試しください。
    </template>
  </p>
</template>

<script>
export default {
  inject: {
    project: {},
    data: {},
    errors: {
      default: false
    }
  }
}
//エラーメッセージを出すケース
//1.認証エラー(ログイン・パスワードリマインダー)
//2.バリデーションエラー
//3.DB接続エラー
//4.パスワードリマインダー リセットリンク送信エラー
//5.問い合わせフォーム 確認メール送信エラー
</script>