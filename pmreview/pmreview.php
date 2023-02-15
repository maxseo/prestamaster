<?php
/**
 * 2012 - 2020 Prestashop Master
 *
 * MODULE Customers Reviews
 *
 * @author    Prestashop Master <dev@prestashopmaster.com>
 * @copyright Copyright (c) permanent, Prestashop Master
 * @license   https://opensource.org/licenses/GPL-3.0  GNU General Public License version 3
 * @version   1.0.0
 * @link      https://www.prestashopmaster.com
 * NOTICE OF LICENSEimages
 *
 * Don't use this module on several shops. The license provided by PrestaShop Master
 * for all its modules is valid only once for a single shop.
 */

if (!defined('_PS_VERSION_')) {
    exit;
}

use PrestaShop\PrestaShop\Core\Module\WidgetInterface;
require_once _PS_MODULE_DIR_.'pmreview/pmReviewModel.php';
require_once _PS_MODULE_DIR_.'pmreview/pmReviewLike.php';

class Pmreview extends Module implements WidgetInterface
{

    public function __construct()
    {
        $this->name = 'pmreview';
        $this->author = 'PrestashopMaster';
        $this->tab = 'front_office_features';
        $this->version = '1.0.0';

        $this->bootstrap = true;
        parent::__construct();

        $this->displayName = $this->l('Customers Reviews');
        $this->description = $this->l('Allow your customers to live comments at your store.');
        $this->ps_versions_compliancy = array('min' => '1.6.0.0', 'max' => _PS_VERSION_);
    }

    public function install()
    {
        if (!parent::install() || !pmReviewModel::createTables()
        || !pmReviewLike::createTables()
        || !$this->registerHook('pmreviewsrating')
        || !$this->registerHook('pmreviewtotal')
        || !$this->registerHook('pmreviewproduct')
        || !$this->registerHook('displayLeftColumn')
        || !$this->registerHook('displayRightColumn')
        || !$this->registerHook('displayHeader')
        || !$this->registerHook('pmreviewmostimportant')
        || !$this->registerHook('productTab')
        || !$this->registerHook('productTabContent')
        || !$this->registerHook('displayFooter')
        || !$this->registerHook('displayReviewProductTop')
        || !$this->createTab('AdminPmReviewReview', array(
                'ru' => 'Отзывы на продукты',
                'en' => 'Products reviews'
            ), 'IMPROVE')
        ) {
            return false;
        }
        return true;
    }

    public function uninstall()
    {
        if (!parent::uninstall() || !pmReviewLike::dropTables() || !pmReviewModel::dropTables() || !$this->deleteTab('AdminPmReviewReview')) {
            return false;
        }
        return true;
    }
    
    public function createTab($class_name, $name, $parent = null)
    {
        if (!is_array($name)) {
            $name = array('en' => $name);
        } elseif (is_array($name) && !count($name)) {
            $name = array('en' => $class_name);
        } elseif (is_array($name) && count($name) && !isset($name['en'])) {
            $name['en'] = current($name);
        }

        $tab = new Tab();
        $tab->class_name = $class_name;
        $tab->module = $this->name;
        $tab->id_parent = (!is_null($parent) ? Tab::getIdFromClassName($parent) : 0);
        if (is_null($parent) && version_compare(_PS_VERSION_, '1.6.0.0', '<')) {
            $this->copyTabIconInRoot($class_name);
        }
        $tab->active = 1;
        foreach (Language::getLanguages() as $l) {
            $tab->name[$l['id_lang']] = (isset($name[$l['iso_code']]) ? $name[$l['iso_code']] : $name['en']);
        }
        return $tab->save();
    }
    
    public function hookproductTab($params)
    {
        return $this->display(__FILE__, 'product_tab.tpl');
    }
    
    public function hookpmreviewproduct($params)
    {
        $params['product'] = new Product(Tools::getValue('id_product'), false, $this->context->language->id);
        return $this->hookproductTabContent($params);
    }
    
