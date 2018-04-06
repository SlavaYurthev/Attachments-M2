<?php
/**
 * Attachments
 * 
 * @author Slava Yurthev
 */
namespace SY\Attachments\Controller\Adminhtml\Advertisment;

class Close extends \Magento\Backend\App\Action {
	protected $resultRedirectFactory;
	public function __construct(
		\Magento\Backend\App\Action\Context $context, 
		\Magento\Framework\Controller\Result\RedirectFactory $resultRedirectFactory
	){
		parent::__construct($context);
		$this->resultRedirectFactory = $resultRedirectFactory;
	}
	public function execute(){
		$time = strtotime('+7 days'); // by default
		if($this->_objectManager
			->get('Magento\Framework\App\Config\ScopeConfigInterface')
			->getValue(
				'sy_developer/advertisment/subscribed', 
				\Magento\Store\Model\ScopeInterface::SCOPE_WEBSITE
			) == 1){
			$time = strtotime('+30 days'); // if subscribed
		}
		$this->_objectManager->get('Magento\Framework\App\Config\Storage\WriterInterface')->save(
			'sy_developer/advertisment/time',  
			$time, 
			\Magento\Framework\App\Config\ScopeConfigInterface::SCOPE_TYPE_DEFAULT, 
			0
		);
		$this->_objectManager->get('Magento\Framework\App\Cache\Manager')->clean(['config']);
		$resultRedirectFactory = $this->resultRedirectFactory->create();
		$resultRedirectFactory->setUrl($this->_redirect->getRefererUrl());
		return $resultRedirectFactory;
	}
	protected function _isAllowed(){
		return true;
	}
}