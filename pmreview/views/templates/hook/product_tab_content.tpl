<div id="pmreview_tab" class="tab-pane">
    {if isset($total_reviews) && $total_reviews > 0}
        <p class="pmreview-total_orders-row mb-4">{l s='Всего отзывов:' mod='pmreview'} <span>{$count_reviews}</span></p>
        <a href="#" data-url="{$pmreview_contr_url}" data-id_product="{$pmreview_id_product}" class="btn btn-primary d-lg-none d-block pmreview_get_popup">{l s='Написать отзыв' mod='pmreview'}</a>
        
        <div class="row">
            <div class="product-reviews col-12">
                {include file='./reviews.tpl'}
                <div class="product-reviews__more-reviews">
                    <input type="hidden" class="pmreview_current_page" name="pmreview_current_page" value="1" />
                    <input type="hidden" class="pmreview_last_page" name="pmreview_last_page" value="{floor($total_reviews/5)}" />
                    {if $total_reviews > 5}
                        <a href="#" data-id_product="{$pmreview_id_product}" data-url="{$pmreview_contr_url}" class="pm_review_more_reviews btn btn-primary">{l s='Ещё' mod='pmreview'}</a>
                    {/if}
                </div>
            </div>
            <div class="product-reviews-summary col-12">
                <div class="product-reviews-summary__wrapper">
                    {include file='./review_lines.tpl'}
                    <a href="#" data-url="{$pmreview_contr_url}" data-id_product="{$pmreview_id_product}" class="btn btn-primary pmreview_get_popup">{l s='Написать отзыв' mod='pmreview'}</a>
                </div>
            </div>
        </div>
    {else}
        <div class="row">
            <div class="product-reviews-empty col-12 col-md-8">
                <h3>{l s='Отзывы' mod='pmreview'}</h3>
                <p>{l s='У данного товара нет отзывов. Станьте первым, кто написал отзыв об этом товаре!' mod='pmreview'}</p>
            </div>
            <div class="product-reviews-start col-12 col-md-4">
                <h4>{l s='Поделитесь своим мнением о товаре' mod='pmreview'}</h4>
                <p>{l s='Это поможет другим покупателям принять решение о покупке' mod='pmreview'}</p>
                <a href="#" data-url="{$pmreview_contr_url}" data-id_product="{$pmreview_id_product}" class="btn btn-primary pmreview_get_popup">{l s='Написать отзыв' mod='pmreview'}</a>
            </div>
        </div>
    {/if}
</div>