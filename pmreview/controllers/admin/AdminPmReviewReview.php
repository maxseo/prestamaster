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
use PrestaShop\PrestaShop\Adapter\Routing\AdminLinkBuilder;
use PrestaShop\PrestaShop\Adapter\Routing\LegacyHelperLinkBuilder;
use PrestaShop\PrestaShop\Adapter\SymfonyContainer;
use PrestaShop\PrestaShop\Core\Routing\EntityLinkBuilderFactory;
use PrestaShop\PrestaShop\Core\Routing\Exception\BuilderNotFoundException;

class AdminPmReviewReviewController extends ModuleAdminController
{

    public function __construct()
    {
        $this->table = 'pm_review';
        $this->className = 'pmReviewModel';
        $this->deleted = false;
        $this->module = 'pmreview';
        $this->bootstrap = true;
        parent::__construct();
        $this->fields_list = array(
            'id_pm_review' => array(
                'title' => $this->l('ID'),
                'width' => 25,
                'type' => 'text',
            ),
            'id_product' => array(
                'title' => $this->l('Product Id'),
                'width' => 100,
                'type' => 'text',
            ),
            'product_name' => array(
                'title' => $this->l('Review on product'),
                'width' => 175,
                'type' => 'text',
            ),
            'review' => array(
                'title' => $this->l('Comment'),
                'width' => 150,
                'type' => 'text',
            ),
            'fio' => array(
                'title' => $this->l('Имя'),
                'width' => 150,
                'type' => 'text',
            ),
            'shop_answer' => array(
                'title' => $this->l('Shop answer'),
                'active' => 'active',
                'type' => 'is_null',
                'class' => 'fixed-width-xs',
                'align' => 'center',
                'ajax' => true,
                'orderby' => false
            ),
            'active' => array(
                'title' => $this->l('Status'),
                'active' => 'active',
                'type' => 'bool',
                'class' => 'fixed-width-xs',
                'align' => 'center',
                'ajax' => true,
                'orderby' => false
            )
        );
        $this->bulk_actions = array(
            'delete' => array(
                'text' => $this->l('Delete selected'),
                'icon' => 'icon-trash',
                'confirm' => $this->l('Delete selected items?')
            )
        );
    }
    
