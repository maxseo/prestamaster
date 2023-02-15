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
 * NOTICE OF LICENSE
 *
 * Don't use this module on several shops. The license provided by PrestaShop Master
 * for all its modules is valid only once for a single shop.
 */

require_once _PS_MODULE_DIR_ . 'pmreview/pmReviewModel.php';
require_once _PS_MODULE_DIR_.'pmreview/pmReviewLike.php';

class PmreviewReviewModuleFrontController extends ModuleFrontController
{
    public function initContent()
    {
        parent::initContent();
        if (Tools::isSubmit('action')) {
            if (Tools::getValue('action') == 'addReview') {
                $review = new pmReviewModel();
                $rating = (int)Tools::getValue('rating');
                $review->rating = (int)Tools::getValue('rating');
                $review->images = $this->uploadImages();
                $review->review = pSql(Tools::getValue('review'));
                $review->minuses = pSql(Tools::getValue('minuses'));
                $review->pluses = pSql(Tools::getValue('pluses'));
                $review->fio = pSql(Tools::getValue('fio'));
                $review->id_product = (int)Tools::getValue('id_product');
                $review->id_customer = (int)$this->context->customer->id;
                $review->id_shop = (int)$this->context->shop->id;
                $review->add();
                $product_o = new Product((int)Tools::getValue('id_product'), false, 1);
                $data = array(
                    '{link}' => '<a href="'.$this->context->link->getProductLink($product_o).'">'.$product_o->name.'</a>'
                );
                Mail::Send(
    				(int)1,
    				'new_review',
    				'Новый отзыв о товаре',
    				$data,
    				'shop@motionlamps.ru',
    				'',
    				null,
    				null,
    				null,
    				null, _PS_MAIL_DIR_, false, (int)1
    			);
                $review->formatted_date = date("d-m-Y", strtotime($review->date_add));
                $this->context->smarty->assign('review', $review);
                $confirm_popup = $this->context->smarty->fetch(_PS_MODULE_DIR_ . 'pmreview/views/templates/front/confirm_popup.tpl');
                die(Tools::jsonEncode(['confirm_popup' => $confirm_popup]));
            } elseif (Tools::getValue('action') == 'getPopup') {
                $id_shop = $this->context->shop->id;
                $id_shop_group = Shop::getGroupFromShop($id_shop, true);
                $file_size = Configuration::get('pm_review_file_size', null, $id_shop_group, $id_shop);
                $files_count = Configuration::get('pm_review_files_count', null, $id_shop_group, $id_shop);
                $this->context->smarty->assign([
                    'url' => $this->context->link->getModuleLink('pmreview', 'review'),
                    'img_dir' => _THEME_IMG_DIR_,
                    'id_product' => (int)Tools::getValue('id_product'),
                    'file_size' => $file_size,
                    'files_count' => $files_count,
                    'customer' => $this->context->customer
                ]);
                $popup = $this->context->smarty->fetch(_PS_MODULE_DIR_ . 'pmreview/views/templates/front/popup.tpl');
                die(Tools::jsonEncode(['popup' => $popup]));
            } elseif (Tools::getValue('action') == 'getMoreReviews') {
                $page = (int)Tools::getValue('page');
                $id_product = (int)Tools::getValue('id_product');
                $reviews = pmReviewModel::getProductReviews($id_product, $page, $this->context->shop->id);
                $reviews = $this->prepareForTemplate($reviews);
                $this->context->smarty->assign([
                    'pmreviews' => $reviews,
                    'reviews_img_path' => Tools::getHttpHost(true).'/modules/pmreview/views/img/',
                    'pmreview_contr_url' => $this->context->link->getModuleLink('pmreview', 'review'),
                    'pmreview_id_product' => $params['product']->id,
                    'review_img_dir' => _PS_MODULE_DIR_."pmreview/views/img/"
                ]);
                $reviews = $this->context->smarty->fetch(_PS_MODULE_DIR_ . 'pmreview/views/templates/hook/reviews.tpl');
                die(Tools::jsonEncode(['reviews' => $reviews]));
            } elseif (Tools::getValue('action') == 'like') {
                $id_review = (int)Tools::getValue('id_review');
                if ($id_review) {
                    $who = $this->context->cookie->customer_cookie;
                    $check_like = pmReviewLike::issetLike($who, $id_review);
                    if (!$check_like) {
                        $check_dislike = pmReviewLike::issetDislike($who, $id_review);
                        if ($check_dislike) {
                            $dislike = new pmReviewLike($check_dislike);
                            $dislike->delete();
                        }
                        $like = new pmReviewLike();
                        $like->id_review = (int)$id_review;
                        $like->likes = 1;
                        $like->customer = pSql($who);
                        $like->add();
                    }
                    
                }
                $likes = pmReviewLike::getLikes($id_review);
                if(!$likes){
                    $likes = 0;
                }
                $dislikes = abs(pmReviewLike::getDislikes($id_review));
                if(!$dislikes){
                    $dislikes = 0;
                }
                die(Tools::jsonEncode(['likes' => $likes, 'dislikes' => $dislikes]));
            } elseif (Tools::getValue('action') == 'dislike') {
                $id_review = (int)Tools::getValue('id_review');
                if ($id_review) {
                    $who = $this->context->cookie->customer_cookie;
                    $check_dislike = pmReviewLike::issetDislike($who, $id_review);
                    if (!$check_dislike) {
                        $check_like = pmReviewLike::issetLike($who, $id_review);
                        if ($check_like) {
                            $like = new pmReviewLike($check_like);
                            $like->delete();
                        }
                        $dislike = new pmReviewLike();
                        $dislike->id_review = (int)$id_review;
                        $dislike->likes = -1;
                        $dislike->customer = pSql($who);
                        $dislike->add();
                    }
                    
                }
                $likes = pmReviewLike::getLikes($id_review);
                if(!$likes){
                    $likes = 0;
                }
                $dislikes = abs(pmReviewLike::getDislikes($id_review));
                if(!$dislikes){
                    $dislikes = 0;
                }
                die(Tools::jsonEncode(['likes' => $likes, 'dislikes' => $dislikes]));
            } elseif (Tools::getValue('action') == 'getMoreAllReviews'){
                $page = (int)Tools::getValue('page');
                $reviews = pmReviewModel::getReviews($page, $this->context->shop->id);
                $reviews = $this->prepareForTemplate($reviews);
                $this->context->smarty->assign([
                    'pmreviews' => $reviews,
                    'reviews_img_path' => Tools::getHttpHost(true).'/modules/pmreview/views/img/',
                    'pmreview_contr_url' => $this->context->link->getModuleLink('pmreview', 'review'),
                    'pmreview_id_product' => $params['product']->id,
                    'review_img_dir' => _PS_MODULE_DIR_."pmreview/views/img/"
                ]);
                $reviews = $this->context->smarty->fetch(_PS_MODULE_DIR_ . 'pmreview/views/templates/hook/reviews.tpl');
                die(Tools::jsonEncode(['reviews' => $reviews]));
            }
        } else {
            $page = (int)Tools::getValue('page', 1);
            $reviews = pmReviewModel::getReviews($page, $this->context->shop->id);
            $total_reviews = pmReviewModel::getAllReviews($this->context->shop->id);
            $id_shop = $this->context->shop->id;
            $id_shop_group = Shop::getGroupFromShop($id_shop, true);
            $customer_group = Configuration::get('pm_review_customer_group', null, $id_shop_group, $id_shop);
            $reviews = $this->prepareForTemplate($reviews);
            $this->context->smarty->assign([
                'pmreviews' => $reviews,
                'customer_group' => $customer_group,
                'reviews_img_path' => Tools::getHttpHost(true).'/modules/pmreview/views/img/',
                'pmreview_contr_url' => $this->context->link->getModuleLink('pmreview', 'review'),
                'review_img_dir' => _PS_MODULE_DIR_."pmreview/views/img/",
                'total_reviews' => count($total_reviews)
            ]);
            $this->setTemplate('module:pmreview/views/templates/front/all_reviews.tpl');
        }
    }
    
