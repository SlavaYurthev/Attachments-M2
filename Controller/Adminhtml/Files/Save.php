<?php
/**
 * Attachments
 * 
 * @author Slava Yurthev
 */
namespace SY\Attachments\Controller\Adminhtml\Files;

use \Magento\Backend\App\Action;
use \Magento\Backend\App\Action\Context;
use \Magento\Framework\View\Result\PageFactory;

class Save extends Action {
	protected $_resultPageFactory;
	protected $_resultPage;
	public function __construct(
			Context $context, 
			PageFactory $resultPageFactory
		){
		parent::__construct($context);
		$this->_resultPageFactory = $resultPageFactory;
	}
	public function execute(){
		$object_manager = $this->_objectManager;
		$directory = $object_manager->get('\Magento\Framework\Filesystem\DirectoryList');
		$data = $this->getRequest()->getPostValue();
		$resultRedirect = $this->resultRedirectFactory->create();
		$id = $this->getRequest()->getParam('id');
		$model = $this->_objectManager->create('SY\Attachments\Model\File');
		$older = false;
		if($id) {
			$model->load($id);
			$older = $model->getData('path');
		}
		$model->setData($data);
		try {
			$model->save();
			try {
				$uploader = new \Magento\MediaStorage\Model\File\Uploader(
					'file',
					$object_manager->create('Magento\MediaStorage\Helper\File\Storage\Database'),
					$object_manager->create('Magento\MediaStorage\Helper\File\Storage'),
					$object_manager->create('Magento\MediaStorage\Model\File\Validator\NotProtectedExtension')
				);
				$uploader->setAllowCreateFolders(true);
				$path = '/media/attachments/files/'.$model->getId().'/'.md5(time());
				if ($uploader->save($directory->getRoot().$path)) {	
					// Remove older file	
					if($older != false){
						$io = $this->_objectManager->get('Magento\Framework\Filesystem\Io\File');
						$io->rmdir(dirname($directory->getRoot().$older), true);
					}
					$filename = $uploader->getUploadedFileName();
					$model->setData('path', $path.'/'.$filename);
					$model->setData('extension', @pathinfo($filename)['extension']);
					$model->setData('size', @filesize($directory->getRoot().$path.'/'.$filename));
					if(!$model->getData('basename')){
						$model->setData('basename', basename($filename));
					}
					if(!$model->getData('filename')){
						$model->setData('filename', @pathinfo($filename)['filename']);
					}
					try {
						$model->save();
					} catch (\Exception $e) {}
				}
			} catch (\Exception $e) {}
			$this->messageManager->addSuccess(__('Saved.'));
			if ($this->getRequest()->getParam('back')) {
				return $resultRedirect->setPath('*/*/edit', ['id' => $model->getId(), '_current' => true]);
			}
			$this->_objectManager->get('Magento\Backend\Model\Session')->setFormData(false);
			return $resultRedirect->setPath('*/*/');
		} catch (\Exception $e) {
			$this->messageManager->addException($e, __('Something went wrong.'));
		}
		$this->_getSession()->setFormData($data);
		return $resultRedirect->setPath('*/*/edit', ['id' => $this->getRequest()->getParam('id')]);
	}
	protected function _isAllowed(){
		return $this->_authorization->isAllowed('SY_Attachments::files');
	}
}