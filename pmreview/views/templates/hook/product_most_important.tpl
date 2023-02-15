{if $review_o->id}
    <section class="container">
        <h2>Самый полезный отзыв</h2>
        <div class="review-important">
            <div class="review-top-row mb-2 d-flex justify-content-between align-items-center">
                <div class="d-flex flex-column">
                    <p class="pmreview-fio m-0">{$review_o->fio}</p>
                </div>
                <div class="d-flex justify-content-end align-items-center">
                    <p class="pmreview-date d-none d-md-block m-0 mr-2">{$review_o->date_add}</p>
                    <img src="{$img_dir}/icon/yellow_star.svg">
                    <p class="pmreview-rating m-0">{$review_o->rating}</p>
                </div>
            </div>
            {if $review_o->pluses}<p class="pmreview-review"><span class="d-block">Достоинства:</span> {$review_o->pluses}</p>{/if}
            {if $review_o->minuses}<p class="pmreview-review"><span class="d-block">Недостатки: </span>{$review_o->minuses}</p>{/if}
            {if $review_o->review}<p class="pmreview-review" itemprop="reviewBody"><span class="d-block">Комментарий:</span> {$review_o->review}</p>{/if}
            {if $review_o->images[0]}
            <div class="d-flex justify-content-between mt-3 mb-3 d-md-none">
                <div class="review-images">
                    {foreach from=$review_o->images item = image}
                        {assign var=img_path value="$review_img_dir`$image`"}
                        {if file_exists($img_path) && $image}
                            <a href="{$reviews_img_path}{$image}"><img src="{$reviews_img_path}{$image}"></a>
                        {/if}
                    {/foreach}
                </div>
                {assign var=count_images value=$review_o->images|@count}
                {if $count_images > 3}
                    <a href="#" class="show_more_review_images justify-content-center d-flex align-items-center text-center">+{$count_images - 3}</a>
                {/if}
            </div>
            <div class="d-none justify-content-between mt-3 mb-3 d-md-flex">
                <div class="review-images">
                    {foreach from=$review_o->images item = image}
                        {assign var=img_path value="$review_img_dir`$image`"}
                        {if file_exists($img_path) && $image}
                            <a href="{$reviews_img_path}{$image}"><img src="{$reviews_img_path}{$image}"></a>
                        {/if}
                    {/foreach}
                </div>
                {assign var=count_images value=$review_o->images|@count}
                {if $count_images > 5}
                    <a href="#" class="show_more_review_images justify-content-center d-flex align-items-center text-center">+{$count_images - 5}</a>
                {/if}
            </div>
            {/if}
            <p class="pmreview-date d-md-none">{$review_o->date_add}</p>
            <div class="pmreviewe-likes d-flex justify-content-md-start justify-content-end" data-id_review="{$review_o->id}" data-url="{$pmreview_contr_url}">
                <span class="pmreview-like mr-3">{$review_o->likes}</span>
                <span class="pmreview-dislike">{$review_o->dislikes}</span>
            </div>
        </div>
        <a href="#" class="show_all_reviews">Все отзывы</a>
    </section>
{/if}