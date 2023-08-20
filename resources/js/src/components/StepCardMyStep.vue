<template>
  <article class="p-step-card">
    <a :href="card[columns.stepShowUrl] + getParam.show" class="p-step-card__wrapper">
      <span class="p-step-card__header">
        <span class="p-step-card__label c-label c-label--category">{{category}}</span>
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
      <span class="p-step-card__label p-step-card__label-pubflg c-label c-label--active" v-if="card[columns.pubFlg]">公開</span>
      <span class="p-step-card__label p-step-card__label-pubflg c-label c-label--inactive" v-else>非公開</span>
    </a>

    <p class="c-link p-step-card__link">
      <a :href="card[columns.stepEditUrl] + getParam.edit" class="c-link__anchor p-step-card__anchor">
        編集する
      </a>
    </p>
  </article>
</template>

<script>
export default {
  inject: ['formatDate', 'getParam', 'columns'],
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
  }
}
</script>