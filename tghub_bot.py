import asyncio
import aiogram
import openpyxl

# Load bot token from file
with open('bot_token.txt', 'r') as f:
    bot_token = f.read().strip()

# Create bot and event loop
bot = aiogram.Bot(token=bot_token)
loop = asyncio.get_event_loop()

# Load channel IDs from file
with open('channels.txt', 'r') as f:
    channel_ids = [line.strip() for line in f]

# Create workbook and worksheet
wb = openpyxl.Workbook()
ws = wb.active

# Write header row to worksheet
ws.cell(row=1, column=1, value='Channel ID')
ws.cell(row=1, column=2, value='Latest Posts')

# Define function to collect latest posts for a channel
async def collect_latest_posts(channel_id):
    try:
        messages = await bot.get_history(chat_id=channel_id, limit=5)

        # Write the posts to a CSV file
        with open('posts.csv', 'a', encoding='utf-8', newline='') as file:
            writer = csv.writer(file)
            row = channel_ids.index(channel_id) + 2
            for message in messages:
                text = message.text
                if message.photo:
                    photo_url = message.photo[-1].file_id
                    text = f'<a href="https://t.me/c/{channel_id}/{message.message_id}">&#8205;</a>\n<a href="{photo_url}">📷</a>\n{text}'
                elif message.video:
                    video_url = message.video.file_id
                    text = f'<a href="https://t.me/c/{channel_id}/{message.message_id}">&#8205;</a>\n<a href="{video_url}">🎥</a>\n{text}'
                else:
                    text = f'<a href="https://t.me/c/{channel_id}/{message.message_id}">{text}</a>'

                writer.writerow([row, text])
                row += 1

    except aiogram.exceptions.TelegramAPIError as e:
        # Handle errors
        print(f'Error collecting data for channel ID {channel_id}: {e}')

# Collect latest posts for each channel
async def main():
    for channel_id in channel_ids:
        await collect_latest_posts(channel_id)

# Run event loop and save workbook to file
if __name__ == '__main__':
    loop.run_until_complete(main())
    wb.save('latest_posts.xlsx')
