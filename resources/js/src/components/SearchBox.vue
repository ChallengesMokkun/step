<template>
  <div class="p-search-box">
    <span class="p-search-box__cmd c-cmd" @click="toggleShow">検索する</span>
    <transition>
      <form action="" method="get" class="p-search-box__form-wrapper" v-show="isShow">
        <input type="text" :name="columns.keyword" placeholder="キーワード" class="p-search-box__form-text c-form-text" v-model="keyword">
        <div class="p-search-box__form-select c-form-select">
          <select :name="columns.catId" class="p-search-box__select-box c-form-select__select-box" v-model="categoryId">
            <option value="">すべて</option>
            <template v-for="category in categories">
              <option :value="category.id">{{category.name}}</option>
            </template>
          </select>
        </div>
        <button  class="p-search-box__btn c-btn c-btn--active c-btn--s">検索</button>
      </form>
    </transition>
  </div>
</template>

<script>
export default {
  inject: ['columns'],
  props: {
    categories: {
      type: Array,
      required: true
    },
    old: {
      type: Object,
      required: true
    }
  },
  data(){
    return {
      categoryId: Object.keys(this.old).length && this.old[this.columns.catId] ? this.old[this.columns.catId] : '',
      keyword: Object.keys(this.old).length && this.columns.keyword in this.old ? this.old[this.columns.keyword] : '',
      isShow: false
    }
  },
  methods: {
    toggleShow(){
      this.isShow = !this.isShow
    }
  }
}
</script>