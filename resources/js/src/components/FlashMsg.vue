<template>
  <transition appear>
    <p class="c-flash-msg" ref="flashMsg" :style="'top: ' + top + 'px; left:' + left + 'px'" v-if="isShow">
      {{ flash }}
    </p>
  </transition>
</template>
<script>
export default {
  props: {
    flash: {
      type: String,
      default: ''
    }
  },
  mounted(){
    this.adjustPosition()
    window.addEventListener('resize', this.adjustPosition) //メッセージ表示中に画面サイズが変わったら、メッセージの表示位置を調整する
    
    //画面が読み込まれてから数秒後にメッセージを消す
    window.onload = () => {
      setTimeout(() => {
        this.isShow = false
        window.removeEventListener('resize', this.adjustPosition)
      }, 2000)
    }
  },
  data(){
    return {
      top: 200,
      left: 0,
      isShow: true
    }
  },
  methods: {
    //メッセージを画面の左右中央に表示させる
    adjustPosition(){
      if(this.isShow){
        this.left = (window.innerWidth - this.$refs.flashMsg.clientWidth) / 2
      }
    }
  }
}
</script>