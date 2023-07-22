<?php
/**
 * Content Plugin for Joomla! - Ads Inner
 *
 * @package     Joomla.Plugin
 * @subpackage  Content.AdsInner
 * @version     1.0
 * @since       1.0
 * @author      Salah JAAFAR
 * @authorEmail salah.jaafar@gmail.com
 * @link        https://www.techno.rn.tn
 * @license     GNU General Public License version 3 or later
 */

defined('_JEXEC') or die;

use Joomla\CMS\Plugin\CMSPlugin;

/**
 * Class PlgContentAdsInner
 *
 * @since  1.0
 */
class PlgContentAdsInner extends CMSPlugin
{
    /**
     * Event method onContentBeforeDisplay
     *
     * @param   string  $context  The context of the content being passed to the plugin
     * @param   object  $item     An object with a "text" property
     * @param   object  $params   Additional parameters
     * @param   int     $page     Optional page number
     *
     * @return  void
     *
     * @since 1.0
     */
    public function onContentBeforeDisplay(string $context, object $item, object $params, int $page = 0): void
    {
        if ($context === 'com_content.article')
        {
            $blocks = array();

            for ($i = 1; $i <= 15; $i++)
            {
                $block = $params->get('block_' . $i, '');
                $location = $params->get('block_' . $i . '_location', $i * 2);
                $blocks[] = $block;
                $locations[] = $location;
            }

            // Output article's content
            $item->text = $this->injectBlocks($item->text, $blocks, $locations);

            // Render modules and other plugins support
            $item->text = Joomla\CMS\HTML\HTMLHelper::contentPrepare($item->text);
        }
    }

    /**
     * Set custom block between paragraphs
     *
     * @param   string  $content   The full HTML from the article
     * @param   array   $blocks    The custom HTML of each set
     * @param   array   $location  Display $blocks after these paragraphs
     *
     * @return  string  The modified content with injected blocks
     *
     * @since 1.0
     */
    protected function injectBlocks(string $content, array $blocks = array(), array $location = array()): string
    {
        $close = '</p>';
        $paragraphs = explode($close, $content);
        $i = 0;

        foreach ($paragraphs as $index => $paragraph)
        {
            if (trim($paragraph))
            {
                $paragraphs[$index] .= $close;
            }

            if (in_array($index + 1, $location))
            {
                $paragraphs[$index] .= $blocks[$i];
                $i++;
                if ($i >= count($blocks))
                {
                    break;
                }
            }
        }

        return implode('', $paragraphs);
    }
}
