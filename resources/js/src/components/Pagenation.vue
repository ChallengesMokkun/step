<template>
  <div class="p-pagenation">
    <nav class="p-pagenation__wrapper">
      <ul class="p-pagenation__page-list">
        <template v-if="dbData.current_page > 1">
          <li class="p-pagenation__page c-page">
            <a :href="getParam.page + 1" class="p-pagenation__page-link c-page__link">|&lt;</a>
          </li>
          <li class="p-pagenation__page c-page" v-if="dbData.current_page > 2">
            <a :href="getParam.page + (dbData.current_page - 1)" class="p-pagenation__page-link c-page__link">&lt;</a>
          </li>
        </template >

        <template v-for="i in pageRange">
          <li class="p-pagenation__page c-page c-page--active" v-if="this.minPage + i - 1 === dbData.current_page">
            <span class="p-pagenation__page-link c-page__link c-page__link--active">{{this.minPage + i - 1}}</span>
          </li>
          <li class="p-pagenation__page c-page" v-else>
            <a :href="getParam.page + (this.minPage + i - 1)" class="p-pagenation__page-link c-page__link">{{this.minPage + i - 1}}</a>
          </li>
        </template>


        <template v-if="dbData.current_page < dbData.last_page">
          <li class="p-pagenation__page c-page" v-if="dbData.current_page < dbData.last_page - 1">
            <a :href="getParam.page + (dbData.current_page + 1)" class="p-pagenation__page-link c-page__link">&gt;</a>
          </li>
          <li class="p-pagenation__page c-page">
            <a :href="getParam.page + dbData.last_page" class="p-pagenation__page-link c-page__link">&gt;|</a>
          </li>
        </template>
      </ul>
    </nav>
  </div>
</template>

<script>
export default {
  inject: ['dbData', 'getParam'],
  created(){
    //ページネーションの設定
    if(this.dbData.last_page < this.pageSpan){
      //総ページ数が、ページ表示件数より小さいとき
      this.minPage = 1
      this.maxPage = this.dbData.last_page
      this.pageRange = this.dbData.last_page
    }else if(this.dbData.current_page <= (this.pageSpan - 1) / 2){
      //現在のページ数が小さく、ページ表示件数の半分以下のとき
      this.minPage = 1
      this.maxPage = this.pageSpan
    }else if(this.dbData.last_page - this.dbData.current_page < (this.pageSpan - 1) / 2){
      //現在のページ数が大きく、最後のページとの差がページ表示件数の半分より小さいとき
      this.minPage = this.dbData.last_page - (this.pageSpan - 1)
      this.maxPage = this.dbData.last_page
    }else{
      //上記以外のとき　現在のページがページ表示の真ん中
      this.minPage = this.dbData.current_page - (this.pageSpan - 1) / 2
      this.maxPage = this.dbData.current_page + (this.pageSpan - 1) / 2
    }
    
  },
  data(){
    return {
      pageSpan: 5,//ページ表示件数
      pageRange: 5,//実際に表示するページ数
      minPage: 0,
      maxPage: 0
    }
  }
}
</script>