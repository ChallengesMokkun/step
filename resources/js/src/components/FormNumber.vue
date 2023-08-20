<template>
  <input type="number" :name="column" :id="column" class="p-form-field-part__form-number c-form-number" :class="{'c-form-number--err': Object.keys(errors).length && column in errors}" :min="min" :max="max" :placeholder="hint" v-model.trim.number="value" :required="required">
</template>
<script>
export default {
  inject: {
    errors: {},
    old: {},
    constant: {},
    dbData: {
      default: null
    }
  },
  props: {
    column: {
      type: String,
      required: true
    },
    required: {//入力必須フラグ
      type: Boolean,
      default: false
    },
    min: {
      type: Number,
      required: true
    },
    max: {
      type: Number,
      required: true
    },
    hint: {
      type: String,
      default: ''
    }
  },
  data(){
    return {
      value: Object.keys(this.old).length && this.column in this.old ? this.old[this.column] : !Object.keys(this.old).length && this.dbData && this.column in this.dbData ? this.dbData[this.column] : ''
    }
  },
}
//入力保持
//1.フォーム送信した値があればその値を入れる
//2.フォーム送信してない状態で、DBに保存した値があればその値を入れる
//3.フォーム送信した値もDBに保存した値もなければ空文字を入れる
</script>