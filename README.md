# ALION 後端面試題目

## 目標

製作登入+書籍 API，並依照 [API 文檔](https://app.swaggerhub.com/apis-docs/COURTDREAM3/bookstore-api/1.0.0)，實作出 api。

## 概要

1. 登入之後才可以***新增 Book***，並且 Book 的擁有者，才有權限進行***編輯***跟***刪除***。
2. 回傳相應的 http status code。
3. 使用 resources 或任何方式來整理 response 的資料。
4. 可使用 Laravel 8 以上的版本。
5. 請自行設計 DB 的 table 結構，只有使用 uuid 的需求。
6. 完成後，請提供 Github 連結。
7. 紀錄編輯 book 的 log