    public function hookproductTabContent($params)
    {
        $params['product'] = new Product(Tools::getValue('id_product'), false, $this->context->language->id);
        $id_shop = $this->context->shop->id;
        $id_shop_group = Shop::getGroupFromShop($id_shop, true);
        $file_size = Configuration::get('pm_review_file_size', null, $id_shop_group, $id_shop);
        $files_count = Configuration::get('pm_review_files_count', null, $id_shop_group, $id_shop);
        $customer_group = Configuration::get('pm_review_customer_group', null, $id_shop_group, $id_shop);
        $reviews = pmReviewModel::getProductReviews($params['product']->id, 1, $this->context->shop->id);
        foreach ($reviews as &$review) {
            $review['formatted_date'] = date("d-m-Y", strtotime($review['date_add']));
            $review['images'] = array_values(array_filter(explode(',', $review['images'])));
            $likes = pmReviewLike::getLikes($review['id_pm_review']);
            if(!$likes){
                $likes = 0;
            }
            $review['likes'] = $likes;
            $dislikes = abs(pmReviewLike::getDislikes($review['id_pm_review']));
            if(!$dislikes){
                $dislikes = 0;
            }
            $review['dislikes'] = $dislikes;
            $id_answer = pmReviewModel::getAnswerId($review['id_pm_review']);
            if($id_answer) {
                $review['answer'] = new pmReviewModel($id_answer);
                $review['answer']->images = array_values(array_filter(explode(',', $review['answer']->images)));
                $likes = pmReviewLike::getLikes($review['answer']->id);
                if(!$likes){
                    $likes = 0;
                }
                $review['answer']->likes = $likes;
                $dislikes = abs(pmReviewLike::getDislikes($review['answer']->id));
                if(!$dislikes){
                    $dislikes = 0;
                }
                $review['answer']->dislikes = $dislikes;
            }
            $review['pluses'] = str_ireplace(array("\r","\n",'\r','\n'),'<br/>',$review['pluses']);
            $review['minuses'] = str_ireplace(array("\r","\n",'\r','\n'),'<br/>',$review['minuses']);
            $review['review'] = str_ireplace(array("\r","\n",'\r','\n'),'<br/>',$review['review']);
            $review['customer'] = new Customer($review['id_customer']);
        }
        $all_reviews = pmReviewModel::getProductAllReviews($params['product']->id, $this->context->shop->id);
        $rating_summary = 0;
        $five = 0;
        $four = 0;
        $three = 0;
        $two = 0;
        $one = 0;
        foreach ($all_reviews as $all_review) {
            switch ($all_review['rating']) {
                case 5:
                    $five++;
                    break;
                case 4:
                    $four++;
                    break;
                case 3:
                    $three++;
                    break;
                case 2:
                    $two++;
                    break;
                case 1:
                    $one++;
                    break;
                default:
                    break;
            }
            $rating_summary += $all_review['rating'];
        }
        $total_reviews = count($all_reviews);
        if ($total_reviews) {
            $five_percent = round($five*100/$total_reviews);
            $four_percent = round($four*100/$total_reviews);
            $three_percent = round($three*100/$total_reviews);
            $two_percent = round($two*100/$total_reviews);
            $one_percent = 100 - round($five*100/$total_reviews,2) - round($four*100/$total_reviews,2) - round($three*100/$total_reviews, 2) - round($two*100/$total_reviews, 2);
            
            $product_rating = Tools::ps_round($rating_summary/$total_reviews, 2);
            $product_rating_floor = floor($product_rating);
            $product_rating_drob = $product_rating - $product_rating_floor;
            $product_raiting_percent = $product_rating_floor*20+3+$product_rating_drob*16;
            $this->smarty->assign('five_percent', $five_percent);
            $this->smarty->assign('product_raiting_percent', $product_raiting_percent);
            $this->smarty->assign('four_percent', $four_percent);
            $this->smarty->assign('three_percent', $three_percent);
            $this->smarty->assign('two_percent', $two_percent);
            $this->smarty->assign('one_percent', $one_percent);
            $this->smarty->assign('five', $five);
            $this->smarty->assign('four', $four);
            $this->smarty->assign('three', $three);
            $this->smarty->assign('two', $two);
            $this->smarty->assign('one', $one);
            $this->smarty->assign('reviews', $reviews);
            $this->smarty->assign('product_rating', Tools::ps_round($product_rating, 1));
            $this->smarty->assign('product_rating_rounded', Tools::ps_round($product_rating));
        }
        $this->smarty->assign('total_reviews', $total_reviews);
        $this->context->smarty->assign([
            'count_reviews' => count($all_reviews),
            'customer_group' => $customer_group,
            'pmreviews' => $reviews,
            'reviews_img_path' => Tools::getHttpHost(true).'/modules/pmreview/views/img/',
            'pmreview_contr_url' => $this->context->link->getModuleLink('pmreview', 'review'),
            'pmreview_id_product' => $params['product']->id,
            'review_img_dir' => _PS_MODULE_DIR_."pmreview/views/img/",
            'file_size' => $file_size,
            'files_count' => $files_count,
        ]);
        return $this->display(__FILE__, 'product_tab_content.tpl');
    }
    
