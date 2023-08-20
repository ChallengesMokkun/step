<template>
  <div class="p-step-detail">
    <p class="c-link c-link--back p-step-detail__back-link">
      <a :href="data.back" class="c-link__anchor p-step-detail__anchor">
        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 -960 480 960" class="p-step-detail__link-icon c-link--back__icon">
          <path d="M400-107.692 27.692-480 400-852.308l36 36.231L99.154-480 436-143.923l-36 36.231Z"/>
        </svg>
        戻る
      </a>
    </p>
    <ErrMsg key="err" v-if="data.dbErr" />
    <div class="p-step-detail__header">
      <div class="p-step-detail__header-row">
        <a :href="data.dbData[data.columns.stepsCategoryUrl]" class="p-step-detail__label-category-link c-label c-label--category">{{category}}</a>
        <span class="p-step-detail__estimate c-item c-item--icon-text">
          <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 -1000 960 960"  class="p-step-detail__header-item-icon c-item__icon">
            <path d="M360-860v-60h240v60H360Zm90 447h60v-230h-60v230Zm30 332q-74 0-139.5-28.5T226-187q-49-49-77.5-114.5T120-441q0-74 28.5-139.5T226-695q49-49 114.5-77.5T480-801q67 0 126 22.5T711-716l51-51 42 42-51 51q36 40 61.5 97T840-441q0 74-28.5 139.5T734-187q-49 49-114.5 77.5T480-81Zm0-60q125 0 212.5-87.5T780-441q0-125-87.5-212.5T480-741q-125 0-212.5 87.5T180-441q0 125 87.5 212.5T480-141Zm0-299Z"/>
          </svg>
          <span class="p-step-detail__header-item-text c-item__text">
            {{estimateTime}}
          </span>
        </span>
        <span class="p-step-detail__datetime c-item c-item--icon-text">
          <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 -1000 960 960" class="p-step-detail__header-item-icon c-item__icon">
            <path d="M180-80q-24 0-42-18t-18-42v-620q0-24 18-42t42-18h65v-60h65v60h340v-60h65v60h65q24 0 42 18t18 42v301h-60v-111H180v430h319v60H180Zm709-219-71-71 29-29q8.311-8 21.156-8Q881-407 889-399l29 29q8 8.311 8 21.156Q926-336 918-328l-29 29ZM559-40v-71l216-216 71 71L630-40h-71ZM180-630h600v-130H180v130Zm0 0v-130 130Z"/>
          </svg>
          <time class="p-step-detail__header-item-text c-item__text">{{formatDate(data.dbData[data.columns.editedAt])}}</time>
        </span>
      </div>
      <div class="p-step-detail__header-row">
        <a :href="data.dbData[data.columns.memberProfileUrl]" class="p-step-detail__contributor c-contributor">
          <span class="p-step-detail__contributor-pic-wrapper c-contributor__pic-wrapper">
            <img :src="userPic" alt="" class="p-step-detail__contributor-pic c-contributor__pic" @error="errContributorImg(this)">
          </span>
          <span class="p-step-detail__contributor-name c-contributor__name">
            {{data.dbData[data.userColumns.name]}}
          </span>
        </a>
      </div>
    </div>
    
    <div class="p-step-detail__title-phrase-wrapper">
      <h2 class="p-step-detail__title c-title c-title--step-detail">
        {{data.dbData[data.columns.title]}}
      </h2>
      <h3 class="p-step-detail__phrase c-title c-title--step-detail-phrase">
        {{data.dbData[data.columns.phrase]}}
      </h3>
    </div>
  
    <div class="p-step-detail__status-steps-wrapper">
      <StatusBar key="statusBar" v-if="Object.keys(data.challenge).length" :current="data.challenge[data.chalColumns.current]" :total="data.dbData[data.columns.total]" :clearFlg="data.challenge[data.chalColumns.clearFlg]" :numChangeFlg="data.challenge[data.chalColumns.numChangeFlg]" />
      <div class="p-step-detail__small-step-wrapper">
        <slot name="step"></slot>
      </div>
    </div>

  
    <div class="p-step-detail__msg-wrapper" v-if="data.dbData[data.columns.supp]">
      <p class="p-step-detail__item c-item">補足・メッセージ</p>
      <div class="p-step-detail__msg-area">
        <p class="p-step-detail__plain-text c-plain-text">{{data.dbData[data.columns.supp]}}</p>
      </div>
    </div>
  
    <div class="p-step-detail__action-wrapper">
      <Tweet :dest="data.dbData[data.columns.stepShowUrl]" :title="data.dbData[data.columns.title]" :appName="data.appName" :catchphrase="data.catchphrase" key="tweet" v-if="data.dbData[data.columns.pubFlg]" />
      <a :href="data.url.login" class="c-btn c-btn--m c-btn--active p-step-detail__btn" v-if="!data.loginFlg">ログイン</a>
      <form :action="data.dbData.chalUrl" method="post" class="p-step-detail__action-btn-wrapper" v-else-if="!Object.keys(data.challenge).length">
        <CSRFToken />
        <Btn optionClass="c-btn--m c-btn--active" :msg="'チャレンジします。\r\nよろしいでしょうか。'">チャレンジ</Btn>
      </form>
      <form :action="data.dbData.chalUrl" method="post" class="p-step-detail__action-btn-wrapper" v-else-if="data.challenge[data.chalColumns.clearFlg]">
        <CSRFToken />
        <Btn optionClass="c-btn--m c-btn--active"  :msg="'再びチャレンジします。\r\nよろしいでしょうか。'">再挑戦</Btn>
      </form>
      <slot name="btn"></slot>
    </div>
  
    <p class="c-link c-link--back p-step-detail__back-link">
      <a :href="data.back" class="c-link__anchor p-step-detail__anchor">
        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 -960 480 960" class="p-step-detail__link-icon c-link--back__icon">
          <path d="M400-107.692 27.692-480 400-852.308l36 36.231L99.154-480 436-143.923l-36 36.231Z"/>
        </svg>
        戻る
      </a>
    </p>
  </div>
  </template>
  
  <script>
  import _ from 'lodash'
  import Btn from './Btn.vue'
  import Tweet from './Tweet.vue'
  import StatusBar from './StatusBar.vue'
  import CSRFToken from './CSRFToken.vue'
  import ErrMsg from './ErrMsg.vue'
  export default {
    inject: ['formatDate', 'errContributorImg', 'data'],
    provide(){
      return {
        project: 'step-detail'
      }
    },
    components: {
      Btn: Btn,
      Tweet: Tweet,
      StatusBar: StatusBar,
      CSRFToken: CSRFToken,
      ErrMsg: ErrMsg
    },
    data(){
      return {
        userPic: this.data.dbData[this.data.columns.memberProfilePic],
        category: _.find(this.data.categories, {'id': this.data.dbData[this.data.columns.catId]}).name,
        estimateTime: this.data.dbData[this.data.columns.estimate] + _.find(this.data.units, {'id': this.data.dbData[this.data.columns.unitId]}).name,
      }
    }
  }
  //inject
  //formatData datetimeをフォーマットする
  //errContributorImg STEP投稿者の画像が読み込めなかったときに別の画像を読み込ませる
  </script>