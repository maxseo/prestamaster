from aiogram import Bot, types
from aiogram.dispatcher import Dispatcher
from aiogram.utils import executor

import os

bot = Bot(token=os.getenv('TOKEN'))
dp = Dispatcher(bot)

async def on_startup(_):
    print('Я в онлайне')

'''****Client***'''
@dp.message_handler(commands=['start','help'])
async def command_start(message : types.Message):
    try:
        await bot.send_message(message.from_user.id, 'Ништяк')
        await message.delete()
    except:
        await message.reply('Общение с ботом через ЛС\nссылка')

@dp.message_handler(commands=['Режим работы'])
async  def bot_open_command(message : types.Message):
    await bot.send_message(message.from_user.id, '24/7')

@dp.message_handler(commands=['Реклама'])
async  def bot_adv_command(message : types.Message):
    await bot.send_message(message.from_user.id, 'Напишите админу')

@dp.message_handler(commands=['Меню'])
async  def bot_menu_command(message : types.Message):
    for ret in cur.execute('SELECT * FROM menu').fetchall():
        await bot.send_photo(message.from_user.id, ret[0], f'{ret[1]}\nОписание: {ret[2]}\nЦена: {ret[-1]}')

@dp.message_handler()
async def echo_send(message : types.Message):
    if message.text == 'Прив':
        await message.answer('На' +message.text + 'обед')
    # await message.reply(message.text)
    # await bot.send_message(message.from_user.id, message.text)


executor.start_polling(dp, skip_updates=True, on_startup=on_startup)