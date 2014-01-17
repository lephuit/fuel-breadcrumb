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

class Render_Bootstrap extends Render {
    
    public function render(Container $container)
    {
        $list = '';
        
        if ( $crumbs = $container->get_crumbs() )
        {
            foreach ( $crumbs as $crumb )
            {
                $list .= $this->renderCrumb($crumb) . PHP_EOL;
            }
        }
        
        return ( $crumbs ? html_tag('ul', array('class' => 'breadcrumb'), $list) : $list );
    }
    
    
    public function renderCrumb(Crumb $crumb)
    {
        return html_tag(
            'li',
            array(
                'class' =>
                ( $crumb->get('last', false)
                    ? 'active'
                    : ''
                )
            ),
            $crumb->get('last', false)
                ? html_tag('span', $crumb->get('attributes', array()), $crumb->get('text', ''))
                : \Html::anchor(
                    $crumb->get('href', '#'),
                    $crumb->get('text'),
                    $crumb->get('attributes', array())
                )
        );
    }

}

/* End of file bootstrap.php */
/* Location: ./fuel/packages/breadcrumb/classes/render/bootstrap.php */
