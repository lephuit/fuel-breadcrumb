<?php

/**
 * Part of the Gasoline framework
 *
 * @package    Gasoline
 * @version    0.1-dev
 * @author     Gasoline Development Teams
 * @license    MIT License
 * @copyright  2013 Gasoline Development Team
 * @link       http://hubspace.github.io/gasoline
 */

return array(
    'use_views' => true,
    'use_lang'  => false,
    
    'template'  => array(
        'wrapper_open'  => '<ul class="breadcrumb" :attr>',
        'wrapper_close' => '</ul>',
        'item_open'     => '<li :attr>',
        'item_close'    => '</li>',
        'class_active'  => 'active',
        'divider'       => '<span class="divider">/</span>',
    ),
);

/* End of file breadcrumb.php */
/* Location: ./fuel/packages/breadcrumb/config/breadcrumb.php */
