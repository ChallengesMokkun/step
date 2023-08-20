<template>
  <div class="p-form-field-part">
    <div class="p-form-field-part__header">
      <label :for="column" class="p-form-field-part__item c-item c-item--form">
        <slot></slot>
      </label>
      <span class="p-form-field-part__label c-label c-label--form" v-if="required">必須</span>
    </div>
    <p class="p-form-field-part__err-msg c-err-msg" v-if="Object.keys(errors).length && column in errors">{{ errors[column][0] }}</p>
    <input type="password" :name="column" class="p-form-field-part__form-text c-form-text"
      :class="{'c-form-text--err': Object.keys(errors).length && (errColumn in errors || column in errors)}" :id="column" required>
    <p class="p-form-field-part__plain-text c-plain-text" v-if="ruleFlag">
      !?_;:&#%+$^と半角英数字&nbsp;&nbsp;8文字以上
    </p>
  </div>
</template>

<script>
export default {
  inject: ['errors'],
  props: {
    column: {
      type: String,
      required: true
    },
    errColumn: {//指定した他のカラムがエラーのとき、このカラムにもエラー時のスタイルをつける
      type: String,
      default: ''
    },
    ruleFlag: {//入力可能文字を表示させる
      type: Boolean,
      default: false
    },
    required: {//入力必須のラベルを表示するフラグ
      type: Boolean,
      default: false
    }
  }
}
</script>