<template>
  <input type="hidden" name="_method" :value="method">
</template>

<script>
export default {
  props: {
    method: {
      type: String,
      required: true,
      validator(value){
        return ['put', 'patch', 'delete'].indexOf(value) !== -1
      }
    }
  }
}
</script>