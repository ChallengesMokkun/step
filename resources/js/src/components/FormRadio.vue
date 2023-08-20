<template>
  <span class="p-form-field-part__form-checkbox c-form-checkbox">
    <input type="radio" :name="column" class="c-form-checkbox__checkbox p-form-field-part__checkbox" :value="val" :id="label" :checked="checked">
    <label :for="label" class="p-form-field-part__checkbox-label c-form-checkbox__label">
      <slot></slot>
    </label>
  </span>
</template>
<script>
export default {
  inject: {
    old: {},
    dbData: {
      default: null
    }
  },
  props: {
    val: {
      type: Number,
      required: true
    },
    label: {//id属性名
      type: String,
      required: true
    },
    column: {
      type: String,
      required: true
    },
    default: {//初期選択フラグ
      type: Boolean,
      default: false
    }
  },
  data(){
    return {
      checked: Object.keys(this.old).length && this.column in this.old && Number(this.old[this.column]) === this.val ? true 
      : !Object.keys(this.old).length && this.dbData && this.column in this.dbData && this.dbData[this.column] === this.val ? true 
      : !Object.keys(this.old).length && !this.dbData && this.default ? true 
      : false,
    }
  }
}
//入力保持
//1.フォーム送信した値があり、その値がこのコンポーネントの値と一致したら選択状態にする
//2.フォーム送信してない状態で、DBに保存した値があり、その値がこのコンポーネントの値と一致したら選択状態にする
//3.送信した値もDBの値もなく、初期選択フラグがあれば選択状態にする
</script>