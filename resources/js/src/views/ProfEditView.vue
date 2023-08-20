<template>
  <div class="p-member-form">
    <h2 class="c-title c-title--main p-member-form__title">プロフィール変更</h2>

    <form :action="data.url.profEdit" enctype="multipart/form-data" method="post" class="p-member-form__form-wrapper">
      <FormMethod method="patch" />
      <CSRFToken />
      <ErrMsg key="err" v-if="Object.keys(data.errors).length || data.dbErr || data.sendEmailConfirmErr" />
      <p class="p-member-form__plain-text c-plain-text">
        ご本人様であることを確認するために<br>
        パスワードのご入力もお願いいたします
      </p>
      <FormPassword :column="data.columns.pass" :required="true" :ruleFlag="true">パスワード</FormPassword>
      <FormText :column="data.columns.name" :required="true" :countFlg="true" :limit="data.constant.nameMax">お名前</FormText>
      <FormEmail :column="data.columns.email" :required="true">メールアドレス</FormEmail>
      <FormTextarea :column="data.columns.intro" :limit="data.constant.introMax" :limitLines="data.constant.introLines" 
        optionClass="c-form-textarea--intro">
          自己紹介文
      </FormTextarea>
      <FormImgUploader :column="data.columns.pic" :columnBef="data.columns.picBef" :limit="data.constant.picMax">アイコン</FormImgUploader>
      <div class="p-member-form__action-wrapper">
        <a :href="data.url.mypage" class="c-btn c-btn--m c-btn--inactive p-member-form__btn">戻る</a>
        <Btn optionClass="c-btn--m c-btn--active"  :notAllowed="notAllowed" :msg="'プロフィールを変更します。\r\nよろしいでしょうか。'">変更する</Btn>
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
  import FormMethod from '../components/FormMethod.vue'

  export default {
    inject: ['data'],
    provide(){
      return {
        project: 'member-form',
        errors: this.data.errors,
        old: this.data.old,
        dbData: this.data.dbData
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
      FormMethod: FormMethod
    }
  }
</script>