<template>
  <div class="p-small-step">
    <div class="p-small-step__title-wrapper">
      <div class="p-small-step__number-wrapper">
        <span class="p-small-step__list-number c-list-number" v-if="detail">
          <span class="p-small-step__number c-list-number__number">{{num}}</span>
        </span>
        <a :href="link" class="p-small-step__list-number c-list-number" v-else-if="link">
          <span class="p-small-step__number c-list-number__number">{{num}}</span>
        </a>
      </div>
      <h3 class="p-small-step__title c-title c-title--small-step" v-if="detail">
        {{stepTitle}}
      </h3>
      <h3 class="p-small-step__title c-title c-title--small-step" v-else-if="link">
        <a :href="link" class="p-small-step__title-link c-title--small-step__link">
          {{stepTitle}}
        </a>
      </h3>
    </div>
    <div class="p-small-step__content-wrapper" v-if="detail">
      <p class="p-small-step__plain-text c-plain-text">
        {{detail}}
      </p>
    </div>
    <span class="p-small-step__line c-list-number__line" v-if="link"></span>
  </div>
</template>
<script>
export default {
  props: {
    num: {
      type: Number,
      required: true
    },
    stepTitle: {
      type: String,
      required: true
    },
    link: {//子STEPのリンクURL
      type: String,
      default: ''
    },
    detail: {//子STEPの説明文
      type: String,
      default: ''
    }
  }
}
//STEP詳細画面では、各子STEPのリンクを受け取る
//子STEP詳細画面では、子STEPの説明文を受け取る
</script>