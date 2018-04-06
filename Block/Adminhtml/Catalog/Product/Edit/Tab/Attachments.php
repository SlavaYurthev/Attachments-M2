<?php
/**
 * Attachments
 * 
 * @author Slava Yurthev
 */
namespace SY\Attachments\Block\Adminhtml\Catalog\Product\Edit\Tab;

class Attachments extends \Magento\Backend\Block\Widget\Grid\Extended {
	protected $logger;
	protected $_coreRegistry = null;
	protected $_collection;
	public function __construct(
			\Magento\Backend\Block\Template\Context $context,
			\Magento\Backend\Helper\Data $backendHelper,
			\SY\Attachments\Model\File $file,
			\Magento\Framework\Registry $coreRegistry,
			array $data = []
		){
		$this->_coreRegistry = $coreRegistry;
		$this->_collection = $file->getCollection();
		parent::__construct($context, $backendHelper, $data);
	}
	protected function _construct(){
		parent::_construct();
		$this->setId('page_attachments_grid');
		$this->setDefaultSort('id');
		$this->setUseAjax(true);
	}
	public function getProduct(){
		return $this->_coreRegistry->registry('product');
	}
	protected function _prepareCollection(){
		$this->setCollection($this->_collection);
		return parent::_prepareCollection();
	}
	protected function _prepareColumns(){
		$values = [];
		try {
			if($this->getProduct()){
				$values = \Zend_Json::decode($this->getProduct()->getData('attachments'));
				$values = array_values($values);
			}
		} catch (\Exception $e) {}
		$this->addColumn(
			'show',
			[
				'type' => 'checkbox',
				'header' => __('Show'),
				'sortable' => true,
				'index' => 'id',
				'values' => $values,
				'header_css_class' => 'col-select col-massaction',
				'column_css_class' => 'col-select col-massaction'
			]
		);
		$this->addColumn(
			'id',
			[
				'header' => __('ID'),
				'sortable' => true,
				'index' => 'id',
				'header_css_class' => 'col-id',
				'column_css_class' => 'col-id'
			]
		);
		$this->addColumn(
			'filename',
			[
				'header' => __('Filename'),
				'sortable' => true,
				'index' => 'filename',
				'header_css_class' => 'col-filename',
				'column_css_class' => 'col-filename'
			]
		);
		$this->addColumn(
			'basename',
			[
				'header' => __('Basename'),
				'sortable' => true,
				'index' => 'basename',
				'header_css_class' => 'col-basename',
				'column_css_class' => 'col-basename'
			]
		);
		$this->addColumn(
			'path',
			[
				'header' => __('Path'),
				'sortable' => true,
				'index' => 'path',
				'header_css_class' => 'col-path',
				'column_css_class' => 'col-path'
			]
		);
	}
	public function getGridUrl(){
		return $this->getUrl('sy_attachments/product/grid', ['_current' => true]);
	}
}