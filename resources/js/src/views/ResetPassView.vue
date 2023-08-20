<template>
  <div class="p-member-form">
    <h2 class="c-title c-title--main p-member-form__title">パスワードリセット</h2>

    <form :action="data.url.passResetForm" method="post" class="p-member-form__form-wrapper">
      <FormMethod method="put" />
      <CSRFToken />
      <input type="hidden" :name="data.columns.token" :value="data.request.token">
      <input type="hidden" :name="data.columns.email" :value="data.request.email">
      <ErrMsg key="err" v-if="Object.keys(data.errors).length || data.dbErr" />
      <FormPassword :column="data.columns.pass" :required="true" :ruleFlag="true">新しいパスワード</FormPassword>
      <FormPassword :column="data.columns.passRe" :errColumn="data.columns.pass" :required="true">新しいパスワード再入力</FormPassword>
      <div class="p-member-form__action-wrapper">
        <a :href="data.url.login" class="c-btn c-btn--m c-btn--inactive p-member-form__btn">やめる</a>
        <Btn optionClass="c-btn--m c-btn--active" :msg="'パスワードをリセットします。\r\nよろしいでしょうか。'">送信する</Btn>
      </div>
    </form>
  </div>
</template>

<script>
  import FormPassword from '../components/FormPassword.vue'
  import ErrMsg from '../components/ErrMsg.vue'
  import CSRFToken from '../components/CSRFToken.vue'
  import Btn from '../components/Btn.vue'
  import FormMethod from '../components/FormMethod.vue'

  export default {
    inject: ['data'],
    provide(){
      return {
        project: 'member-form',
        errors: this.data.errors
      }
    },
    components: {
      FormPassword: FormPassword,
      ErrMsg: ErrMsg,
      CSRFToken: CSRFToken,
      Btn: Btn,
      FormMethod: FormMethod
    }
  }
</script>