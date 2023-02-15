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

class pmReviewLike extends ObjectModel
{
    public $id_review;
    public $likes;
    public $customer;
    
    public static $definition = array(
        'table' => 'pm_review_like',
        'primary' => 'id_pm_review_like',
        'fields' => array(
            'likes' => array('type' => self::TYPE_INT, 'validate' => 'isInt'),
            'id_review' => array('type' => self::TYPE_INT, 'validate' => 'isInt'),
            'customer' => array('type' => self::TYPE_STRING, 'validate' => 'isString'),
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

    public static function createTables()
    {
        if (!self::createTable()) {
            return false;
        }
        return true;
    }

    public static function createTable()
    {
        $sql = 'CREATE TABLE IF NOT EXISTS `'._DB_PREFIX_.'pm_review_like` (
                    `id_pm_review_like` INT UNSIGNED NOT NULL AUTO_INCREMENT,
                    `id_review` int,
                    likes int,
                    `customer` varchar(255),
                    PRIMARY KEY (`id_pm_review_like`)
			    ) ENGINE='._MYSQL_ENGINE_.' DEFAULT CHARSET=utf8 ;';

        return Db::getInstance()->execute($sql);
    }

    public static function dropTables()
    {
        if (!self::dropTable()) {
            return false;
        }
        return true;
    }

    public static function dropTable()
    {
        $sql = "DROP TABLE "._DB_PREFIX_."pm_review_like";

        return Db::getInstance()->execute($sql);
    }
    
    public static function issetLike($customer, $id_review)
    {
        return Db::getInstance()->getValue("SELECT id_pm_review_like FROM "._DB_PREFIX_."pm_review_like 
        WHERE id_review=".(int)$id_review." AND customer='".pSql($customer)."' AND likes = 1");
    }
    
    public static function issetDislike($customer, $id_review)
    {
        return Db::getInstance()->getValue("SELECT id_pm_review_like FROM "._DB_PREFIX_."pm_review_like 
        WHERE id_review=".(int)$id_review." AND customer='".pSql($customer)."' AND likes = -1");
    }
    
    public static function getLikes($id_review)
    {
        return Db::getInstance()->getValue("SELECT SUM(likes) FROM "._DB_PREFIX_."pm_review_like 
        WHERE id_review=".(int)$id_review." AND likes = 1");
    }
    
    public static function getDislikes($id_review)
    {
        return Db::getInstance()->getValue("SELECT SUM(likes) FROM "._DB_PREFIX_."pm_review_like 
        WHERE id_review=".(int)$id_review." AND likes = -1");
    }
}
