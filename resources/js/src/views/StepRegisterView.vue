<template>
  <WrapperStepForm :action="data.url.stepRegister" :back="data.url.mypage" :msg="'STEPを登録します。\r\nよろしいでしょうか。'">
    <template #title>STEP登録</template>
    <template #btnName>登録する</template>
  </WrapperStepForm>
</template>
<script>
import WrapperStepForm from '../components/WrapperStepForm.vue'
export default {
  inject: ['data'],
  components: {
    WrapperStepForm: WrapperStepForm
  }
}
</script>