    public function hookdisplayRightColumn($params)
    {
        return $this->hookdisplayLeftColumn($params);
    }
    
    public function hookdisplayLeftColumn($params)
    {
        if ($this->context->controller->php_self == 'category') {
            $id_category = (int)Tools::getValue('id_category');
            $reviews = pmReviewModel::getCategoryReviews($id_category, 5, $this->context->shop->id);
            $id_shop = $this->context->shop->id;
            $id_shop_group = Shop::getGroupFromShop($id_shop, true);
            $file_size = Configuration::get('pm_review_file_size', null, $id_shop_group, $id_shop);
            $files_count = Configuration::get('pm_review_files_count', null, $id_shop_group, $id_shop);
            $customer_group = Configuration::get('pm_review_customer_group', null, $id_shop_group, $id_shop);
            foreach ($reviews as &$review) {
                $review['formatted_date'] = date("d-m-Y", strtotime($review['date_add']));
                $review['images'] = array_values(array_filter(explode(',', $review['images'])));
                $likes = pmReviewLike::getLikes($review['id_pm_review']);
                if(!$likes){
                    $likes = 0;
                }
                $review['likes'] = $likes;
                $dislikes = abs(pmReviewLike::getDislikes($review['id_pm_review']));
                if(!$dislikes){
                    $dislikes = 0;
                }
                $review['dislikes'] = $dislikes;
                $id_answer = pmReviewModel::getAnswerId($review['id_pm_review']);
                if($id_answer) {
                    $review['answer'] = new pmReviewModel($id_answer);
                    $review['answer']->images = array_values(array_filter(explode(',', $review['answer']->images)));
                    $likes = pmReviewLike::getLikes($review['answer']->id);
                    if(!$likes){
                        $likes = 0;
                    }
                    $review['answer']->likes = $likes;
                    $dislikes = abs(pmReviewLike::getDislikes($review['answer']->id));
                    if(!$dislikes){
                        $dislikes = 0;
                    }
                    $review['answer']->dislikes = $dislikes;
                }
                $review['review'] = str_ireplace(array("\r","\n",'\r','\n'),'<br/>',$review['review']);
                $review['product'] = new Product($review['id_product'], true, $this->context->language->id);
                $review['customer'] = new Customer($review['id_customer']);
                $images = $review['product']->getImages((int)$this->context->cookie->id_lang);
        		
        		foreach ($images as $k => $image)
        		{
        			if ($image['cover'])
        			{
        				$review['product']->cover = $image;
        			}
        		}
            }
            $this->context->smarty->assign([
                'pmreviews' => $reviews,
                'customer_group' => $customer_group,
                'pmreview_contr_url' => $this->context->link->getModuleLink('pmreview', 'review'),
                'reviews_img_path' => Tools::getHttpHost(true).'/modules/pmreview/views/img/',
                'review_img_dir' => _PS_MODULE_DIR_."pmreview/views/img/",
            ]);
            return $this->display(__FILE__, 'left_column.tpl');
        }
    }

