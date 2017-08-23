@echo off
php %~dp0yii week-check/big-balance >>E:\xampp\htdocs\tripclub\weekCheckLog.txt
php %~dp0yii week-check/fast-award >>E:\xampp\htdocs\tripclub\weekCheckLog.txt
php %~dp0yii week-check/week-close >>E:\xampp\htdocs\tripclub\weekCloseLog.txt
php %~dp0yii week-check/week-total >>E:\xampp\htdocs\tripclub\weekCloseLog.txt
exit