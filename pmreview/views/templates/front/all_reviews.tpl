{**
 * 2012 - 2020 Prestashop Master
 *
 * MODULE Compare
 *
 * @author    Prestashop Master <dev@prestashopmaster.com>
 * @copyright Copyright (c) permanent, Prestashop Master
 * @license   https://opensource.org/licenses/GPL-3.0  GNU General Public License version 3
 * @version   1.0.0
 * @link      https://www.prestashopmaster.com
 *
 * NOTICE OF LICENSE
 *
 * Don't use this module on several shops. The license provided by PrestaShop Master
 * for all its modules is valid only once for a single shop.
 *}
{extends file='page.tpl'}
{block name="page_content_container"}
<div class="row">
<div class="col-12 col-md-8 mx-auto">
	<h1 class="text-center mb-4">Opinie o sklepie</h1>
    <p class="pmreview-total_orders-row mb-4">Wszystkie recenzje: <span>{$total_reviews}</span></p>
    <a href="#" data-url="{$pmreview_contr_url}" data-id_product="{$pmreview_id_product}" class="btn btn-primary d-lg-none d-block pmreview_get_popup">Napisz opinię</a>
    <div class="row">
			<ul class="pmreview-container  col-12">
				{include file='module:pmreview/views/templates/hook/reviews.tpl'}
            <li>
                <input type="hidden" class="pmreview_current_page" name="pmreview_current_page" value="1" />
                <input type="hidden" class="pmreview_last_page" name="pmreview_last_page" value="{floor($total_reviews/5)}" />
                {if $total_reviews > 5}
                    <a href="#" data-url="{$pmreview_contr_url}" class="pm_review_all_more_reviews btn btn-primary d-block">Jeszcze</a>
                {/if}
            </li>
        </ul>
    </div>
</div>
</div>
 {/block}