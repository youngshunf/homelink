@echo off
php %~dp0yii month-check/car-house >>E:\xampp\htdocs\tripclub\monthCheckLog.txt
php %~dp0yii month-check/little-balance >>E:\xampp\htdocs\tripclub\monthCheckLog.txt
php %~dp0yii month-check/wheel-award >>E:\xampp\htdocs\tripclub\monthCheckLog.txt
php %~dp0yii month-check/month-total >>E:\xampp\htdocs\tripclub\monthCheckLog.txt
exit