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
<a href="#" title="{$action|escape:'html':'UTF-8'}" onclick="{if $confirm}confirm_link('', '{$confirm|escape:'html':'UTF-8'}', '{l s='Yes' d='Admin.Global'}', '{l s='No' d='Admin.Global'}', '{$location_ok|escape:'html':'UTF-8'}', '{$location_ko|escape:'html':'UTF-8'}'){else}document.location = '{$location_ko|escape:'html':'UTF-8'}'{/if}">
	<i class="icon-copy"></i> {$action}
</a>
