<template>
  <div class="p-member-form">
    <h2 class="c-title c-title--main p-member-form__title">ユーザー登録</h2>
    <form :action="data.url.userRegister" enctype="multipart/form-data" method="post" class="p-member-form__form-wrapper">
      <CSRFToken />
      <ErrMsg key="err" v-if="Object.keys(data.errors).length || data.dbErr || data.sendEmailConfirmErr" />
      <p class="p-member-form__plain-text c-plain-text">
        ご登録いただきますと、STEPをシェアしたり<br>
        STEPにチャレンジしたりすることができます
      </p>
      <FormText :column="data.columns.name" :required="true" :countFlg="true" :limit="data.constant.nameMax">お名前</FormText>
      <FormEmail :column="data.columns.email" :required="true">メールアドレス</FormEmail>
      <FormPassword :column="data.columns.pass" :required="true" :ruleFlag="true">パスワード</FormPassword>
      <FormPassword :column="data.columns.passRe" :errColumn="data.columns.pass" :required="true">パスワード再入力</FormPassword>
      <FormTextarea :column="data.columns.intro" :limit="data.constant.introMax" :limitLines="data.constant.introLines" 
        optionClass="c-form-textarea--intro" initial="よろしくお願いします！">
          自己紹介文
      </FormTextarea>
      <FormImgUploader :column="data.columns.pic" :limit="data.constant.picMax">アイコン</FormImgUploader>

      <div class="p-form-field-part">
        <div class="p-form-field-part__confirm-wrapper">
          <p class="c-link p-form-field-part__link">
            <a :href="data.url.tos" class="c-link__anchor p-form-field-part__anchor" target="_blank" rel="noopener noreferrer">
              利用規約
            </a>
          </p>
          <p class="c-link p-form-field-part__link">
            <a :href="data.url.privacy" class="c-link__anchor p-form-field-part__anchor" target="_blank" rel="noopener noreferrer">
              プライバシーポリシー
            </a>
          </p>
          <FormCheckbox :required="true"  @toggleCheck="updateCheck" column="agreement">上記の内容に同意する</FormCheckbox>
        </div>
      </div>
      <div class="p-member-form__action-wrapper">
        <div class="p-member-form__link-wrapper">
          <p class="c-link p-member-form__link">
            <a :href="data.url.login" class="c-link__anchor p-member-form__anchor">
              登録済みの方はこちら
            </a>
          </p>
        </div>
        <Btn :optionClass="checkClass" :notAllowed="notAllowed" :msg="'ユーザー登録します。\r\nよろしいでしょうか。'">登録する</Btn>
      </div>
    </form>
  </div>
</template>

<script>
  import FormText from '../components/FormText.vue'
  import FormEmail from '../components/FormEmail.vue'
  import FormPassword from '../components/FormPassword.vue'
  import FormTextarea from '../components/FormTextarea.vue'
  import ErrMsg from '../components/ErrMsg.vue'
  import CSRFToken from '../components/CSRFToken.vue'
  import Btn from '../components/Btn.vue'
  import FormImgUploader from '../components/FormImgUploader.vue'
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
      FormPassword: FormPassword,
      ErrMsg: ErrMsg,
      CSRFToken: CSRFToken,
      Btn: Btn,
      FormTextarea: FormTextarea,
      FormImgUploader: FormImgUploader,
      FormCheckbox: FormCheckbox,
    }
  }
</script>