<template>
  <div class="c-form-select" :class="'p-' + project + '__form-select'">
    <select :name="column" class="c-form-select__select-box" 
    :class="['p-' + project + '__select-box',{'c-form-select__select-box--err': Object.keys(errors).length && this.column in errors, [optionClass]: optionClass}]" :id="column" v-model="value" :required="required">
      <option value="" hidden>
        <slot></slot>
      </option>
      <template v-for="choice in choices">
        <option :value="choice.id">{{choice.name}}</option>
      </template>
    </select>
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
    project: {//プロジェクト名
      type: String,
      required: true
    },
    choices: {//選択肢 Array形式で受け取る
      type: Array,
      required: true
    },
    optionClass: {//スタイルを適用するためのクラス
      type: String,
      default: ''
    },
    required: {//選択必須フラグ
      type: Boolean,
      default: false
    }
  },
  data(){
    return {
      value: Object.keys(this.old).length && this.column in this.old ? this.old[this.column] : !Object.keys(this.old).length && this.dbData && this.column in this.dbData ? this.dbData[this.column] : '',
    }
  }
}
//入力保持
//1.フォーム送信した値があればその値を入れる
//2.フォーム送信してない状態で、DBに保存した値があればその値を入れる
//3.フォーム送信した値もDBに保存した値もなければ空文字を入れる
</script>