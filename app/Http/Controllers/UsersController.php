<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password as PasswordRule;
use App\Http\Requests\ProfileUpdateRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;
use App\Http\Requests\Auth\LoginRequest;
use App\Providers\RouteServiceProvider;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Validation\Rules;
use Illuminate\Support\Facades\Password as PasswordFacade;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;

use Illuminate\Support\Facades\Log;
use Illuminate\Database\QueryException;
use Symfony\Component\Mailer\Exception\TransportException;

use App\Models\Challenge;
use App\Models\Step;
use App\Mail\MemberRegisterConfirmMail;
use App\Mail\EmailChangeConfirmMail;
use App\Function\HelpFunc;// 独自のバリデーション・GETパラメータの調整を行う
use App\Rules\NewLineLimit;//テキストエリアの改行数チェックを行うルール
use App\Rules\MaxTextarea;//テキストエリアの最大文字数をチェックするルール
use App\Rules\PasswordMatch;//現在のパスワードと一致するか調べるルール

class UsersController extends Controller
{
  public function mypage(Request $request){

    //ユーザーが登録したSTEPを数件取得する
    $mystep = Auth::user()->steps()->select(
      Step::COLUMNS['id'],
      Step::COLUMNS['catId'], 
      Step::COLUMNS['editedAt'], 
      Step::COLUMNS['title'],
      Step::COLUMNS['phrase'],
      Step::COLUMNS['estimate'],
      Step::COLUMNS['unitId'],
      Step::COLUMNS['total'],
      Step::COLUMNS['pubFlg']
    )->latest(Step::COLUMNS['editedAt'])->offset(0)->limit(Step::CONSTANT['perDisp'])
    ->get()->append(Step::COLUMNS['stepEditUrl'])->all();

    if(empty($mystep)){
      $mystep = [];
      $mystepNum = 0;
    }else{
      $mystepNum = count($mystep);
    }

    $userId = Auth::id();
    //ユーザーがチャレンジしたSTEPを数件取得する
    $challenge = Step::join('challenges', 'steps.'.Step::COLUMNS['id'], '=', Challenge::COLUMNS['stepId'])->select(
      Step::COLUMNS['catId'], 
      Step::COLUMNS['editedAt'], 
      Step::COLUMNS['title'], 
      Step::COLUMNS['total'],
      'steps.'.Step::COLUMNS['id'],
      Challenge::COLUMNS['current'], 
      Challenge::COLUMNS['clearFlg'], 
      Challenge::COLUMNS['numChangeFlg'],
      Challenge::COLUMNS['latestAt'],
      'challenges.'.Challenge::COLUMNS['createdAt']
      )->where('challenges.'.Challenge::COLUMNS['userId'] , $userId)->where(function ($query) use ($userId){
        $query->where(Step::COLUMNS['pubFlg'], true)->orWhere('steps.'.Step::COLUMNS['userId'], $userId);
      })->latest(Challenge::COLUMNS['latestAt'])->offset(0)->limit(Step::CONSTANT['perDisp'])->get()->all();

    if(empty($challenge)){
      $challenge = [];
      $challengeNum = 0;
    }else{
      $challengeNum = count($challenge);
    }

    $getParamStrings = HelpFunc::genarateGetParam(null, Step::FROM['mypage']);

    $data = [
      'dbData' => compact('mystep', 'mystepNum', 'challenge', 'challengeNum'),
      'getParamStrings' => $getParamStrings,
      'flash' => ($request->session()->has('status')) ? $request->session()->pull('status') : ''
    ];

    return view('users.mypage', compact('data'));
  }

  // ユーザー登録画面
  public function new(Request $request): View
  {
    $data = [
      'old' => $request->old(),
      'dbErr' => !empty($request->session()->pull('dbErr')) ? true : false,
      'sendEmailConfirmErr' => !empty($request->session()->pull('sendEmailConfirmErr')) ? true : false
    ];
    return view('users.register', compact('data'));
  }

