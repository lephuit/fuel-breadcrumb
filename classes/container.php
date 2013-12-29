<?php namespace Breadcrumb;

/**
 * Part of the Gasoline framework
 *
 * @package     Gasoline
 * @namespace   Breadcrumb
 * @version     1.0-dev
 * @author      Gasoline Development Teams
 * @license     MIT License
 * @copyright   2013 Gasoline Development Team
 * @link        http://hubspace.github.io/gasoline
 */

class Container extends \Gasoline\DataContainer {
    
    protected static $instance = null;
    
    public static function _init()
    {
        static::$instance = new static();
    }
    
    
    public static function instance()
    {
        return static::$instance;
    }
    
    
    
    public $crumbs = array();
    
    
    public function set($key, $value = null)
    {
        if ( $value instanceof Crumb )
        {
            $this->crumbs[$key] = $value;
            
            return $this;
        }
        
        return parent::set($key, $value);
    }
    
    
    public function set_crumb($href, $text = '', array $attributes = array())
    {
        // foreach ( $this->crumbs as $key => &$crumb )
        // {
        //     $crumb->set('last', false);
        // }
        
        if ( $this->crumbs )
        {
            $keys = array_keys($this->crumbs);
            $last_href = end($keys);
            $this->crumbs[$last_href] = $this->crumbs[$last_href]->set('last', false);
        }
        
        $last = true;
        $this->crumbs[$href] = new Crumb(compact('href', 'text', 'attributes', 'last'));
        
        return $this;
    }
    
    
    public function count()
    {
        return count($this->crumbs);
    }
    
    
    public function delete_crumb($href)
    {
        if ( isset($this->crumbs[$href]) )
        {
            unset($this->crumbs[$href]);
        }
        
        return $this;
    }
    
    
    public function get_crumbs()
    {
        return $this->crumbs;
    }
    
}

/* End of file container.php */
/* Location: ./fuel/packages/breadcrumb/classes/container.php */
