<template>
  <WrapperStepDetail>
     <template #step>
       <SmallStep :stepTitle="data.dbData[data.columns['step' + data.step]]" :detail="data.dbData[data.columns['stepDetail' + data.step]]" :num="data.step" />
     </template>
     <template #btn>
       <form :action="data.dbData.clearUrl" method="post" class="p-step-detail__action-btn-wrapper"
       v-if="data.loginFlg && Object.keys(data.challenge).length && !data.challenge[data.chalColumns.clearFlg] && ((data.challenge[data.chalColumns.current] + 1 === data.step) || (data.challenge[data.chalColumns.current] >= data.dbData[data.columns.total]))">
         <FormMethod method="put" />
         <CSRFToken />
         <Btn optionClass="c-btn--m c-btn--active"  :msg="'STEP' + data.step + 'のクリアを\r\n記録します。' + '\r\nよろしいでしょうか。'">クリア</Btn>
       </form>
     </template>
  </WrapperStepDetail>
 </template>
 <script>
import WrapperStepDetail from '../components/WrapperStepDetail.vue'
import SmallStep from '../components/SmallStep.vue'
import CSRFToken from '../components/CSRFToken.vue'
import FormMethod from '../components/FormMethod.vue'
import Btn from '../components/Btn.vue'
export default {
  inject: ['data'],
  components: {
    WrapperStepDetail: WrapperStepDetail,
    SmallStep: SmallStep,
    CSRFToken: CSRFToken,
    FormMethod: FormMethod,
    Btn: Btn,
  }
}
//クリアボタンを押せる条件
// ログイン済み かつ チャレンジしている かつ 完全クリア前 かつ (1 または 2)
// 1. 開いているページが、到達STEPの次である場合(現在の到達STEP + 1の場合)
// 2. STEP編集でSTEP数が減少し、到達STEPが総STEP数以上になってしまった場合
 </script>