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
 
<div class="productOpinions row">
    <div class="productOpinions__commentsBody col">
        <div class="comments">
            {if isset($reviews) && $reviews}
            <div class="comments__wrapper">
                <ul class="comments__list">
                    {foreach from=$reviews item=review}
                        <li class="comments__item">
                            <div class="comments__itemContent">
                                <div class="comments__head">
                                    <div class="comments__info">
                                        <div class="comments__photo">
                                            {if $review['id_customer'] == 0}
                                                <span class="imagePlaceholder__typePhoto imagePlaceholder">
                                                    <i class="material-icons">face</i>
                                                </span>
                                            {else}
                                                <span class="imagePlaceholder__typePhoto imagePlaceholder">
                                                    <i class="material-icons">face</i>
                                                </span>
                                            {/if}
                                        </div>
                                        <div>
                                            <span class="comments__name">{if $review['id_customer'] == 0}{l s='Anonymous user' mod='pmreview'}{else}{$review['customer']->firstname} {$review['customer']->lastname}{/if}</span>
                                            <span class="comments__date">
                                                <i class="material-icons">edit</i>
                                                <span>{$review['formatted_date']}</span>
                                            </span>
                                        </div>
                                    </div>
                                    <div class="comments__rating">
                                        <div class="rating rating__themeWhite rating__starsGreen">
                                            <div class="rating__stars"><span class="rating__progress"><span class="rating__bar" style="width: {$review['rating']*20}%;"></span><span class="rating__mask"></span></span></div>
                                            <div class="rating__text">
                                                {if $review['rating'] == 5}
                                                    {l s='Great, I advise' mod='pmreview'}
                                                {elseif $review['rating'] == 4}
                                                    {l s='Good' mod='pmreview'}
                                                {elseif $review['rating'] == 3}
                                                    {l s='Normal' mod='pmreview'}
                                                {elseif $review['rating'] == 2}
                                                    {l s='Satisfactorily' mod='pmreview'}
                                                {elseif $review['rating'] == 1}
                                                    {l s='Awful' mod='pmreview'}
                                                {/if}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="comments__body">
                                    {$review['review']}
                                </div>
                            </div>
                        </li>
                    {/foreach}
                </ul>
            </div>
            {/if}
            {*<div class="more">
                <span class="more__link">
                    <span>{l s='View more' mod='pmreview'}</span>
                    <svg class="more__linkIcon" viewBox="0 0 15 16">
                    </svg>
                </span>
            </div>*}
            <div class="comments__addHolder">
                <div class="comments__productForm">
                    <div class="comments__formHeader">
                        <div class="comments__formRating">
                            <div class="comments__formRatingCaption">{l s='Your rate' mod='pmreview'}</div>
                            <div class="ratingForm">
                                <div class="ratingForm__progress">
                                    <span class="ratingForm__bar" style="width: 0%;"></span>
                                    <span class="ratingForm__mask"></span>
                                    <span class="ratingForm__button"></span>
                                    <span class="ratingForm__button"></span>
                                    <span class="ratingForm__button"></span>
                                    <span class="ratingForm__button"></span>
                                    <span class="ratingForm__button"></span>
                                </div>
                                <span class="ratingForm__text"></span>
                            </div>
                        </div>
                        {*
                        <div class="comments__formAnonymous">
                            <label class="checkbox">
                                <input name="anonymous" class="checkbox__input" value="anonymous" type="checkbox">
                                <span class="checkbox__fakeInput"></span>
                                <span>{l s='Send review anonymously' mod='pmreview'}</span>
                            </label>
                        </div>
                        *}
                    </div>
                    <div class="comments__formHolder">
                        <textarea name="comment" class="textarea" placeholder="Your comment..."></textarea>
                        <div class="comments__formControls">
                            <span class="comments__mainButton">
                                <button data-id_product="{$id_product}" data-url="{url entity='module' name='pmreview' controller='review'}" class="button button__fullWidth button__withShadow pm_send_review" type="button">
                                    <span class="button__caption">
                                        <span class="comments__buttonContent">
                                            <span>{l s='Send review' mod='pmreview'}</span>
                                        </span>
                                    </span>
                                </button>
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    
    {if isset($total_reviews) && $total_reviews > 0}
    <div class="productOpinions__aside col hidden-md-down">
        <div class="productOpinions__total">
            <span class="productOpinions__totalTitle">{l s='Average product rating' mod='pmreview'}</span><span class="productOpinions__totalCount">{$product_rating} {l s='from 5' mod='pmreview'}</span><span class="productOpinions__totalText">{l s='Total reviews' mod='pmreview'} {$total_reviews}</span>
            <span class="productOpinions__totalStars">
                <div class="rating rating__sizeBig rating__themeWhite rating__starsGreen">
                    <div class="rating__stars"><span class="rating__progress"><span class="rating__bar" style="width: {$product_raiting_percent}%;"></span><span class="rating__mask"></span></span></div>
                </div>
            </span>
        </div>
        <ul class="productOpinions__stats">
            <li class="productOpinions__statsItem">
                <span class="productOpinions__statsStars">
                    <div class="rating rating__sizeSmall rating__themeWhite rating__starsGreen">
                        <div class="rating__stars"><span class="rating__progress"><span class="rating__bar" style="width: 100%;"></span><span class="rating__mask"></span></span></div>
                    </div>
                </span>
                <span class="productOpinions__statsProgress"><span class="productOpinions__statsProgressBar" style="width: {$five_percent}%;"></span></span><span class="productOpinions__statsText">{$five_percent}% {l s='votes' mod='pmreview'}</span>
            </li>
            <li class="productOpinions__statsItem">
                <span class="productOpinions__statsStars">
                    <div class="rating rating__sizeSmall rating__themeWhite rating__starsGreen">
                        <div class="rating__stars"><span class="rating__progress"><span class="rating__bar" style="width: 80%;"></span><span class="rating__mask"></span></span></div>
                    </div>
                </span>
                <span class="productOpinions__statsProgress"><span class="productOpinions__statsProgressBar" style="width: {$four_percent}%;"></span></span><span class="productOpinions__statsText">{$four_percent}% {l s='votes' mod='pmreview'}</span>
            </li>
            <li class="productOpinions__statsItem">
                <span class="productOpinions__statsStars">
                    <div class="rating rating__sizeSmall rating__themeWhite rating__starsGreen">
                        <div class="rating__stars"><span class="rating__progress"><span class="rating__bar" style="width: 60%;"></span><span class="rating__mask"></span></span></div>
                    </div>
                </span>
                <span class="productOpinions__statsProgress"><span class="productOpinions__statsProgressBar" style="width: {$three_percent}%;"></span></span><span class="productOpinions__statsText">{$three_percent}% {l s='votes' mod='pmreview'}</span>
            </li>
            <li class="productOpinions__statsItem">
                <span class="productOpinions__statsStars">
                    <div class="rating rating__sizeSmall rating__themeWhite rating__starsGreen">
                        <div class="rating__stars"><span class="rating__progress"><span class="rating__bar" style="width: 40%;"></span><span class="rating__mask"></span></span></div>
                    </div>
                </span>
                <span class="productOpinions__statsProgress"><span class="productOpinions__statsProgressBar" style="width: {$two_percent}%;"></span></span><span class="productOpinions__statsText">{$two_percent}% {l s='votes' mod='pmreview'}</span>
            </li>
            <li class="productOpinions__statsItem">
                <span class="productOpinions__statsStars">
                    <div class="rating rating__sizeSmall rating__themeWhite rating__starsGreen">
                        <div class="rating__stars"><span class="rating__progress"><span class="rating__bar" style="width: 20%;"></span><span class="rating__mask"></span></span></div>
                    </div>
                </span>
                <span class="productOpinions__statsProgress"><span class="productOpinions__statsProgressBar" style="width: {$one_percent}%;"></span></span><span class="productOpinions__statsText">{$one_percent}% {l s='votes' mod='pmreview'}</span>
            </li>
        </ul>
        
    </div>
    {/if}
  {*  *}
</div>
