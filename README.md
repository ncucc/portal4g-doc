# portal4g-doc
中央大學第四代 Portal (2020 年版) 文件

### 簡介
中央大學第四代 Portal 單一簽入的方式有四種，包括偉康協定 (第一代，制式),
OpenID (第二代), OAuth (Portal3g), SimpleAuth (第四代，制式）等。
其中，我們建議新的系統採 OAuth 的方式做單一簽入，其它的模式僅供相容及特殊應用使用，
所以本文件的內容以介紹如何使用 OAuth 為主。

中央大學第四代 Portal 在 OAuth 的實作上採 RFC 6749 The OAuth 2.0 Authorization Framework
的標準，實現的部份為 Authorization Code Grant 的流程。因為符合規範，所以在實作上，可以用現成的程式庫就可以跟 
Portal 介接。

在 Grant 流程中的幾個 URL 如下

* Authorization: https://portal.ncu.edu.tw/oauth2/authorization (GET Method)
* Token: https://portal.ncu.edu.tw/oauth2/token (POST Method, 需要以 ClientId/ClientSecret 做為
Basic Auth 的帳號密碼, 另外在 header 要送 Accept: application/json)
* User Info: https://portal.ncu.edu.tw/apis/oauth/v1/info (Get Method, 傳回 JSON 格式的使用者資訊)

在測試階段可將上述 URL 的主機置換成測試主機即可。

另外，Scopes 決定希望使用者授權取得的資料，Scopes 的間隔符號為空白。Scopes 同使用者資訊的欄位。

### 使用者資訊

* id : 使用者的 id，一個 64 位元的長整數，跨系統時，id 是不同的。(一定有的資訊，系統可以不要求使用者帳號資訊做為無記名的系統)
* identifier : 使用者的帳號。當應用系統在 Portal 上記錄需要 identifier 時，使用者只能選擇不登入，但不能拒絕授權。
* chinese-name : 中文姓名 (使用者可以決定是否授權)
* english-name : 英文姓名 (使用者可以決定是否授權)
* gender : 姓別 (使用者可以決定是否授權)
* birthday : 出生日期 (使用者可以決定是否授權)
* personal-id : 身分證字號/居留證號 (使用者可以決定是否授權)
* student-id : 學號 (學生才有, 使用者可以決定是否授權)
* academy-records : 學籍資料 (學生才有, 使用者可以決定是否授權)
* faculty-records : 教職員資料 (教職員才有, 使用者可.以決定是否授權)
* email : 電子郵件信箱 (使用者有在系統上登記才有, 使用者可以決定是否授權)
* mobile-phone : 行動電話號碼 (使用者有在系統上登記才有, 使用者可以決定是否授權)

### 原本使用 Portal 3g OAuth 的系統如何移轉

* 應用系統必需到新的 Portal 重新註用應用系統，會得到新的 ClientId/ClientSecret。
* 把原本 OAuth 相關模組指定的 URL 從 portal3g.ncu.edu.tw 改到 portal.ncu.edu.tw。
* 在取 Token 動作時，Portal 3G 並沒有要求要 Basic Auth，所以把 Basic Auth 補上，同時在 Header
加 Accept: application/json 是必要的。

比較要注意的是 id 的部份，因為改到 portal 之後，使用者的 64 位元長整數的 id
會變，但使用者的帳號 (identifier) 是一樣的，如果系統開發有參考 id 這個數值的話，需要做一點處理.

如果開發時，使用 GitHub 上的 [Portal3g-php-oauth](https://github.com/ncucc/portal3g-php-oauth)
的話，可以把 vendor/linuzilla/portal3g-php-oauth/src/NCUPortal.php 置換成這個
[NCUPortal.php](https://raw.githubusercontent.com/ncucc/portal4g-docs/master/NCUPortal.php)

### Samples

* [Java / Spring Framework](https://github.com/ncucc/portal4g-java-oauth-client)
* [PHP / Laravel](https://github.com/ncucc/laravel-socialite-portal-provider)
