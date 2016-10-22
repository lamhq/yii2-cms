<?php

use yii\db\Migration;

class m161018_004133_create_database extends Migration
{
	public function up()
	{
		$this->initRbac();
		$this->createTables();
		$this->addForeignKeys();
	}

	public function down()
	{
		$this->dropTables();
		echo "database tables droped.\n";
		return true;
	}

	public function createTables() {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            // http://stackoverflow.com/questions/766809/whats-the-difference-between-utf8-general-ci-and-utf8-unicode-ci
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }

		// category
		$this->createTable('{{%category}}', [
			'id' => $this->primaryKey(),
			'name' => $this->string()->notNull(),
			'slug' => $this->string(),
			'status' => $this->smallInteger(),
			'parent_category_id' => $this->integer(),
		], $tableOptions);

		// tag
		$this->createTable('{{%tag}}', [
			'id' => $this->primaryKey(),
			'name' => $this->string()->notNull(),
			'slug' => $this->string(),
		], $tableOptions);

		// post
		$this->createTable('{{%post}}', [
			'id' => $this->primaryKey(),
			'title' => $this->string()->notNull(),
			'slug' => $this->string(),
			'featured_image' => $this->string(),
			'short_content' => $this->text(),
			'content' => $this->text(),
			'status' => $this->smallInteger(),
			'created_at' => $this->datetime(),
			'updated_at' => $this->datetime(),
			'published_at' => $this->datetime(),
			'created_by' => $this->integer(),
			'category_id' => $this->integer(),
		], $tableOptions);

		// post_tag
		$this->createTable('{{%post_tag}}', [
			'post_id' => $this->integer()->notNull(),
			'tag_id' => $this->integer()->notNull(),
			'PRIMARY KEY(post_id, tag_id)',
		], $tableOptions);

		// page
		$this->createTable('{{%page}}', [
			'id' => $this->primaryKey(),
			'title' => $this->string()->notNull(),
			'slug' => $this->string(),
			'content' => $this->text(),
			'status' => $this->smallInteger(),
			'created_at' => $this->datetime(),
			'updated_at' => $this->datetime(),
		], $tableOptions);

		// email_template
		$this->createTable('{{%email_template}}', [
			'id' => $this->primaryKey(),
			'name' => $this->string(30)->notNull(),
			'subject' => $this->string(),
			'body' => $this->text(),
		], $tableOptions);

		// master_role
		$this->createTable('{{%master_role}}', [
			'name' => $this->string(64)->notNull(),
			'PRIMARY KEY(name)',
		], $tableOptions);

		// lookup
		$this->createTable('{{%lookup}}', [
			'id' => $this->primaryKey(),
			'name' => $this->string(),
			'code' => $this->string(30),
			'type' => $this->string(30),
			'position' => $this->smallInteger(),
		], $tableOptions);

		// setting
		$this->createTable('{{%setting}}', [
			'name' => $this->string(100)->notNull(),
			'value' => $this->text(),
			'PRIMARY KEY(name)',
		], $tableOptions);

		// user
		$this->createTable('{{%user}}', [
			'id' => $this->primaryKey(),
			'username' => $this->string()->notNull(),
			'password_hash' => $this->string(),
			'email' => $this->string(),
			'auth_key' => $this->string(32),
			'status' => $this->smallInteger(),
			'created_at' => $this->datetime(),
			'updated_at' => $this->datetime(),
		], $tableOptions);

		// user_token
		$this->createTable('{{%user_token}}', [
			'id' => $this->primaryKey(),
			'token' => $this->string(40)->notNull(),
			'user_id' => $this->integer(),
			'type' => $this->smallInteger(),
			'expired_at' => $this->datetime(),
			'created_at' => $this->datetime(),
			'updated_at' => $this->datetime(),
		], $tableOptions);
	}

	public function addForeignKeys() {
		// category.parent_category_id
		$this->createIndex(
			'idx-category-parent_category_id',
			'{{%category}}',
			'parent_category_id'
		);
		$this->addForeignKey(
			'fk-category-parent_category_id',
			'{{%category}}',
			'parent_category_id',
			'{{%category}}',
			'id',
			'CASCADE',
			'CASCADE'
		);

		// post.created_by
		$this->createIndex(
			'idx-post-created_by',
			'{{%post}}',
			'created_by'
		);
		$this->addForeignKey(
			'fk-post-created_by',
			'{{%post}}',
			'created_by',
			'{{%user}}',
			'id',
			'CASCADE',
			'CASCADE'
		);

		// post.category_id
		$this->createIndex(
			'idx-post-category_id',
			'{{%post}}',
			'category_id'
		);
		$this->addForeignKey(
			'fk-post-category_id',
			'{{%post}}',
			'category_id',
			'{{%category}}',
			'id',
			'SET NULL',
			'SET NULL'
		);

		// post_tag.post_id
		$this->createIndex(
			'idx-post_tag-post_id',
			'{{%post_tag}}',
			'post_id'
		);
		$this->addForeignKey(
			'fk-post_tag-post_id',
			'{{%post_tag}}',
			'post_id',
			'{{%post}}',
			'id',
			'CASCADE',
			'CASCADE'
		);

		// post_tag.tag
		$this->createIndex(
			'idx-post_tag-tag_id',
			'{{%post_tag}}',
			'tag_id'
		);
		$this->addForeignKey(
			'fk-post_tag-tag_id',
			'{{%post_tag}}',
			'tag_id',
			'{{%tag}}',
			'id',
			'CASCADE',
			'CASCADE'
		);

		// master_role
		$this->createIndex(
			'idx-master_role-name',
			'{{%master_role}}',
			'name'
		);
		$this->addForeignKey(
			'fk-master_role-name',
			'{{%master_role}}',
			'name',
			Yii::$app->getAuthManager()->itemTable,
			'name',
			'CASCADE',
			'CASCADE'
		);

		// user_token
		$this->createIndex(
			'idx-user_token-user_id',
			'{{%user_token}}',
			'user_id'
		);
		$this->addForeignKey(
			'fk-user_token-user_id',
			'{{%user_token}}',
			'user_id',
			'{{%user}}',
			'id',
			'CASCADE',
			'CASCADE'
		);
	}

	public function initRbac() {
		\Yii::$app->runAction('migrate', [
			'migrationPath' => '@yii/rbac/migrations', 
			'interactive'=>0
		]);
	}

	public function dropTables() {
		$this->dropTable('{{%user_token}}');
		$this->dropTable('{{%setting}}');
		$this->dropTable('{{%post_tag}}');
		$this->dropTable('{{%tag}}');
		$this->dropTable('{{%post}}');
		$this->dropTable('{{%category}}');
		$this->dropTable('{{%user}}');
		$this->dropTable('{{%page}}');
		$this->dropTable('{{%master_role}}');
		$this->dropTable('{{%lookup}}');
		$this->dropTable('{{%email_template}}');
		$authManager = Yii::$app->getAuthManager();
		$this->dropTable($authManager->assignmentTable);
		$this->dropTable($authManager->itemChildTable);
		$this->dropTable($authManager->itemTable);
		$this->dropTable($authManager->ruleTable);
        $this->db->createCommand()->delete('{{%migration}}', [
            // 'version' => self::className(),
        ])->execute();		
	}
}
