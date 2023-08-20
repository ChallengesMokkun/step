<template>
  <article class="p-step-card">
    <a :href="card[columns.stepsCategoryUrl]" class="p-step-card__label p-step-card__label-category-link c-label c-label--category">{{category}}</a>
    <a :href="card[columns.stepShowUrl] + getParam.show" class="p-step-card__wrapper">
      <span class="p-step-card__header">
        <span class="p-step-card__datetime c-item c-item--icon-text">
          <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 -960 960 960" class="p-step-card__datetime-icon c-item__icon">
            <path d="M180-80q-24 0-42-18t-18-42v-620q0-24 18-42t42-18h65v-60h65v60h340v-60h65v60h65q24 0 42 18t18 42v301h-60v-111H180v430h319v60H180Zm709-219-71-71 29-29q8.311-8 21.156-8Q881-407 889-399l29 29q8 8.311 8 21.156Q926-336 918-328l-29 29ZM559-40v-71l216-216 71 71L630-40h-71ZM180-630h600v-130H180v130Zm0 0v-130 130Z"/>
          </svg>
          <time class="p-step-card__datetime-text c-item__text">{{formatDate(card[columns.editedAt])}}</time>
        </span>
      </span>
      <h3 class="p-step-card__title c-title c-title--step-card">
        {{card[columns.title]}}
      </h3>
      <p class="p-step-card__plain-text c-plain-text">
        {{card[columns.phrase]}}
      </p>
      <span class="p-step-card__footer">
        <span class="p-step-card__footer-container">
          <span class="p-step-card__item c-item">達成目安 {{card[columns.estimate] + unit}}</span>
        </span>
        <span class="p-step-card__footer-container">
          <span class="p-step-card__item c-item">{{card[columns.total]}} STEP</span>
        </span>
      </span>
    </a>
    <a :href="card[columns.memberProfileUrl]" class="p-step-card__contributor c-contributor">
      <span class="p-step-card__contributor-pic-wrapper c-contributor__pic-wrapper">
        <img :src="userPic" alt="" class="p-step-card__contributor-pic c-contributor__pic" @error="errContributorImg(this)">
      </span>
      <span class="p-step-card__contributor-name c-contributor__name">
        {{card[userColumns.name]}}
      </span>
    </a>
  </article>
</template>

<script>
export default {
  inject: ['formatDate', 'errContributorImg', 'getParam', 'columns', 'userColumns'],
  props: {
    card: {
      type: Object,
      required: true
    },
    category: {
      type: String,
      required: true
    },
    unit: {
      type: String,
      required: true
    }
  },
  data(){
    return {
      userPic: this.card[this.columns.memberProfilePic] //アイコンが読み込めない場合は別の画像を設定する
    }
  }
}
//inject
//formatDate STEPの更新日付をフォーマットする
//errContributorImg アイコンが読み込めない場合に別の画像を読み込ませる
</script>