<?php
/**
 * Attachments
 * 
 * @author Slava Yurthev
 */
namespace SY\Attachments\Setup;

use Magento\Eav\Setup\EavSetup;
use Magento\Eav\Setup\EavSetupFactory;
use Magento\Framework\Setup\UpgradeSchemaInterface;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\SchemaSetupInterface;

class UpgradeSchema implements UpgradeSchemaInterface {
	public function upgrade(SchemaSetupInterface $setup, ModuleContextInterface $context){
		$setup->startSetup();
		if(version_compare($context->getVersion(), '0.1.0') < 0) {
			$tableName = $setup->getTable('cms_page');
			if($setup->getConnection()->isTableExists($tableName) == true) {
				$columns = [
					'attachments' => [
						'type' => \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
						'nullable' => true,
						'comment' => 'Attachments',
					]
				];
				$connection = $setup->getConnection();
				foreach ($columns as $name => $definition) {
					$connection->addColumn($tableName, $name, $definition);
				}
			}
		}
		$setup->endSetup();
	}
}