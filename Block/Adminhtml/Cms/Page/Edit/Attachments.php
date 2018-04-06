<?php
/**
 * Attachments
 * 
 * @author Slava Yurthev
 */
namespace SY\Attachments\Block\Adminhtml\Cms\Page\Edit;

class Attachments extends \Magento\Backend\Block\Template {
	protected $_template = 'attachments/grid.phtml';
	protected $_blockGrid;
	protected $_registry;
	public function __construct(
			\Magento\Backend\Block\Template\Context $context,
			\Magento\Framework\Registry $registry,
			array $data = []
		){
		$this->_registry = $registry;
		parent::__construct($context, $data);
	}
	public function getBlockGrid(){
		if ($this->_blockGrid === null) {
			$this->_blockGrid = $this->getLayout()->createBlock(
				'SY\Attachments\Block\Adminhtml\Cms\Page\Edit\Tab\Attachments',
				'cms.page.attachments.grid'
			);
		}
		return $this->_blockGrid;
	}
	public function getGridHtml(){
		return $this->getBlockGrid()->toHtml();
	}
	public function getSelectedJson(){
		try {
			return \Zend_Json::encode(\Zend_Json::decode($this->getCmsPage()->getData('attachments')));
		} catch (\Exception $e) {
			return \Zend_Json::encode([]);
		}
	}
	public function getCmsPage(){
		return $this->_registry->registry('cms_page');
	}
	public function getFormName(){
		return 'cms_page_form';
	}
	public function getFieldScopeName(){
		return 'attachments';
	}
}