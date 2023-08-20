<template>
  <div class="p-step-list">
    <h2 class="c-title c-title--main p-step-list__title">チャレンジ</h2>

    <div class="p-step-list__result-wrapper">
      <template v-if="data.dbData.data.length">
        <p class="p-step-list__plain-text c-plain-text">全部で{{data.dbData.total}}個チャレンジしました</p>
        <p class="p-step-list__num-wrapper">
          <span class="p-step-list__item c-item">件数 {{data.dbData.from}} - {{data.dbData.to}}</span>
          <span class="p-step-list__item c-item">ページ {{data.dbData.current_page}} - {{data.dbData.last_page}}</span>
        </p>
      </template>
      <p class="p-step-list__plain-text c-plain-text" v-else>チャレンジしたSTEPはありません</p>
    </div>
  
    <transition appear v-if="data.dbData.data.length" key="challenge">
      <div class="p-step-list__card-wrapper" :class="{'p-step-list__card-wrapper--1-card': data.dbData.to - data.dbData.from === 0}">
        <StepCardChallenge v-for="challengeCard in data.dbData.data" :key="challengeCard[data.columns.id]" :card="challengeCard" />
      </div>
    </transition>
    
    <Pagenation key="pagenation" v-if="data.dbData.last_page > 1" />
  </div>
</template>

<script>
import Pagenation from '../components/Pagenation.vue'
import StepCardChallenge from '../components/StepCardChallenge.vue'

export default {
  inject: ['data', 'formatDate'],
  provide(){
    return {
      getParam: this.data.getParamStrings,
      dbData: this.data.dbData,
      columns: this.data.columns,
      chalColumns: this.data.chalColumns
    }
  },
  components: {
    Pagenation: Pagenation,
    StepCardChallenge: StepCardChallenge
  }
}
</script>