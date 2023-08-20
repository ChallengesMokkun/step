<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Step;
use App\Models\User;
use App\Models\Category;
use App\Models\Challenge;
use Illuminate\View\View;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;


use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\QueryException;
use App\Function\HelpFunc;// 独自のバリデーション・GETパラメータの調整を行う
use App\Http\Requests\StepRequest;//STEPの登録・更新時のバリデーションを行う

class StepsController extends Controller
{
  //step一覧・検索
  public function index(Request $request)
  {
    //ページを受け取る
    //nullなら、1ページ目の内容を表示する
    $page = $request->page;
    if(empty($page)){
      $page = 1;
    }elseif(HelpFunc::invalidNumberParam($page)){
      //無効な値ならリダイレクト
      return redirect()->route('step.index');
    }else{
      $page = (int)$page;
    }

    $cond = Step::join('users', Step::COLUMNS['userId'], '=', 'users.'.User::COLUMNS['id'])->select(
      'steps.'.Step::COLUMNS['id'],
      Step::COLUMNS['userId'],
      Step::COLUMNS['catId'], 
      Step::COLUMNS['editedAt'], 
      Step::COLUMNS['title'],
      Step::COLUMNS['phrase'],
      Step::COLUMNS['estimate'],
      Step::COLUMNS['unitId'],
      Step::COLUMNS['total'],
      User::COLUMNS['name'],
      User::COLUMNS['pic']
    )->where(Step::COLUMNS['pubFlg'], true);

    //GETパラメータをすべて取得
    $getParams = $request->query();

    //カテゴリーで絞り込む
    if(!empty($getParams[Step::COLUMNS['catId']])){
      if(!HelpFunc::invalidNumberParam($getParams[Step::COLUMNS['catId']]) && (int)$getParams[Step::COLUMNS['catId']] <= Category::NUM){
        //無効な値でなければ検索条件に含める
        $cond = $cond->where(Step::COLUMNS['catId'], $getParams[Step::COLUMNS['catId']]);
      }else{
        //無効な値の場合はリダイレクト
        return redirect()->route('step.index');
      }
    }

    //ユーザーで絞り込む 
    if(!empty($getParams[Step::COLUMNS['userId']])){
      if(!HelpFunc::invalidNumberParam($getParams[Step::COLUMNS['userId']])){
        //無効な値でなければ検索条件に含める
        $cond = $cond->where(Step::COLUMNS['userId'], $getParams[Step::COLUMNS['userId']]);
      }else{
        //無効な値の場合はリダイレクト
        return redirect()->route('step.index');
      }
    }


    //キーワードで絞り込む タイトルまたはキャッチコピーに含まれる語句を探す
    if(!empty($getParams[Step::COLUMNS['keyword']])){
      //検索語句をスペース区切りで配列に入れる
      $keywords = explode(' ', mb_convert_kana($getParams[Step::COLUMNS['keyword']],'s'));
      if(count($keywords) > 1){ 
        //2語以上ある場合 いずれかの語句に当てはまるものを探す
        $cond = $cond->where(function ($query) use ($keywords, $cond){
          foreach($keywords as $val){
            //SQLのワイルドカード文字をエスケープする
            $word = '%'.addcslashes($val, '%_\\').'%';
            if(empty(array_search($val, $keywords))){
              //最初の語
              $query = $query->where(function ($query) use ($word){
                $query->where(Step::COLUMNS['title'], 'LIKE', $word)->orWhere(Step::COLUMNS['phrase'], 'LIKE', $word);
              });
            }else{
              //最初以外の語
              $query = $query->orWhere(function ($query) use ($word){
                $query->where(Step::COLUMNS['title'], 'LIKE', $word)->orWhere(Step::COLUMNS['phrase'], 'LIKE', $word);
              });
            }
          }
        });
      }else{
        //1語だけの場合
        //SQLのワイルドカード文字をエスケープする
        $word = '%'.addcslashes($keywords[0], '%_\\').'%';
        $cond = $cond->where(function ($query) use ($word){
          $query->where(Step::COLUMNS['title'], 'LIKE', $word)->orWhere(Step::COLUMNS['phrase'], 'LIKE', $word);
        });
      }
    }

    $result = $cond->latest(Step::COLUMNS['editedAt'])->paginate(Step::CONSTANT['perPage']);
    //$pageが最終ページよりも大きい場合は1ページ目にリダイレクトさせる
    if($page > $result->lastPage()){
      unset($getParams['page']);
      return redirect()->route('step.index', $getParams);
    }


    //各STEP登録者のプロフィールURL・アイコンのパスを追加する
    $dbData = $result->append([
      Step::COLUMNS['memberProfileUrl'],
      Step::COLUMNS['memberProfilePic']
    ]);

    $getParamStrings = HelpFunc::genarateGetParam($getParams);//GETパラメータ文字列を生成する

    $data = [
      'dbData' => $result->setCollection($dbData),//STEPのデータをセットする
      'getParamStrings' => $getParamStrings,
      'old' => $getParams,
      'flash' => ($request->session()->has('status')) ? $request->session()->pull('status') : ''
    ];

    $current = $result->currentPage();
    return view('steps.index', compact('data', 'current'));
  }

