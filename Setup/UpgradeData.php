<?php
/**
 * Attachments
 * 
 * @author Slava Yurthev
 */
namespace SY\Attachments\Setup;

use Magento\Catalog\Model\Product;
use Magento\Eav\Setup\EavSetupFactory;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\ModuleDataSetupInterface;
use Magento\Framework\Setup\UpgradeDataInterface;

class UpgradeData implements UpgradeDataInterface {
	private $eavSetupFactory;
	public function __construct(
			EavSetupFactory $eavSetupFactory
		) {
		$this->eavSetupFactory = $eavSetupFactory;
	}
	public function upgrade(ModuleDataSetupInterface $setup, ModuleContextInterface $context) {
		$setup->startSetup();
		if(version_compare($context->getVersion(), '0.1.0') < 0) {
			$eavSetup = $this->eavSetupFactory->create(['setup' => $setup]);
			$eavSetup->addAttribute(
				\Magento\Catalog\Model\Product::ENTITY,
				'attachments',
				[
					'type' => 'text',
					'backend' => '',
					'frontend' => '',
					'label' => 'Attachments',
					'input' => '',
					'class' => '',
					'source' => '',
					'global' => \Magento\Eav\Model\Entity\Attribute\ScopedAttributeInterface::SCOPE_GLOBAL,
					'visible' => false,
					'required' => false,
					'user_defined' => false,
					'default' => '',
					'searchable' => false,
					'filterable' => false,
					'comparable' => false,
					'visible_on_front' => false,
					'used_in_product_listing' => false,
					'unique' => false,
					'apply_to' => ''
				]
			);
		}
		$setup->endSetup();
	}
}