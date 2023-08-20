<template>
  <teleport to="#js-flash-msg" v-if="flash">
    <FlashMsg :flash="flash" />
  </teleport>
  <RouterView />
</template>

<script>
import FlashMsg from './components/FlashMsg.vue'
export default {
  props: ['data'],
  provide(){
    return {
      data: this.$props.data,
      csrf: this.$props.data.csrf,
      formatDate: this.formatDate,
      adjustImg: this.adjustImg,
      errContributorImg: this.errContributorImg,
      convertCardAttributes: this.convertCardAttributes
    }
  },
  data(){
    return {
      flash: this.data.flash ? this.data.flash : '',
    }
  },
  methods: {
    formatDate(dateTime){ //datetimeを yyyy/m/d h:mm 形式に変換する
      const date = new Date(dateTime)
      return date.getFullYear() +  '/' +
            (date.getMonth() + 1) + '/' +
            date.getDate() + ' ' +
            date.getHours() + ':' +
            ("0" + date.getMinutes()).slice(-2)
    },
    adjustImg(component, picUrl, ref){ //画像の横と縦を比較し、どちらが大きいか調べる
      if(picUrl){
        if(component.$refs[ref]['width'] >= component.$refs[ref]['height']){
          component.gtWidth = true
        }
        component.picLoad = true
      }
    },
    errContributorImg(component){ //STEP投稿者の画像が読み込めなかったときに、別の画像を読み込ませる
      component.userPic = this.data.url.nullUser
    },
    convertCardAttributes(component){ //カテゴリー・時間の単位をオブジェクトに変換する
      let categories = {}
      let units = {}

      this.data.categories.forEach(elm => categories[elm.id] = elm.name)
      this.data.units.forEach(elm => units[elm.id] = elm.name)

      component.categories = categories
      component.units = units
    }
  },
  components: {
    FlashMsg: FlashMsg
  }
}
</script>