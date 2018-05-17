<?php

use think\migration\Migrator;
use think\migration\db\Column;

class AuthRule extends Migrator
{
    /**
     * Change Method.
     *
     * Write your reversible migrations using this method.
     *
     * More information on writing migrations is available here:
     * http://docs.phinx.org/en/latest/migrations.html#the-abstractmigration-class
     *
     * The following commands can be used in this method and Phinx will
     * automatically reverse them when rolling back:
     *
     *    createTable
     *    renameTable
     *    addColumn
     *    renameColumn
     *    addIndex
     *    addForeignKey
     *
     * Remember to call "create()" or "update()" and NOT "save()" when working
     * with the Table class.
     */
    public function change()
    {
        // create the table
        $table = $this->table('auth_rule',array('engine'=>'MyISAM'));
        $table->addColumn('name', 'string',array('limit' => 15,'default'=>'','comment'=>'控制器方法'))
            ->addColumn('title', 'string',array('limit' => 32,'default'=>md5('123456'),'comment'=>'名称'))
            ->addColumn('type', 'boolean',array('limit' => 1,'default'=>0,'comment'=>'类型'))
            ->addColumn('status', 'string',array('tinyint' => 32,'default'=>0,'comment'=>'状态'))
            ->addColumn('pid', 'integer',array('int' => 11,'default'=>0,'comment'=>'父id'))
            ->addColumn('icon', 'string',array('default'=>'','comment'=>'icon图标'))
            ->addColumn('sort', 'integer',array('int' => 4,'default'=>0,'comment'=>'排序'))
            ->addColumn('condition','string')
            ->addIndex(array('name'), array('unique' => true))
            ->create();
    }
}