  // ユーザー登録処理
  public function create(Request $request)
  {
    try{
      $request->validate([
        User::COLUMNS['name'] => ['bail', 'required', 'string', 'max:'.User::CONSTANT['nameMax']],
        User::COLUMNS['email'] => ['bail', 'required', 'string', 'max:255', 'email:filter,dns', 'unique:'.User::class],
        User::COLUMNS['pass'] => ['bail', 'required', 'max:255', PasswordRule::defaults(), 'regex:/^[a-zA-Z0-9!?_;:&#%\+\$\^]+$/', 'confirmed'],
        User::COLUMNS['pic'] => ['bail', 'image', 'max:'.User::CONSTANT['picMax'], 'nullable'],
        User::COLUMNS['intro'] => ['bail', 'string', new NewLineLimit(User::CONSTANT['introLines']), new MaxTextarea(User::CONSTANT['introMax']), 'nullable'],
      ]);

      $file = $request->file(User::COLUMNS['pic']);
      //画像のアップロード
      if(!empty($file)){
        $fileName = $file->hashname();

        $extension = $file->extension();
        if($extension !== 'gif'){
          $fileName = preg_replace("/(.+)(\.[^.]+$)/", "$1", $fileName).'.jpg'; //gif以外は、圧縮過程でjpgに変換する
          $resizedPic = HelpFunc::compressImage($file, $fileName); //画像を圧縮
        }else{
          $file->storeAs('public/'.User::DIR, $fileName);// gifはそのまま保存
        }
        $path = 'storage/'.User::DIR.'/'.$fileName;

      }else{
        $path = null;
      }

      $user = new User;
      $user->fill(array_merge(
        $request->only([
          User::COLUMNS['name'], 
          User::COLUMNS['email'], 
          User::COLUMNS['intro']
        ]),
        [
          User::COLUMNS['pass'] => Hash::make($request->post(User::COLUMNS['pass'])),
          User::COLUMNS['pic'] => $path
        ]
      ))->save();

      //メール送信
      Mail::to($request->post(User::COLUMNS['email']))->send(new MemberRegisterConfirmMail($user));


      event(new Registered($user));

      Auth::login($user);

      return redirect(RouteServiceProvider::HOME)->with('status', 'ユーザー登録しました');
    }catch(QueryException $e){
      return back()->withInput()->with('dbErr', true);
    }catch(TransportException $e){
      return back()->withInput()->with('sendEmailConfirmErr', true);
    }
  }

  //ログインページ
  public function login(Request $request): View
  {
    $data = [
      'old' => $request->old(),
      'flash' => ($request->session()->has('status')) ? $request->session()->pull('status') : '',
      'dbErr' => !empty($request->session()->pull('dbErr')) ? true : false
    ];
    return view('users.login', compact('data'));
  }

  //ログイン処理
  public function login_auth(LoginRequest $request)
  {
    try{
      $request->authenticate();
      $request->session()->regenerate();

      return redirect()->intended(RouteServiceProvider::HOME)->with('status', 'ログインしました');
    }catch(QueryException $e){
      return back()->withInput()->with('dbErr', true);
    }
  }

  // ログアウト処理
  public function logout(Request $request): RedirectResponse
  {
      Auth::guard('web')->logout();

      $request->session()->invalidate();

      $request->session()->regenerateToken();

      return redirect()->route('login')->with('status', 'ログアウトしました');
  }

  //パスワード変更画面
  public function pass_edit(Request $request): View
  {
    $data = [
      'dbErr' => !empty($request->session()->pull('dbErr')) ? true : false
    ];
    return view('users.passedit', compact('data'));
  }

  //パスワード変更処理
  public function pass_update(Request $request)
  {
    try{
      $request->validate([
        User::COLUMNS['passCurrent'] => ['bail', 'required', 'max:255', 'current_password'],
        User::COLUMNS['pass'] => ['bail', 'required', 'max:255', PasswordRule::defaults(), 'regex:/^[a-zA-Z0-9!?_;:&#%\+\$\^]+$/', 'confirmed', 'different:'.User::COLUMNS['passCurrent']],
      ]);
  
      $request->user()->update([
        User::COLUMNS['pass'] => Hash::make($request->post(User::COLUMNS['pass'])),
      ]);
  
      return redirect()->route('mypage')->with('status', 'パスワードを変更しました');
    }catch(QueryException $e){
      return back()->with('dbErr', true);
    }
  }

