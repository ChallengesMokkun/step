<template>
 <WrapperStepDetail>
    <template #step>
      <SmallStep v-for="(step, index) in smallSteps" :link="smallSteps[index]['link']" :stepTitle="smallSteps[index]['title']" 
      :num="smallSteps[index]['num']" :key="smallSteps[index]['num']" />
    </template>
    <template #btn>
      <form :action="data.dbData.chalUrl" method="post" class="p-step-detail__action-btn-wrapper" v-if="data.loginFlg && Object.keys(data.challenge).length && !data.challenge[data.chalColumns.clearFlg]">
        <FormMethod method="delete" />
        <CSRFToken />
        <Btn optionClass="c-btn--m c-btn--inactive" :msg="'チャレンジを\r\nキャンセルします。\r\nよろしいでしょうか。'">キャンセル</Btn>
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
  data(){
    return {
      smallSteps: [] //子STEPの情報を入れておく
    }
  },
  created(){
      //各子STEPの情報を配列に入れておく
      for(let i = 1; i <= this.data.dbData[this.data.columns.total]; i++){
        this.smallSteps.push({
          num: i,
          title: this.data.dbData[this.data.columns['step' + i]],
          link: this.data.dbData.stepLinks[i - 1],
        })
      }
    },
  components: {
    WrapperStepDetail: WrapperStepDetail,
    SmallStep: SmallStep,
    CSRFToken: CSRFToken,
    FormMethod: FormMethod,
    Btn: Btn,
  }
}
//キャンセルボタンが押せる条件
// ログイン済み かつ チャレンジしている かつ 完全クリア前
</script>