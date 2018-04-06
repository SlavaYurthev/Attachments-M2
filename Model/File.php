<?php
/**
 * Attachments
 * 
 * @author Slava Yurthev
 */
namespace SY\Attachments\Model;

use Magento\Framework\Model\AbstractModel;

class File extends AbstractModel {
	protected $urlInterface;
	protected $_objectManager;
	public function __construct(
		\Magento\Framework\Model\Context $context,
		\Magento\Framework\Registry $registry,
		\Magento\Framework\UrlInterface $urlInterface,
		\Magento\Framework\ObjectManagerInterface $objectmanager
		){
		$this->urlInterface = $urlInterface;
		$this->_objectManager = $objectmanager;
		parent::__construct($context, $registry);
	}
	protected function _construct() {
		$this->_init('SY\Attachments\Model\ResourceModel\File');
	}
	public function getUrl(){
		return $this->urlInterface->getUrl(
				'attachments/download/file', 
				[
					'id' => $this->getData('id')
				]
			).$this->getData('basename');
	}
	public function afterDelete(){
		$objectManager = \Magento\Framework\App\ObjectManager::getInstance();
		$directory = $this->_objectManager->get('\Magento\Framework\Filesystem\DirectoryList');
		$io = $this->_objectManager->get('Magento\Framework\Filesystem\Io\File');
		try {
			$io->rmdir($directory->getRoot().'/media/attachments/files/'.$this->getData('id').'/', true);
		} catch (\Exception $e) {}
		parent::afterDelete();
	}
}