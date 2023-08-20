<template>

<div class="p-step-list">
    <h2 class="c-title c-title--main p-step-list__title">チャレンジ</h2>
    <div class="p-step-list__result-wrapper">
      <template v-if="data.dbData.challenge.length">
        <p class="p-step-list__plain-text c-plain-text">最新{{data.dbData.challengeNum}}件</p>
        <p class="c-link p-step-list__link">
          <a :href="data.url.challenges" class="c-link__anchor p-step-list__anchor">
            すべてのチャレンジ
          </a>
        </p>
      </template>
      <p class="p-step-list__plain-text c-plain-text" v-else>チャレンジしたSTEPはありません</p>
    </div>
    <transition appear v-if="data.dbData.challenge.length" key="challenge">
      <div class="p-step-list__card-wrapper" :class="{'p-step-list__card-wrapper--1-card': data.dbData.challengeNum === 1}">
        <StepCardChallenge v-for="challengeCard in data.dbData.challenge" :key="'c-' + challengeCard[data.columns.id]" :card="challengeCard" />
      </div>
    </transition>
  </div>

  <div class="p-step-list">
    <h2 class="c-title c-title--main p-step-list__title">マイSTEP</h2>
    <div class="p-step-list__result-wrapper">
      <template v-if="data.dbData.mystep.length">
        <p class="p-step-list__plain-text c-plain-text">最新{{data.dbData.mystepNum}}件</p>
        <p class="c-link p-step-list__link">
          <a :href="data.url.myStep" class="c-link__anchor p-step-list__anchor">
            すべてのマイSTEP
          </a>
        </p>
      </template>
      <p class="p-step-list__plain-text c-plain-text" v-else>登録したSTEPはありません</p>
    </div>
    <transition appear v-if="data.dbData.mystep.length" key="mystep">
      <div class="p-step-list__card-wrapper" :class="{'p-step-list__card-wrapper--1-card': data.dbData.mystepNum === 1}">
        <StepCardMyStep v-for="myStepCard in data.dbData.mystep" :key="'s-' + myStepCard[data.columns.id]" :category="categories[myStepCard[data.columns.catId]]" :unit="units[myStepCard[data.columns.unitId]]" :card="myStepCard" />
      </div>
    </transition>
  </div>

  <div class="p-mypage-menu">
    <h2 class="c-title c-title--main p-mypage-menu__title">メニュー</h2>
    <div class="p-mypage-menu__link-wrapper">
      <div class="p-mypage-menu__link-row">
        <p class="p-mypage-menu__link c-link">
          <a :href="data.url.stepRegister" class="p-mypage-menu__anchor c-link__anchor">STEP登録</a>
        </p>
        <p class="p-mypage-menu__link c-link">
          <a :href="data.url.myStep" class="p-mypage-menu__anchor c-link__anchor">マイSTEP</a>
        </p>
        <p class="p-mypage-menu__link c-link">
          <a :href="data.url.challenges" class="p-mypage-menu__anchor c-link__anchor">チャレンジ</a>
        </p>
      </div>
      <div class="p-mypage-menu__link-row">
        <p class="p-mypage-menu__link c-link">
          <a :href="data.url.profEdit" class="p-mypage-menu__anchor c-link__anchor">プロフィール変更</a>
        </p>
        <p class="p-mypage-menu__link c-link">
          <a :href="data.url.passEdit" class="p-mypage-menu__anchor c-link__anchor">パスワード変更</a>
        </p>
        <p class="p-mypage-menu__link c-link">
          <a :href="data.url.withdraw" class="p-mypage-menu__anchor c-link__anchor">退会</a>
        </p>
      </div>
    </div>
  </div>


</template>

<script>
import StepCardChallenge from '../components/StepCardChallenge.vue'
import StepCardMyStep from '../components/StepCardMyStep.vue'
export default {
  inject: ['data', 'convertCardAttributes'],
  provide(){
    return {
      getParam: this.data.getParamStrings,
      columns: this.data.columns,
      chalColumns: this.data.chalColumns
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
    StepCardChallenge: StepCardChallenge,
    StepCardMyStep: StepCardMyStep
  },
}
//inject
//convertCardAttributes Array形式からオブジェクト形式に変換する
</script>