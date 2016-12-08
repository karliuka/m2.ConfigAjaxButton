<?php
/**
 * Faonni
 *  
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Open Software License (OSL 3.0)
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://opensource.org/licenses/osl-3.0.php
 *
 *
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade module to newer
 * versions in the future.
 * 
 * @package     Faonni_ConfigAjaxButton
 * @copyright   Copyright (c) 2016 Karliuka Vitalii(karliuka.vitalii@gmail.com) 
 * @license     http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */
namespace Faonni\ConfigAjaxButton\Block\Adminhtml\System\Config;

/**
 * Faonni config ajax button block
 */
class Button extends \Magento\Config\Block\System\Config\Form\Field
{
    /**
     * Ajax Button Label
     *
     * @var string
     */
    protected $_buttonLabel = 'Ajax Button';
    
    /**
     * Ajax Button Params
     *
     * @var string
     */
    protected $_buttonParams;    

    /**
     * Set Ajax Button Label
     *
     * @param string $buttonLabel
     * @return \Faonni\ConfigAjaxButton\Block\Adminhtml\System\Config\Button
     */
    public function setButtonLabel($buttonLabel)
    {
        $this->_buttonLabel = $buttonLabel;
        return $this;
    }

    /**
     * Add param to button
     *
     * @param string $name	 
     * @param string $element
     * @return \Faonni\ConfigAjaxButton\Block\Adminhtml\System\Config\Button
     */
    public function addParam($name, $element)
    {
		$this->_buttonParams->addData($name, $element);		
        return $this;
    }
	
    /**
     * Overwrite params to button
     *
     * @param string|array $name	 
     * @param string $element
     * @return \Faonni\ConfigAjaxButton\Block\Adminhtml\System\Config\Button
     */
    public function setParam($name, $element=null)
    {
		$this->_buttonParams->setData($name, $element);		
        return $this;
    }
	
    /**
     * Set template to itself
     *
     * @return \Faonni\ConfigAjaxButton\Block\Adminhtml\System\Config\Button
     */
    protected function _prepareLayout()
    {
        parent::_prepareLayout();
        
        if (!$this->getTemplate()) {
            $this->setTemplate('system/config/button.phtml');
        }       
        $this->_buttonParams = new \Magento\Framework\DataObject();       
        return $this;
    }

    /**
     * Unset some non-related element parameters
     *
     * @param \Magento\Framework\Data\Form\Element\AbstractElement $element
     * @return string
     */
    public function render(\Magento\Framework\Data\Form\Element\AbstractElement $element)
    {
        $element->unsScope()->unsCanUseWebsiteValue()->unsCanUseDefaultValue();
        return parent::render($element);
    }

    /**
     * Get the button and scripts contents
     *
     * @param \Magento\Framework\Data\Form\Element\AbstractElement $element
     * @return string
     */
    protected function _getElementHtml(\Magento\Framework\Data\Form\Element\AbstractElement $element)
    {
        $originalData = $element->getOriginalData();
        $buttonLabel = !empty($originalData['button_label']) ? $originalData['button_label'] : $this->_buttonLabel;
        $this->addData(
            [
                'button_label' => __($buttonLabel),
                'html_id'  => $element->getHtmlId(),
                'ajax_url' => $this->_urlBuilder->getUrl('configajaxbutton/system_config_validate/button'),
                'js_function' => 'validateAjaxButton' . md5($element->getHtmlId()),
                'html_result_id' => 'ajax_button_validation_result_' . md5($element->getHtmlId()),
            ]
        );
        return $this->_toHtml();
    }
    
    /**
     * Get the button params
     *
     * @return \Magento\Framework\DataObject
     */
    public function getParams()
    {  
        $this->_eventManager->dispatch(
            'adminhtml_ajax_button_params', ['button' => $this]
        ); 
        $this->_eventManager->dispatch(
            'adminhtml_ajax_button_params_' . $this->getHtmlId(), ['button' => $this]
        );         
        return $this->_buttonParams;
    }    
}
