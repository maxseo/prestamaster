{**
 * 2012 - 2020 Prestashop Master
 *
 * MODULE Customers Reviews
 *
 * @author    Prestashop Master <dev@prestashopmaster.com>
 * @copyright Copyright (c) permanent, Prestashop Master
 * @license   https://opensource.org/licenses/GPL-3.0  GNU General Public License version 3
 * @version   1.0.0
 * @link      https://www.prestashopmaster.com
 * NOTICE OF LICENSE
 *
 * Don't use this module on several shops. The license provided by PrestaShop Master
 * for all its modules is valid only once for a single shop.
 *}

{**
 * 2012 - 2020 Prestashop Master
 *
 * MODULE Customers Reviews
 *
 * @author    Prestashop Master <dev@prestashopmaster.com>
 * @copyright Copyright (c) permanent, Prestashop Master
 * @license   https://opensource.org/licenses/GPL-3.0  GNU General Public License version 3
 * @version   1.0.0
 * @link      https://www.prestashopmaster.com
 * NOTICE OF LICENSE
 *
 * Don't use this module on several shops. The license provided by PrestaShop Master
 * for all its modules is valid only once for a single shop.
 *}

<div class="product-minireviews">
    <div class="product-minireviews__icons {if $total_reviews}w120{/if}">
        <svg version="1.1" id="Слой_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px"
        viewBox="0 0 15 14" style="width:14px;fill:{if $product_raiting_percent > 10}#f8981d{else}#a9a9a9{/if}" xml:space="preserve">
            <g style="clip-path:url(#SVGID_00000039113184228437632860000003561846459438739086_);">
            <path class="st1" d="M6.8,0.5L5.1,3.9L1.2,4.5c-0.7,0.1-1,0.9-0.5,1.4l2.8,2.7l-0.7,3.8c-0.1,0.7,0.6,1.2,1.2,0.9l3.4-1.8l3.4,1.8
            	c0.6,0.3,1.3-0.2,1.2-0.9l-0.7-3.8l2.8-2.7c0.5-0.5,0.2-1.3-0.5-1.4L9.9,3.9L8.2,0.5C7.9-0.2,7.1-0.2,6.8,0.5L6.8,0.5z"/>
            </g>
        </svg>
         <svg version="1.1" id="Слой_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px"
        viewBox="0 0 15 14" style="width:14px;fill:{if $product_raiting_percent > 30}#f8981d{else}#a9a9a9{/if}" xml:space="preserve">
            <g style="clip-path:url(#SVGID_00000039113184228437632860000003561846459438739086_);">
            <path class="st1" d="M6.8,0.5L5.1,3.9L1.2,4.5c-0.7,0.1-1,0.9-0.5,1.4l2.8,2.7l-0.7,3.8c-0.1,0.7,0.6,1.2,1.2,0.9l3.4-1.8l3.4,1.8
            	c0.6,0.3,1.3-0.2,1.2-0.9l-0.7-3.8l2.8-2.7c0.5-0.5,0.2-1.3-0.5-1.4L9.9,3.9L8.2,0.5C7.9-0.2,7.1-0.2,6.8,0.5L6.8,0.5z"/>
            </g>
        </svg>
         <svg version="1.1" id="Слой_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px"
        viewBox="0 0 15 14" style="width:14px;fill:{if $product_raiting_percent > 50}#f8981d{else}#a9a9a9{/if}" xml:space="preserve">
            <g style="clip-path:url(#SVGID_00000039113184228437632860000003561846459438739086_);">
            <path class="st1" d="M6.8,0.5L5.1,3.9L1.2,4.5c-0.7,0.1-1,0.9-0.5,1.4l2.8,2.7l-0.7,3.8c-0.1,0.7,0.6,1.2,1.2,0.9l3.4-1.8l3.4,1.8
            	c0.6,0.3,1.3-0.2,1.2-0.9l-0.7-3.8l2.8-2.7c0.5-0.5,0.2-1.3-0.5-1.4L9.9,3.9L8.2,0.5C7.9-0.2,7.1-0.2,6.8,0.5L6.8,0.5z"/>
            </g>
        </svg>
         <svg version="1.1" id="Слой_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px"
        viewBox="0 0 15 14" style="width:14px;fill:{if $product_raiting_percent > 70}#f8981d{else}#a9a9a9{/if}" xml:space="preserve">
            <g style="clip-path:url(#SVGID_00000039113184228437632860000003561846459438739086_);">
            <path class="st1" d="M6.8,0.5L5.1,3.9L1.2,4.5c-0.7,0.1-1,0.9-0.5,1.4l2.8,2.7l-0.7,3.8c-0.1,0.7,0.6,1.2,1.2,0.9l3.4-1.8l3.4,1.8
            	c0.6,0.3,1.3-0.2,1.2-0.9l-0.7-3.8l2.8-2.7c0.5-0.5,0.2-1.3-0.5-1.4L9.9,3.9L8.2,0.5C7.9-0.2,7.1-0.2,6.8,0.5L6.8,0.5z"/>
            </g>
        </svg>
         <svg version="1.1" id="Слой_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px"
        viewBox="0 0 15 14" style="width:14px;fill:{if $product_raiting_percent > 90}#f8981d{else}#a9a9a9{/if}" xml:space="preserve">
            <g style="clip-path:url(#SVGID_00000039113184228437632860000003561846459438739086_);">
            <path class="st1" d="M6.8,0.5L5.1,3.9L1.2,4.5c-0.7,0.1-1,0.9-0.5,1.4l2.8,2.7l-0.7,3.8c-0.1,0.7,0.6,1.2,1.2,0.9l3.4-1.8l3.4,1.8
            	c0.6,0.3,1.3-0.2,1.2-0.9l-0.7-3.8l2.8-2.7c0.5-0.5,0.2-1.3-0.5-1.4L9.9,3.9L8.2,0.5C7.9-0.2,7.1-0.2,6.8,0.5L6.8,0.5z"/>
            </g>
        </svg>
    </div>
    {if $total_reviews}
        <svg version="1.1" id="Слой_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px"
            viewBox="0 0 14 14" style="width:14px;fill:#f8981d" xml:space="preserve">
            <path class="st0" d="M2.3,10.4c0.1,0.1,0.2,0.2,0.2,0.3c0,0.1,0.1,0.3,0,0.4c-0.1,0.6-0.2,1.2-0.3,1.8c1.2-0.3,2-0.6,2.3-0.8
            C4.7,12,5,12,5.2,12c0.6,0.2,1.2,0.2,1.8,0.2c3.5,0,6.1-2.5,6.1-5.2c0-2.8-2.6-5.2-6.1-5.2S0.9,4.2,0.9,7C0.9,8.3,1.4,9.5,2.3,10.4z
            M1.9,13.8c-0.2,0-0.4,0.1-0.6,0.1C1.1,14,1,13.8,1,13.6c0.1-0.2,0.1-0.4,0.2-0.6l0,0c0.2-0.6,0.4-1.4,0.5-2C0.7,9.9,0,8.5,0,7
            c0-3.4,3.1-6.1,7-6.1c3.9,0,7,2.7,7,6.1c0,3.4-3.1,6.1-7,6.1c-0.7,0-1.4-0.1-2.1-0.3C4.5,13.1,3.5,13.5,1.9,13.8L1.9,13.8z"/>
        </svg>
        {$total_reviews}
        <div class="product-minireviews__stats">
            {if isset($total_reviews) && $total_reviews > 0}
                <div class="product-minireviews__stats-sum">{l s='Average' mod='pmreview'} {$product_rating} {l s='from 5' mod='pmreview'}</div>
                <div class="product-minireviews__stats-total">{l s='Total reviews' mod='pmreview'} {$total_reviews}</div>
                
                <div class="product-minireviews__stats-details">
                    <div class="product-minireviews__stats-stars">
                        <div>{l s='5 star' mod='pmreview'}</div>
                        <div class="product-minireviews__stats-progress">
                            <div class="product-minireviews__stats-progressbar" style="width: {$five_percent}%;"></div>
                        </div>
                        <div class="product-minireviews__stats-text">{$five_percent}%</div>
                    </div>
                    <div class="product-minireviews__stats-stars">
                        <div>{l s='4 star' mod='pmreview'}</div>
                        <div class="product-minireviews__stats-progress">
                            <div class="product-minireviews__stats-progressbar" style="width: {$four_percent}%;"></div>
                        </div>
                        <div class="product-minireviews__stats-text">{$four_percent}%</div>
                    </div>
                    <div class="product-minireviews__stats-stars">
                        <div>{l s='3 star' mod='pmreview'}</div>
                        <div class="product-minireviews__stats-progress">
                            <div class="product-minireviews__stats-progress-Bar" style="width: {$three_percent}%;"></div>
                        </div>
                        <div class="product-minireviews__stats-text">{$three_percent}%</div>
                    </div>
                    <div class="product-minireviews__stats-stars">
                        <div>{l s='2 star' mod='pmreview'}</div>
                        <div class="product-minireviews__stats-progress">
                            <div class="product-minireviews__stats-progressbar" style="width: {$two_percent}%;"></div>
                        </div>
                        <div class="product-minireviews__stats-text">{$two_percent}%</div>
                    </div>
                    <div class="product-minireviews__stats-stars">
                        <div>{l s='1 star' mod='pmreview'}</div>
                        <div class="product-minireviews__stats-progress">
                            <div class="product-minireviews__stats-progress-Bar" style="width: {$one_percent}%;"></div>
                        </div>
                        <div class="product-minireviews__stats-text">{$one_percent}%</div>
                    </div>
                </div>    
            {/if}
        </div>
    {/if}
</div>