  //ユーザーが登録したstep一覧
  public function mystep(Request $request)
  {
    //ページを受け取る
    //nullなら、1ページ目の内容を表示する
    $page = $request->page;
    if(empty($page)){
      $page = 1;
    }elseif(HelpFunc::invalidNumberParam($page)){
      //無効な値なら1ページ目にリダイレクト
      return redirect()->route('mystep');
    }else{
      $page = (int)$page;
    }

    $result = Auth::user()->steps()->select(
      Step::COLUMNS['id'],
      Step::COLUMNS['catId'], 
      Step::COLUMNS['editedAt'], 
      Step::COLUMNS['title'],
      Step::COLUMNS['phrase'],
      Step::COLUMNS['estimate'],
      Step::COLUMNS['unitId'],
      Step::COLUMNS['total'],
      Step::COLUMNS['pubFlg']
    )->latest(Step::COLUMNS['editedAt'])->paginate(Step::CONSTANT['perPage']);
    //$pageが最終ページよりも大きい場合は1ページ目にリダイレクトさせる
    if($page > $result->lastPage()){
      return redirect()->route('mystep');
    }

    $getParamStrings = HelpFunc::genarateGetParam($request->query(), Step::FROM['mystep']);//GETパラメータ文字列を生成する

    $dbData = $result->append(Step::COLUMNS['stepEditUrl']);//各STEPのデータに編集用URLを追加する

    $data = [
      'dbData' => $result->setCollection($dbData),//STEPのデータをセットする
      'getParamStrings' => $getParamStrings,
      'flash' => ($request->session()->has('status')) ? $request->session()->pull('status') : ''
    ];
    $current = $result->currentPage();

    return view('steps.mystep', compact('data', 'current'));
  }

  //チャレンジしたstep一覧
  public function challenges(Request $request)
  {
    //ページを受け取る
    //nullなら、1ページ目の内容を表示する
    $page = $request->page;
    if(empty($page)){
      $page = 1;
    }elseif(HelpFunc::invalidNumberParam($page)){
      //無効な値なら1ページ目にリダイレクトさせる
      return redirect()->route('challenges');
    }else{
      $page = (int)$page;
    }

    $userId = Auth::id();

    //公開されているSTEP または ユーザーが登録したSTEPを取得する
    $result = Step::join('challenges', 'steps.'.Step::COLUMNS['id'], '=', Challenge::COLUMNS['stepId'])->select(
      Step::COLUMNS['editedAt'], 
      Step::COLUMNS['title'], 
      Step::COLUMNS['total'],
      'steps.'.Step::COLUMNS['id'],
      Challenge::COLUMNS['current'], 
      Challenge::COLUMNS['clearFlg'], 
      Challenge::COLUMNS['numChangeFlg'],
      Challenge::COLUMNS['latestAt'],
      'challenges.'.Challenge::COLUMNS['createdAt'], 
      )->where('challenges.'.Challenge::COLUMNS['userId'], $userId)->where(function ($query) use ($userId){
        $query->where(Step::COLUMNS['pubFlg'], true)->orWhere('steps.'.Step::COLUMNS['userId'], $userId);
      })->latest(Challenge::COLUMNS['latestAt'])->paginate(Step::CONSTANT['perPage']);

    //$pageが最終ページよりも大きい場合は1ページ目にリダイレクトさせる
    if($page > $result->lastPage()){
      return redirect()->route('challenges');
    }

    $getParamStrings = HelpFunc::genarateGetParam($request->query(), Step::FROM['challenge']);//GETパラメータ文字列を生成する

    $data = [
      'dbData' => $result,
      'getParamStrings' => $getParamStrings
    ];
    $current = $result->currentPage();

    return view('steps.challenges', compact('data', 'current'));
  }
  
