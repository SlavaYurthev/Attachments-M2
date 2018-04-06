<?php
/**
 * Attachments
 * 
 * @author Slava Yurthev
 */
namespace SY\Attachments\Block\Adminhtml\Files\Edit\Tab;
 
use Magento\Backend\Block\Widget\Form\Generic;
use Magento\Backend\Block\Widget\Tab\TabInterface;
use Magento\Backend\Block\Template\Context;
use Magento\Framework\Registry;
use Magento\Framework\Data\FormFactory;
use Magento\Cms\Model\Wysiwyg\Config;
 
class General extends Generic implements TabInterface {
	protected $_wysiwygConfig;
	protected $_newsStatus;
	public function __construct(
		Context $context,
		Registry $registry,
		FormFactory $formFactory,
		Config $wysiwygConfig,
		array $data = []
	) {
		$this->_wysiwygConfig = $wysiwygConfig;
		parent::__construct($context, $registry, $formFactory, $data);
	}
	private function convertPHPSizeToBytes($sSize) {  
		if (is_numeric($sSize)){
	   		return $sSize;
		}
		$sSuffix = substr($sSize, -1);  
		$iValue = substr($sSize, 0, -1);  
		switch(strtoupper($sSuffix)){  
			case 'P':  
				$iValue *= 1024;  
			case 'T':  
				$iValue *= 1024;  
			case 'G':  
				$iValue *= 1024;  
			case 'M':  
				$iValue *= 1024;  
			case 'K':  
			$iValue *= 1024;  
			break;  
		}  
		return $iValue;  
	}
	protected function _prepareForm(){
		$model = $this->_coreRegistry->registry('file');
		$form = $this->_formFactory->create();

		$fieldset = $form->addFieldset(
			'base_fieldset',
			['legend' => __('General')]
		);

		if ($model->getId()) {
			$fieldset->addField(
				'id',
				'hidden',
				['name' => 'id']
			);
		}
		if($model->getData('path')){
			$fieldset->addField(
				'path',
				'text',
				[
					'name' => 'path',
					'label'	=> __('Path'),
					'required' => false,
					'readonly' => true
				]
			);
		}
		$phpMaxSize = $this->convertPHPSizeToBytes(ini_get('upload_max_filesize'));
		$newValidationJs = "
<script type='text/javascript'>
require([
	'jquery',
	'jquery/ui',
	'jquery/validate',
	'mage/translate'
], function ($) {
	$.validator.addMethod(
		'validate-filesize', 
		function (v, elm) {
				var maxSize = ".$phpMaxSize.";
				if (navigator.appName == 'Microsoft Internet Explorer') {
					if (elm.value) {
						var oas = new ActiveXObject('Scripting.FileSystemObject');
						var e = oas.getFile(elm.value);
						var size = e.size;
					}
				} else {
					if (elm.files[0] != undefined) {
						size = elm.files[0].size;
					}
				}
				if (size != undefined && size > maxSize) {
					return false;
				}
				return true;
		}, 
		$.mage.__('The file size should not exceed ".ini_get('upload_max_filesize')."')
	);
});
</script>
		";
		$fieldset->addField(
			'file',
			'file',
			[
				'name' => 'file',
				'label'	=> __('File'),
				'required' => false,
				'class' => 'validate-filesize',
				'after_element_html' => $newValidationJs
			]
		);
		$fieldset->addField(
			'filename',
			'text',
			[
				'name' => 'filename',
				'label'	=> __('Filename'),
				'required' => false,
				'note' => __('If the field is empty, will be used file pathinfo($file)["filename"]')
			]
		);
		$fieldset->addField(
			'basename',
			'text',
			[
				'name' => 'basename',
				'label'	=> __('Basename'),
				'required' => false,
				'note' => __('If the field is empty, will be used file basename()')
			]
		);
		$fieldset->addField(
			'active',
			'select',
			[
				'name' => 'active',
				'label'	=> __('Active'),
				'required' => true,
				'values' => [
					['value'=>"1",'label'=>__('Yes')],
					['value'=>"0",'label'=>__('No')]
				]
			]
		);
		if(!$model->getData('sort')){
			$model->setData('sort', 0);
		}
		$fieldset->addField(
			'sort',
			'text',
			[
				'name' => 'sort',
				'label'	=> __('Sort'),
				'required' => true
			]
		);
		if($model->getData('edited')){
			$fieldset->addField(
				'edited',
				'date',
				[
					'name' => 'edited',
					'label'	=> __('Edited'),
					'required' => false,
					'date_format' => 'yyyy-MM-dd',
					'time_format' => 'hh:mm:ss'
				]
			);
		}
		$data = $model->getData();
		$form->setValues($data);
		$this->setForm($form);
 
		return parent::_prepareForm();
	}
	public function getTabLabel(){
		return __('File');
	}
	public function getTabTitle(){
		return __('File');
	}
	public function canShowTab(){
		return true;
	}
	public function isHidden(){
		return false;
	}
}