    /*public function getList(
        $id_lang,
        $order_by = null,
        $order_way = null,
        $start = 0,
        $limit = null,
        $id_lang_shop = false
    )
    {
        $this->dispatchFieldsListingModifierEvent();

        $this->ensureListIdDefinition();

        
        $use_limit = true;
        if ($limit === false) {
            $use_limit = false;
        } elseif (empty($limit)) {
            if (isset($this->context->cookie->{$this->list_id.'_pagination'}) && $this->context->cookie->{$this->list_id.'_pagination'}) {
                $limit = $this->context->cookie->{$this->list_id.'_pagination'};
            } else {
                $limit = $this->_default_pagination;
            }
        }

        if (!Validate::isTableOrIdentifier($this->table)) {
            throw new PrestaShopException(sprintf('Table name %s is invalid:', $this->table));
        }
        $prefix = str_replace(array('admin', 'controller'), '', Tools::strtolower(get_class($this)));
        if (empty($order_by)) {
            if ($this->context->cookie->{$prefix.$this->list_id.'Orderby'}) {
                $order_by = $this->context->cookie->{$prefix.$this->list_id.'Orderby'};
            } elseif ($this->_orderBy) {
                $order_by = $this->_orderBy;
            } else {
                $order_by = $this->_defaultOrderBy;
            }
        }

        if (empty($order_way)) {
            if ($this->context->cookie->{$prefix.$this->list_id.'Orderway'}) {
                $order_way = $this->context->cookie->{$prefix.$this->list_id.'Orderway'};
            } elseif ($this->_orderWay) {
                $order_way = $this->_orderWay;
            } else {
                $order_way = $this->_defaultOrderWay;
            }
        }

        $limit = (int)Tools::getValue($this->list_id.'_pagination', $limit);
        if (in_array($limit, $this->_pagination) && $limit != $this->_default_pagination) {
            $this->context->cookie->{$this->list_id.'_pagination'} = $limit;
        } else {
            unset($this->context->cookie->{$this->list_id.'_pagination'});
        }

        
        if (!Validate::isOrderBy($order_by) || !Validate::isOrderWay($order_way)
            || !is_numeric($start) || !is_numeric($limit)
            || !Validate::isUnsignedId($id_lang)) {
            throw new PrestaShopException('get list params is not valid');
        }

        if (!isset($this->fields_list[$order_by]['order_key']) && isset($this->fields_list[$order_by]['filter_key'])) {
            $this->fields_list[$order_by]['order_key'] = $this->fields_list[$order_by]['filter_key'];
        }

        if (isset($this->fields_list[$order_by]) && isset($this->fields_list[$order_by]['order_key'])) {
            $order_by = $this->fields_list[$order_by]['order_key'];
        }

       
        $start = 0;
        if ((int)Tools::getValue('submitFilter'.$this->list_id)) {
            $start = ((int)Tools::getValue('submitFilter'.$this->list_id) - 1) * $limit;
        } elseif (empty($start) && isset($this->context->cookie->{$this->list_id.'_start'}) && Tools::isSubmit('export'.$this->table)) {
            $start = $this->context->cookie->{$this->list_id.'_start'};
        }

        // Either save or reset the offset in the cookie
        if ($start) {
            $this->context->cookie->{$this->list_id.'_start'} = $start;
        } elseif (isset($this->context->cookie->{$this->list_id.'_start'})) {
            unset($this->context->cookie->{$this->list_id.'_start'});
        }

        
        $this->_lang = (int)$id_lang;
        $this->_orderBy = $order_by;

        if (preg_match('/[.!]/', $order_by)) {
            $order_by_split = preg_split('/[.!]/', $order_by);
            $order_by = bqSQL($order_by_split[0]).'.`'.bqSQL($order_by_split[1]).'`';
        } elseif ($order_by) {
            $order_by = '`'.bqSQL($order_by).'`';
        }
        if($this->_orderByReference) {
            $order_by = $this->_orderByReference;
        }
        $this->_orderWay = Tools::strtoupper($order_way);

        
        $sql_table = $this->table == 'order' ? 'orders' : $this->table;

        // Add SQL shop restriction
        $select_shop = $join_shop = $where_shop = '';
        if ($this->shopLinkType) {
            $select_shop = ', shop.name as shop_name ';
            $join_shop = ' LEFT JOIN '._DB_PREFIX_.$this->shopLinkType.' shop
							ON a.id_'.$this->shopLinkType.' = shop.id_'.$this->shopLinkType;
            $where_shop = Shop::addSqlRestriction($this->shopShareDatas, 'a', $this->shopLinkType);
        }

        if ($this->multishop_context && Shop::isTableAssociated($this->table) && !empty($this->className)) {
            if (Shop::getContext() != Shop::CONTEXT_ALL || !$this->context->employee->isSuperAdmin()) {
                $test_join = !preg_match('#`?'.preg_quote(_DB_PREFIX_.$this->table.'_shop').'`? *sa#', $this->_join);
                if (Shop::isFeatureActive() && $test_join && Shop::isTableAssociated($this->table)) {
                    $this->_where .= ' AND EXISTS (
						SELECT 1
						FROM `'._DB_PREFIX_.$this->table.'_shop` sa
						WHERE a.'.$this->identifier.' = sa.'.$this->identifier.' AND sa.id_shop IN ('.implode(', ', Shop::getContextListShopID()).')
					)';
                }
            }
        }

        
        $lang_join = '';
        if ($this->lang) {
            $lang_join = 'LEFT JOIN `'._DB_PREFIX_.$this->table.'_lang` b ON (b.`'.$this->identifier.'` = a.`'.$this->identifier.'` AND b.`id_lang` = '.(int)$id_lang;
            if ($id_lang_shop) {
                if (!Shop::isFeatureActive()) {
                    $lang_join .= ' AND b.`id_shop` = '.(int)Configuration::get('PS_SHOP_DEFAULT');
                } elseif (Shop::getContext() == Shop::CONTEXT_SHOP) {
                    $lang_join .= ' AND b.`id_shop` = '.(int)$id_lang_shop;
                } else {
                    $lang_join .= ' AND b.`id_shop` = a.id_shop_default';
                }
            }
            $lang_join .= ')';
        }

        $having_clause = '';
        if (isset($this->_filterHaving) || isset($this->_having)) {
            $having_clause = ' HAVING ';
            if (isset($this->_filterHaving)) {
                $having_clause .= ltrim($this->_filterHaving, ' AND ');
            }
            if (isset($this->_having)) {
                $having_clause .= $this->_having.' ';
            }
        }

        do {
            $this->_listsql = '';

            if ($this->explicitSelect) {
                foreach ($this->fields_list as $key => $array_value) {
                    // Add it only if it is not already in $this->_select
                    if (isset($this->_select) && preg_match('/[\s]`?'.preg_quote($key, '/').'`?\s*,/', $this->_select)) {
                        continue;
                    }

                    if (isset($array_value['filter_key'])) {
                        $this->_listsql .= str_replace('!', '.`', $array_value['filter_key']).'` AS `'.$key.'`, ';
                    } elseif ($key == 'id_'.$this->table) {
                        $this->_listsql .= 'a.`'.bqSQL($key).'`, ';
                    } elseif ($key != 'image' && !preg_match('/'.preg_quote($key, '/').'/i', $this->_select)) {
                        $this->_listsql .= '`'.bqSQL($key).'`, ';
                    }
                }
                $this->_listsql = rtrim(trim($this->_listsql), ',');
            } else {
                $this->_listsql .= ($this->lang ? 'b.*,' : '').' a.*';
            }

            $this->_listsql .= '
			'.(isset($this->_select) ? ', '.rtrim($this->_select, ', ') : '').$select_shop;

            $sql_from = '
			FROM `'._DB_PREFIX_.$sql_table.'` a ';
            $sql_join = '
			'.$lang_join.'
			'.(isset($this->_join) ? $this->_join.' ' : '').'
			'.$join_shop;
            $sql_where = ' '.(isset($this->_where) ? $this->_where.' ' : '').($this->deleted ? 'AND a.`deleted` = 0 ' : '').
            (isset($this->_filter) ? $this->_filter : '').$where_shop.'
			'.(isset($this->_group) ? $this->_group.' ' : '').'
			'.$having_clause;
            $sql_order_by = ' ORDER BY '.((str_replace('`', '', $order_by) == $this->identifier) ? 'a.' : '').$order_by.' '.pSQL($order_way).
            ($this->_tmpTableFilter ? ') tmpTable WHERE 1'.$this->_tmpTableFilter : '');
            $sql_limit = ' '.(($use_limit === true) ? ' LIMIT '.(int)$start.', '.(int)$limit : '');

            if ($this->_use_found_rows || isset($this->_filterHaving) || isset($this->_having)) {
                $this->_listsql = 'SELECT * FROM (SELECT 
								'.($this->_tmpTableFilter ? ' * FROM (SELECT ' : '').$this->_listsql.$sql_from.$sql_join.')a WHERE 1 '.$sql_where.
                                $sql_order_by.$sql_limit;
                $list_count = 'SELECT FOUND_ROWS() AS `'._DB_PREFIX_.$this->table.'`';
            } else {
                $this->_listsql = 'SELECT
								'.($this->_tmpTableFilter ? ' * FROM (SELECT ' : '').$this->_listsql.$sql_from.$sql_join.' WHERE 1 '.$sql_where.
                                $sql_order_by.$sql_limit;
                $list_count = 'SELECT COUNT(*) AS `'._DB_PREFIX_.$this->table.'` '.$sql_from.$sql_join.' WHERE 1 '.$sql_where;
            }
            //var_dump($this->_listsql);die();
            $this->_list = Db::getInstance()->executeS($this->_listsql, true, false);

            if ($this->_list === false) {
                $this->_list_error = Db::getInstance()->getMsgError();
                break;
            }

            $this->_listTotal = Db::getInstance()->getValue($list_count, false);

            if ($use_limit === true) {
                $start = (int)$start - (int)$limit;
                if ($start < 0) {
                    break;
                }
            } else {
                break;
            }
        } while (empty($this->_list));

        Hook::exec('action'.$this->controller_name.'ListingResultsModifier', array(
            'list' => &$this->_list,
            'list_total' => &$this->_listTotal,
        ));
    }*/
    