  //ユーザープロフィールページ
  public function show_profile(Request $request, $id)
  {
    //$idが無効な値ならリダイレクト
    if(HelpFunc::invalidNumberParam($id)){
      return redirect()->route('step.index');
    }

    //STEP投稿者を取得する
    $contributor = User::find($id, [User::COLUMNS['name'], User::COLUMNS['pic'], User::COLUMNS['intro']]);
    //取得できない場合はリダイレクト
    if(empty($contributor)){
      return redirect()->route('step.index');
    }

    //STEP投稿者が投稿したSTEP一覧のURL
    $contributor->setAttribute('userStepUrl', route('step.index', ['user_id' => $id]));
    //STEP投稿者のアイコン
    if(!empty($contributor->getAttribute(User::COLUMNS['pic']))){
      $contributor->setAttribute(User::COLUMNS['pic'], asset($contributor->getAttribute(User::COLUMNS['pic'])));
    }else{
      $contributor->setAttribute(User::COLUMNS['pic'], asset('null-user.png'));
    }

    //最新のSTEPを数件取得する
    $result = Step::join('users', Step::COLUMNS['userId'], '=', 'users.'.User::COLUMNS['id'])->select(
      'steps.'.Step::COLUMNS['id'],
      Step::COLUMNS['userId'],
      Step::COLUMNS['catId'], 
      Step::COLUMNS['editedAt'], 
      Step::COLUMNS['title'],
      Step::COLUMNS['phrase'],
      Step::COLUMNS['estimate'],
      Step::COLUMNS['unitId'],
      Step::COLUMNS['total'],
      User::COLUMNS['name'],
      User::COLUMNS['pic']
    )->where([Step::COLUMNS['userId'] => $id, Step::COLUMNS['pubFlg'] => true])
    ->latest(Step::COLUMNS['editedAt'])->offset(0)->limit(Step::CONSTANT['perDisp'])
    ->get()->append([Step::COLUMNS['memberProfileUrl'], Step::COLUMNS['memberProfilePic']])->all();
    
    if(empty($result)){
      $result = [];
      $num = 0;
    }else{
      $num = count($result);
    }

    $getParamStrings = HelpFunc::genarateGetParam(null, Step::FROM['profile']);//GETパラメータ文字列を生成する

    $data = [
      'dbData' => ['data' => $result, 'num' => $num],
      'contributor' => $contributor,
      'getParamStrings' => $getParamStrings,
    ];

    return view('steps.show_profile', compact('data'));
  }

