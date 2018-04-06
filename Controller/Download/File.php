<?php
/**
 * Attachments
 * 
 * @author Slava Yurthev
 */
namespace SY\Attachments\Controller\Download;

use Magento\Framework\App\Action\Action;

class File extends Action {
	protected $resultPageFactory;
	public function __construct(
		\Magento\Framework\App\Action\Context $context,
		\Magento\Framework\View\Result\PageFactory $resultPageFactory
	){
		$this->resultPageFactory = $resultPageFactory;
		parent::__construct($context);
	}
	public function execute() {
		$id = $this->getRequest()->getParam('id');
		$file = $this->_objectManager->get('SY\Attachments\Model\File')->load($id);
		if($file->getId()){
			$directory = $this->_objectManager->get('\Magento\Framework\Filesystem\DirectoryList');
			if(is_file($directory->getRoot().$file->getData('path'))){
				header('Content-Type: application/download');
				header('Content-Disposition: attachment; filename="'.$file->getData('basename').'"');
				header("Content-Length: ".$file->getData('size'));
				readfile($directory->getRoot().$file->getData('path'));
				return;
			}
			else{
				$this->_forward('index', 'noroute', 'cms');
			}
		}
		else{
			$this->_forward('index', 'noroute', 'cms');
		}
	}
}