    public function hookpmreviewmostimportant($params){
        $review = pmReviewModel::getProductMostImportantReviews($params['product']->id, $this->context->shop->id);
        $id_review = $review['id_review'];
        $review_o = new pmReviewModel($id_review);
        $review_o->formatted_date = date("d-m-Y", strtotime($review_o->date_add));
        $review_o->images = array_values(array_filter(explode(',', $review_o->images)));
        $review_o->pluses = str_ireplace(array("\r","\n",'\r','\n'),'<br/>',$review_o->pluses);
        $likes = pmReviewLike::getLikes($review_o->id);
        if(!$likes){
            $likes = 0;
        }
        $review_o->likes = $likes;
        $dislikes = abs(pmReviewLike::getDislikes($review_o->id));
        if(!$dislikes){
            $dislikes = 0;
        }
        $review_o->dislikes = $dislikes;
        $this->context->smarty->assign([
            'review_o' => $review_o,
            'reviews_img_path' => Tools::getHttpHost(true).'/modules/pmreview/views/img/',
            'pmreview_contr_url' => $this->context->link->getModuleLink('pmreview', 'review'),
            'review_img_dir' => _PS_MODULE_DIR_."pmreview/views/img/"
        ]);
        return $this->display(__FILE__, 'product_most_important.tpl');
    }

    public function copyTabIconInRoot($icon)
    {
        $icon = $icon.'.gif';
        $path = _PS_MODULE_DIR_.basename(dirname(__FILE__)).'/';
        if (!file_exists($path.$icon) && file_exists($path.'views/img/'.$icon) && _PS_VERSION_ < 1.6) {
            copy($path . 'views/img/' . $icon, $path . $icon);
        }
    }

    public static function deleteTab($class_name)
    {
        $tab = Tab::getInstanceFromClassName($class_name);
        return $tab->delete();
    }

    public function hookHeader($params)
    {
        $this->context->controller->addJS($this->_path . 'views/js/pmreview.js');
        $this->context->controller->addCSS($this->_path . 'views/css/pmreview.css', 'all');
    }

    public function postProcess()
    {
        $cookie = Context::getContext()->cookie->getAll();
            if (isset($cookie['shopContext']) && $cookie['shopContext']) {
                $shop_context = explode('-', $cookie['shopContext']);
                $shop_ids = [];
                if (!$shop_context[0]) {
                    $shops = Shop::getShops();
                    foreach ($shops as $shop) {
                        $shop_ids[] = $shop['id_shop'];
                    }
                } else if ($shop_context[0] == 's') {
                    $shop_ids[] = $shop_context[1];
                } else if ($shop_context[0] == 'g') {
                    $group_shops = ShopGroup::getShopsFromGroup($shop_context[1]);
                    foreach ($group_shops as $group_shop) {
                        $shop_ids[] = $group_shop['id_shop'];
                    }
                }
            } else {
                $shop_ids[] = 1;
            }
            foreach ($shop_ids as $id_shop) {
                $id_shop_group = Shop::getGroupFromShop($id_shop, true);
                Configuration::updateValue('pm_review_file_size', Tools::getValue('file_size'), null, $id_shop_group, $id_shop);
                Configuration::updateValue('pm_review_files_count', Tools::getValue('files_count'), null, $id_shop_group, $id_shop);
                Configuration::updateValue('pm_review_customer_group', Tools::getValue('customer_group'), null, $id_shop_group, $id_shop);
                Configuration::updateValue('pm_review_id_order_state', Tools::getValue('id_order_state'), null, $id_shop_group, $id_shop);
                Configuration::updateValue('pm_review_days', Tools::getValue('days'), null, $id_shop_group, $id_shop);
                Configuration::updateValue('pm_review_days2', Tools::getValue('days2'), null, $id_shop_group, $id_shop);
                Configuration::updateValue('pm_review_days_start', Tools::getValue('days_start'), null, $id_shop_group, $id_shop);
                Configuration::updateValue('pm_review_discount', Tools::getValue('discount'), null, $id_shop_group, $id_shop);
                
                $languages = Language::getLanguages();
                foreach ($languages as $language) {
                    Configuration::updateValue('pm_review_email_'.$language['id_lang'], Tools::getValue('email_'.$language['id_lang']), null, $id_shop_group, $id_shop);
                    Configuration::updateValue('pm_review_email2_'.$language['id_lang'], Tools::getValue('email2_'.$language['id_lang']), null, $id_shop_group, $id_shop);
                }
            }
            $module_path = _PS_MODULE_DIR_.'pmreview/';
            if (!file_exists($module_path.'mails')) {
                mkdir($module_path.'mails', 0777, true);
            }
            foreach ($languages as $language) {
                if (!file_exists($module_path.'mails/'.$language['iso_code'])) {
                    mkdir($module_path.'mails/'.$language['iso_code'], 0777, true);
                }
                file_put_contents($module_path.'mails/'.$language['iso_code'].'/email.txt', '');
                file_put_contents($module_path.'mails/'.$language['iso_code'].'/email.html', Tools::getValue('email_'.$language['id_lang']));
                file_put_contents($module_path.'mails/'.$language['iso_code'].'/email2.txt', '');
                file_put_contents($module_path.'mails/'.$language['iso_code'].'/email2.html', Tools::getValue('email2_'.$language['id_lang']));
            }
            
        return '';
    }
    
