<?php namespace Breadcrumb;

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

class Breadcrumb {
    
    /**
     * Initialization function
     * 
     * @access  public
     * @static
     * 
     * @return  void
     */
    public static function _init()
    {
        \Config::load('breadcrumb', true);
    }
    
    /**
     * Avoid instantiation of the class
     */
    public function __construct() {}
    
    
    /**
     * Storage for the breadcrumbs
     * 
     * @access  protected
     * @static
     * @var     array
     */
    protected static $_crumbs = array();
    
    
    /**
     * Storage for attributes on the breadcrumb-object itself
     * 
     * @access  protected
     * @static
     * @var     array
     */
    protected static $_attributes = array();
    
    
    /**
     * Default configuration options. Overwritten by config/breadcrumb.php
     * 
     * @access  protected
     * @static
     * @var     array
     */
    protected static $_config = array(
        'use_views' => true,
        'use_lang'  => false,
    );
    
    
    /**
     * Default template when not using views
     * 
     * @access  protected
     * @static
     * @var     array
     */
    protected static $template = array(
        'wrapper_open'  => '<ul class="breadcrumb">',
        'wrapper_close' => '</ul>',
        'item_open'     => '<li :attr>',
        'item_close'    => '</li>',
        'class_active'  => 'active',
        'divider'       => '<span class="divider">/</span>',
    );
    
    
    
    
    
    //--------------------------------------------------------------------------
    
    /**
     * Add a breadcrumb to the items
     * 
     * @access  public
     * @static
     * @param   string|array    $uri    The uri to point to or an array that represents
     *                                  the arguments passed to this method. Either as
     *                                  associative or numerical-array
     * @param   string          $text   The text to display in the list. Defaults
     *                                  to '' which will display the content of $uri
     * @param   array           $attributes     An array of attributes to add to
     *                                          the wrapper that was defined in
     *                                          config/breadcrump.template.wrapper_open
     *                                          by :attr
     * 
     * @return  void
     */
    public static function add($uri, $text = '', $attributes = array())
    {
        if ( is_array($uri) )
        {
            if ( \Arr::is_assoc($uri, true) )
            {
                $item = \Arr::filter_keys($uri, array('uri', 'text', 'attributes'));
            }
            else
            {
                $item = array_combine(array('uri', 'text', 'attributes'), $uri);
            }
            
            extract($item);
        }
        else
        {
            $item = compact('uri', 'text', 'attributes');
        }
        
        $item['uri'] = trim($item['uri']);
        empty($item['text']) && $item['text'] = $item['uri'];
        
        $item['active'] = false;
        
        static::$_crumbs[] = $item;
        
        return count(static::$_crumbs) - 1;
    }
    
    
    //--------------------------------------------------------------------------
    
    /**
     * Reset the array of breadcrumb items
     * 
     * @access  public
     * @static
     * @return  void
     */
    public static function reset()
    {
        static::$_crumbs = array();
    }
    
    
    //--------------------------------------------------------------------------
    
    /**
     * Set an attribute of the breadcrumb wraper
     * 
     * @access  public
     * @static
     * @param   string  $attribute  The attribute to set. Will override previously
     *                              set attributes
     * @param   mixed   $value      The value to set
     * 
     * @return  void
     */
    public static function set($attribute, $value = null)
    {
        static::$_attributes[$attribute] = $value;
    }
    
    
    //--------------------------------------------------------------------------
    
    /**
     * Get an attribute of the breadcrumb-wrapper
     * 
     * @access  public
     * @static
     * @param   string  $attribute  The attribute to get
     * @param   mixed   $default    Default value to return if attribute cannot be
     *                              found
     *
     * @return  mixed|null          Returns the attribute requested or $default = null
     *                              when the attribute cannot be found
     */
    public static function get($attribute, $default = null)
    {
        return ( isset(static::$_attributes[$attribute]) ? static::$_attributes[$attribute] : $default );
    }
    
    
    //--------------------------------------------------------------------------
    
