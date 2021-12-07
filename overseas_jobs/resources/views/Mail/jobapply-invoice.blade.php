<pre>{{$data['name']}} 様

この度はボーダレスワーキングをご利用いただきありがとうございます。

仲介手数料として以下の通り請求いたします。
   
●求人
【管理番号】
{{$data['management_number']}} 
【求人タイトル】
{{$data['job_title']}}
【募集ポジション】
{{$data['occupation_description']}}
【雇用形態】
{{$data['job_location']}} 
【勤務地】
{{$data['job_apply_status']}} 

●求職者会員 
【会員番号】
{{$data['seeker_number']}}
【名前】
{{$data['seeker_name']}} 
【名前(フリガナ)】
{{$data['seeker_furigana']}} 

●請求額 
報酬    {{number_format($data['invoice_amount'])}}円 
【内訳】
報酬    {{number_format($data['default_amount'])}}円 
消費税  {{number_format($data['tax'])}} 円 
    
●支払い方法 
銀行振り込み 
※振込先、支払い期限については請求書をご覧ください。
※振り込み名義人の後ろに管理番号の記入をお願いいたします。
管理番号: {{$data['management_number']}} 

本メールにお心当たりがない場合 support@borderless-working.jp までご連絡をお願いいたします。     
_______________________________________________________________
ボーダレスワーキング
[運営会社]
株式会社 Trust Growth
〒163-0713 東京都新宿区西新宿2丁目7-1小田急第一生命ビル13階
TEL : 03-6911-3010
https://www.trust-growth.co.jp/ 
_______________________________________________________________</pre>