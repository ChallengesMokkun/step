<template>
<div class="p-member-form">
  <h2 class="c-title c-title--main p-member-form__title">退会</h2>

  <form :action="data.url.withdraw" method="post" class="p-member-form__form-wrapper">
    <FormMethod method="delete" />
    <CSRFToken />
    <ErrMsg key="err" v-if="Object.keys(data.errors).length || data.dbErr" />
    <p class="p-member-form__plain-text c-plain-text">
      退会をすると、登録したデータを<br>
      二度と復元することはできません<br>
      本当に退会をご希望の場合に限り<br>
      退会の手続きを実行してください
    </p>
    <FormPassword :column="data.columns.pass" :ruleFlag="true">パスワード</FormPassword>
    <div class="p-member-form__action-wrapper">
      <a :href="data.url.mypage" class="c-btn c-btn--m c-btn--inactive p-member-form__btn">戻る</a>
      <Btn optionClass="c-btn--m c-btn--active" :msg="'退会します。\r\n本当によろしいでしょうか。'">退会する</Btn>
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
    FormMethod: FormMethod,
  }
}
</script>