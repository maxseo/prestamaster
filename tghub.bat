@echo off

call %~dp0TGHUB_BOT\venv\Scripts\activate

cd %~dp0TGHUB_BOT

set TOKEN=6110127126:AAGawBKPluFmeYwbJdfe4h7TRNAlwYzALOA

python tghub_bot.py

pause