<?php
namespace dsw\Lottery\Setup;

use Magento\Framework\Setup\InstallSchemaInterface;
use Magento\Framework\Setup\SchemaSetupInterface;
use Magento\Framework\Setup\ModuleContextInterface;

class InstallSchema implements InstallSchemaInterface
{
    public function install(SchemaSetupInterface $setup, ModuleContextInterface $context)
    {
        $installer = $setup;
        $installer->startSetup();
    
        if (!$installer->getConnection()->isTableExists('project_lottery')) {
            /**
             * Create table 'project_lottery'
             */
            $table = $installer->getConnection()->newTable(
                $installer->getTable('project_lottery')
            )->addColumn(
                'id',
                \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
                null,
                ['identity' => true, 'unsigned' => true, 'nullable' => false, 'primary' => true],
                'ID'
            )->addColumn(
                'customer_name',
                \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                255,
                ['nullable' => false],
                'Customer Name'
            )->addColumn(
                'customer_lastname',
                \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                255,
                ['nullable' => false],
                'Customer Lastname'
            )->addColumn(
                'product_details',
                \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                '64k',
                ['nullable' => true],
                'Product Details'
            )->addColumn(
                'winning_chance',
                \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
                null,
                ['nullable' => true],
                'Winning Chance'
            )->addColumn(
                'minimum_amount',
                \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
                null,
                ['nullable' => true],
                'Minimum Amount'
                )->addColumn(
                'date_time',
                 \Magento\Framework\DB\Ddl\Table::TYPE_TIMESTAMP,
                 null,
                ['nullable' => false, 'default' => \Magento\Framework\DB\Ddl\Table::TIMESTAMP_INIT],
                'date time'    
            )->setComment(
                'Project Lottery Table'
            );
            $installer->getConnection()->createTable($table);
        }
        $installer->endSetup();
    }
    
}
