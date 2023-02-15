{**
* 2012 - 2021 Prestashop Master
*
* MODULE Cashback
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

{* Workaround to add compatibility for enable/disable actions to be able to use symfony endpoints *}
{if isset($migrated_url_enable)}
  {assign var="url_enable" value=$migrated_url_enable}
{/if}

<a class="list-action-enable{if isset($ajax) && $ajax} ajax_table_link{/if}{if $enabled} action-enabled{else} action-disabled{/if}" href="{$url_enable|escape:'html':'UTF-8'}"{if isset($confirm)} onclick="return confirm('{$confirm}');"{/if} title="{if $enabled}{l s='Enabled' d='Admin.Global'}{else}{l s='Disabled' d='Admin.Global'}{/if}">
	<i class="icon-check{if !$enabled} hidden{/if}"></i>
	<i class="icon-remove{if $enabled} hidden{/if}"></i>
</a>
