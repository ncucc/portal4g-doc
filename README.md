# portal4g-doc
中央大學第四代 Portal (2020 年版) 文件

中央大學第四代 Portal 單一簽入的方式有四種，包括偉康協定 (第一代，制式), OpenID (第二代), OAuth (Portal3g), SimpleAuth (第四代，制式）等。其中，我們建議新的系統採 OAuth 的方式做單一簽入，其它的模式僅供相容及特殊應用使用，所以本文件的內容以介紹如何使用 OAuth 為主。

中央大學第四代 Portal 在 OAuth 的實作上採 RFC 6749 The OAuth 2.0 Authorization Framework 的標準，實現的部份為 Authorization Code Grant 的流程。

## Samples

* [Java/Spring Boot](https://github.com/ncucc/portal4g-java-oauth-client)