    public function processFilter()
    {
        Hook::exec('action'.$this->controller_name.'ListingFieldsModifier', array(
            'fields' => &$this->fields_list,
        ));

        $this->ensureListIdDefinition();

        $prefix = $this->getCookieFilterPrefix();

        if (isset($this->list_id)) {
            foreach ($_POST as $key => $value) {
                if ($value === '') {
                    unset($this->context->cookie->{$prefix.$key});
                } elseif (stripos($key, $this->list_id.'Filter_') === 0) {
                    $this->context->cookie->{$prefix.$key} = !is_array($value) ? $value : serialize($value);
                } elseif (stripos($key, 'submitFilter') === 0) {
                    $this->context->cookie->$key = !is_array($value) ? $value : serialize($value);
                }
            }

            foreach ($_GET as $key => $value) {
                if (stripos($key, $this->list_id.'Filter_') === 0) {
                    $this->context->cookie->{$prefix.$key} = !is_array($value) ? $value : serialize($value);
                } elseif (stripos($key, 'submitFilter') === 0) {
                    $this->context->cookie->$key = !is_array($value) ? $value : serialize($value);
                }
                if (stripos($key, $this->list_id.'Orderby') === 0 && Validate::isOrderBy($value)) {
                    if ($value === '' || $value == $this->_defaultOrderBy) {
                        unset($this->context->cookie->{$prefix.$key});
                    } else {
                        $this->context->cookie->{$prefix.$key} = $value;
                    }
                } elseif (stripos($key, $this->list_id.'Orderway') === 0 && Validate::isOrderWay($value)) {
                    if ($value === '' || $value == $this->_defaultOrderWay) {
                        unset($this->context->cookie->{$prefix.$key});
                    } else {
                        $this->context->cookie->{$prefix.$key} = $value;
                    }
                }
            }
        }

        $filters = $this->context->cookie->getFamily($prefix.$this->list_id.'Filter_');
        $definition = false;
        if (isset($this->className) && $this->className) {
            $definition = ObjectModel::getDefinition($this->className);
        }

        foreach ($filters as $key => $value) {
            /* Extracting filters from $_POST on key filter_ */
            if ($value != null && !strncmp($key, $prefix.$this->list_id.'Filter_', 7 + Tools::strlen($prefix.$this->list_id))) {
                $key = Tools::substr($key, 7 + Tools::strlen($prefix.$this->list_id));
                /* Table alias could be specified using a ! eg. alias!field */
                $tmp_tab = explode('!', $key);
                $filter = count($tmp_tab) > 1 ? $tmp_tab[1] : $tmp_tab[0];

                if ($field = $this->filterToField($key, $filter)) {
                    $type = (array_key_exists('filter_type', $field) ? $field['filter_type'] : (array_key_exists('type', $field) ? $field['type'] : false));
                    if (($type == 'date' || $type == 'datetime') && is_string($value)) {
                        $value = Tools::unSerialize($value);
                    }
                    $key = isset($tmp_tab[1]) ? $tmp_tab[0].'.`'.$tmp_tab[1].'`' : '`'.$tmp_tab[0].'`';

                    // Assignment by reference
                    if (array_key_exists('tmpTableFilter', $field)) {
                        $sql_filter = & $this->_tmpTableFilter;
                    } elseif (array_key_exists('havingFilter', $field)) {
                        $sql_filter = & $this->_filterHaving;
                    } else {
                        $sql_filter = & $this->_filter;
                    }

                    /* Only for date filtering (from, to) */
                    if (is_array($value)) {
                        if (isset($value[0]) && !empty($value[0])) {
                            if (!Validate::isDate($value[0])) {
                                $this->errors[] = Tools::displayError('The \'From\' date format is invalid (YYYY-MM-DD)');
                            } else {
                                $sql_filter .= ' AND '.pSQL($key).' >= \''.pSQL(Tools::dateFrom($value[0])).'\'';
                            }
                        }

                        if (isset($value[1]) && !empty($value[1])) {
                            if (!Validate::isDate($value[1])) {
                                $this->errors[] = Tools::displayError('The \'To\' date format is invalid (YYYY-MM-DD)');
                            } else {
                                $sql_filter .= ' AND '.pSQL($key).' <= \''.pSQL(Tools::dateTo($value[1])).'\'';
                            }
                        }
                    } else {
                        $sql_filter .= ' AND ';
                        $check_key = ($key == $this->identifier || $key == '`'.$this->identifier.'`');
                        $alias = ($definition && !empty($definition['fields'][$filter]['shop'])) ? 'sa' : 'a';

                        if ($type == 'int' || $type == 'bool') {
                            $sql_filter .= (($check_key || $key == '`active`') ?  $alias.'.' : '').pSQL($key).' = '.(int)$value.' ';
                        } elseif ($type == 'decimal') {
                            $sql_filter .= ($check_key ?  $alias.'.' : '').pSQL($key).' = '.(float)$value.' ';
                        } elseif ($type == 'select') {
                            $sql_filter .= ($check_key ?  $alias.'.' : '').pSQL($key).' = \''.pSQL($value).'\' ';
                        } elseif ($type == 'price') {
                            $value = (float)str_replace(',', '.', $value);
                            $sql_filter .= ($check_key ?  $alias.'.' : '').pSQL($key).' = '.pSQL(trim($value)).' ';
                        } elseif ($type == 'is_null') {
                            $sql_filter .= ($check_key ?  $alias.'.' : '').pSQL($key).' '.pSQL($value).' ';
                        } else {
                            $sql_filter .= ($check_key ?  $alias.'.' : '').pSQL($key).' LIKE \'%'.pSQL(trim($value)).'%\' ';
                            if ($key == '`reference`') {
                                $this->_orderByReference =  'udf_NaturalSortFormat(`reference`, 10, ".")';/*' SUBSTR('.($check_key ?  $alias.'.' : '').pSQL($key).' FROM 1 FOR 1),
                                    CAST(SUBSTR('.($check_key ?  $alias.'.' : '').pSQL($key).' FROM 2) AS UNSIGNED)';*/
                            }
                        }
                    }
                }
            }
        }
    }

