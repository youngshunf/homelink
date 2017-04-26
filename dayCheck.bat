@echo off
php %~dp0yii day-check/user-status >>E:\xampp\htdocs\tripclub\dayCheckLog.txt
php %~dp0yii day-check/sales >>E:\xampp\htdocs\tripclub\dayCheckLog.txt
php %~dp0yii day-check/update-user-rank >>E:\xampp\htdocs\tripclub\dayCheckLog.txt
exit