<?php 

use Phalcon\Db\Column;
use Phalcon\Db\Index;
use Phalcon\Db\Reference;
use Phalcon\Mvc\Model\Migration;

/**
 * Class AttachmentMigration_102
 */
class AttachmentMigration_102 extends Migration
{
    /**
     * Define the table structure
     *
     * @return void
     */
    public function morph()
    {
        $this->morphTable('attachment', array(
                'columns' => array(
                    new Column(
                        'id',
                        array(
                            'type' => Column::TYPE_INTEGER,
                            'unsigned' => true,
                            'notNull' => true,
                            'autoIncrement' => true,
                            'size' => 10,
                            'first' => true
                        )
                    ),
                    new Column(
                        'userId',
                        array(
                            'type' => Column::TYPE_INTEGER,
                            'size' => 11,
                            'after' => 'id'
                        )
                    ),
                    new Column(
                        'userType',
                        array(
                            'type' => Column::TYPE_VARCHAR,
                            'size' => 64,
                            'after' => 'userId'
                        )
                    ),
                    new Column(
                        'path',
                        array(
                            'type' => Column::TYPE_VARCHAR,
                            'size' => 255,
                            'after' => 'userType'
                        )
                    ),
                    new Column(
                        'thumbPath',
                        array(
                            'type' => Column::TYPE_VARCHAR,
                            'size' => 255,
                            'after' => 'path'
                        )
                    ),
                    new Column(
                        'name',
                        array(
                            'type' => Column::TYPE_VARCHAR,
                            'size' => 255,
                            'after' => 'thumbPath'
                        )
                    ),
                    new Column(
                        'ext',
                        array(
                            'type' => Column::TYPE_VARCHAR,
                            'size' => 30,
                            'after' => 'name'
                        )
                    ),
                    new Column(
                        'size',
                        array(
                            'type' => Column::TYPE_INTEGER,
                            'default' => '0',
                            'notNull' => true,
                            'size' => 11,
                            'after' => 'ext'
                        )
                    ),
                    new Column(
                        'mimetype',
                        array(
                            'type' => Column::TYPE_VARCHAR,
                            'size' => 60,
                            'after' => 'size'
                        )
                    ),
                    new Column(
                        'isImage',
                        array(
                            'type' => Column::TYPE_INTEGER,
                            'default' => '0',
                            'notNull' => true,
                            'size' => 1,
                            'after' => 'mimetype'
                        )
                    ),
                    new Column(
                        'width',
                        array(
                            'type' => Column::TYPE_INTEGER,
                            'default' => '0',
                            'notNull' => true,
                            'size' => 11,
                            'after' => 'isImage'
                        )
                    ),
                    new Column(
                        'height',
                        array(
                            'type' => Column::TYPE_INTEGER,
                            'default' => '0',
                            'notNull' => true,
                            'size' => 11,
                            'after' => 'width'
                        )
                    ),
                    new Column(
                        'weiboPicId',
                        array(
                            'type' => Column::TYPE_VARCHAR,
                            'size' => 128,
                            'after' => 'height'
                        )
                    ),
                    new Column(
                        'created',
                        array(
                            'type' => Column::TYPE_INTEGER,
                            'notNull' => true,
                            'size' => 11,
                            'after' => 'weiboPicId'
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
                    new Index('userId', array('userId')),
                    new Index('isImage', array('isImage'))
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