    public function renderList()
    {
        if (isset($this->_filter) && trim($this->_filter) == '') {
            $this->_filter = $this->original_filter;
        }
        $id_shop = Shop::getContextShopID(true);
        if (!Configuration::get('PS_MULTISHOP_FEATURE_ACTIVE')) {
            $id_shop = 1;
        }
        $this->addRowAction('edit');
        $this->addRowAction('answer');
        $this->addRowAction('delete');
        $this->_join .= ' LEFT JOIN '._DB_PREFIX_.'product_lang pl ON (a.id_product = pl.id_product AND pl.id_lang = '.(int)$this->context->language->id.')';
        $this->_select .= ' pl.name as product_name, (SELECT DISTINCT pr.id_pm_review  FROM '._DB_PREFIX_.'pm_review pr WHERE pr.id_parent=a.id_pm_review GROUP BY pr.id_parent) as shop_answer';
        $this->_where .= " AND a.id_parent = 0";
        if (!($this->fields_list && is_array($this->fields_list))) {
            return false;
        }
        $this->getList($this->context->language->id);

        // If list has 'active' field, we automatically create bulk action
        if (array_key_exists('active', $this->fields_list) && $this->fields_list['active'] == true) {
            if (!is_array($this->bulk_actions)) {
                $this->bulk_actions = [];
            }

            $this->bulk_actions = array_merge([
                'enableSelection' => [
                    'text' => $this->trans('Enable selection', [], 'Admin.Actions'),
                    'icon' => 'icon-power-off text-success',
                ],
                'disableSelection' => [
                    'text' => $this->trans('Disable selection', [], 'Admin.Actions'),
                    'icon' => 'icon-power-off text-danger',
                ],
                'divider' => [
                    'text' => 'divider',
                ],
            ], $this->bulk_actions);
        }

        $helper = new HelperList();
        $helper->base_folder = _PS_MODULE_DIR_.'pmreview/views/templates/admin/_configure/helpers/list/';

        // Empty list is ok
        if (!is_array($this->_list)) {
            $this->displayWarning($this->trans('Bad SQL query', [], 'Admin.Notifications.Error') . '<br />' . htmlspecialchars($this->_list_error));

            return false;
        }

        $this->setHelperDisplay($helper);
        $helper->_default_pagination = $this->_default_pagination;
        $helper->_pagination = $this->_pagination;
        $helper->tpl_vars = $this->getTemplateListVars();
        $helper->tpl_delete_link_vars = $this->tpl_delete_link_vars;
        
        
        
        $helper->is_cms = $this->is_cms;
        $helper->sql = $this->_listsql;
        $list = $helper->generateList($this->_list, $this->fields_list);

        return $list;
    }
    