  //stepの詳細画面
  public function show(Request $request, $id)
  {
    //$idが無効な値ならリダイレクト
    if(HelpFunc::invalidNumberParam($id)){
      return redirect()->route('step.index');
    }
    //STEPを取得する
    $dbData = Step::join('users', Step::COLUMNS['userId'], '=', 'users.'.User::COLUMNS['id'])->select(
      'steps.'.Step::COLUMNS['id'],
      Step::COLUMNS['userId'],
      Step::COLUMNS['catId'], 
      Step::COLUMNS['editedAt'], 
      Step::COLUMNS['title'],
      Step::COLUMNS['phrase'],
      Step::COLUMNS['estimate'],
      Step::COLUMNS['unitId'],
      Step::COLUMNS['total'],
      Step::COLUMNS['supp'],
      Step::COLUMNS['pubFlg'],
      Step::COLUMNS['step1'],
      Step::COLUMNS['step2'],
      Step::COLUMNS['step3'],
      Step::COLUMNS['step4'],
      Step::COLUMNS['step5'],
      Step::COLUMNS['step6'],
      Step::COLUMNS['step7'],
      Step::COLUMNS['step8'],
      Step::COLUMNS['step9'],
      Step::COLUMNS['step10'],
      User::COLUMNS['name'],
      User::COLUMNS['pic']
    )->where('steps.'.Step::COLUMNS['id'], $id)->get()->append([Step::COLUMNS['memberProfileUrl'], Step::COLUMNS['memberProfilePic']])->first();

    //stepが存在しない場合はリダイレクト
    if(empty($dbData)){
      return redirect()->route('step.index');
    }

    //ログインしているか確認(ログインの有無で表示内容が異なる)
    $userId = Auth::id();
    
    //非公開のSTEPかどうか確認　登録者以外かつ非公開ならリダイレクト
    if((empty($userId) || $dbData->getAttribute(Step::COLUMNS['userId']) !== $userId) && empty($dbData->getAttribute(Step::COLUMNS['pubFlg']))){
      return redirect()->route('step.index');
    }

    //GETパラメータをすべて取得
    $getParams = $request->query();

    //子STEP詳細のリンクを生成する
    $stepLinks = [];

    for($i = 1; $i <= $dbData->getAttribute(Step::COLUMNS['total']); $i++){
      $stepLinks[] = route('step.show_small', array_merge(['id' => $id, 'step' => $i], $getParams));
    }

    $dbData->setAttribute('stepLinks', $stepLinks);

    //戻るボタンのURLを調整
    if(empty($getParams['from'])){
      $back = route('step.index', $getParams);
    }else{
      $from = $getParams['from'];
      unset($getParams['from']);
      switch($from){
        case Step::FROM['profile']:
          $back = route('profile.show', array_merge(['id' => $dbData->getAttribute(Step::COLUMNS['userId'])], $getParams));
          break;
        case Step::FROM['challenge']:
          $back = route('challenges', $getParams);
          break;
        case Step::FROM['mystep']:
          $back = route('mystep', $getParams);
          break;
        case Step::FROM['mypage']:
          $back = route('mypage');
          break;
        default:
          $back = route('step.index', $getParams);
      }
    }

    
    if(!empty($userId)){
      //チャレンジまたはキャンセルするためのURLを渡す
      $dbData->setAttribute('chalUrl', route('step.challenge', array_merge(['id' => $id], $getParams)));

      //ログインユーザーがSTEPにチャレンジしてるか確認
      $challenge = Challenge::select(
        Challenge::COLUMNS['clearFlg'],
        Challenge::COLUMNS['current'],
        Challenge::COLUMNS['numChangeFlg'],
      )->where([Challenge::COLUMNS['userId'] => $userId, Challenge::COLUMNS['stepId'] => $id])->first();
      if(empty($challenge)){
        $challenge = [];
      }
    }else{
      $challenge = [];
    }

    $data = [
      'dbErr' => !empty($request->session()->pull('dbErr')) ? true : false,
      'flash' => ($request->session()->has('status')) ? $request->session()->pull('status') : '',
      'loginFlg' => !empty($userId) ? true : false,
      'dbData' => $dbData,
      'challenge' => $challenge,
      'back' => $back
    ];

    return view('steps.show', compact('data'));
  }

  //子stepの詳細画面
  public function show_small(Request $request, $id, $step)
  {
    //$idが無効な値ならリダイレクト
    if(HelpFunc::invalidNumberParam($id)){
      return redirect()->route('step.index');
    }
    //$stepが無効な値ならリダイレクト
    if(HelpFunc::invalidNumberParam($step) || $step > Step::CONSTANT['maxSmallStep']){
      return redirect()->route('step.index');
    }

    //STEPを取得する
    $dbData = Step::join('users', Step::COLUMNS['userId'], '=', 'users.'.User::COLUMNS['id'])->select(
      'steps.'.Step::COLUMNS['id'],
      Step::COLUMNS['userId'],
      Step::COLUMNS['catId'], 
      Step::COLUMNS['editedAt'], 
      Step::COLUMNS['title'],
      Step::COLUMNS['phrase'],
      Step::COLUMNS['estimate'],
      Step::COLUMNS['unitId'],
      Step::COLUMNS['total'],
      Step::COLUMNS['supp'],
      Step::COLUMNS['pubFlg'],
      Step::COLUMNS['step'.$step],
      Step::COLUMNS['stepDetail'.$step],
      User::COLUMNS['name'],
      User::COLUMNS['pic']
    )->where('steps.'.Step::COLUMNS['id'], $id)->get()->append([Step::COLUMNS['memberProfileUrl'], Step::COLUMNS['memberProfilePic']])->first();

    //stepが存在しない場合はリダイレクト
    if(empty($dbData)){
      return redirect()->route('step.index');
    }

    //ログインしているか確認(ログインの有無で表示内容が異なる)
    $userId = Auth::id();
    
    //非公開のSTEPかどうか確認　登録者以外かつ非公開ならリダイレクト
    if((empty($userId) || $dbData->getAttribute(Step::COLUMNS['userId']) !== $userId) && empty($dbData->getAttribute(Step::COLUMNS['pubFlg']))){
      return redirect()->route('step.index');
    }

    $step = (int)$step;
    //$stepが総STEP数よりも大きい、または子STEPのタイトルか説明がnullならリダイレクト
    if($step > $dbData->getAttribute(Step::COLUMNS['total']) || $dbData->getAttribute(Step::COLUMNS['step'.$step] === null) || $dbData->getAttribute(Step::COLUMNS['stepDetail'.$step] === null)){
      return redirect()->route('step.show', ['id' => $id]);
    }


    //戻るボタンの調整
    //GETパラメータをすべて取得
    $getParams = $request->query();
    $back = route('step.show', array_merge(['id' => $id], $getParams));

    
    if(!empty($userId)){
      //チャレンジ・キャンセルするためのURLを渡す
      $dbData->setAttribute('chalUrl', route('step.challenge', array_merge(['id' => $id], $getParams)));

      //ログインユーザーがSTEPにチャレンジしてるか確認
      $challenge = Challenge::select(
        Challenge::COLUMNS['clearFlg'],
        Challenge::COLUMNS['current'],
        Challenge::COLUMNS['numChangeFlg'],
      )->where([Challenge::COLUMNS['userId'] => $userId, Challenge::COLUMNS['stepId'] => $id])->first();
      if(empty($challenge)){
        $challenge = [];
      }elseif(empty($challenge->getAttribute(Challenge::COLUMNS['clearFlg']))){
        //完全クリア前の場合はクリアURLを渡す
        $dbData->setAttribute('clearUrl', route('small_step.clear', array_merge(['id' => $id, 'step' => $step], $getParams)));
      }
    }else{
      $challenge = [];
    }

    $data = [
      'dbErr' => !empty($request->session()->pull('dbErr')) ? true : false,
      'flash' => ($request->session()->has('status')) ? $request->session()->pull('status') : '',
      'loginFlg' => !empty($userId) ? true : false,
      'dbData' => $dbData,
      'challenge' => $challenge,
      'back' => $back,
      'step' => $step
    ];
    return view('steps.show_small', compact('data'));
  }

