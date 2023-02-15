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
        # Get the latest 5 messages from the channel
        messages = await bot.get_messages(chat_id=channel_id, limit=5)

        # Format the messages as HTML
        posts = []
        for message in messages:
            if message.photo:
                post_content = f'<a href="{message.link}">&#8205;</a><a href="{message.photo[-1].file_id}">&#8205;</a>'
            elif message.video:
                post_content = f'<a href="{message.link}">&#8205;</a><a href="{message.video.file_id}">&#8205;</a>'
            else:
                post_content = message.text_html
            posts.append(f'<div class="tg-post">{post_content}</div>')

        # Return the formatted posts
        return posts

                # Write channel ID and latest post to worksheet
                row = channel_ids.index(channel_id) + 2
                ws.cell(row=row, column=1, value=channel_id)
                ws.cell(row=row, column=2, value=post_html)
                break

    except aiogram.exceptions.TelegramAPIError:
        # Handle errors
        print('Error collecting data for channel ID {}'.format(channel_id))


# Collect latest posts for each channel
async def main():
    for channel_id in channel_ids:
        await collect_latest_posts(channel_id)


# Run event loop and save workbook to file
if __name__ == '__main__':
    loop.run_until_complete(main())
    wb.save('latest_posts.xlsx')
