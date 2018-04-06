<?php
/**
 * Attachments
 * 
 * @author Slava Yurthev
 */
namespace SY\Attachments\Setup;

use Magento\Framework\Setup\InstallSchemaInterface;
use Magento\Framework\Setup\SchemaSetupInterface;
use Magento\Framework\Setup\ModuleContextInterface;

class InstallSchema implements InstallSchemaInterface {
	public function install(SchemaSetupInterface $setup, ModuleContextInterface $context) {
		$setup->startSetup();

		$table = $setup->getConnection()->newTable($setup->getTable('sy_attachments_files'))
			->addColumn(
				'id',
				\Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
				null,
				[
					'identity' => true, 
					'unsigned' => true, 
					'nullable' => false, 
					'primary' => true
				],
				'Id'
			)->addColumn(
				'extension',
				\Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
				255,
				[
					'nullable' => true
				],
				'Extension'
			)->addColumn(
				'size',
				\Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
				255,
				[
					'nullable' => true
				],
				'Size'
			)->addColumn(
				'path',
				\Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
				255,
				[
					'nullable' => true
				],
				'Path'
			)->addColumn(
				'filename',
				\Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
				255,
				[
					'nullable' => true
				],
				'Filename'
			)->addColumn(
				'basename',
				\Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
				255,
				[
					'nullable' => true
				],
				'Basename'
			)->addColumn(
				'active',
				\Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
				1,
				[
					'nullable' => false,
					'default' => '1'
				],
				'Active'
			)->addColumn(
				'sort',
				\Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
				1,
				[
					'nullable' => false,
					'default' => '0'
				],
				'Sort'
			)->addColumn(
				'edited',
				\Magento\Framework\DB\Ddl\Table::TYPE_TIMESTAMP,
				null,
				[
					'nullable' => false,
					'default' => \Magento\Framework\DB\Ddl\Table::TIMESTAMP_INIT_UPDATE
				],
				'Edited'
			)->setComment(
				'Files Table'
			);
		$setup->getConnection()->createTable($table);

		$setup->endSetup();
	}
}