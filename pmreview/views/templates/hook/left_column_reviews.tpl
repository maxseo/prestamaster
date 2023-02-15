{$i=0}
{foreach from = $pmreviews item = review}
    {if $i <= 5}
    <a href="{$link->getProductLink($review.product)}">
    <div class="product-reviews__review" itemprop="reviewRating" itemscope itemtype="http://schema.org/Rating">
        <div class="product-review-head">
            {assign var=randomindex value=$colors|@array_rand}
            <div class="product-review-head__image">
                <img src="{$link->getImageLink($review.product->link_rewrite, $review.product->cover.id_image, 'small_default')}">
            </div>
            <div class="product-review-head__user">
                <div class="product-review-head__name" itemprop="author">{if $review.fio}{$review.fio}{else}{$review.customer->firstname}{/if}</div>
                <div class="product-review-head__date">{$review.date_add}</div>
                <div class="product-review-head__rating" >
                    <meta itemprop="worstRating" content = "0">    
                    {section name=star loop=$review.rating}
                        <i class="lni lni-star-filled"></i>
                    {/section}
                    {section name=star loop=(5-$review.rating)}
                        <i class="lni lni-star"></i>
                    {/section}
                    {*<span class="product-review-head__stars" itemprop="ratingValue">{$review.rating}</span>*}
                    <meta itemprop="bestRating" content = "5">
                </div>  
            </div>
        </div> 
        <div class="product-review-info">
            {if $review.product}
                <div class="product-review-info__name">{$review.product->name}</div>
            {/if}
        </div>
        
        <div class="product-review-body">
            <div class="product-review-body__wrapper">
                {if $review.review}
                    <div class="product-review-body__text" itemprop="reviewBody">{$review.review|cleanHtml nofilter}</div>
                {/if}
                <meta itemprop="datePublished" content="{$review.date_add}">
            </div>
        </div>
    </div>
    </a>
    {/if}
    {$i=$i+1}
{/foreach}