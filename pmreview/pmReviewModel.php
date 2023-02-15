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

class PmReviewModel extends ObjectModel
{
    public $id_product;
    public $id_customer;
    public $review;
    public $rating;
    public $active;
    public $images;
    public $avatar;
    public $date_add;
    public $fio;
    public $minuses;
    public $pluses;
    public $id_shop;
    public $id_parent;
    
    public static $definition = array(
        'table' => 'pm_review',
        'primary' => 'id_pm_review',
        'fields' => array(
            'id_product' => array('type' => self::TYPE_INT, 'validate' => 'isInt'),
            'id_customer' => array('type' => self::TYPE_INT, 'validate' => 'isInt'),
            'rating' => array('type' => self::TYPE_INT, 'validate' => 'isInt'),
            'minuses' => array('type' => self::TYPE_STRING, 'validate' => 'isString'),
            'pluses' => array('type' => self::TYPE_STRING, 'validate' => 'isString'),
            'active' => array('type' => self::TYPE_INT, 'validate' => 'isInt'),
            'id_shop' => array('type' => self::TYPE_INT, 'validate' => 'isInt'),
            'avatar' => array('type' => self::TYPE_STRING, 'validate' => 'isString'),
            'review' => array('type' => self::TYPE_STRING, 'validate' => 'isString'),
            'fio' => array('type' => self::TYPE_STRING, 'validate' => 'isString'),
            'images' => array('type' => self::TYPE_STRING, 'validate' => 'isString'),
            'date_add' => array('type' => self::TYPE_DATE, 'validate' => 'isDate'),
            'id_parent' => array('type' => self::TYPE_INT, 'validate' => 'isInt'),
        )
    );

    public function add($auto_date = true, $null_values = false, $id_shop = false)
    {
        parent::add($auto_date, $null_values);
        return $this->id;
    }

    public function delete()
    {
        return  parent::delete();
    }
    
    public function update($null_values = fals)
    {
        $images = explode(',', $this->images);
        $img_dir = _PS_MODULE_DIR_.'pmreview/view/img';
        foreach ($images as $i => $image) {
            $remove_image_name = Tools::getValue('remove_image_'.$i);
            if ($remove_image_name) {
                unlink($img_dir.'/'.$remove_image_name);
                unset($images[$i]);
            }
        }
        $this->images = implode($images, ',');
        return  parent::update($null_values);
    }

    public static function createTables()
    {
        if (!pmReviewModel::createTable()) {
            return false;
        }
        if (!pmReviewModel::alterOrderTable()) {
            return false;
        }
        if (!pmReviewModel::alterOrderTable2()) {
            return false;
        }
        return true;
    }
    
    public static function getProductReviews($id_product, $page, $id_shop)
    {
        $sql = "SELECT * FROM "._DB_PREFIX_."pm_review 
        WHERE id_product=".(int)$id_product." AND id_shop=".(int)$id_shop." AND active = 1 AND id_parent = 0 ORDER BY id_pm_review DESC LIMIT ". (int)(($page-1)*5).", 5";
        return Db::getInstance()->executeS($sql);
    }
    
    public static function getProductAllReviews($id_product, $id_shop)
    {
        $sql = "SELECT * FROM "._DB_PREFIX_."pm_review 
        WHERE id_product=".(int)$id_product." AND id_shop=".(int)$id_shop." AND active = 1 AND id_parent = 0";
        return Db::getInstance()->executeS($sql);
    }
    
    public static function getProductReviewsTotal($id_product, $id_shop)
    {
        $sql = "SELECT COUNT(id_pm_review) FROM "._DB_PREFIX_."pm_review 
        WHERE id_product=".(int)$id_product." AND id_shop=".(int)$id_shop." AND active = 1 AND id_parent = 0";
        return Db::getInstance()->getValue($sql);
    }

    public static function getProductMostImportantReviews($id_product, $id_shop)
    {
        $sql = "SELECT rs.id_review, max(rs.likes_sum) FROM (SELECT id_review, sum(likes) as likes_sum FROM "._DB_PREFIX_."pm_review_like rl 
         LEFT JOIN "._DB_PREFIX_."pm_review r ON (r.id_pm_review=rl.id_review) 
        WHERE r.id_product=".(int)$id_product." AND r.id_shop=".(int)$id_shop." AND r.active = 1 AND r.id_parent = 0 GROUP BY rl.id_review) rs";
        
        return Db::getInstance()->getRow($sql);
    }
    
    public static function getReviews($page, $id_shop)
    {
        $sql = "SELECT * FROM "._DB_PREFIX_."pm_review 
        WHERE  id_shop=".(int)$id_shop." AND active = 1 AND id_parent = 0 ORDER BY id_pm_review DESC LIMIT ". (int)(($page-1)*5).", 5";
        return Db::getInstance()->executeS($sql);
    }
    
    public static function getAllReviews($id_shop)
    {
        $sql = "SELECT * FROM "._DB_PREFIX_."pm_review 
        WHERE id_shop=".(int)$id_shop." AND active = 1 AND id_parent = 0 ORDER BY id_pm_review DESC";
        return Db::getInstance()->executeS($sql);
    }
    
    public static function getCategoryReviews($id_category, $random, $id_shop)
    {
        $sql = "SELECT * FROM "._DB_PREFIX_."pm_review r
        LEFT JOIN "._DB_PREFIX_."category_product cp ON (r.id_product=cp.id_product) 
        WHERE cp.id_category=".(int)$id_category." AND r.id_shop=".(int)$id_shop." AND r.active = 1 AND r.id_parent = 0 ORDER BY RAND(".(int)$random.")";
        return Db::getInstance()->executeS($sql);
    }

    public static function createTable()
    {
        $sql = 'CREATE TABLE IF NOT EXISTS `'._DB_PREFIX_.'pm_review` (
                    `id_pm_review` INT UNSIGNED NOT NULL AUTO_INCREMENT,
                    `id_product` int,
                    `id_customer` int,
                    `rating` int,
                    pluses text,
                    minuses text,
                    active int,
                    id_shop int,
                    `review` text,
                    `avatar` varchar(255),
                    `fio` varchar(255),
                    images text,
                    id_parent int,
                    `date_add` date,
                    PRIMARY KEY (`id_pm_review`)
			    ) ENGINE='._MYSQL_ENGINE_.' DEFAULT CHARSET=utf8 ;';

        return Db::getInstance()->execute($sql);
    }
    
    public static function alterOrderTable()
    {
        $sql = 'ALTER TABLE `'._DB_PREFIX_.'orders` ADD `is_send_review_email` INT NOT NULL AFTER `date_upd`;';

        return Db::getInstance()->execute($sql);
    }

    public static function alterOrderTable2()
    {
        $sql = 'ALTER TABLE `'._DB_PREFIX_.'orders` ADD `is_send_review_email2` INT NOT NULL AFTER `is_send_review_email`;';

        return Db::getInstance()->execute($sql);
    }

    public static function dropTables()
    {
        if (!pmReviewModel::dropTable()) {
            return false;
        }
        return true;
    }

    public static function dropTable()
    {
        $sql = "DROP TABLE "._DB_PREFIX_."pm_review";

        return Db::getInstance()->execute($sql);
    }
    
    public static function getAnswerId($id_review)
    {
        return Db::getInstance()->getValue("SELECT id_pm_review FROM "._DB_PREFIX_."pm_review
        WHERE id_parent=".(int)$id_review);
    }
}
