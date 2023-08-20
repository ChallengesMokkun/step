<template>
  <span class="c-status-bar" :class="'p-' + project + '__status-bar'">
    <span class="c-status-bar__text-wrapper" :class="'p-' + project + '__status-text-wrapper'">
      <span class="c-status-bar__text" :class="'p-' + project + '__status-text'">達成度</span>
      <span class="c-status-bar__text c-status-bar__text--notice" :class="'p-' + project + '__status-text'" v-if="numChangeFlg">※STEP数が更新されました</span>
      <span class="c-status-bar__text" :class="'p-' + project + '__status-text'">{{currentStep}}  /  {{total}}</span>
    </span>
    <span class="c-status-bar__container" :class="'p-' + project + '__status-container'">
      <span class="c-status-bar__status" :class="'p-' + project + '__status'" :style="'width: ' + achievement + '%'"></span>
    </span>
  </span>
</template>

<script>
export default {
  inject: ['project'],
  props: {
    current: {//DBに保存されている、到達STEP数
      type: Number,
      required: true
    },
    total: {
      type: Number,
      required: true
    },
    numChangeFlg: { //STEP編集によって、総STEP数が変化したか知らせるフラグ
      type: Boolean,
      required: true
    },
    clearFlg: {
      type: Boolean,
      required: true
    }
  },
  created(){
    if(this.total > 0){
      if(this.current >= this.total){
        if(this.clearFlg){
          //完全クリアしているとき
          this.currentStep = this.total
          this.achievement = 100
        }else{
          //チャレンジ中のとき　STEP編集によってSTEP数が減り、到達STEPが総STEP数以上になってしまった場合
          this.currentStep = this.total - 1
          this.achievement = Math.round((this.total - 1) / this.total * 100)
        }
      }else{
        if(this.clearFlg){
          //完全クリアした後で、STEP編集によって総STEP数が増えたとき
          this.achievement = 100
        }else{
          //チャレンジ中の通常時
          this.achievement = Math.round(this.current / this.total * 100)
        }
      }
    }else{
      this.achievement = 0
    }
  },
  data(){
    return {
      achievement: 0, //達成度(%)
      currentStep: this.current //実際に表示する、到達STEP数
    }
  }
}
</script>