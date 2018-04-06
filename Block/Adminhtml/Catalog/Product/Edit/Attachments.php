<?php
/**
 * Attachments
 * 
 * @author Slava Yurthev
 */
namespace SY\Attachments\Block\Adminhtml\Catalog\Product\Edit;

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
				'SY\Attachments\Block\Adminhtml\Catalog\Product\Edit\Tab\Attachments',
				'catalog.product.attachments.grid'
			);
		}
		return $this->_blockGrid;
	}
	public function getGridHtml(){
		return $this->getBlockGrid()->toHtml();
	}
	public function getSelectedJson(){
		try {
			return \Zend_Json::encode(\Zend_Json::decode($this->getProduct()->getData('attachments')));
		} catch (\Exception $e) {
			return \Zend_Json::encode([]);
		}
	}
	public function getProduct(){
		return $this->_registry->registry('product');
	}
	public function getFormName(){
		return 'product_form';
	}
	public function getFieldScopeName(){
		return 'product[attachments]';
	}
}