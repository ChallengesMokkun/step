<template>
  <div :class="wrapper">
    <div :class="projectName + '__header'">
      <label :for="column" class="c-item c-item--form" :class="projectName + '__item'">
        <slot></slot>
      </label>
      <span class="c-label c-label--form" :class="projectName + '__label'" v-if="required">必須</span>
    </div>
    <p class="c-err-msg" :class="projectName + '__err-msg'" v-if="Object.keys(errors).length && column in errors && !add">{{ errors[column][0] }}</p>
    <textarea :name="column" class="c-form-textarea" :class="[projectName + '__form-textarea', {'c-form-textarea--err': overTextNum || overLines || (Object.keys(errors).length && column in errors && !add), [optionClass]: optionClass}]" 
      :id="column" v-model.trim="value" :required="required"></textarea>
    <template v-if="limit">
      <p class="c-form-counter" :class="[projectName + '__form-counter', {'c-form-counter--err': overTextNum}]">
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
    limit: {//文字数上限
      type: Number,
      default: 0
    },
    limitLines: {//改行数上限
      type: Number,
      required: true
    },
    optionClass: {//スタイルを適用するためのクラス(c)
      type: String,
      default: ''
    },
    project: {//プロジェクト名
      type: String,
      default: ''
    },
    projectClass: {//プロジェクトの中のクラス名
      type: String,
      default: ''
    },
    add: { //元からあるのか(false)、新規で生成されたか(true)区別する trueなら入力保持しない
      type: Boolean,
      default: false
    },
    initial: {//初期値
      type: String,
      default: ''
    }
  },
  data(){
    return {
      projectName: this.project ? 'p-' + this.project : 'p-form-field-part',
      wrapper: this.project && this.projectClass ? 'p-' + this.project + '__' + this.projectClass : 'p-form-field-part',
      value: Object.keys(this.old).length && this.column in this.old && !this.add ? this.old[this.column] : !Object.keys(this.old).length && this.dbData && this.column in this.dbData && !this.add ? this.dbData[this.column] : this.initial
    }
  },
  computed: {
    textNum(){
      return this.value ? this.value.replace(/\r\n|\n/g, '').length : 0 //改行をカウントしない
    },
    overTextNum(){
      return this.textNum > this.limit
    },
    overLines(){//改行の個数が規定の数を超えるか調べる
      return this.value ? (this.value.match(/\r\n|\n/g) || []).length >= this.limitLines : false
    }
  }
}
//入力保持
//1.フォーム送信した値があればその値を入れる(後から生成された場合は保持しない)
//2.フォーム送信してない状態で、DBに保存した値があればその値を入れる(後から生成された場合は保持しない)
//3.フォーム送信した値もDBに保存した値もなければ初期値を入れる
</script>