  // プロフィール編集画面
  public function prof_edit(Request $request): View
  {
    $dbData = Auth::user();

    if(!empty($dbData->getAttribute(User::COLUMNS['pic']))){
      $dbData->setAttribute(User::COLUMNS['picBef'], $dbData->getAttribute(User::COLUMNS['pic']));//元の画像パスを保存しておく
      $dbData->setAttribute(User::COLUMNS['pic'], asset($dbData->getAttribute(User::COLUMNS['pic'])));//画像のパスを取得する
    }

    $data = [
      'dbData' => $dbData,
      'old' => $request->old(),
      'dbErr' => !empty($request->session()->pull('dbErr')) ? true : false,
      'sendEmailConfirmErr' => !empty($request->session()->pull('sendEmailConfirmErr')) ? true : false
    ];
    return view('users.profedit', compact('data'));
  }

  // プロフィール更新処理
  public function prof_update(ProfileUpdateRequest $request)
  {
    try{
      $request->validated();

      //画像のアップロード
      $file = $request->file(User::COLUMNS['pic']);

      $dbData = Auth::user();

      $beforePath = $dbData->getAttribute(User::COLUMNS['pic']);//元の画像パス
      if(!empty($file)){
        //新たに画像をアップロードしたとき
        $fileName = $file->hashname();

        $extension = $file->extension();
        if($extension !== 'gif'){
          $fileName = preg_replace("/(.+)(\.[^.]+$)/", "$1", $fileName).'.jpg'; //gif以外は、圧縮過程でjpgに変換する
          $resizedPic = HelpFunc::compressImage($file, $fileName); //画像を圧縮
        }else{
          $file->storeAs('public/'.User::DIR, $fileName); //gifはそのまま保存
        }
        $path = 'storage/'.User::DIR.'/'.$fileName;

        //元の画像があれば削除
        if(!empty($beforePath)){
          Storage::delete(str_replace('storage/', 'public/', $beforePath));
        }

      }elseif(empty($request->only(User::COLUMNS['picBef']))){
        //元から画像がない または 画像を消したとき
        $path = null;

        //元の画像があれば削除
        if(!empty($beforePath)){
          Storage::delete(str_replace('storage/', 'public/', $beforePath));
        }
      }else{
        //元から画像をアップロードしていて、変更を加えないとき
        $path = $beforePath;//保存されているパス
      }

      $beforeEmail = $dbData->getAttribute(User::COLUMNS['email']);//変更前のメールアドレスを取得する

      $dbData->fill(array_merge(
        $request->safe()->only([
          User::COLUMNS['name'],
          User::COLUMNS['email'],
          User::COLUMNS['intro']
        ]),
        [
          User::COLUMNS['pic'] => $path
        ]
      ))->save();

      //メールアドレスが変更された場合はメール送信する
      if($request->post(User::COLUMNS['email']) !== $beforeEmail){
        //メール送信
        Mail::to($request->post(User::COLUMNS['email']))->bcc($beforeEmail)->send(new EmailChangeConfirmMail($dbData));
      }


      return redirect(RouteServiceProvider::HOME)->with('status', 'プロフィールを更新しました');
    }catch(QueryException $e){
      return back()->with('dbErr', true);
    }catch(TransportException $e){
      return back()->withInput()->with('sendEmailConfirmErr', true);
    }
  }

  //退会画面
  public function destroy_confirm(Request $request){
    $data = [
      'dbErr' => !empty($request->session()->pull('dbErr')) ? true : false
    ];
    return view('users.withdraw', compact('data'));
  }

