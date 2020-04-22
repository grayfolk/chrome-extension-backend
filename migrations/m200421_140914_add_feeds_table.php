<?php
use yii\db\Migration;

/**
 * Class m200421_140914_add_feeds_table
 */
class m200421_140914_add_feeds_table extends Migration
{

    private $siteTable = '{{%site}}';

    private $interestTable = '{{%interest}}';

    private $siteInterestTable = '{{%site_interest}}';

    private $tableOptions = null;

    /**
     *
     * {@inheritdoc}
     *
     */
    public function safeUp()
    {
        if ('mysql' === $this->db->driverName) {
            // http://stackoverflow.com/questions/766809/whats-the-difference-between-utf8-general-ci-and-utf8-unicode-ci
            $this->tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }
        
        $this->createTable($this->siteTable, [
            'id' => $this->primaryKey(),
            'site' => $this->string(255)->notNull(),
            'feed' => $this->string(255)->null()->defaultValue(null),
            'created_at' => $this->integer()->null()->defaultValue(null),
            'updated_at' => $this->integer()->null()->defaultValue(null),
            'parsed_at' => $this->integer()->null()->defaultValue(0)
        ], $this->tableOptions);
        
        $this->createTable($this->interestTable, [
            'id' => $this->primaryKey(),
            'title' => $this->string(255)->notNull(),
            'created_at' => $this->integer()->null()->defaultValue(null),
            'updated_at' => $this->integer()->null()->defaultValue(null)
        ], $this->tableOptions);
        
        $this->createTable($this->siteInterestTable, [
            'site_id' => $this->integer()->notNull(),
            'interest_id' => $this->integer()->notNull()
        ], $this->tableOptions);
        
        $this->addPrimaryKey('', $this->siteInterestTable, [
            'site_id',
            'interest_id'
        ]);
        
        $this->addForeignKey('fk_interest_to_site', $this->siteInterestTable, 'site_id', $this->siteTable, 'id', 'CASCADE', 'CASCADE');
        $this->addForeignKey('fk_site_to_interest', $this->siteInterestTable, 'interest_id', $this->interestTable, 'id', 'CASCADE', 'CASCADE');
    }

    /**
     *
     * {@inheritdoc}
     *
     */
    public function safeDown()
    {
        $this->dropForeignKey('fk_interest_to_site', $this->siteInterestTable);
        $this->dropForeignKey('fk_site_to_interest', $this->siteInterestTable);
        
        $this->dropTable($this->siteTable);
        $this->dropTable($this->interestTable);
        $this->dropTable($this->siteInterestTable);
    }
}
