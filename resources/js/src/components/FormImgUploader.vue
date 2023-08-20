<template>
  <div class="p-form-field-part">
    <div class="p-form-field-part__header">
      <label :for="column" class="p-form-field-part__item c-item c-item--form">
        <slot></slot>
      </label>
      <span class="p-form-field-part__label c-label c-label--form" v-if="required">必須</span>
    </div>

    <template v-if="!url && ((Object.keys(errors).length && column in errors) || prevErr)">
      <p class="p-form-field-part__err-msg c-err-msg" v-if="Object.keys(errors).length && column in errors">{{ errors[column][0] }}</p>
      <p class="p-form-field-part__err-msg c-err-msg" v-if="prevErr">{{ prevErr }}</p>
    </template>
    <div class="p-img-uploader">
      <div class="p-img-uploader__upload-area">
        <label :for="column"  class="p-img-uploader__form-img c-form-img" :class="{'c-form-img--err': !url && ((Object.keys(errors).length && column in errors) || prevErr)}">
          <span class="p-img-uploader__hint c-form-img__hint" v-if="!url && !value">
            {{limitMB}}MBまでのアイコン画像<br><br>
            タップして選択<br>
            または<br>
            ドラッグ＆ドロップ
          </span>
          <input type="file" :name="column" :id="column" ref="upfile"  @change="prevImg" :required="required" 
            class="p-img-uploader__file-selector c-form-img__file-selector" :accept="mime.join()">
          <input type="hidden" :name="columnBef" :value="before" v-if="before">
        </label>
        <img :src="url" alt="アイコン" class="p-img-uploader__picture c-picture" :class="{'c-picture--gt-width': gtWidth, 'c-picture--gt-height': !gtWidth}" v-if="url">
        <img :src="value" alt="アイコン" class="p-img-uploader__picture c-picture" :class="{'c-picture--gt-width': gtWidth, 'c-picture--gt-height': !gtWidth}" v-if="!url && value" v-show="picLoad" 
          ref="userPic" @load="adjustImg(this, value, 'userPic')" @error="notFoundImg">
      </div>
      <div class="p-img-uploader__cmd-wrapper" v-if="url || value">
        <span class="p-img-uploader__cmd c-cmd" @click="deleteImg">
          <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512" class="p-img-uploader__cmd-icon c-cmd__icon c-cmd__icon--minus">
            <path d="M256 512A256 256 0 1 0 256 0a256 256 0 1 0 0 512zM184 232H328c13.3 0 24 10.7 24 24s-10.7 24-24 24H184c-13.3 0-24-10.7-24-24s10.7-24 24-24z"/>
          </svg>
          <span class="p-img-uploader__cmd-text c-cmd__text c-cmd__text--minus">消す</span>
        </span>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  inject: {
    errors: {},
    adjustImg: {},//DBに保存されたアイコンのサイズを調整するメソッド
    dbData: {
      default: null
    }
  },
  props: {
    limit: { //画像の最大容量(KB)
      type: Number,
      required: true
    },
    column: {
      type: String,
      required: true
    },
    columnBef: {//DBに保存したアイコンを入力保持するためのカラム
      type: String,
      default: ''
    },
    required: {//ファイル選択必須フラグ
      type: Boolean,
      default: false
    }
  },
  data(){
    return {
      value: this.dbData && this.column in this.dbData ? this.dbData[this.column] : '',//DBに保存したアイコンの画像パス
      before: this.dbData && this.columnBef in this.dbData ? this.dbData[this.columnBef] : '',//DBに保存したアイコンのパス
      limitMB: Math.floor((this.limit / 1024) * 10) / 10,//指定した容量(MB)を整数または少数第一位まで表示する
      url: '',
      prevErr: '',//画像ライブビューの過程で生じたエラーメッセージを格納する
      mime: [
        'image/jpeg',
        'image/png',
        'image/png',
        'image/svg+xml',
        'image/gif',
        'image/webp'
      ],
      gtWidth: false,//読み込んだ画像 widthの方が大きいかどうか知らせるフラグ
      picLoad: false//DBに保存されたアイコンのサイズが調整されて、読み込めるようになったことを知らせるフラグ
    }
  },
  methods: {
    checkImg(url){
      //objectUrlが、imgタグで表示できるものかどうか調べるメソッド
      return new Promise((resolve, reject) => {
        const img = new Image();
        img.src = url;

        img.onload = () => {
          if(img.width >= img.height){//読み込んだ画像が、横と縦どちらが大きいか調べる
            resolve([url, true])
          }else{
            resolve([url, false])
          }
        }

        img.onerror = () => reject(url)
      });
    },
    prevImg(){
      const fileObj = this.$refs.upfile.files[0]
      if(fileObj){
        //ファイルの拡張子からmimeタイプを確認
        if(this.mime.indexOf(fileObj.type) !== -1){
          //ファイルサイズ(B)が上限以内か確認
          if(fileObj.size <= this.limit * 1024){
            const file = URL.createObjectURL(fileObj)

            //imgタグで表示可能か調べる
            this.checkImg(file)
            .then(result => {
              this.prevErr = ''
              this.url = file
              this.gtWidth = result[1]
              if(this.before && this.value){
                this.before = ''
                this.value = ''
              }
            })
            .catch(result => {
              this.deleteImg()
              this.prevErr = '非対応のファイルです。'
            })
          }else{
            this.deleteImg()
            this.prevErr = this.limitMB + 'MB以下の画像を選択してください。'
          }
        }else{
          this.deleteImg()
          this.prevErr = '非対応のファイルです。'
        }
      }
    },
    deleteImg(){//画像を未選択の状態に戻す
      if(this.url){
        URL.revokeObjectURL(this.url)
        this.url = ''
      }
      if(this.before && this.value){
        this.before = ''
        this.value = ''
      }
      this.$refs.upfile.value = ''
    },
    notFoundImg(){//DBのアイコンが読み込めなかった場合に、エラーアイコンを表示させないようにする
      this.value = ''
    }
  }
}
</script>