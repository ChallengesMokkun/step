<template>
  <div class="p-member-form">
    <h2 class="c-title c-title--main p-member-form__title">パスワード変更</h2>

    <form :action="data.url.passEdit" method="post" class="p-member-form__form-wrapper">
      <FormMethod method="put" />
      <CSRFToken />
      <ErrMsg key="err" v-if="Object.keys(data.errors).length || data.dbErr" />
      <FormPassword :column="data.columns.passCurrent" :required="true">今のパスワード</FormPassword>
      <FormPassword :column="data.columns.pass" :required="true" :ruleFlag="true">新しいパスワード</FormPassword>
      <FormPassword :column="data.columns.passRe" :errColumn="data.columns.pass" :required="true">新しいパスワード再入力</FormPassword>
      <div class="p-member-form__action-wrapper">
        <a :href="data.url.mypage" class="c-btn c-btn--m c-btn--inactive p-member-form__btn">戻る</a>
        <Btn optionClass="c-btn--m c-btn--active" :msg="'パスワードを変更します。\r\nよろしいでしょうか。'">変更する</Btn>
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
        errors: this.data.errors,
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