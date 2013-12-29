<?php namespace Breadcrumb;

/**
 * Part of the Gasoline framework
 *
 * @package     Gasoline
 * @version     1.0-dev
 * @author      Gasoline Development Teams
 * @license     MIT License
 * @copyright   2013 Gasoline Development Team
 * @link        http://hubspace.github.io/gasoline
 */

class Crumb extends \Gasoline\DataContainer {
    
    protected $data = array(
        'uri'           => null,
        'href'          => null,
        'attributes'    => array(),
        'last'          => false,
    );
    
}

/* End of file crumb.php */
/* Location: ./fuel/packages/breadcrumb/classes/crumb.php */