    public function hookpmreviewtotal($params)
    {
        $all_reviews = pmReviewModel::getProductAllReviews($params['id_product'], $this->context->shop->id);
        if (count($all_reviews)) {
            return count($all_reviews);
        }
    }
    
    public function getRussianReviewByTotal($total){
        if ($total > 10 && $total <= 20){
            return 'отзывов';
        }
        $total = (string)$total;
        $last_digit = $total[strlen($total)-1];
        switch ($last_digit) {
            case '1':
                return 'отзыв';
            case '2':
            case '3':
            case '4':
                return 'отзыва';
            default:
                return 'отзывов';
        }
    }
    
    public function hookpmreviewsrating($params)
    {
        
        $all_reviews = pmReviewModel::getProductAllReviews($params['id_product'], $this->context->shop->id);
        $rating_summary = 0;
        $total_reviews = count($all_reviews);
        $product_raiting_percent = 0;
        /*if ($total_reviews) {
            $product_rating = Tools::ps_round($rating_summary/$total_reviews, 2);
            $product_rating_floor = floor($product_rating);
            $product_rating_drob = $product_rating - $product_rating_floor;
            $product_raiting_percent = $product_rating_floor*20+3+$product_rating_drob*16;
            $this->smarty->assign('product_rating', Tools::ps_round($product_rating, 2));
        }*/
        if ($total_reviews) {
            $five = 0;
            $four = 0;
            $three = 0;
            $two = 0;
            $one = 0;
            foreach ($all_reviews as $all_review) {
                switch ($all_review['rating']) {
                    case 5:
                        $five++;
                        break;
                    case 4:
                        $four++;
                        break;
                    case 3:
                        $three++;
                        break;
                    case 2:
                        $two++;
                        break;
                    case 1:
                        $one++;
                        break;
                    default:
                        break;
                }
                $rating_summary += $all_review['rating'];
            }
            $five_percent = round($five*100/$total_reviews);
            $four_percent = round($four*100/$total_reviews);
            $three_percent = round($three*100/$total_reviews);
            $two_percent = round($two*100/$total_reviews);
            $one_percent = 100 - round($five*100/$total_reviews,2) - round($four*100/$total_reviews,2) - round($three*100/$total_reviews, 2) - round($two*100/$total_reviews, 2);
            
            $product_rating = Tools::ps_round($rating_summary/$total_reviews, 2);
            $product_rating_floor = floor($product_rating);
            $product_rating_drob = $product_rating - $product_rating_floor;
            $product_raiting_percent = $product_rating_floor*20+3+$product_rating_drob*16;
            $this->smarty->assign('five_percent', $five_percent);
            $this->smarty->assign('product_raiting_percent', $product_raiting_percent);
            $this->smarty->assign('four_percent', $four_percent);
            $this->smarty->assign('three_percent', $three_percent);
            $this->smarty->assign('two_percent', $two_percent);
            $this->smarty->assign('one_percent', $one_percent);
            $this->smarty->assign('five', $five);
            $this->smarty->assign('four', $four);
            $this->smarty->assign('three', $three);
            $this->smarty->assign('two', $two);
            $this->smarty->assign('one', $one);
            $this->smarty->assign('reviews', $reviews);
            $this->smarty->assign('product_rating', Tools::ps_round($product_rating, 1));
            $this->smarty->assign('product_rating_rounded', Tools::ps_round($product_rating));
        }
        $this->smarty->assign('product_raiting_percent', $product_raiting_percent);
        $this->smarty->assign('total_reviews', $total_reviews);
        return $this->display(__FILE__, 'views/templates/hook/rating.tpl');
    }
    
