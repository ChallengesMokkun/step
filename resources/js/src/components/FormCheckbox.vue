<template>
  <span class="p-form-field-part__form-checkbox c-form-checkbox">
    <input type="checkbox" :name="column" :id="column" class="p-form-field-part__checkbox c-form-checkbox__checkbox" v-model="check" @change="transmitCheck">
    <label :for="column" class="p-form-field-part__checkbox-label c-form-checkbox__label">
      <slot></slot>
    </label>
  </span>
</template>

<script>
export default {
  emits: ['toggleCheck'],
  props: {
    column: {
      type: String,
      required: true
    },
    required: {
      type: Boolean,
      default: false
    }
  },
  data(){
    return {
      check: false
    }
  },
  methods: {
    transmitCheck(){//親に自身の状態を伝える
      if(this.required){
        this.$emit('toggleCheck', this.check)
      }
    }
  }
}
</script>