  //step新規登録　画面
  public function new(Request $request): View
  {
    $data = [
      'old' => $request->old(),
      'dbErr' => !empty($request->session()->pull('dbErr')) ? true : false
    ];
    return view('steps.new', compact('data'));
  }
  
  //step新規登録　処理
  public function create(StepRequest $request)
  {
    try{
      $request->validated();
  
      // 入力された子STEPの個数を正確に数える(後の処理の準備)
      $smallStepTitle = [];
      $smallStepDetail = [];

      for($i = 1; $i <= Step::CONSTANT['maxSmallStep']; $i++){
        if(!empty($request->post(Step::COLUMNS['step'.$i])) && !empty($request->post(Step::COLUMNS['stepDetail'.$i]))){
          $smallStepTitle[] = $request->post(Step::COLUMNS['step'.$i]);
          $smallStepDetail[] = $request->post(Step::COLUMNS['stepDetail'.$i]);
        }
      }

      $totalStep = count($smallStepTitle); //　子STEPのタイトル・詳細が同数であることをバリデーションで確認している

      $userId = Auth::id();

      $inputValue = array_merge(
        $request->safe()->only([
          Step::COLUMNS['catId'],
          Step::COLUMNS['estimate'],
          Step::COLUMNS['unitId'],
          Step::COLUMNS['title'],
          Step::COLUMNS['phrase'],
          Step::COLUMNS['supp'],
          Step::COLUMNS['pubFlg'],
          Step::COLUMNS['step1'],
          Step::COLUMNS['stepDetail1']
        ]),
        [
          Step::COLUMNS['userId'] => $userId,
          Step::COLUMNS['total'] => $totalStep,
          Step::COLUMNS['editedAt'] => Carbon::now()
        ]
      );

      // 子STEPの途中に空欄があれば上に詰める(途中に空欄を作らせないようにする)
      for($i = 2; $i <= Step::CONSTANT['maxSmallStep']; $i++){
        $inputValue[Step::COLUMNS['step'.$i]] = array_key_exists($i - 1, $smallStepTitle) ? $smallStepTitle[$i - 1] : null;
        $inputValue[Step::COLUMNS['stepDetail'.$i]] = array_key_exists($i - 1, $smallStepDetail) ? $smallStepDetail[$i - 1] : null;
        if(empty($inputValue[Step::COLUMNS['step'.$i]]) && empty($inputValue[Step::COLUMNS['stepDetail'.$i]])){
          break;
        }
      }
   
      $step = new Step;
      $step->fill($inputValue)->save();
  
      return redirect()->route('mystep')->with('status', 'STEPを登録しました');
    }catch(QueryException $e){
      return back()->withInput()->with('dbErr', true);
    }
  }

