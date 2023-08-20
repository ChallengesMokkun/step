<template>
  <button class="c-btn" :class="['p-' + project + '__btn ', {[optionClass]: optionClass}]" type="submit" @click="dialog(msg, $event)" :disabled="notAllowed">
    <slot></slot>
  </button>
</template>

<script>
export default {
  inject: ['project'],
  props: {
    optionClass: {//スタイルを適用するためのクラス
      type: String,
      default: ''
    },
    msg: {
      type: String,
      default: ''
    },
    notAllowed: {//ボタンを押せなくするためのフラグ
      type: Boolean,
      default: false
    }
  },
  methods: {
    dialog(msg, e){
      if(msg){
        if(!confirm(msg)){
          e.preventDefault()
          return
        }
      }
    },
  }
}
</script>