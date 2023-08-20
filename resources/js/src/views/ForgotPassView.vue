<template>
  <div class="p-member-form">
    <h2 class="c-title c-title--main p-member-form__title">パスワードリセット</h2>

    <form :action="data.url.passForgot" method="post" class="p-member-form__form-wrapper">
      <CSRFToken />
      <ErrMsg key="err" v-if="Object.keys(data.errors).length || data.dbErr || data.sendLinkErr" />
      <p class="p-member-form__plain-text c-plain-text">
        登録されているメールアドレス宛に<br>
        パスワードリセットリンクをお送りします。
      </p>
      <FormEmail :column="data.columns.email">メールアドレス</FormEmail>
      <div class="p-member-form__action-wrapper">
        <a :href="data.url.login" class="c-btn c-btn--m c-btn--inactive p-member-form__btn">戻る</a>
        <Btn optionClass="c-btn--m c-btn--active"  :msg="'リセットリンクを送信します\r\nよろしいでしょうか。'">送信する</Btn>
      </div>
    </form>
  </div>
</template>

<script>
  import FormEmail from '../components/FormEmail.vue'
  import ErrMsg from '../components/ErrMsg.vue'
  import CSRFToken from '../components/CSRFToken.vue'
  import Btn from '../components/Btn.vue'
  export default {
    inject: ['data'],
    provide(){
      return {
        project: 'member-form',
        errors: this.data.errors,
        old: this.data.old,
      }
    },
    components: {
      FormEmail: FormEmail,
      ErrMsg: ErrMsg,
      CSRFToken: CSRFToken,
      Btn: Btn,
    }
  }
</script>