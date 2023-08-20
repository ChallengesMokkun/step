<template>
  <div class="p-step-form">
    <h2 class="c-title c-title--main p-step-form__title">
      <slot name="title"></slot>
    </h2>
    
    <form :action="action" method="post" class="p-step-form__form-wrapper">
      <FormMethod v-if="method === 'put'" key="method" :method="method"/>
      <CSRFToken />
      <ErrMsg key="err" v-if="Object.keys(data.errors).length || data.dbErr" />
      <div class="p-form-field-part">
        <div class="p-form-field-part__container">
          <div class="p-form-field-part__wrapper">
            <div class="p-form-field-part__header">
              <label for="category_id" class="p-form-field-part__item c-item c-item--form">
                カテゴリー
              </label>
              <span class="p-form-field-part__label c-label c-label--form">必須</span>
            </div>
            <FormSelect :column="data.columns.catId" :choices="data.categories" :required="true" optionClass="c-form-select--category" project="form-field-part">カテゴリー</FormSelect>
            <p class="p-form-field-part__err-msg c-err-msg" v-if="Object.keys(data.errors).length && data.columns.catId in data.errors">{{ data.errors[data.columns.catId][0] }}</p>
          </div>
          <div class="p-form-field-part__wrapper">
            <div class="p-form-field-part__header">
              <label for="estimate" class="p-form-field-part__item c-item c-item--form">達成目安</label>
              <span class="p-form-field-part__label c-label c-label--form">必須</span>
            </div>
            <FormNumber :column="data.columns.estimate" hint="数値" :min="data.constant.estimateMin" :max="data.constant.estimateMax" :required="true" />
            <FormSelect :column="data.columns.unitId" :choices="data.units" :required="true" project="form-field-part">単位</FormSelect>
            <p class="p-form-field-part__err-msg c-err-msg" v-if="Object.keys(data.errors).length && data.columns.estimate in data.errors">{{ data.errors[data.columns.estimate][0] }}</p>
            <p class="p-form-field-part__err-msg c-err-msg" v-if="Object.keys(data.errors).length && data.columns.unitId in data.errors">{{ data.errors[data.columns.unitId][0] }}</p>
          </div>
        </div>
      </div>
      <FormTextarea :column="data.columns.title" :required="true" :limit="data.constant.titleMax" :limitLines="data.constant.titleLines" optionClass="c-form-textarea--step-title">タイトル</FormTextarea>
      <FormTextarea :column="data.columns.phrase" :required="true" :limit="data.constant.phraseMax" :limitLines="data.constant.phraseLines" optionClass="c-form-textarea--phrase">キャッチコピー</FormTextarea>

      <div class="p-step-form__small-step-form-wrapper">
        <SmallStepForm v-for="(id, index) in smallSteps" :key="id" :id="id" :num="index + 1" @smallStepDelete="deleteStep" />

        <div class="p-step-form__cmd-wrapper" v-if="currentStepNum < data.constant.maxSmallStep">
          <span class="p-step-form__cmd c-cmd" @click="addStep">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512" class="p-step-form__cmd-icon c-cmd__icon c-cmd__icon--plus">
              <path d="M256 512A256 256 0 1 0 256 0a256 256 0 1 0 0 512zM232 344V280H168c-13.3 0-24-10.7-24-24s10.7-24 24-24h64V168c0-13.3 10.7-24 24-24s24 10.7 24 24v64h64c13.3 0 24 10.7 24 24s-10.7 24-24 24H280v64c0 13.3-10.7 24-24 24s-24-10.7-24-24z"/>
            </svg>
            <span class="p-step-form__cmd-text c-cmd__text c-cmd__text--plus">STEPを追加する</span>
          </span>
          <p class="p-step-form__plain-text c-plain-text">あと{{data.constant.maxSmallStep - currentStepNum}}個追加できます</p>
        </div>
      </div>

      <FormTextarea :column="data.columns.supp" :limit="data.constant.suppMax" :limitLines="data.constant.suppLines" optionClass="c-form-textarea--supplement">補足・メッセージ</FormTextarea>

      <div class="p-form-field-part">
        <div class="p-form-field-part__header">
          <span class="c-item c-item--form p-form-field-part__item">公開設定</span>
          <span class="c-label c-label--form p-form-field-part__label">必須</span>
        </div>
        <p class="p-form-field-part__plain-text c-plain-text">
          公開を選ぶとあなたのSTEPを投稿できます。<br>
          非公開を選ぶと途中保存することができます。
        </p>
        <p class="c-err-msg p-form-field-part__err-msg" v-if="Object.keys(data.errors).length && data.columns.pubFlg in data.errors">{{ data.errors[data.columns.pubFlg][0] }}</p>
        <FormRadio :column="data.columns.pubFlg" label="public" :val="1">公開</FormRadio>
        <FormRadio :column="data.columns.pubFlg" label="private" :val="0" :default="true">非公開</FormRadio>
      </div>

      <div class="p-step-form__action-wrapper">
        <Btn optionClass="c-btn--m c-btn--active" :msg="msg">
          <slot name="btnName"></slot>
        </Btn>
      </div>

      <p class="c-link c-link--back p-step-form__link">
        <a :href="back" class="c-link__anchor p-step-form__anchor">
          <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 -960 480 960" class="p-step-form__link-icon c-link--back__icon">
            <path d="M400-107.692 27.692-480 400-852.308l36 36.231L99.154-480 436-143.923l-36 36.231Z"/>
          </svg>
          戻る
        </a>
      </p>
    </form>
    <slot name="deleteBtn"></slot>
  </div>