    private function prepareForTemplate($reviews)
    {
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
            }
            $review['customer'] = new Customer($review['id_customer']);
            $review['product'] = new Product($review['id_product'], true, $this->context->language->id);$images = $review['product']->getImages((int)$this->context->cookie->id_lang);
        		
    		foreach ($images as $k => $image)
    		{
    			if ($image['cover'])
    			{
    				$review['product']->cover = $image;
    			}
    		}
            $review['answer']->dislikes = $dislikes;
            $review['pluses'] = str_ireplace(array("\r","\n",'\r','\n'),'<br/>',$review['pluses']);
            $review['minuses'] = str_ireplace(array("\r","\n",'\r','\n'),'<br/>',$review['minuses']);
            $review['review'] = str_ireplace(array("\r","\n",'\r','\n'),'<br/>',$review['review']);
        }
        return $reviews;
    }
    
    private function uploadImages()
    {
        $total = count($_FILES['images']['name']);
        $images = '';
        for( $i=0 ; $i < $total ; $i++ ) {
            $file_tmp_path = $_FILES['images']['tmp_name'][$i];
            $file_name = $_FILES['images']['name'][$i];
            $file_name_exploded = explode(".", $file_name);
            $file_extension = Tools::strtolower(end($file_name_exploded));
            $new_file_name = md5(time() . $file_name) . '.' . $file_extension;
            $allowed_file_extensions = array('jpg', 'jpeg','gif', 'png', 'svg', 'mp4', 'm4v', 'f4v', 'Irv');
            if (in_array($file_extension, $allowed_file_extensions)) {
                $image_path = _PS_MODULE_DIR_.'pmreview/views/img/'.$new_file_name;
                if (move_uploaded_file($file_tmp_path, $image_path)) {
                    $images .= $new_file_name.',';
                }
            }
        }
        return rtrim($images, ',');
    }
}