    /**
     * Renders the breadcrumb either by a view or using the configured template
     * 
     * @access  public
     * @static
     * 
     * @return  string  Returns the formatted breadcrumb-menu
     */
    public static function render()
    {
        if ( ! static::$_crumbs )
        {
            return '';
        }
        
        // Mark the last one as active;
        $last_idx = count(static::$_crumbs) - 1;
        static::$_crumbs[$last_idx]['active'] = true;
        
        // Initialize our parsed result
        $parsed = '';
        
        // Do we use views?
        if ( \Config::get('breadcrumb.use_views', static::$_config['use_views']) )
        {
            try
            {
                $theme = \Theme::instance();
            }
            catch ( \Exception $e )
            {
                logger(\Fuel::L_WARNING, 'Cannot get an instance of \Theme', __METHOD__);
                
                return '';
            }
            
            try
            {
                $parsed = $theme->view('_layouts/breadcrumb/breadcrumb', array('crumbs' => static::$_crumbs), FALSE);
            }
            // Didn't work...
            catch ( \Exception $e )
            {
                // Log a debug message
                logger(\Fuel::L_DEBUG, 'Cannot find view for type breadcrumbs', __METHOD__);
            }
        }
        else
        {
            $wrapper_open  = \Config::get('breadcrumb.template.wrapper_open',  static::$template['wrapper_open']);
            $wrapper_close = \Config::get('breadcrumb.template.wrapper_close', static::$template['wrapper_close']);
            
            $item_open  = \Config::get('breadcrumb.template.item_open',  static::$template['item_open']);
            $item_close = \Config::get('breadcrumb.template.item_close', static::$template['item_close']);
            
            $class_active = \Config::get('breadcrumb.template.class_active', static::$template['class_active']);
            
            $divider = \Config::get('breadcrumb.template.divider', static::$template['divider']);
            
            $parsed = static::_parse_attributes($wrapper_open);
            
            $use_lang = \Config::get('breadcrumb.use_lang', static::$_config['use_lang']);
            
            foreach ( static::$_crumbs as $k => $crumb )
            {
                if ( static::$_crumbs[$last_idx]['active'] == true )
                {
                    static::$_crumbs[$last_idx]['attributes']['class'] = ( isset(static::$_crumbs[$last_idx]['attributes']['class']) ? static::$_crumbs[$last_idx]['attributes']['class'] . ' ' : '' ) . $class_active;
                }
                
                $parsed .= static::_parse_attributes($item_open, $crumb['attributes']) . \Html::anchor($crumb['uri'], ( $use_lang ? __($crumb['text']) : $crumb['text'] ));
                
                $k < $last_idx && $parsed .= $divider;
                
                $parsed .= $item_close;
            }
            
            $parsed .= $wrapper_close;
        }
        
        return $parsed;
    }
    
    
    //--------------------------------------------------------------------------
    
    /**
     * Parse attributes within the given subject
     * 
     * @access  protected
     * @static
     * @param   string  $subject        Where to replace in. Must have an :attr
     *                                  part to have it replaced
     * @param   array   $attributes     The attributes to use. If set to null, will
     *                                  use the global object attributes
     * 
     * @return  string  Returns the replaced string
     */
    protected static function _parse_attributes($subject, $attributes = null)
    {
        $attributes === null && $attributes = static::$_attributes;
        
        return str_replace(':attr', array_to_attr($attributes), $subject);
    }
    
    
    //--------------------------------------------------------------------------
    
    /**
     * Magically get an attribute
     * 
     * @access  public
     * @static 
     * @param   string  $attribute  The attribute to get
     *
     * @return  mixed   Returns the attributes value or null
     */
    public function __get($attribute)
    {
        return static::$get($attribute);
    }
    
    
    /**
     * Magically set an attribute
     * 
     * @access  public
     * @static
     * @param   string  $attribute  The attribute to set. Overrides previously
     *                              set attribute.
     * @param   mixed   $value      Value to set for the attribute.
     */
    public function __set($attribute, $value)
    {
        return static::$set($attribute, $value);
    }
    
    
    //--------------------------------------------------------------------------
    
    /**
     * Magically check whether an attribute is set
     * 
     * @access  public
     * @static
     * @param   string  $attribute The attribute to check for existance
     * 
     * @return  bool    Returns true if the attribute exists, otherwise false
     */
    public function __isset($attribute)
    {
        return isset(static::$_attributes[$attribute]);
    }
    
}

/* End of file breadcrumb.php */
/* Location: ./fuel/packages/breadcrumb/classes/breadcrumb.php */
