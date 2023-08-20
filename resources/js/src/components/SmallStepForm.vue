<template>
<transition appear>
  <div class="p-small-step-form" v-show="isShow">
    <div class="p-small-step-form__title-wrapper">
      <div class="p-small-step-form__number-wrapper">
        <span class="p-small-step-form__list-number c-list-number">
          <span class="p-small-step-form__number c-list-number__number">{{num}}</span>
        </span>
      </div>
      <FormTextarea :column="columns['step' + num]" :required="num === 1" :limit="constant.stepMax" :limitLines="constant.stepLines" :add="add" 
        project="small-step-form" projectClass="step-wrapper" optionClass="c-form-textarea--step">
          STEP{{num}}のタイトル
      </FormTextarea>
    </div>
    <div class="p-small-step-form__content-wrapper">
      <FormTextarea :column="columns['stepDetail' + num]" :required="num === 1" :limit="constant.stepDetailMax" :limitLines="constant.stepDetailLines" :add="add" 
        project="small-step-form" projectClass="step-detail-wrapper" optionClass="c-form-textarea--step-detail">
          STEP{{num}}の説明
      </FormTextarea>
      <div class="p-small-step-form__cmd-wrapper" v-if="num > 1">
        <span class="p-small-step-form__cmd c-cmd" @click="transmitId">
          <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512" class="p-small-step-form__cmd-icon c-cmd__icon c-cmd__icon--minus">
            <path d="M256 512A256 256 0 1 0 256 0a256 256 0 1 0 0 512zM184 232H328c13.3 0 24 10.7 24 24s-10.7 24-24 24H184c-13.3 0-24-10.7-24-24s10.7-24 24-24z"/>
          </svg>
          <span class="p-small-step-form__cmd-text c-cmd__text c-cmd__text--minus">STEP{{num}}を消す</span>
        </span>
      </div>
    </div>
    <span class="p-small-step-form__line c-list-number__line"></span>
  </div>
</transition>
</template>
<script>
import FormTextarea from './FormTextarea.vue'
export default {
  emits: ['smallStepDelete'],
  props: {
    num: {
      type: Number,
      required: true
    },
    id: {
      type: String,
      required: true
    }
  },
  inject: ['initSteps', 'constant', 'columns'],
  created(){
    //最初から作られていたか、後から生成されたか調べる
    this.add = this.initSteps.indexOf(this.id) === -1 ? true : false
  },
  data(){
    return {
      isShow: true,
      add: false
    }
  },
  methods: {
    transmitId(){
      // 削除を行うためのメソッド
      // 親に自身のuuidを伝える
      this.isShow = !this.isShow
      //消えるアニメーションのため、処理を遅らせる
      setTimeout(() => {
        this.$emit('smallStepDelete', this.id)
      }, 750)
    }
  },
  components: {
    FormTextarea: FormTextarea
  }
}
</script>