    public function displayAnswerLink($token, $id, $name = null)
    {
        $helper = new HelperList();
        $tpl = $helper->createTemplate('list_action_answer.tpl');
        if (!array_key_exists('Answer', self::$cache_lang)) {
            self::$cache_lang['Answer'] = Context::getContext()->getTranslator()->trans('Answer', [], 'Admin.Actions');
        }
        
        $href = 'index.php?controller=AdminPmReviewReview&answerpm_review=&id_pm_review='.$id.'&token='.$token;
        
        $tpl->assign([
            'href' => $href,
            'action' => self::$cache_lang['Answer'],
            'id' => $id,
        ]);

        return $tpl->fetch();
    }
    
    public function initContent()
    {
        //parent::initContent();
        if (Tools::isSubmit('answerpm_review')) {
            $this->content .= $this->renderAnswer();
            $this->context->smarty->assign(array(
                'content' => $this->content,
            ));
        } elseif (Tools::isSubmit('editanswerpm_review')) {
            $this->content .= $this->renderAnswer('edit');
            $this->context->smarty->assign(array(
                'content' => $this->content,
            ));
        } else {
            if (Tools::isSubmit('answerReview')) {
                $id_answer_review = (int)Tools::getValue('id_review');
                $review = new pmReviewModel($id_answer_review);
                $images = $this->uploadImages();
                $review->images = $review->images.($images ? ','.$images : '');
                $review->review = pSql(Tools::getValue('review'));
                $review->fio = 'Ответ магазина';
                $review->id_parent = (int)Tools::getValue('id_parent_review');
                $review->active = (int)Tools::getValue('active');
                $review->id_shop = (int)$this->context->shop->id;
                if($review->id){
                    $review->update();
                } else {
                    $review->add();
                }
            }
            parent::initContent();
        }
    }
    
