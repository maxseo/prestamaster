{$colors = ['#edf4fa']}
{foreach from = $pmreviews item = review}
    <div class="product-reviews__review" itemprop="reviewRating" itemscope itemtype="http://schema.org/Rating">
        <div class="product-review-head">
            {assign var=randomindex value=$colors|@array_rand}
            <div class="product-review-head__avatar" style="background-color:{$colors[$randomindex]}">
                <span>{if $review.fio}{$review.fio|substr:0:1}{elseif $review.customer->firstname}{$review.customer->firstname|mb_substr:0:1}{else}A{/if}</span>
            </div>
            <div class="product-review-head__user">
                <div class="product-review-head__name" itemprop="author">{if $review.fio}{$review.fio}{elseif $review.customer->firstname}{$review.customer->firstname}{else}{l s='Anonim' mod='pmreview'}{/if}</div>
                {if $review.customer->id_default_group == $customer_group}
                <div class="product-review-head__verify">
                    <i class="lni lni-checkmark"></i> {l s='Клиент' mod='pmreview'} {$shop.name}
                </div>
                {/if}
            </div>
            <div class="product-review-head__date">{$review.date_add}</div>
            <div class="product-review-head__rating" >
                <meta itemprop="worstRating" content = "0">    
                {section name=star loop=$review.rating}
                    {*<i class="lni lni-star-filled"></i>*}
                    <svg version="1.1" id="Слой_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px"
                    viewBox="0 0 15 14" style="width:14px;fill:#f8981d" xml:space="preserve">
                        <g style="clip-path:url(#SVGID_00000039113184228437632860000003561846459438739086_);">
                        <path class="st1" d="M6.8,0.5L5.1,3.9L1.2,4.5c-0.7,0.1-1,0.9-0.5,1.4l2.8,2.7l-0.7,3.8c-0.1,0.7,0.6,1.2,1.2,0.9l3.4-1.8l3.4,1.8
                            c0.6,0.3,1.3-0.2,1.2-0.9l-0.7-3.8l2.8-2.7c0.5-0.5,0.2-1.3-0.5-1.4L9.9,3.9L8.2,0.5C7.9-0.2,7.1-0.2,6.8,0.5L6.8,0.5z"/>
                        </g>
                    </svg>
                {/section}
                {section name=star loop=(5-$review.rating)}
                    {*<i class="lni lni-star"></i>*}
                    <svg version="1.1" id="Слой_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px"
                    viewBox="0 0 15 14" style="width:14px;fill:#a9a9a9" xml:space="preserve">
                        <g style="clip-path:url(#SVGID_00000039113184228437632860000003561846459438739086_);">
                        <path class="st1" d="M6.8,0.5L5.1,3.9L1.2,4.5c-0.7,0.1-1,0.9-0.5,1.4l2.8,2.7l-0.7,3.8c-0.1,0.7,0.6,1.2,1.2,0.9l3.4-1.8l3.4,1.8
                            c0.6,0.3,1.3-0.2,1.2-0.9l-0.7-3.8l2.8-2.7c0.5-0.5,0.2-1.3-0.5-1.4L9.9,3.9L8.2,0.5C7.9-0.2,7.1-0.2,6.8,0.5L6.8,0.5z"/>
                        </g>
                    </svg>
                {/section}
                {*<span class="product-review-head__stars" itemprop="ratingValue">{$review.rating}</span>*}
                <meta itemprop="bestRating" content = "5">
            </div>    
        </div> 
        
		<div class="product-review-info__wrapper">
		<div class="product-review-info">            
            {if $review.product}
                <img src="{$link->getImageLink($review.product->link_rewrite, $review.product->cover.id_image, 'small_default')}"><a href="{$link->getProductLink($review.product)}" class="prod-title">{$review.product->name}</a>
            {/if}
        </div>
        
        <div class="product-review-body">
            <div class="product-review-body__wrapper">
                {if $review.pluses}
                    <h5>Достоинства</h5>
                    <div class="product-review-body__text">{$review.pluses}</div>
                {/if}
                {if $review.minuses}
                    <h5>Недостатки</h5>
                    <div class="product-review-body__text">{$review.minuses}</div>
                {/if}
                {if $review.review}
                    <h5>{l s='Комментарий' mod='pmreview'}</h5>
                    <div class="product-review-body__text" itemprop="reviewBody">{$review.review|cleanHtml nofilter}</div>
                {/if}
                <meta itemprop="datePublished" content="{$review.date_add}">
                
                {if $review.images[0]}
                    <div class="product-review-body__images-wrapper">
                        <div class="product-review-body__images">
                            {foreach from=$review.images item = image}
                                {assign var=img_path value="$review_img_dir`$image`"}
                                {if file_exists($img_path) && $image}
                                    <a {if $image|strstr:".mp4" || $image|strstr:".m4v" || $image|strstr:".f4v" || $image|strstr:".Irv"}class="video-link"{/if} href="{$reviews_img_path}{$image}">
                                    {if $image|strstr:".mp4" || $image|strstr:".m4v" || $image|strstr:".f4v" || $image|strstr:".Irv"}
                                        <div class="video-popup">
                                            <video preload="false" poster="/videos/01.png">
                                                <source src="{$reviews_img_path}{$image}" type="video/mp4">
                                            </video>
                                            <div class="video-icon">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" class="xw3"><path fill="currentColor" d="M14.286 11c.229 0 .458.06.661.178l6.429 3.75c.387.226.624.633.624 1.072 0 .44-.237.846-.624 1.072l-6.429 3.75a1.313 1.313 0 0 1-1.295.016A1.244 1.244 0 0 1 13 19.75v-7.5c0-.45.25-.866.652-1.088.197-.108.415-.162.634-.162Z"></path><path fill="currentColor" d="M1 16C1 7.716 7.716 1 16 1c8.284 0 15 6.716 15 15 0 8.284-6.716 15-15 15-8.284 0-15-6.716-15-15ZM16 3C8.82 3 3 8.82 3 16s5.82 13 13 13 13-5.82 13-13S23.18 3 16 3Z"></path></svg>
                                            </div>
                                        </div>
                                    {else}
                                        <a href="{$reviews_img_path}{$image}"><img src="{$reviews_img_path}{$image}"></a>
                                    {/if}
                                    </a>
                                {/if}
                            {/foreach}
                        </div>
                        {assign var=count_images value=$review.images|@count}
                        {if $count_images > 5}
                            <a href="#" class="show_more_review_images">+{$count_images - 5}</a>
                        {/if}
                    </div>
                {/if}
            
    
                {if $review.answer->id}
                    <div class="product-review-body__answer">
                        
                        <div class="product-review-body__answer-wrapper">
                            <img src="{$shop.logo|escape:'htmlall':'UTF-8'}" width="50" height="50" alt="{l s='Admin answer' mod='pmreview'}">
                            <h5>{l s='Ответ магазина' mod='pmreview'}</h5>
                            <div class="product-review-body__date">{$review.answer->date_add}</div>
                        </div>
                        <div class="product-review-body__text">{$review.answer->review}</div>
                        
                        {if $review.answer->images[0]}
                            <div class="product-review-body__answer-wrapper">
                                <div class="product-review-body__images">
                                    {foreach from=$review.answer->images item = image}
                                        {assign var=img_path value="$review_img_dir`$image`"}
                                        {if file_exists($img_path) && $image}
                                            <a href="{$reviews_img_path}{$image}"><img src="{$reviews_img_path}{$image}"></a>
                                        {/if}
                                    {/foreach}
                                </div>
                                {assign var=count_images value=$review.answer->images|@count}
                                {if $count_images > 3}
                                    <a href="#" class="show_more_review_images">+{$count_images - 3}</a>
                                {/if}
                            </div>
                        {/if}
                        <div class="product-review__answer-footer">
                            <div class="product-review-likes" data-id_review="{$review.answer->id}" data-url="{$pmreview_contr_url}">
                                <span class="pmreview-like"><i class="lni lni-thumbs-up"></i> <span>{$review.likes}</span></span>
                                <span class="pmreview-dislike"><i class="lni lni-thumbs-down"></i> <span>{$review.dislikes}</span></span>
                            </div>
                        </div>
                    </div>
                {/if}
            </div>
			</div>
        </div>
        <div class="product-review-footer">
            <div class="product-review-likes" data-id_review="{$review.id_pm_review}" data-url="{$pmreview_contr_url}">
                <span>{l s='Вам помог этот отзыв?' mod='pmreview'}</span>
                <span class="pmreview-like"><i class="lni lni-thumbs-up"></i> <span>{$review.likes}</span></span>
                <span class="pmreview-dislike"><i class="lni lni-thumbs-down"></i> <span>{$review.dislikes}</span></span>
            </div>
        </div>
    </div>
{/foreach}