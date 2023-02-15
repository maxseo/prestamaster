<?php

include(dirname(__FILE__).'/../../config/config.inc.php');


$orders = getOrdersToSendEmail();
foreach ($orders as $order) {
    sendEmail($order);
}

$orders = getOrdersToSendEmail2();
foreach ($orders as $order) {
    sendEmail2($order);
}

function getOrdersToSendEmail()
{
    $context = Context::getContext();
    $id_shop_group = Shop::getGroupFromShop($context->shop->id, true);
    $day_start = Configuration::get('pm_review_days_start', null, $id_shop_group, $context->shop->id);
    $days = Configuration::get('pm_review_days', null, $id_shop_group, $context->shop->id);
    $orders = Db::getInstance()->executeS("SELECT * FROM "._DB_PREFIX_."orders WHERE is_send_review_email != 1 AND date_add > '".$day_start."'");
    $now = time();
    foreach ($orders as $key => $order) {
        if ($now - strtotime($order['date_add']) < $days*24*60*60) {
            unset($orders[$key]);
        }
    }
    return $orders;
}

function getOrdersToSendEmail2()
{
    $context = Context::getContext();
    $id_shop_group = Shop::getGroupFromShop($context->shop->id, true);
    $day_start = Configuration::get('pm_review_days_start', null, $id_shop_group, $context->shop->id);
    $days = Configuration::get('pm_review_days2', null, $id_shop_group, $context->shop->id);
    $orders = Db::getInstance()->executeS("SELECT * FROM "._DB_PREFIX_."orders WHERE is_send_review_email2 != 1 AND date_add > '".$day_start."'");
    $now = time();
    foreach ($orders as $key => $order) {
        if ($now - strtotime($order['date_add']) < $days*24*60*60) {
            unset($orders[$key]);
        }
    }
    return $orders;
}

function sendEmail($order)
{
    $context = Context::getContext();
	$order_o = new Order($order['id_order']);
    $products = $order_o->getProductsDetail();
    $product_html = '<table>';
    foreach ($products as $product) {
        $product_o = new Product($product['id_product'], false, $order_o->id_lang);
        $images = $product_o->getImages((int)$order_o->id_lang);

		foreach ($images as $k => $image)
		{
			if ($image['cover'])
			{
				$cover = $image;
				$cover['id_image'] = (Configuration::get('PS_LEGACY_IMAGES') ? ($this->product->id.'-'.$image['id_image']) : $image['id_image']);
				$cover['id_image_only'] = (int)$image['id_image'];
			}
		}
        $product_html .= '<tr><td><a href="'.$context->link->getProductLink($product_o).'"><img src="'.$context->link->getImageLink($product_o->link_rewrite, $cover['id_image'],'small_default').'"></a></td><td><a href="'.$context->link->getProductLink($product_o).'">'.$product_o->name.'</a></td></tr>';
    }
    $data = array(
		'{products}' => $product_html,
	);
	$customer_o = new Customer($order['id_customer']);
	if (Mail::Send(
		(int)$order_o->id_lang,
		'email',
		'Получите купон за отзыв',
		$data,
		$customer_o->email,
		$customer_o->firstname.' '.$customer_o->lastname,
		null,
		null,
		null,
		null,
		_PS_MODULE_DIR_.'pmreview/mails/',
		false,
		(int)$order_o->id_shop
	)) {
	    Db::getInstance()->execute("UPDATE "._DB_PREFIX_."orders SET is_send_review_email = 1 WHERE id_order=".(int)$order_o->id);
	}
}

function sendEmail2($order)
{
    $context = Context::getContext();
	$order_o = new Order($order['id_order']);
    $products = $order_o->getProductsDetail();
    $product_html = '<table>';
    foreach ($products as $product) {
        $product_o = new Product($product['id_product'], false, $order_o->id_lang);
        $images = $product_o->getImages((int)$order_o->id_lang);

		foreach ($images as $k => $image)
		{
			if ($image['cover'])
			{
				$cover = $image;
				$cover['id_image'] = (Configuration::get('PS_LEGACY_IMAGES') ? ($this->product->id.'-'.$image['id_image']) : $image['id_image']);
				$cover['id_image_only'] = (int)$image['id_image'];
			}
		}
        $product_html .= '<tr><td><a href="'.$context->link->getProductLink($product_o).'"><img src="'.$context->link->getImageLink($product_o->link_rewrite, $cover['id_image'],'small_default').'"></a></td><td><a href="'.$context->link->getProductLink($product_o).'">'.$product_o->name.'</a></td></tr>';
    }
    $data = array(
		'{products}' => $product_html,
	);
	$customer_o = new Customer($order['id_customer']);
	if (Mail::Send(
		(int)$order_o->id_lang,
		'email2',
		'Получите купон за отзыв',
		$data,
		$customer_o->email,
		$customer_o->firstname.' '.$customer_o->lastname,
		null,
		null,
		null,
		null,
		_PS_MODULE_DIR_.'pmreview/mails/',
		false,
		(int)$order_o->id_shop
	)) {
	    Db::getInstance()->execute("UPDATE "._DB_PREFIX_."orders SET is_send_review_email2 = 1 WHERE id_order=".(int)$order_o->id);
	}
}