{**
* 2012 - 2020 Prestashop Master
*
* MODULE Simple Blog
*
* @author    Prestashop Master <dev@prestashopmaster.com>
* @copyright Copyright (c) permanent, Prestashop Master
* @license   https://opensource.org/licenses/GPL-3.0  GNU General Public License version 3
* @link      https://www.prestashopmaster.com
* @version   1.0.0
* NOTICE OF LICENSE
*
* Don't use this module on several shops. The license provided by PrestaShop Master
 * for all its modules is valid only once for a single shop.
 *}
{extends file="helpers/form/form.tpl"}

{block name="input"}

    {if $input.name == "review_image"}
		<div class="review-images row">
		    {assign var=i value=0}
            {foreach from=$input.images item = image}
                {assign var=img_path value="`$input.review_img_dir``$image`"}
                {if file_exists($img_path) && $image}
                    <img src="{$input.reviews_img_path}{$image}" width="200">
                    <label for="remove_image_{$i}">Удалить<input id="remove_image_{$i}" type="checkbox" name="remove_image_{$i}" value="{$image}"></label>
                {/if}
                {$i = $i + 1}
            {/foreach}
        </div>
    {else if $input.name == "answer"}
        {if $input.answer_review->id}
    		<div class="review-images row">
                <p>Ответ магазина : {$input.answer_review->review}</p>
                {$input.button}
            </div>
        {/if}
	{else}
        {$smarty.block.parent}
    {/if}

{/block}