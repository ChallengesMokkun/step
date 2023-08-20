<template>
  <div class="p-step-list">
    <h2 class="c-title c-title--main p-step-list__title">投稿されたSTEP</h2>

    <div class="p-step-list__result-wrapper">
      <template v-if="data.dbData.data.length">
        <p class="p-step-list__plain-text c-plain-text">{{data.dbData.total}}個のSTEPが見つかりました</p>
        <p class="p-step-list__num-wrapper">
          <span class="p-step-list__item c-item">件数 {{data.dbData.from}} - {{data.dbData.to}}</span>
          <span class="p-step-list__item c-item">ページ {{data.dbData.current_page}} - {{data.dbData.last_page}}</span>
        </p>
      </template>
      <p class="p-step-list__plain-text c-plain-text" v-else>STEPは見つかりませんでした</p>
    </div>
    <SearchBox key="searchBox" :categories="data.categories" :old="data.old" v-if="!(data.columns.userId in data.old)" />
    
    <transition appear v-if="data.dbData.data.length" key="steps">
      <div class="p-step-list__card-wrapper" :class="{'p-step-list__card-wrapper--1-card': data.dbData.to - data.dbData.from === 0}">
        <StepCard v-for="step in data.dbData.data" :key="step[data.columns.id]" 
          :category="categories[step[data.columns.catId]]" :unit="units[step[data.columns.unitId]]" :card="step" />
      </div>
    </transition>

    <Pagenation key="pagenation" v-if="data.dbData.last_page > 1" />
  </div>
</template>

<script>
import Pagenation from '../components/Pagenation.vue'
import StepCard from '../components/StepCard.vue'
import SearchBox from '../components/SearchBox.vue'
export default {
  inject: ['data', 'convertCardAttributes'],
  provide(){
    return {
      getParam: this.data.getParamStrings,
      dbData: this.data.dbData,
      columns: this.data.columns,
      userColumns: this.data.userColumns,
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
    StepCard: StepCard,
    SearchBox: SearchBox,
    Pagenation: Pagenation,
  }
}
//inject
//convertCardAttributes Array形式からオブジェクト形式に変換する
</script>