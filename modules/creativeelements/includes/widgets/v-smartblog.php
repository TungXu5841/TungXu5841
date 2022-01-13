<?php
/**
 * Creative Elements - live PageBuilder
 *
 * @author    WebshopWorks, Elementor
 * @copyright 2019-2021 WebshopWorks.com & Elementor.com
 * @license   https://www.gnu.org/licenses/gpl-3.0.html
 */

namespace CE;

defined('_PS_VERSION_') or die;

/**
 * Elementor heading widget.
 *
 * Elementor widget that displays an eye-catching headlines.
 *
 * @since 1.0.0
 */
class WidgetVSmartblog extends WidgetBase
{
    /**
     * Get widget name.
     *
     * Retrieve heading widget name.
     *
     * @since 1.0.0
     * @access public
     *
     * @return string Widget name.
     */
    public function getName()
    {
        return 'v-smartblog';
    }

    /**
     * Get widget title.
     *
     * Retrieve heading widget title.
     *
     * @since 1.0.0
     * @access public
     *
     * @return string Widget title.
     */
    public function getTitle()
    {
        return __('Latest post');
    }

    /**
     * Get widget icon.
     *
     * Retrieve heading widget icon.
     *
     * @since 1.0.0
     * @access public
     *
     * @return string Widget icon.
     */
    public function getIcon()
    {
        return 'eicon-commenting-o';
    }

    /**
     * Get widget categories.
     *
     * Retrieve the list of categories the heading widget belongs to.
     *
     * Used to determine where to display the widget in the editor.
     *
     * @since 2.0.0
     * @access public
     *
     * @return array Widget categories.
     */
    public function getCategories()
    {
        return ['premium'];
    }

    /**
     * Get widget keywords.
     *
     * Retrieve the list of keywords the widget belongs to.
     *
     * @since 2.1.0
     * @access public
     *
     * @return array Widget keywords.
     */
    public function getKeywords()
    {
        return ['blog'];
    }

    /**
     * Register heading widget controls.
     *
     * Adds different input fields to allow the user to change and customize the widget settings.
     *
     * @since 1.0.0
     * @access protected
     */
    protected function _registerControls()
    {
        $this->startControlsSection(
            'section_content',
            [
                'label' => __('Content'),
            ]
        );

        $this->addControl(
            'limit',
            [
                'label' => __('Number post to show'),
                'type' => ControlsManager::NUMBER,
            ]
        );
        $this->addControl(
            'style',
            [
                'label' => __('Display'),
                'type' => ControlsManager::SELECT,
                'default' => 'style1',
                'options' => [
                    ''       => __('Default'),
                    'style1' => __('Style 1'),
                    'style2' => __('Style 2'),
                    'style3' => __('Style 3'),
                    'style4' => __('Style 4'),
                ],
            ]
        );

        $this->endControlsSection();
    }

    /**
     * Render heading widget output on the frontend.
     *
     * Written in PHP and used to generate the final HTML.
     *
     * @since 1.0.0
     * @access protected
     */
    protected function render()
    {
        $settings = $this->getSettingsForDisplay();

        if (empty($settings['title'])) {
            return;
        }

        $this->addRenderAttribute('title', 'class', 'elementor-heading-title');

        if (!empty($settings['size'])) {
            $this->addRenderAttribute('title', 'class', 'elementor-size-' . $settings['size']);
        }

        $this->addInlineEditingAttributes('title');

        $title = $settings['title'];

        if (!empty($settings['link']['url'])) {
            $this->addRenderAttribute('url', 'href', $settings['link']['url']);

            if ($settings['link']['is_external']) {
                $this->addRenderAttribute('url', 'target', '_blank');
            }

            if (!empty($settings['link']['nofollow'])) {
                $this->addRenderAttribute('url', 'rel', 'nofollow');
            }

            $title = sprintf('<a %1$s>%2$s</a>', $this->getRenderAttributeString('url'), $title);
        }

        echo sprintf(
            '<%1$s %2$s>%3$s</%1$s>',
            $settings['header_size'],
            $this->getRenderAttributeString('title'),
            $title
        );
    }

    /**
     * Render heading widget output in the editor.
     *
     * Written as a Backbone JavaScript template and used to generate the live preview.
     *
     * @since 1.0.0
     * @access protected
     */
    protected function _contentTemplate(){}
}
