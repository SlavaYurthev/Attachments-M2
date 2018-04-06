<?php
/**
 * Attachments
 * 
 * @author Slava Yurthev
 */
namespace SY\Attachments\Block\Cms\Page;

class Attachments extends \Magento\Framework\View\Element\Template {
	protected $_collection;
	public function __construct(
			\Magento\Framework\View\Element\Template\Context $context,
			\Magento\Cms\Model\Page $cmsPage,
			\SY\Attachments\Model\ResourceModel\File\CollectionFactory $collectionFactory,
			array $data = []
		){
		$ids = [];
		try {
			$ids = \Zend_Json::decode($cmsPage->getData('attachments'));
		} catch (\Exception $e) {}
		$_collection = $collectionFactory->create();
		$_collection->addFieldToFilter('id', ['in'=>$ids]);
		$_collection->addFieldToFilter('active', true);
		$_collection->setOrder('sort', 'asc');
		parent::__construct($context, $data);
		$this->_collection = $_collection;
	}
	public function getCollection(){
		return $this->_collection;
	}
}