    public function hookDisplayProductExtraContent($params)
    {
        //$this->smarty->assign('product_in_whishlist', $product_in_whishlist);
        $reviews = pmReviewModel::getProductReviews($params['product']->id, 1, $this->context->shop->id);
        
        $rating_summary = 0;
        foreach ($reviews as &$review) {
            $review['formatted_date'] = date("d-m-Y", strtotime($review['date_add']));
            if ($review['id_customer'] > 0) {
                $review['customer'] = new Customer($review['id_customer']);
            }
        }
        $all_reviews = pmReviewModel::getProductAllReviews($params['product']->id, $this->context->shop->id);
        $five = 0;
        $four = 0;
        $three = 0;
        $two = 0;
        $one = 0;
        foreach ($all_reviews as $all_review) {
            switch ($all_review['rating']) {
                case 5:
                    $five++;
                    break;
                case 4:
                    $four++;
                    break;
                case 3:
                    $three++;
                    break;
                case 2:
                    $two++;
                    break;
                case 1:
                    $one++;
                    break;
                default:
                    break;
            }
            $rating_summary += $all_review['rating'];
        }
        $total_reviews = count($all_reviews);
        if ($total_reviews) {
            $five_percent = round($five*100/$total_reviews);
            $four_percent = round($four*100/$total_reviews);
            $three_percent = round($three*100/$total_reviews);
            $two_percent = round($two*100/$total_reviews);
            $one_percent = 100 - $five_percent - $four_percent - $three_percent - $two_percent;
            $product_rating = Tools::ps_round($rating_summary/$total_reviews, 2);
            $product_rating_floor = floor($product_rating);
            $product_rating_drob = $product_rating - $product_rating_floor;
            $product_raiting_percent = $product_rating_floor*20+3+$product_rating_drob*16;
            $this->smarty->assign('five_percent', $five_percent);
            $this->smarty->assign('product_raiting_percent', $product_raiting_percent);
            $this->smarty->assign('four_percent', $four_percent);
            $this->smarty->assign('three_percent', $three_percent);
            $this->smarty->assign('two_percent', $two_percent);
            $this->smarty->assign('one_percent', $one_percent);
            $this->smarty->assign('reviews', $reviews);
            $this->smarty->assign('product_rating', Tools::ps_round($product_rating, 2));
        }
        $this->smarty->assign('total_reviews', $total_reviews.' '.$this->getRussianReviewByTotal($total_reviews));
        $this->context->smarty->assign('img_dir', _PS_IMG_DIR_);
        $this->smarty->assign('id_product', $params['product']->id);
        
        $content = $this->display(__FILE__, 'views/templates/hook/catalog.tpl');
        $tab = new PrestaShop\PrestaShop\Core\Product\ProductExtraContent();
        $tab->setTitle($this->l('Reviews'));
        $tab->setContent($content);
        return [ $tab ];
    }

