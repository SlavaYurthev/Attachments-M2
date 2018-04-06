<?php
/**
 * Attachments
 * 
 * @author Slava Yurthev
 */
namespace SY\Attachments\Block\Product\View;

class Attachments extends \Magento\Framework\View\Element\Template {
	protected $_collection;
	public function __construct(
			\Magento\Framework\View\Element\Template\Context $context,
			\Magento\Framework\Registry $registry,
			\SY\Attachments\Model\ResourceModel\File\CollectionFactory $collectionFactory,
			array $data = []
		){
		$_product = $registry->registry('current_product');
		$ids = [];
		try {
			$ids = \Zend_Json::decode($_product->getData('attachments'));
		} catch (\Exception $e) {}
		$_collection = $collectionFactory->create();
		$_collection->addFieldToFilter('id', ['in'=>$ids]);
		$_collection->addFieldToFilter('active', true);
		$_collection->setOrder('sort', 'asc');
		parent::__construct($context, $data);
		$this->setData('title', __('Attachments (%1)', $_collection->count()));
		$this->_collection = $_collection;
	}
	public function getCollection(){
		return $this->_collection;
	}
}