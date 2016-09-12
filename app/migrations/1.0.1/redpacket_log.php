<?php 

use Phalcon\Db\Column;
use Phalcon\Db\Index;
use Phalcon\Db\Reference;
use Phalcon\Mvc\Model\Migration;

/**
 * Class RedpacketLogMigration_101
 */
class RedpacketLogMigration_101 extends Migration
{
    /**
     * Define the table structure
     *
     * @return void
     */
    public function morph()
    {
        $this->morphTable('redpacket_log', array(
                'columns' => array(
                    new Column(
                        'id',
                        array(
                            'type' => Column::TYPE_INTEGER,
                            'unsigned' => true,
                            'notNull' => true,
                            'autoIncrement' => true,
                            'size' => 11,
                            'first' => true
                        )
                    ),
                    new Column(
                        'redpacketId',
                        array(
                            'type' => Column::TYPE_INTEGER,
                            'notNull' => true,
                            'size' => 11,
                            'after' => 'id'
                        )
                    ),
                    new Column(
                        'prizeId',
                        array(
                            'type' => Column::TYPE_INTEGER,
                            'notNull' => true,
                            'size' => 11,
                            'after' => 'redpacketId'
                        )
                    ),
                    new Column(
                        'openId',
                        array(
                            'type' => Column::TYPE_VARCHAR,
                            'notNull' => true,
                            'size' => 128,
                            'after' => 'prizeId'
                        )
                    ),
                    new Column(
                        'amount',
                        array(
                            'type' => Column::TYPE_INTEGER,
                            'notNull' => true,
                            'size' => 11,
                            'after' => 'openId'
                        )
                    ),
                    new Column(
                        'code',
                        array(
                            'type' => Column::TYPE_VARCHAR,
                            'size' => 64,
                            'after' => 'amount'
                        )
                    ),
                    new Column(
                        'msg',
                        array(
                            'type' => Column::TYPE_VARCHAR,
                            'size' => 255,
                            'after' => 'code'
                        )
                    ),
                    new Column(
                        'sign',
                        array(
                            'type' => Column::TYPE_VARCHAR,
                            'size' => 255,
                            'after' => 'msg'
                        )
                    ),
                    new Column(
                        'resultCode',
                        array(
                            'type' => Column::TYPE_VARCHAR,
                            'size' => 255,
                            'after' => 'sign'
                        )
                    ),
                    new Column(
                        'errCode',
                        array(
                            'type' => Column::TYPE_VARCHAR,
                            'size' => 255,
                            'after' => 'resultCode'
                        )
                    ),
                    new Column(
                        'errCodeDes',
                        array(
                            'type' => Column::TYPE_VARCHAR,
                            'size' => 255,
                            'after' => 'errCode'
                        )
                    ),
                    new Column(
                        'mchBillNo',
                        array(
                            'type' => Column::TYPE_VARCHAR,
                            'size' => 128,
                            'after' => 'errCodeDes'
                        )
                    ),
                    new Column(
                        'mchId',
                        array(
                            'type' => Column::TYPE_VARCHAR,
                            'size' => 128,
                            'after' => 'mchBillNo'
                        )
                    ),
                    new Column(
                        'sentAt',
                        array(
                            'type' => Column::TYPE_VARCHAR,
                            'size' => 32,
                            'after' => 'mchId'
                        )
                    ),
                    new Column(
                        'sentListId',
                        array(
                            'type' => Column::TYPE_VARCHAR,
                            'size' => 255,
                            'after' => 'sentAt'
                        )
                    ),
                    new Column(
                        'ipAddr',
                        array(
                            'type' => Column::TYPE_VARCHAR,
                            'size' => 255,
                            'after' => 'sentListId'
                        )
                    ),
                    new Column(
                        'created',
                        array(
                            'type' => Column::TYPE_INTEGER,
                            'notNull' => true,
                            'size' => 11,
                            'after' => 'ipAddr'
                        )
                    ),
                    new Column(
                        'updated',
                        array(
                            'type' => Column::TYPE_INTEGER,
                            'notNull' => true,
                            'size' => 11,
                            'after' => 'created'
                        )
                    )
                ),
                'indexes' => array(
                    new Index('PRIMARY', array('id')),
                    new Index('redpacketId', array('redpacketId')),
                    new Index('prizeId', array('prizeId')),
                    new Index('openId', array('openId')),
                    new Index('amount', array('amount')),
                    new Index('code', array('code')),
                    new Index('resultCode', array('resultCode')),
                    new Index('created', array('created'))
                ),
                'options' => array(
                    'TABLE_TYPE' => 'BASE TABLE',
                    'AUTO_INCREMENT' => '1',
                    'ENGINE' => 'InnoDB',
                    'TABLE_COLLATION' => 'utf8_general_ci'
                ),
            )
        );
    }

    /**
     * Run the migrations
     *
     * @return void
     */
    public function up()
    {

    }

    /**
     * Reverse the migrations
     *
     * @return void
     */
    public function down()
    {

    }

}
