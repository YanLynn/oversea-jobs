
## Oversea-jobs
Oversea Jobs Project

### INSTALLATION
```bash
git clone https://github.com/Thuzar-TS92/Oversea-jobs.git
```
Go to project folder.
```bash
git checkout your-branch-name (optional)
composer install
npm install
```

### Database Setup
- In Browser, go to localhost/phpmyadmin
- Create database ```oversea_jobs``` with collation ```utf8mb4_unicode_ci```
- Clone ```.env.example``` file as ```.env```
- Change your database connection in .env file.
- run ```php artisan migrate --seed```
(already exist database => ```php artisan migrate:refresh --seed```)

```
php artisan key:generate
php artisan jwt:secret
```

### For recruiter
- role_id => 2

### For Jobseeker
- role_id => 3

### Run Project
- run ```php artisan serve```
- run ```npm run watch```

### Status
Jobseeker (record_status)
- active (1) 有効
- inactive (2) 停止
- stop (deactivate) (3) 退会
- delete (0)

Recruiter (record_status)
- private (2) 停止
- public (1) 有効
- stop (3) 退会
- delete (0)

Job (record_status)
- private (2) 非公開
- public (1) 公開
- stop (3) 停止
- delete (0)

Users
- password_locked  (0,1,2 = able to login, 3 = locked) Only for recruiters
- record_status (1 = able to login)

job_post_status (draft,post)
scout_setting_status (1 = scoutable, 0 = not scout) 
face_image_private_status (1 = private, 0 = public)

JobApply (status)

検討中 (Under Review)
辞退/不採用 (Rejected/declined)
内定未請求 (Unclaimed)
請求済 (Billed)
入金確認済 (Payment confirmed)

Scout (status)

回答待ち (waiting)
興味あり (Interested)
期限切れ (Expired)
不採用/辞退 (Rejected/declined)
内定未請求 (Unclaimed)
請求済 (Billed)
入金確認済 (Payment confirmed)

uploaded_files

file_type
- photo
- cv-en
- cv-jp
- cv-other
- exp-en
- exp-jp
- exp-other

user_type
- recruiter
- jobseeker

vue-select
Sorry, no matching options. => "検索条件に当てはまるデータはありません"