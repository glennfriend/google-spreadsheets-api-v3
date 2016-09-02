# google-spreadsheets-api-v3
申請 https://console.developers.google.com/
建立 API 憑證 OAuth
取得
- CLIENT_ID
- CLIENT_EMAIL
- p12 key

建立 google sheet 並且共用給使用者 CLIENT_EMAIL

### Environment
- PHP 5.5
- composer (https://getcomposer.org/)

### Installation & Update
```sh
$ composer.phar self-update
$ composer.phar install
$ cp app/config/template/* app/config/
$ vi app/config/*.php
$ php autorun.php
```

### Execute
```sh
$ php public/index.php
```

### 停止維護
將轉移至 "google-spreadsheets-tool"


