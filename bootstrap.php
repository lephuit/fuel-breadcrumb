<?php

/**
 * Part of the Gasoline framework
 *
 * @package     Gasoline
 * @namespace   Breadcrumb
 * @version     0.1-dev
 * @author      Gasoline Development Team
 * @license     MIT License
 * @copyright   2013 Gasoline Development Team
 * @link        http://hubspace.github.io/gasoline
 */

Autoloader::add_core_namespace('Breadcrumb');

Autoloader::add_classes(array(
    'Breadcrumb\\Crumb'     => __DIR__ . '/classes/crumb.php',
    'Breadcrumb\\Container' => __DIR__ . '/classes/container.php',
    'Breadcrumb\\Render'    => __DIR__ . '/classes/render.php',
    
    'Breadcrumb\\Render_Bootstrap'  => __DIR__ . '/classes/render/bootstrap.php',
));

/* End of file bootstrap.php */
/* Location: ./fuel/packages/breadcrumb/bootstrap.php */

