<template>
  <WrapperStepForm :action="data.action" :msg="'STEPを更新します。\r\nよろしいでしょうか。'" :back="data.back" method="put">
    <template #title>STEP編集</template>
    <template #btnName>更新する</template>
    <template #deleteBtn>
      <form :action="data.action" method="post" class="p-step-form__action-btn-wrapper">
        <FormMethod method="delete" />
        <CSRFToken />
        <Btn optionClass="c-btn--m c-btn--inactive" :msg="'STEPを削除します。\r\nよろしいでしょうか。'">削除する</Btn>
      </form>
    </template>
  </WrapperStepForm>
</template>
<script>
import WrapperStepForm from '../components/WrapperStepForm.vue'
import CSRFToken from '../components/CSRFToken.vue'
import FormMethod from '../components/FormMethod.vue'
import Btn from '../components/Btn.vue'
export default {
  inject: ['data'],
  components: {
    WrapperStepForm: WrapperStepForm,
    CSRFToken: CSRFToken,
    FormMethod: FormMethod,
    Btn: Btn
  }
}
</script>