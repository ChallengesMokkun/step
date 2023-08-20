<template>
  <div class="p-member-form">
    <h2 class="c-title c-title--main p-member-form__title">ログイン</h2>
    <form :action="data.url.login" method="post" class="p-member-form__form-wrapper">
      <CSRFToken />
      <ErrMsg key="err" v-if="(Object.keys(data.errors).length) || data.dbErr" />
      <FormEmail :column="data.columns.email">メールアドレス</FormEmail>
      <FormPassword :column="data.columns.pass" :ruleFlag="true">パスワード</FormPassword>
      <div class="p-member-form__action-wrapper">
        <div class="p-member-form__link-wrapper">
          <p class="c-link p-member-form__link">
            <a :href="data.url.userRegister" class="c-link__anchor p-member-form__anchor">ユーザー登録はこちらから</a>
          </p>
          <p class="c-link p-member-form__link">
            <a :href="data.url.passForgot" class="c-link__anchor p-member-form__anchor">パスワードをお忘れの方</a>
          </p>
        </div>
        <Btn optionClass="c-btn--m c-btn--active">ログイン</Btn>
      </div>
    </form>
  </div>
</template>

<script>
  import FormEmail from '../components/FormEmail.vue'
  import FormPassword from '../components/FormPassword.vue'
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
      FormPassword: FormPassword,
      ErrMsg: ErrMsg,
      CSRFToken: CSRFToken,
      Btn: Btn,
    }
  }
</script>