  //退会処理
  public function destroy(Request $request)
  {
    try{
      $request->validate([
        User::COLUMNS['pass'] => ['bail', 'required', 'max:255', PasswordRule::defaults(), 'regex:/^[a-zA-Z0-9!?_;:&#%\+\$\^]+$/', 'current_password'],
      ]);

      $user = Auth::user();
      Auth::logout();

      //退会処理
      $path = $user->getAttribute(User::COLUMNS['pic']);//アイコンのパス
      //アイコンがあれば削除
      if(!empty($path)){
        Storage::delete(str_replace('storage/', 'public/', $path));
      }
      //ユーザーの削除
      //ユーザーを削除すると、ユーザーが投稿したSTEP・そのSTEPへのチャレンジ(他のユーザー含む)・ユーザーのチャレンジが同時に削除される
      $user->delete();

      $request->session()->invalidate();
      $request->session()->regenerateToken();

      return redirect()->route('login')->with('status', '退会しました');
    }catch(QueryException $e){
      return back()->with('dbErr', true);
    }
  }

  //パスワードリマインダー　メールアドレス送信画面
  public function forgot_pass(Request $request): View
  {
    $data = [
      'old' => $request->old(),
      'flash' => ($request->session()->has('status')) ? $request->session()->pull('status') : '',
      'dbErr' => !empty($request->session()->pull('dbErr')) ? true : false,
      'sendLinkErr' => !empty($request->session()->pull('sendLinkErr')) ? true : false
    ];
    return view('users.forgot-pass', compact('data'));
  }

  //パスワードリマインダー　リセットリンク送信処理
  public function forgot_pass_auth(Request $request)
  {
    $request->validate([
      User::COLUMNS['email'] => ['bail', 'required', 'max:255', 'email:filter,dns'],
    ]);

    try{
      $status = PasswordFacade::sendResetLink(
        $request->only(User::COLUMNS['email'])
      );

      return $status === PasswordFacade::RESET_LINK_SENT
                  ? back()->with('status', __($status))
                  : back()->withInput($request->only(User::COLUMNS['email']))
                          ->withErrors([User::COLUMNS['auth'] => __($status)]);
    }catch(QueryException $e){
      return back()->withInput()->with('dbErr', true);
    }catch(TransportException $e){
      return back()->withInput()->with('sendLinkErr', true);
    }
  }

  // パスワードリマインダー　リセット画面
  public function reset_pass(Request $request): View
  {
    $data = [
      'request' => [
        'token' => $request->route(User::COLUMNS['token']),
        'email' => $request->input(User::COLUMNS['email']),
      ],
      'dbErr' => !empty($request->session()->pull('dbErr')) ? true : false
    ];
    
    return view('users.reset-pass', compact('data'));
  }

  //パスワードリマインダー リセット処理
  public function reset_pass_auth(Request $request)
  {
    $request->validate([
      User::COLUMNS['token'] => ['required'],
      User::COLUMNS['email'] => ['bail', 'required', 'max:255', 'email:filter,dns'],
      User::COLUMNS['pass'] => ['bail', 'required', 'max:255', PasswordRule::defaults(), 'regex:/^[a-zA-Z0-9!?_;:&#%\+\$\^]+$/', 'confirmed', new PasswordMatch($request->post(User::COLUMNS['email']))],
    ]);

    try{
      $status = PasswordFacade::reset(
        $request->only(User::COLUMNS['email'], User::COLUMNS['pass'], User::COLUMNS['passRe'], User::COLUMNS['token']),
        function ($user) use ($request) {
            $user->forceFill([
              User::COLUMNS['pass'] => Hash::make($request->post(User::COLUMNS['pass'])),
              User::COLUMNS['rememberToken'] => Str::random(60),
            ])->save();

            event(new PasswordReset($user));
          }
      );

      return $status == PasswordFacade::PASSWORD_RESET
                  ? redirect()->route('login')->with('status', __($status))
                  : back()->withErrors([User::COLUMNS['auth'] => __($status)]);
    }catch(QueryException $e){
      return back()->with('dbErr', true);
    }
  }
  
}