    private function uploadImages()
    {
        $total = count($_FILES['images']['name']);
        if($total){
            $images = '';
            for( $i=0 ; $i < $total ; $i++ ) {
                $file_tmp_path = $_FILES['images']['tmp_name'][$i];
                $file_name = $_FILES['images']['name'][$i];
                $file_name_exploded = explode(".", $file_name);
                $file_extension = Tools::strtolower(end($file_name_exploded));
                $new_file_name = md5(time() . $file_name) . '.' . $file_extension;
                $allowed_file_extensions = array('jpg', 'jpeg','gif', 'png', 'svg');
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
    
    public function renderAnswer($mode = 'add')
    {
        if($mode == 'add'){
            $id_parent_review = Tools::getValue('id_pm_review');
        } else {
            $id_review = Tools::getValue('id_pm_review');
            $review_o = new pmReviewModel($id_review);
            $images = explode(',', $review_o->images);
        }
        $this->fields_form = array(
            'legend' => array(
                'title' => $this->l('Ответ магазина'),
            ),
            'input' => array(
                array(
                    'type' => 'hidden',
                    'name' => 'id_review',
                ),
                array(
                    'type' => 'hidden',
                    'name' => 'id_parent_review',
                ),
                array(
                    'type' => 'textarea',
                    'label' => $this->l('Ответ'),
                    'name' => 'review'
                ),
                array(
                    'type' => 'file',
                    'label' => $this->l('Изображения'),
                    'name' => 'images',
                     'multiple' => true
                ),
                array(
                    'type' => 'review_image',
                    'label' => $this->l('Картинка отзыва'),
                    'name' => 'review_image',
                    'images' => $images,
                    'reviews_img_path' => Tools::getHttpHost(true).'/modules/pmreview/views/img/',
                    'review_img_dir' => _PS_MODULE_DIR_."pmreview/views/img/"
                ),
                array(
                    'type' => 'switch',
                    'label' => $this->l('Status'),
                    'name' => 'active',
                    'class' => 't',
                    'is_bool' => true,
                    'values' => array(
                        array(
                            'id' => 'active_en',
                            'value' => 1,
                            'label' => $this->l('Enabled')
                        ),
                        array(
                            'id' => 'active_en',
                            'value' => 0,
                            'label' => $this->l('Disabled')
                        )
                    )
                )
            ),
            'submit' => array(
                'title' => $this->l('Save'),
                'class' => 'btn btn-default pull-right',
                'name' => 'answerReview'
            )
        );
        
        $this->fields_value['id_review'] = $id_review;
        $this->fields_value['id_parent_review'] = ($review_o->id_parent ? $review_o->id_parent : $id_parent_review);
        $this->fields_value['review'] = $review_o->review;
        $this->fields_value['active'] = $review_o->active;
        if (!$this->default_form_language) {
            $this->getLanguages();
        }

        if (Tools::getValue('submitFormAjax')) {
            $this->content .= $this->context->smarty->fetch('form_submit_ajax.tpl');
        }

        if ($this->fields_form && is_array($this->fields_form)) {
            if (!$this->multiple_fieldsets) {
                $this->fields_form = array(array('form' => $this->fields_form));
            }

            // For add a fields via an override of $fields_form, use $fields_form_override
            if (is_array($this->fields_form_override) && !empty($this->fields_form_override)) {
                $this->fields_form[0]['form']['input'] = array_merge($this->fields_form[0]['form']['input'], $this->fields_form_override);
            }

            $fields_value = $this->getFieldsValue($this->object);

            Hook::exec('action'.$this->controller_name.'FormModifier', array(
                'fields' => &$this->fields_form,
                'fields_value' => &$fields_value,
                'form_vars' => &$this->tpl_form_vars,
            ));

            $helper = new HelperForm($this);
            $this->setHelperDisplay($helper);
            $helper->fields_value = $fields_value;
            $helper->submit_action = 'submitanswer';
            $helper->tpl_vars = $this->getTemplateFormVars();
            $helper->show_cancel_button = (isset($this->show_form_cancel_button)) ? $this->show_form_cancel_button : ($this->display == 'add' || $this->display == 'edit');

            $back = Tools::safeOutput(Tools::getValue('back', ''));
            if (empty($back)) {
                $back = self::$currentIndex.'&token='.$this->token;
            }
            if (!Validate::isCleanHtml($back)) {
                die(Tools::displayError());
            }

            $helper->back_url = $back;
            !is_null($this->base_tpl_form) ? $helper->base_tpl = $this->base_tpl_form : '';
            if ($this->tabAccess['view']) {
                if (Tools::getValue('back')) {
                    $helper->tpl_vars['back'] = Tools::safeOutput(Tools::getValue('back'));
                } else {
                    $helper->tpl_vars['back'] = Tools::safeOutput(Tools::getValue(self::$currentIndex.'&token='.$this->token));
                }
            }
            $form = $helper->generateForm($this->fields_form);

            return $form;
        }
    }

    public function init()
    {
        parent::init();
        $this->_defaultOrderBy = 'a.id_pm_review';
        $this->_defaultOrderWay = 'DESC';
        if (Tools::isSubmit('action')) {
            $this->changeCommentStatus();
        }
    }
    
    public function setMedia($isNewTheme = false)
    {
        parent::setMedia($isNewTheme);
        $this->addJqueryUi('ui.widget');
        $this->addJqueryPlugin('tagify');
    }
    
    public function renderForm()
    {
        $id_review = Tools::getValue('id_pm_review');
        if (isset($id_review) && $id_review) {
            $review_o = new pmReviewModel((int)$id_review);
        } else {
            $review_o = new pmReviewModel();
        }
        if (isset($review_o->id) && $review_o->id) {
            if (isset($review_o->cover) && $review_o->cover) {
                $post_cover = '<img src="'._MODULE_DIR_.'pmreviews/views/img/'.$review_o->cover.'" height="150" />';
            }
        } else {
            $post_cover = '';
        }
        if ($id_review) {
            $id_answer = pmReviewModel::getAnswerId($id_review);
            if($id_answer) {
                $rewiev_answer = new pmReviewModel($id_answer);
               $helper = new HelperList();
                $helper->identifier = 'id_pm_review';
                $helper->table ='pm_review';
                $helper->token = $this->token;
                $helper->currentIndex = self::$currentIndex;
                $button = $helper->displayEditLink(Tools::getAdminToken($this->controller_name . (int) $this->id . (int) $this->context->employee->id), $rewiev_answer->id);
            }
        }
        $images = explode(',', $review_o->images);
        $this->fields_form = array(
            'legend' => array(
                'title' => $this->l('Comment'),
            ),
            'input' => array(
                array(
                    'type' => 'hidden',
                    'name' => 'id_review',
                ),
                array(
                    'type' => 'textarea',
                    'label' => $this->l('Комментарий'),
                    'name' => 'review'
                ),
                array(
                    'type' => 'review_image',
                    'label' => $this->l('Картинка отзыва'),
                    'name' => 'review_image',
                    'images' => $images,
                    'reviews_img_path' => Tools::getHttpHost(true).'/modules/pmreview/views/img/',
                    'review_img_dir' => _PS_MODULE_DIR_."pmreview/views/img/"
                ),
                array(
                    'type' => 'switch',
                    'label' => $this->l('Status'),
                    'name' => 'active',
                    'class' => 't',
                    'is_bool' => true,
                    'values' => array(
                        array(
                            'id' => 'active_en',
                            'value' => 1,
                            'label' => $this->l('Enabled')
                        ),
                        array(
                            'id' => 'active_en',
                            'value' => 0,
                            'label' => $this->l('Disabled')
                        )
                    )
                ),
                array(
                    'type' => 'answer',
                    'label' => $this->l('Ответ магазина'),
                    'name' => 'answer',
                    'answer_review' => $rewiev_answer,
                    'button' => $button
                )
            ),
            'submit' => array(
                'title' => $this->l('Save'),
                'class' => 'btn btn-default pull-right'
            )
        );
        
        $this->fields_value['id_review'] = $review_o->id;
        $this->fields_value['review'] = $review_o->review;
        $this->fields_value['minuses'] = $review_o->minuses;
        $this->fields_value['active'] = $review_o->active;
        return parent::renderForm();
    }
    
    public function changeCommentStatus()
    {
        if (!$id_pm_review = (int) Tools::getValue('id_pm_review')) {
            die(json_encode(array('success' => false, 'error' => true, 'text' => $this->trans('Failed to update the status', array(), 'Admin.Notifications.Error'))));
        } else {
            $review = new pmReviewModel((int) $id_pm_review);
            $review->active = $review->active == 1 ? 0 : 1;
            if ($review->save()) {
                if ($review->id_customer && $review->active = 1) {
                    $customer = new Customer($review->id_customer);
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
                    $discount = Configuration::get('pm_review_discount', null, $id_shop_group, $id_shop);
                    $coupon = $this->createCoupon($customer, $discount);
                    $data = [
                        '{code}' => $coupon->code
                    ];
                    
                    Mail::Send(
                		(int)$this->context->language->id,
                		'coupon',
                		'Kупон за отзыв',
                		$data,
                		$customer->email,
                		$customer->firstname.' '.$customer->lastname,
                		null,
                		null,
                		null,
                		null,
                		_PS_MODULE_DIR_.'pmreview/mails/',
                		false,
                		(int)$id_shop
                	);
                }
                die(json_encode(array('success' => true, 'text' => $this->trans('The status has been updated successfully', array(), 'Admin.Notifications.Success'))));
            } else {
                die(json_encode(array('success' => false, 'error' => true, 'text' => $this->trans('Failed to update the status', array(), 'Admin.Notifications.Success'))));
            }
        }
    }
    
    public function createCoupon($customer, $discount)
    {
        $cart_rule = new CartRule();
        $cart_rule->id_customer = (int)$customer->id;
        $cart_rule->date_from = date("Y-m-d H:i:s");
        $cart_rule->date_to = date("Y-m-d H:i:s", strtotime('+5 year'));
        $cart_rule->active = 1;
        $cart_rule->reduction_percent = $discount;
        $cart_rule->quantity_per_user = 1;
        $cart_rule->quantity = 1;
        $cart_rule->code = $this->generateRandomString(8);
        $languages = Language::getLanguages();
        foreach($languages as $language) {
            $cart_rule->name[$language['id_lang']] = $customer->id.'-'.$customer->email;
        }
        $cart_rule->add();
        return $cart_rule;
    }
    
    public function generateRandomString($length = 10) {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }
        return $randomString;
    }
}