  //step編集　画面
  public function edit(Request $request, $id)
  {
    //$idが無効な値ならリダイレクト
    if(HelpFunc::invalidNumberParam($id)){
      return redirect()->route('mystep');
    }
    
    //stepを取得する
    $userId = Auth::id();
    $dbData = Step::where([Step::COLUMNS['id'] => $id, Step::COLUMNS['userId'] => $userId])->first();

    //stepが存在しない場合はリダイレクト
    if(empty($dbData)){
      return redirect()->route('mystep');
    }

    //GETパラメータを取得
    $getParams = $request->query();

    //戻るボタンのURLを調整
    if(empty($getParams['from'])){
      $back = route('mystep', $getParams);
    }else{
      $from = $getParams['from'];
      unset($getParams['from']);
      switch($from){
        case Step::FROM['mypage']:
          $back = route('mypage');
          break;
        default:
          $back = route('mystep', $getParams);
      }
    }

    $data = [
      'old' => $request->old(),
      'dbErr' => !empty($request->session()->pull('dbErr')) ? true : false,
      'dbData' => $dbData,
      'action' => route('step.edit', ['id' => $id]),//フォームの送信先URL
      'back' => $back,
      'flash' => ($request->session()->has('status')) ? $request->session()->pull('status') : ''
    ];
    return view('steps.edit', compact('data'));
  }

  //step編集　処理
  public function update(StepRequest $request, $id){
    try{
      //$idが無効な値ならリダイレクト
      if(HelpFunc::invalidNumberParam($id)){
        return back()->withInput()->with('status', '不正な操作です');
      }

      //stepを取得する
      $userId = Auth::id();
      $dbData = Step::where([Step::COLUMNS['id'] => $id, Step::COLUMNS['userId'] => $userId])->first();

      //stepが存在しない場合はリダイレクト
      if(empty($dbData)){
        return redirect()->route('mystep')->with('status', '不正な操作です');
      }

      $request->validated();

  
      // 入力された子STEPの個数を正確に数える(後の処理の準備)
      $smallStepTitle = [];
      $smallStepDetail = [];
      for($i = 1; $i <= Step::CONSTANT['maxSmallStep']; $i++){
        if(!empty($request->post(Step::COLUMNS['step'.$i])) && !empty($request->post(Step::COLUMNS['stepDetail'.$i]))){
          $smallStepTitle[] = $request->post(Step::COLUMNS['step'.$i]);
          $smallStepDetail[] = $request->post(Step::COLUMNS['stepDetail'.$i]);
        }
      }

      $totalStep = count($smallStepTitle); // 子STEPのタイトル・詳細が同数であることをバリデーションで確認している
      $beforeTotalStep = $dbData->getAttribute(Step::COLUMNS['total']); //変更前の子STEPの合計

      $inputValue = array_merge(
        $request->safe()->only([
          Step::COLUMNS['catId'],
          Step::COLUMNS['estimate'],
          Step::COLUMNS['unitId'],
          Step::COLUMNS['title'],
          Step::COLUMNS['phrase'],
          Step::COLUMNS['supp'],
          Step::COLUMNS['pubFlg'],
          Step::COLUMNS['step1'],
          Step::COLUMNS['stepDetail1']
        ]),
        [
          Step::COLUMNS['userId'] => $userId,
          Step::COLUMNS['total'] => $totalStep,
          Step::COLUMNS['editedAt'] => Carbon::now(),
        ]
      );

      // 子STEPの途中に空欄があれば上に詰める(途中に空欄を作らせないようにする)
      for($i = 2; $i <= Step::CONSTANT['maxSmallStep']; $i++){
        $inputValue[Step::COLUMNS['step'.$i]] = array_key_exists($i - 1, $smallStepTitle) ? $smallStepTitle[$i - 1] : null;
        $inputValue[Step::COLUMNS['stepDetail'.$i]] = array_key_exists($i - 1, $smallStepDetail) ? $smallStepDetail[$i - 1] : null;
      }
   
      $dbData->fill($inputValue)->save();

      // STEP数が変化したか確認する
      if($totalStep !== $beforeTotalStep){
        //チャレンジした人がいたら、STEP数が更新されたことを伝える
        Challenge::where(Challenge::COLUMNS['stepId'], $id)->update([
            Challenge::COLUMNS['numChangeFlg'] => true
        ]);
      }

      return back()->with('status', 'STEPを更新しました');
    }catch(QueryException $e){
      return back()->withInput()->with('dbErr', true);
    }
  }