    public function getContent()
    {
        if (Tools::isSubmit('days')) {
            $this->postProcess();
        }
        return $this->renderForm();
    }
    
    public function hookdisplayReviewProductTop($params)
    {
        $all_reviews = pmReviewModel::getProductAllReviews($params['product']->id, $this->context->shop->id);   
        foreach ($all_reviews as $all_review) {
            $rating_summary += $all_review['rating'];
        }
        $total_reviews = count($all_reviews);
        if($total_reviews) {
            $product_rating = Tools::ps_round($rating_summary/$total_reviews, 2);
            $product_rating_floor = floor($product_rating);
            $product_rating_drob = $product_rating - $product_rating_floor;
            $product_raiting_percent = $product_rating_floor*20+3+$product_rating_drob*16;
            $this->smarty->assign('product_raiting_percent', $product_raiting_percent);
            $this->smarty->assign('product_rating', Tools::ps_round($product_rating, 2));
            $this->smarty->assign('img_dir', _THEME_IMG_DIR_);
            $this->smarty->assign('total_reviews', $total_reviews.' '.$this->getRussianReviewByTotal($total_reviews));
            return $this->display(__FILE__, 'views/templates/hook/product_top.tpl');
        }
    }

    public function renderForm()
    {
        $cookie = Context::getContext()->cookie->getAll();
        if (isset($cookie['shopContext']) && $cookie['shopContext']) {
            $shop_context = explode('-', $cookie['shopContext']);
            $id_shop_group = 0;

            if (!$shop_context[0]) {
                $id_shop = 1;
            } else if ($shop_context[0] == 's') {
                $id_shop = $shop_context[1];
            } else if ($shop_context[0] == 'g') {
                $id_shop_group = $shop_context[1];
                $shop_group_shops = ShopGroup::getShopsFromGroup($id_shop_group);
                $id_shop = $shop_group_shops[0]['id_shop'];
            }

            if (!$id_shop_group) {
                $id_shop_group = Shop::getGroupFromShop($id_shop, true);
            }
        } else {
            $id_shop = 1;
            $id_shop_group = Shop::getGroupFromShop($id_shop, true);
        }
        $fields_value['file_size'] = Configuration::get('pm_review_file_size', null, $id_shop_group, $id_shop);
        $fields_value['files_count'] = Configuration::get('pm_review_files_count', null, $id_shop_group, $id_shop);
        $fields_value['customer_group'] = Configuration::get('pm_review_customer_group', null, $id_shop_group, $id_shop);
        $fields_value['id_order_state'] = Configuration::get('pm_review_id_order_state', null, $id_shop_group, $id_shop);
        $fields_value['days'] = Configuration::get('pm_review_days', null, $id_shop_group, $id_shop);
        $fields_value['days2'] = Configuration::get('pm_review_days2', null, $id_shop_group, $id_shop);
        $fields_value['days_start'] = Configuration::get('pm_review_days_start', null, $id_shop_group, $id_shop);
        $fields_value['discount'] = Configuration::get('pm_review_discount', null, $id_shop_group, $id_shop);
        
        $languages = Language::getLanguages();
        foreach ($languages as $language) {
            $fields_value['email'][$language['id_lang']] = Configuration::get('pm_review_email_'.$language['id_lang'], null, $id_shop_group, $id_shop);
            $fields_value['email2'][$language['id_lang']] = Configuration::get('pm_review_email2_'.$language['id_lang'], null, $id_shop_group, $id_shop);
            //var_dump(Configuration::get('pm_review_email_'.$language['id_lang'], null, $id_shop_group, $id_shop));die();
        }
        $customer_groups = $this->_getCustomerGroupsSelect();
        $order_states = $this->_getOrderStatesSelect();
        $fields_form = array(
            'form' => array(
                'legend' => array(
                    'title' => $this->l('Settings'),
                    'icon' => 'icon-cogs'
                ),
                'input' => array(
                    array(
                        'type' => 'hidden',
                        'name' => 'id_shop'
                    ),
                    array(
                        'type' => 'hidden',
                        'name' => 'id_shop_group'
                    ),
                    array(
                        'type' => 'text',
                        'label' => $this->l('Upload file size (MB):'),
                        'name' => 'file_size',
                    ),
                    array(
                        'type' => 'text',
                        'label' => $this->l('Upload file count:'),
                        'name' => 'files_count',
                    ),
                    array(
                        'type' => 'select',
                        'label' => $this->l('Customer group:'),
                        'name' => 'customer_group',
                        'options' => array(
                            'query' => $customer_groups,
                            'id' => 'id',
                            'name' => 'name'
                        )
                    ),
                    array(
                        'type' => 'select',
                        'label' => $this->l('Order state:'),
                        'name' => 'id_order_state',
                        'options' => array(
                            'query' => $order_states,
                            'id' => 'id',
                            'name' => 'name'
                        )
                    ),
                    array(
                        'type' => 'text',
                        'label' => $this->l('days after order to send email:'),
                        'name' => 'days',
                    ),
                    array(
                        'type' => 'text',
                        'label' => $this->l('days after order to send 2 email:'),
                        'name' => 'days2',
                    ),
                    array(
                        'type' => 'text',
                        'label' => $this->l('Coupon discount:'),
                        'name' => 'discount',
                    ),
                    array(
                        'type' => 'date',
                        'label' => $this->l('Start order date to send email:'),
                        'name' => 'days_start',
                    ),
                    array(
                        'type' => 'textarea',
                        'label' => $this->l('Email:'),
                        'name' => 'email',
                        'lang' => true
                    ),
                    array(
                        'type' => 'textarea',
                        'label' => $this->l('Email2:'),
                        'name' => 'email2',
                        'lang' => true
                    )
                ),
                'submit' => array(
                    'title' => $this->l('Save'),
                    'name' => 'savePmReview',
                    'class' => 'savePmReview'
                )
            ),
        );

        $lang = new Language((int)Configuration::get('PS_LANG_DEFAULT'));

        $helper = new HelperForm();
        $helper->show_toolbar = false;
        $helper->table = $this->table;
        $helper->default_form_language = $lang->id;
        $helper->module = $this;
        $helper->allow_employee_form_lang = Configuration::get('PS_BO_ALLOW_EMPLOYEE_FORM_LANG') ? Configuration::get('PS_BO_ALLOW_EMPLOYEE_FORM_LANG') : 0;
        $helper->identifier = $this->identifier;
        $helper->submit_action = 'submitStoreConf';
        $helper->currentIndex = $this->context->link->getAdminLink('AdminModules', false) . '&configure=' . $this->name . '&tab_module=' . $this->tab . '&module_name=' . $this->name;
        $helper->token = Tools::getAdminTokenLite('AdminModules');
        $helper->tpl_vars = array(
            'uri' => $this->getPathUri(),
            'fields_value' => $fields_value,
            'languages' => $this->context->controller->getLanguages(),
            'id_language' => $this->context->language->id
        );

        return $helper->generateForm(array($fields_form));
    }
    
    public function _getCustomerGroupsSelect()
    {
        $shopGroups = Group::getGroups($this->context->language->id, Shop::getContextShopID());
        $select_data = array();
        foreach ($shopGroups as $key => $shopGroup) {
            $select_data[$key]['id'] = $shopGroup['id_group'];
            $select_data[$key]['name'] = $shopGroup['name'];
        }
        return $select_data;
    }
    
    public function _getOrderStatesSelect()
    {
        $shopGroups = OrderState::getOrderStates($this->context->language->id);
        $select_data = array();
        foreach ($shopGroups as $key => $shopGroup) {
            $select_data[$key]['id'] = $shopGroup['id_order_state'];
            $select_data[$key]['name'] = $shopGroup['name'];
        }
        return $select_data;
    }

    public function renderWidget($hookName = null, array $configuration = [])
    {
    }

    public function getWidgetVariables($hookName = null, array $configuration = [])
    {
    }
}
