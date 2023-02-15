<h3>{l s='Общая оценка'}</h3>
<div class="product-reviews-summary__head">
    <div class="product-reviews-summary__head-best">
        <div class="product-reviews-summary__head-title">{l s='Рекомендуют:' mod='pmreview'}</div>
        <div class="product-reviews-summary__head-value">{$five_percent}%</div>
    </div>
    <div class="product-reviews-summary__head-avarage">
        <span>{$product_rating}</span>
    </div>
    <div class="product-reviews-summary__head-count">
        <div class="product-reviews-summary__head-title">{l s='Отзывов:' mod='pmreview'}</div>
        <div class="product-reviews-summary__head-value">{$total_reviews}</div>
    </div>
</div>
<div class="product-reviews-summary__stars">
    {for $i=1 to 5}
        {*<img src="{$img_dir}icon/{if $i <= $product_rating_rounded}yellow{else}grey{/if}_star.svg">
         <i class="lni lni-star{if $i <= $product_rating_rounded}-filled{else}{/if}"></i>*}
         <svg version="1.1" id="Слой_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px"
        viewBox="0 0 15 14" style="width:14px;fill:{if $i <= $product_rating_rounded}#f8981d{else}#a9a9a9{/if}" xml:space="preserve">
            <g style="clip-path:url(#SVGID_00000039113184228437632860000003561846459438739086_);">
            <path class="st1" d="M6.8,0.5L5.1,3.9L1.2,4.5c-0.7,0.1-1,0.9-0.5,1.4l2.8,2.7l-0.7,3.8c-0.1,0.7,0.6,1.2,1.2,0.9l3.4-1.8l3.4,1.8
            	c0.6,0.3,1.3-0.2,1.2-0.9l-0.7-3.8l2.8-2.7c0.5-0.5,0.2-1.3-0.5-1.4L9.9,3.9L8.2,0.5C7.9-0.2,7.1-0.2,6.8,0.5L6.8,0.5z"/>
            </g>
        </svg>
    {/for}
</div>
<div class="product-reviews-summary__stars-detailed">
    <div class="product-reviews-summary__stars-detailed-line">
        <div class="product-reviews-summary__stars-detailed-progress">
            <div class="product-reviews-summary__stars-detailed-title">{l s='Рекомендую!' mod='pmreview'}</div>
            <div class="product-reviews-summary__stars-detailed-progress-wrap"><div class="product-reviews-summary__stars-detailed-progressbar" style="width: {$five_percent}%;"></div></div>
        </div>
        <div class="product-reviews-summary__stars-detailed-count">{$five}</div>
    </div>
    <div class="product-reviews-summary__stars-detailed-line">
        <div class="product-reviews-summary__stars-detailed-progress">
        <div class="product-reviews-summary__stars-detailed-title">{l s='Хорошо' mod='pmreview'}</div>
        <div class="product-reviews-summary__stars-detailed-progress-wrap"><div class="product-reviews-summary__stars-detailed-progressbar" style="width: {$four_percent}%;"></div></div>
        </div>
        <div class="product-reviews-summary__stars-detailed-count">{$four}</div>
    </div>
    <div class="product-reviews-summary__stars-detailed-line">
        <div class="product-reviews-summary__stars-detailed-progress">
        <div class="product-reviews-summary__stars-detailed-title">{l s='Я доволен' mod='pmreview'}</div>
        <div class="product-reviews-summary__stars-detailed-progress-wrap"><div class="product-reviews-summary__stars-detailed-progressbar" style="width: {$three_percent}%;"></div></div>
        </div>
        <div class="product-reviews-summary__stars-detailed-count">{$three}</div>
    </div>
    <div class="product-reviews-summary__stars-detailed-line">
        <div class="product-reviews-summary__stars-detailed-progress">
        <div class="product-reviews-summary__stars-detailed-title">{l s='Ну так себе' mod='pmreview'}</div>
        <div class="product-reviews-summary__stars-detailed-progress-wrap"><div class="product-reviews-summary__stars-detailed-progressbar" style="width: {$two_percent}%;"></div></div>
        </div>
        <div class="product-reviews-summary__stars-detailed-count">{$two}</div>
    </div>
    <div class="product-reviews-summary__stars-detailed-line">
        <div class="product-reviews-summary__stars-detailed-progress">
        <div class="product-reviews-summary__stars-detailed-title">{l s='Отстой' mod='pmreview'}</div>
        <div class="product-reviews-summary__stars-detailed-progress-wrap"><div class="product-reviews-summary__stars-detailed-progressbar" style="width: {$one_percent}%;"></div></div>
        </div>
        <div class="product-reviews-summary__stars-detailed-count">{$one}</div>
    </div>
</div>