  //step削除
  public function destroy($id){
    try{
      //$idが無効な値ならリダイレクト
      if(HelpFunc::invalidNumberParam($id)){
        return back()->with('status', '不正な操作です');
      }

      //stepを取得する
      $userId = Auth::id();
      $dbData = Step::where([Step::COLUMNS['id'] => $id, Step::COLUMNS['userId'] => $userId])->first();

      //stepが存在しない場合はリダイレクト
      if(empty($dbData)){
        return redirect()->route('mystep')->with('status', '不正な操作です');
      }

      //STEPを削除
      $dbData->delete();

      return redirect()->route('mystep')->with('status', 'STEPを削除しました');
    }catch(QueryException $e){
      return back()->with('dbErr', true);
    }
  }

  //stepにチャレンジする
  public function challenge(Request $request, $id){
    //$idが正しく指定されてるか確認
    if(HelpFunc::invalidNumberParam($id)){
      return back()->with('status', '不正な操作です');
    }
    
    try{
      //実在するSTEPか確認
      $dbData = Step::find($id);
      if(empty($dbData)){
        return redirect()->route('step.index')->with('status', 'チャレンジできませんでした');
      }

      $userId = Auth::id();

      //非公開のSTEPかどうか確認　登録者以外かつ非公開ならチャレンジ不可
      if($dbData->getAttribute(Step::COLUMNS['userId']) !== $userId && empty($dbData->getAttribute(Step::COLUMNS['pubFlg']))){
        return redirect()->route('step.index')->with('status', 'チャレンジできませんでした');
      }
      //既にチャレンジしているか確認
      $challenge = Challenge::where([Challenge::COLUMNS['userId'] => $userId, Challenge::COLUMNS['stepId'] => $id])->first();
      
      //GETパラメータをすべて取得
      $getParams = $request->query();

      if(empty($challenge)){
        //新規でチャレンジする
        $challenge = new Challenge;
        $challenge->fill([
          Challenge::COLUMNS['userId'] => $userId,
          Challenge::COLUMNS['stepId'] => $id,
          Challenge::COLUMNS['latestAt'] => Carbon::now(),
        ])->save();

        return redirect()->route('step.show_small', array_merge(['id' => $id, 'step' => 1], $getParams))->with('status', 'チャレンジしました');

      }elseif(!empty($challenge->getAttribute(Challenge::COLUMNS['clearFlg']))){
        //既にチャレンジしていても、完全クリア済みの場合は再チャレンジ可能
        $challenge->fill([
          Challenge::COLUMNS['current'] => 0,
          Challenge::COLUMNS['clearFlg'] => false,
          Challenge::COLUMNS['numChangeFlg'] => false,
          Challenge::COLUMNS['retryFlg'] => true,
          Challenge::COLUMNS['latestAt'] => Carbon::now(),
          Challenge::COLUMNS['createdAt'] => Carbon::now()
        ])->save();
        return redirect()->route('step.show_small', array_merge(['id' => $id, 'step' => 1], $getParams))->with('status', 'チャレンジしました');

      }else{
        //既にチャレンジしていて、完全クリア前なら新たなチャレンジは不可
        return back()->with('status', 'チャレンジ中です');
      }
    }catch(QueryException $e){
      return back()->with('dbErr', true);
    }
  }

