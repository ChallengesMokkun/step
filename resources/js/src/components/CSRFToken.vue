<template>
  <input type="hidden" name="_token" :value="csrf">
</template>

<script>
export default {
  inject: ['csrf']
}
</script>