<template>
  <div class="p-step-list">
    <h2 class="c-title c-title--main p-step-list__title">マイSTEP</h2>

    <div class="p-step-list__result-wrapper">
      <template v-if="data.dbData.data.length">
        <p class="p-step-list__plain-text c-plain-text">全部で{{data.dbData.total}}個登録しています</p>
        <p class="p-step-list__num-wrapper">
          <span class="p-step-list__item c-item">件数 {{data.dbData.from}} - {{data.dbData.to}}</span>
          <span class="p-step-list__item c-item">ページ {{data.dbData.current_page}} - {{data.dbData.last_page}}</span>
        </p>
      </template>
      <p class="p-step-list__plain-text c-plain-text" v-else>登録したSTEPはありません</p>
    </div>

    <transition appear v-if="data.dbData.data.length" key="mystep">
      <div class="p-step-list__card-wrapper" :class="{'p-step-list__card-wrapper--1-card': data.dbData.to - data.dbData.from === 0}">
        <StepCardMyStep v-for="myStepCard in data.dbData.data" :key="myStepCard[data.columns.id]" :category="categories[myStepCard[data.columns.catId]]" :unit="units[myStepCard[data.columns.unitId]]" :card="myStepCard" />
      </div>
    </transition>
    <Pagenation key="pagenation" v-if="data.dbData.last_page > 1" />
  </div>
</template>

<script>
import Pagenation from '../components/Pagenation.vue'
import StepCardMyStep from '../components/StepCardMyStep.vue'
export default {
  inject: ['data', 'convertCardAttributes'],
  provide(){
    return {
      getParam: this.data.getParamStrings,
      dbData: this.data.dbData,
      columns: this.data.columns
    }
  },
  created(){
    //カテゴリ・時間の単位のオブジェクトをそれぞれ作る
    this.convertCardAttributes(this)
  },
  data(){
    return {
      categories: {},
      units: {},
    }
  },
  components: {
    Pagenation: Pagenation,
    StepCardMyStep: StepCardMyStep
  }
}
//inject
//convertCardAttributes Array形式からオブジェクト形式に変換する
</script>