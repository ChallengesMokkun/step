<template>
  <div class="p-member-profile">
    <div class="p-member-profile__picture-name-wrapper">
      <div class="p-member-profile__pic-wrapper">
        <img :src="userPic" :alt="data.contributor[data.userColumns.name]" ref="userPic" v-show="picLoad" @load="adjustImg(this, userPic, 'userPic')" @error="errContributorImg(this)" 
          class="p-member-profile__picture c-picture" :class="{'c-picture--gt-width': gtWidth, 'c-picture--gt-height': !gtWidth}">
      </div>
      <div class="p-member-profile__name-wrapper">
        <p class="p-member-profile__item c-item">{{data.contributor[data.userColumns.name]}}</p>
      </div>
    </div>
    <div class="p-member-profile__intro-wrapper">
      <p class="p-member-profile__plain-text c-plain-text">{{data.contributor[data.userColumns.intro]}}</p>
    </div>
  </div>

  <div class="p-step-list">
    <h2 class="c-title c-title--main p-step-list__title">投稿されたSTEP</h2>
    <div class="p-step-list__result-wrapper">
      <template v-if="data.dbData.data.length">
        <p class="p-step-list__plain-text c-plain-text">最新{{data.dbData.num}}件</p>
        <p class="c-link p-step-list__link">
          <a :href="data.contributor.userStepUrl" class="c-link__anchor p-step-list__anchor">
            投稿されたすべてのSTEP
          </a>
        </p>
      </template>
      <p class="p-step-list__plain-text c-plain-text" v-else>投稿されたSTEPは見つかりませんでした</p>
    </div>
  
    <transition appear v-if="data.dbData.data.length" key="steps">
      <div class="p-step-list__card-wrapper" :class="{'p-step-list__card-wrapper--1-card': data.dbData.num === 1}">
        <StepCard v-for="step in data.dbData.data" :key="step[data.columns.id]" 
          :category="categories[step[data.columns.catId]]" :unit="units[step[data.columns.unitId]]" :card="step" />
      </div>
    </transition>
  </div>
</template>

<script>
import StepCard from '../components/StepCard.vue'

export default {
  inject: ['data', 'adjustImg', 'errContributorImg', 'convertCardAttributes'],
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
      userPic: this.data.contributor[this.data.userColumns.pic], //アイコンが読み込めない場合は別の画像を設定する
      gtWidth: false,//アイコン widthの方が大きいかどうか知らせるフラグ
      picLoad: false //アイコンのサイズが調整されて、読み込めるようになったことを知らせるフラグ
    }
  },
  components: {
    StepCard: StepCard
  }
}
//inject
//adjustImg 画像の横と縦を比較し、どちらが大きいか調べる
//errContributorImg //STEP投稿者の画像が読み込めなかったときに、別の画像を読み込ませる
//convertCardAttributes Array形式からオブジェクト形式に変換する
</script>