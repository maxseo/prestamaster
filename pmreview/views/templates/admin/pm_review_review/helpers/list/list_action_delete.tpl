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
<a href="{$href|escape:'html':'UTF-8'}"{if isset($confirm)} onclick="if (confirm('{$confirm}')){ldelim}return true;{rdelim}else{ldelim}event.stopPropagation(); event.preventDefault();{rdelim};"{/if} title="{$action|escape:'html':'UTF-8'}" class="delete">
	<i class="icon-trash"></i> {$action|escape:'html':'UTF-8'}
</a>
