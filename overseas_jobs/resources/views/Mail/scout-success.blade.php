<pre>{{ $data['jobseeker_name']}}　様

ボーダレスワーキングに登録の企業からスカウトがありました。
求職者会員管理画面にて詳細をご確認ください。
{{ $data['domain'] }}/jobseeker/scouted-list?uid={{ $data['user_id'] }}

●スカウト詳細
【管理番号】
{{ $data['management_number'] }}
【求人タイトル】
{{ $data['job_title'] }}
【募集ポジション】
{{ $data['scout_occupation_description'] }}
【雇用形態】
{{ $data['scout_employment_types'] }}
【勤務地詳細】
{{ $data['scout_job_location'] }}

●スカウト対象の求人
【求人番号】
{{ $data['job_number'] }}
【求人タイトル】
{{ $data['job_title'] }}
【職種】
{{ $data['occupation_description'] }}
【雇用形態】
{{ $data['employment_types'] }}
【仕事の内容】
{{ $data['job_description'] }}
【応募資格】
{{ $data['qualification'] }}
【勤務地詳細】
{{ $data['job_location'] }}
【給与・待遇・ビザサポート】
{{ $data['allowance'] }}
【勤務開始日】
{{ $data['job_start_date'] }}
【求職者へのメッセージ】
{{ $data['message'] }}

●企業会員
【会員番号】
{{ $data['recruiter_number'] }}
【企業名】
{{ $data['recruiter_name'] }}
_______________________________________________________________
ボーダレスワーキング
[運営会社]
株式会社 Trust Growth
〒163-0713 東京都新宿区西新宿2丁目7-1小田急第一生命ビル13階
TEL : 03-6911-3010
https://www.trust-growth.co.jp/
_______________________________________________________________
</pre>