  //子stepをクリアする
  public function clear(Request $request, $id, $step){
    //$idが無効な値ならリダイレクト
    if(HelpFunc::invalidNumberParam($id)){
      return redirect()->route('step.index')->with('status', '不正な操作です');
    }
    //$stepが無効な値ならリダイレクト
    if(HelpFunc::invalidNumberParam($step) || (int)$step > Step::CONSTANT['maxSmallStep']){
      return redirect()->route('step.index')->with('status', '不正な操作です');
    }

    $step = (int)$step;
    try{
      //STEPを取得する
      $dbData = Step::find($id);
      //stepが存在しない場合はリダイレクト
      if(empty($dbData)){
        return redirect()->route('step.index')->with('status', '記録できませんでした');
      }

      $userId = Auth::id();
      
      //非公開のSTEPかどうか確認　登録者以外かつ非公開ならリダイレクト
      if($dbData->getAttribute(Step::COLUMNS['userId']) !== $userId && empty($dbData->getAttribute(Step::COLUMNS['pubFlg']))){
        return redirect()->route('step.index')->with('status', '記録できませんでした');
      }

      //チャレンジしているか確認
      $challenge = Challenge::where([Challenge::COLUMNS['userId'] => $userId, Challenge::COLUMNS['stepId'] => $id])->first();
      if(empty($challenge)){
        return redirect()->route('step.show', ['id' => $id])->with('status', 'チャレンジしていません');
      }

      //GETパラメータをすべて取得
      $getParams = $request->query();

      //$stepが総STEP数よりも大きければリダイレクト
      if($step > $dbData->getAttribute(Step::COLUMNS['total'])){
        return redirect()->route('step.show', array_merge(['id' => $id], $getParams))->with('status', '記録できませんでした');
      }

      //既にクリア済みか確認する
      if(!empty($challenge->getAttribute(Challenge::COLUMNS['clearFlg']))){
        //既に完全クリア済みか確認
        return redirect()->route('step.show', array_merge(['id' => $id], $getParams))->with('status', '既にクリアしています');
      }elseif($challenge->getAttribute(Challenge::COLUMNS['current']) >= $step && $challenge->getAttribute(Challenge::COLUMNS['current']) < $dbData->getAttribute(Step::COLUMNS['total'])){
        //既に子STEP($step)をクリア済みか確認
        //ただしSTEP編集でSTEP数が減少し、現在のユーザーのSTEP数が総STEP数以上になってしまった場合は除外する
        return redirect()->route('step.show', array_merge(['id' => $id], $getParams))->with('status', '既にSTEP'.$step.'をクリアしています');
      }
      //次以降のSTEPのクリアボタンを押しているか確認 
      //現在のユーザーのSTEP数が総STEP数以上になってしまった場合は除外する
      if(($challenge->getAttribute(Challenge::COLUMNS['current']) + 1 < $step && $challenge->getAttribute(Challenge::COLUMNS['current']) < $dbData->getAttribute(Step::COLUMNS['total']))){
        return redirect()->route('step.show', array_merge(['id' => $id], $getParams))->with('status', '前のSTEPをクリアしていません');
      }

      //クリアを記録する
      //最後の子STEPをクリアした場合
      if($step === $dbData->getAttribute(Step::COLUMNS['total'])){
        $challenge->fill([
          Challenge::COLUMNS['current'] => $step,
          Challenge::COLUMNS['numChangeFlg'] => false,
          Challenge::COLUMNS['clearFlg'] => true,
          Challenge::COLUMNS['latestAt'] => Carbon::now()
        ])->save();
        return redirect()->route('step.show', array_merge(['id' => $id], $getParams))->with('status', 'このSTEPをコンプリートしました');

      }else{
        //最後以外の子STEPをクリアした場合
        $challenge->fill([
          Challenge::COLUMNS['current'] => $step,
          Challenge::COLUMNS['numChangeFlg'] => false,
          Challenge::COLUMNS['latestAt'] => Carbon::now()
        ])->save();
        return redirect()->route('step.show_small', array_merge(['id' => $id, 'step' => $step + 1], $getParams))->with('status', 'STEP'.$step.'をクリアしました');
      }
    }catch(QueryException $e){
      return back()->with('dbErr', true);
    }
  }

  //チャレンジを中止する
  public function cancel(Request $request, $id){
    //$idが正しく指定されてるか確認
    if(HelpFunc::invalidNumberParam($id)){
      return back()->with('status', '不正な操作です');
    }

    try{
      //実在するSTEPか確認
      $dbData = Step::find($id);
      if(empty($dbData)){
        return redirect()->route('step.index')->with('status', 'キャンセルできませんでした');
      }

      $userId = Auth::id();

      //非公開のSTEPかどうか確認　登録者以外かつ非公開ならリダイレクトさせる
      if($dbData->getAttribute(Step::COLUMNS['userId']) !== $userId && empty($dbData->getAttribute(Step::COLUMNS['pubFlg']))){
        return redirect()->route('step.index')->with('status', 'キャンセルできませんでした');
      }
      //チャレンジしているか確認
      $challenge = Challenge::where([Challenge::COLUMNS['userId'] => $userId, Challenge::COLUMNS['stepId'] => $id])->first();
      if(empty($challenge)){
        return back()->with('status', 'チャレンジしていません');
      }elseif($challenge->getAttribute(Challenge::COLUMNS['clearFlg'])){
        //クリア済みならキャンセル不可
        return back()->with('status', '既にクリアしています');
      }

      //チャレンジを削除
      $challenge->delete();
      
      return back()->with('status', 'チャレンジをキャンセルしました');

    }catch(QueryException $e){
      return back()->with('dbErr', true);
    }
  }
  
}
