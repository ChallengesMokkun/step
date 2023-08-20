<template>
  <div class="p-member-form">
    <h2 class="c-title c-title--main p-member-form__title">お問い合わせ</h2>
    <form :action="data.url.inquiry" method="post" class="p-member-form__form-wrapper">
      <CSRFToken />
      <ErrMsg key="err" v-if="Object.keys(data.errors).length  || data.dbErr || data.sendEmailConfirmErr" />
      <p class="p-member-form__plain-text c-plain-text">
        お問い合わせを受付いたします。<br>
        回答までしばらくお待ちください。
      </p>
      <FormText :column="data.columns.name" :required="true" :countFlg="true" :limit="data.constant.nameMax">お名前</FormText>
      <FormEmail :column="data.columns.email" :required="true">メールアドレス</FormEmail>
      <FormText :column="data.columns.purpose" :required="true">ご用件</FormText>
      <FormTextarea :column="data.columns.msg" :limit="data.constant.msgMax" :limitLines="data.constant.msgLines" :required="true" optionClass="c-form-textarea--inquiry">ご用件の詳細</FormTextarea>

      <div class="p-form-field-part">
        <div class="p-form-field-part__confirm-wrapper">
          <p class="c-link p-form-field-part__link">
            <a :href="data.url.privacy" class="c-link__anchor p-form-field-part__anchor" target="_blank" rel="noopener noreferrer">
              プライバシーポリシー
            </a>
          </p>
          <FormCheckbox :required="true" @toggleCheck="updateCheck" column="agreement">上記の内容に同意する</FormCheckbox>
        </div>
      </div>
      <div class="p-member-form__action-wrapper">
        <a :href="data.url.stepIndex" class="c-btn c-btn--m c-btn--inactive p-member-form__btn">戻る</a>
        <Btn :optionClass="checkClass" :notAllowed="notAllowed" :msg="'送信します。\r\nよろしいでしょうか。'">送信する</Btn>
      </div>
    </form>
  </div>
</template>

<script>
  import FormText from '../components/FormText.vue'
  import FormEmail from '../components/FormEmail.vue'
  import FormTextarea from '../components/FormTextarea.vue'
  import ErrMsg from '../components/ErrMsg.vue'
  import CSRFToken from '../components/CSRFToken.vue'
  import Btn from '../components/Btn.vue'
  import FormCheckbox from '../components/FormCheckbox.vue'

  export default {
    inject: ['data'],
    provide(){
      return {
        project: 'member-form',
        errors: this.data.errors,
        old: this.data.old,
      }
    },
    data(){
      return {
        checkClass: 'c-btn--m c-btn--not-allowed',
        notAllowed: true
      }
    },
    methods: {
      updateCheck(e){
        this.checkClass = e ? 'c-btn--m c-btn--active' : 'c-btn--m c-btn--not-allowed',
        this.notAllowed = e ? false : true
      }
    },
    components: {
      FormText: FormText,
      FormEmail: FormEmail,
      ErrMsg: ErrMsg,
      CSRFToken: CSRFToken,
      Btn: Btn,
      FormTextarea: FormTextarea,
      FormCheckbox: FormCheckbox,
    }
  }
</script>