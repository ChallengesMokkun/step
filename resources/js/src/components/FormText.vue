<template>
  <div class="p-form-field-part">
    <div class="p-form-field-part__header">
      <label :for="column" class="p-form-field-part__item c-item c-item--form">
        <slot></slot>
      </label>
      <span class="p-form-field-part__label c-label c-label--form" v-if="required">必須</span>
    </div>
    <p class="p-form-field-part__err-msg c-err-msg" v-if="Object.keys(errors).length && column in errors">{{ errors[column][0] }}</p>
    <input type="text" :name="column" class="p-form-field-part__form-text c-form-text" :class="{'c-form-text--err': overTextNum || (Object.keys(errors).length && column in errors)}" 
     :id="column" v-model.trim="value" :required="required">
    <template v-if="countFlg && limit">
      <p class="p-form-field-part__form-counter c-form-counter" :class="{'c-form-counter--err': overTextNum}">
        {{ textNum }} / {{ limit }}
      </p>
    </template>
  </div>
</template>

<script>
export default {
  inject: {
    errors: {},
    old: {},
    dbData: {
      default: null
    }
  },
  props: {
    column: {
      type: String,
      required: true
    },
    required: {//入力必須にする・入力必須のラベルを表示するフラグ
      type: Boolean,
      default: false
    },
    countFlg: {
      type: Boolean,
      default: false
    },
    limit: {
      type: Number,
      default: 0
    }
  },
  data(){
    return {
      value: Object.keys(this.old).length && this.column in this.old ? this.old[this.column] : !Object.keys(this.old).length && this.dbData && this.column in this.dbData ? this.dbData[this.column] : ''
    }
  },
  computed: {
    textNum(){
      if(this.countFlg && this.limit){
        return this.value ? this.value.length : 0
      }
    },
    overTextNum(){
      if(this.countFlg && this.limit) return this.textNum > this.limit
    }
  }
}
//入力保持
//1.フォーム送信した値があればその値を入れる
//2.フォーム送信してない状態で、DBに保存した値があればその値を入れる
//3.フォーム送信した値もDBに保存した値もなければ空文字を入れる
</script>