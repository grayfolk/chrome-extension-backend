<?php
use yii\db\Migration;

/**
 * Class m200421_213523_add_news_table
 */
class m200421_213523_add_news_table extends Migration
{

    private $siteTable = '{{%site}}';

    private $newsTable = '{{%news}}';

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
        
        $this->createTable($this->newsTable, [
            'id' => $this->primaryKey(),
            'site_id' => $this->integer()->notNull(),
            'title' => $this->string(255)->notNull(),
            'description' => $this->string(512)->null()->defaultValue(null),
            'link' => $this->string(255)->notNull(),
            'created_at' => $this->integer()->null()->defaultValue(null),
            'updated_at' => $this->integer()->null()->defaultValue(null)
        ], $this->tableOptions);
        
        $this->addForeignKey('fk_news_to_site', $this->newsTable, 'site_id', $this->siteTable, 'id', 'CASCADE', 'CASCADE');
    }

    /**
     *
     * {@inheritdoc}
     *
     */
    public function safeDown()
    {
        $this->dropForeignKey('fk_news_to_site', $this->newsTable);
        
        $this->dropTable($this->siteTable);
    }
}