</template>

<script>
  import { UUID } from 'uuidjs'
  import FormTextarea from './FormTextarea.vue'
  import ErrMsg from './ErrMsg.vue'
  import CSRFToken from './CSRFToken.vue'
  import Btn from './Btn.vue'
  import SmallStepForm from './SmallStepForm.vue'
  import FormRadio from './FormRadio.vue'
  import FormSelect from './FormSelect.vue'
  import FormMethod from './FormMethod.vue'
  import FormNumber from './FormNumber.vue'

  export default {
    inject: ['data'],
    props: {
      action: {//フォームの送信先URL
        type: String,
        required: true
      },
      msg: {//登録・編集ボタンを押した時の確認メッセージ
        type: String,
        required: true
      },
      back: {//戻るボタンの戻り先URL
        type: String,
        required: true
      },
      method: {//HTTPメソッド
        type: String,
        default: ''
      }
    },
    provide(){
      return {
        initSteps: this.initSmallSteps,
        project: 'step-form',
        errors: this.data.errors,
        old: this.data.old,
        dbData: 'dbData' in this.data ? this.data.dbData : false,
        constant: this.data.constant,
        columns: this.data.columns
      }
    },
    data(){
      return {
        initSmallSteps: [], //フォーム全体が生成されたときに生成した、子STEPフォームのuuidを入れる
        smallSteps: [],//現在生成している子STEPフォームのuuidを入れる
      }
    },
    computed: {
      currentStepNum(){//いま生成されている子ステップの個数
        return this.smallSteps.length
      }
    },
    created(){
      //smallStepFormコンポーネントを何個作れば良いか調べて、生成する
      let createStepNum = 3 // 生成数(デフォルト)

      if(Object.keys(this.data.old).length){
         //バリデーションチェックに引っかかった場合(入力保持する場合)
        for(let i = 1; i <= this.data.constant.maxSmallStep; i++){
          if(this.data.columns['step' + i] in this.data.old || this.data.columns['stepDetail' + i] in this.data.old){
            createStepNum = i
          }
        }
      }else if('dbData' in this.data){
        //値送信をしていない、STEP編集時
        createStepNum = this.data.dbData[this.data.columns.total]
      }
      for(let i = 0; i < createStepNum; i++){
        const uuid = UUID.generate()
        this.smallSteps.push(uuid)
        this.initSmallSteps.push(uuid)
      }
    },
    methods: {
      //対象の子ステップを削除する
      deleteStep(e){
        const targetIndex = this.smallSteps.indexOf(e)
        if(targetIndex > 0){
          //対象のuuidを削除して再描画させる
          this.smallSteps.splice(targetIndex, 1)
        }
      },
      //子ステップを追加する
      addStep(){
        if(this.currentStepNum < this.data.constant.maxSmallStep){
          this.smallSteps.push(UUID.generate())
        }
      },

    },
    components: {
      ErrMsg: ErrMsg,
      CSRFToken: CSRFToken,
      Btn: Btn,
      FormTextarea: FormTextarea,
      SmallStepForm: SmallStepForm,
      FormRadio: FormRadio,
      FormSelect: FormSelect,
      FormMethod: FormMethod,
      FormNumber: FormNumber
    }
  }
</script>