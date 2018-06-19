<?php
namespace Vvc\Task\Setup;

class InstallSchema implements \Magento\Framework\Setup\InstallSchemaInterface
{

	public function install(\Magento\Framework\Setup\SchemaSetupInterface $setup, \Magento\Framework\Setup\ModuleContextInterface $context)
	{
		echo "start create table";
		$installer = $setup;
		$installer->startSetup();
		if (!$installer->tableExists('customer_task')) {
			$table = $installer->getConnection()->newTable(
				$installer->getTable('customer_task')
			)
				->addColumn(
					'task_id',
					\Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
					11,
					[
						'identity' => true,
						'nullable' => false,
						'primary'  => true,
						'unsigned' => true,
					],
					'Task ID'
				)
				->addColumn(
					'task_name',
					\Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
					300,
					['nullable => true'],
					'Task Name'
				)
				->addColumn(
					'task_content',
					\Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
					600,
					['nullable => true'],
					'Task Content'
				)
				->addColumn(
					'start_date',
					\Magento\Framework\DB\Ddl\Table::TYPE_DATE,
					null,
					['nullable' => false],
					'Start Date'
				)
				->addColumn(
					'end_date',
					\Magento\Framework\DB\Ddl\Table::TYPE_DATE,
					null,
					['nullable' => false],
					'End date'
				)
				->addColumn(
					'status',
					\Magento\Framework\DB\Ddl\Table::TYPE_SMALLINT,
					null,
					['nullable' => false],
					'Status'
				)
				->addColumn(
					'assign_to',
					\Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
					255,
					[],
					'Assign_to'
				)
				->addColumn(
					'progress',
					\Magento\Framework\DB\Ddl\Table::TYPE_DECIMAL,
					null,
					['nullable' => false],
					'Progress'
				)
				->addColumn(
					'description',
					\Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
					600,
					['nullable' => false],
					'Description'
				)
				->addColumn(
					'priority',
					\Magento\Framework\DB\Ddl\Table::TYPE_SMALLINT,
					null,
					[],
					'Priority'
				)
				->addColumn(
					'created_at',
					\Magento\Framework\DB\Ddl\Table::TYPE_TIMESTAMP,
					null,
					['nullable' => false, 'default' => \Magento\Framework\DB\Ddl\Table::TIMESTAMP_INIT],
					'Created At'
				)->addColumn(
					'updated_at',
					\Magento\Framework\DB\Ddl\Table::TYPE_TIMESTAMP,
					null,
					['nullable' => false, 'default' => \Magento\Framework\DB\Ddl\Table::TIMESTAMP_INIT_UPDATE],
					'Updated At')
				->addColumn(
					'user_created',
					\Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
					null,
					[],
					'user_created'
				)
				->addColumn(
					'user_updated',
					\Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
					null,
					[],
					'user_updated'
				)				
				->setComment('Customer Task Table');
			$installer->getConnection()->createTable($table);

			$installer->getConnection()->addIndex(
				$installer->getTable('customer_task'),
				$setup->getIdxName(
					$installer->getTable('customer_task'),
					['task_name','task_content','assign_to','description'],
					\Magento\Framework\DB\Adapter\AdapterInterface::INDEX_TYPE_FULLTEXT
				),
				['task_name','task_content','assign_to','description'],
				\Magento\Framework\DB\Adapter\AdapterInterface::INDEX_TYPE_FULLTEXT
			);
		}
		$installer->endSetup();
	}
}