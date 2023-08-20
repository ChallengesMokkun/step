<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Database\QueryException;
use Symfony\Component\Mailer\Exception\TransportException;
use App\Mail\InquiryConfirmMail;
use App\Models\Inquiry;
use App\Models\User;
use App\Rules\NewLineLimit;//テキストエリアの改行数チェックを行うルール
use App\Rules\MaxTextarea;//テキストエリアの最大文字数をチェックするルール

use Illuminate\Support\Facades\Log;

class InquiriesController extends Controller
{
  //お問い合わせフォーム画面
  public function new(Request $request){
    $data = [
      'old' => $request->old(),
      'dbErr' => !empty($request->session()->pull('dbErr')) ? true : false,
      'sendEmailConfirmErr' => !empty($request->session()->pull('sendEmailConfirmErr')) ? true : false,
      'flash' => ($request->session()->has('status')) ? $request->session()->pull('status') : '',
      'columns' => Inquiry::COLUMNS,
      'constant' => Inquiry::CONSTANT,
    ];
    return view('inquiry.new', compact('data'));
  }

  //お問い合わせバリデーション・登録・メール送信
  public function create(Request $request){
    try{
      $request->validate([
        Inquiry::COLUMNS['email'] => ['bail', 'required', 'string', 'max:255', 'email:filter,dns'],
        Inquiry::COLUMNS['name'] => ['bail', 'required', 'string', 'max:'.Inquiry::CONSTANT['nameMax']],
        Inquiry::COLUMNS['purpose'] => ['bail', 'required', 'string', 'max:255'],
        Inquiry::COLUMNS['msg'] => ['bail', 'required', 'string', new NewLineLimit(Inquiry::CONSTANT['msgLines']), new MaxTextarea(Inquiry::CONSTANT['msgMax'])]
      ]);

      $inquiry = new Inquiry;
      //DBに保存
      $inquiry->fill($request->only([
        Inquiry::COLUMNS['email'],
        Inquiry::COLUMNS['name'],
        Inquiry::COLUMNS['purpose'],
        Inquiry::COLUMNS['msg']
      ]))->save();
  

      //メール送信
      Mail::to($request->post(Inquiry::COLUMNS['email']))->bcc(config('mail.receive'))->send(new InquiryConfirmMail($inquiry));

      return back()->with('status', 'お問い合わせを送信しました');

    }catch(QueryException $e){
      return back()->withInput()->with('dbErr', true);
    }catch(TransportException $e){
      return back()->withInput()->with('sendEmailConfirmErr', true);
    }
  }
}
