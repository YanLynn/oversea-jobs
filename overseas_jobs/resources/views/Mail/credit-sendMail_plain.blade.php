{{ $data['name'] }} 様

この度はボーダレスワーキングをご利用いただきありがとうございます。

仲介手数料として以下の通り請求いたします。
最下部に記述のあるURLよりクレジットカードにてお支払い願います。

●求人
【管理番号】
{{ $data['management_number'] }}
【求人タイトル】
{{ $data['title'] }}

●求職者会員
【会員番号】
{{ $data['jobseeker_number'] }}
【名前】
{{ $data['jobseeker_name'] }}
【名前(フリガナ)】
{{ $data['jobseeker_furigana_name'] }}

●請求額
{{ number_format($data['invoice_amount']) }}円
【内訳】
仲介手数料　{{ number_format($data['amount']) }}円
消費税　{{ number_format($data['tax']) }}円

●支払い方法
クレジットカード
お支払いはこちらのURLからお願いいたします。

{{ $data['url'] }}

本メールにお心当たりがない場合 support@borderless-working.jp までご連絡をお願いいたします。
_______________________________________________________________
ボーダレスワーキング
[運営会社]
株式会社 Trust Growth
〒163-0713 東京都新宿区西新宿2丁目7-1小田急第一生命ビル13階
TEL : 03-6911-3010
https://www.trust-growth.co.jp/
_______________________________________________________________