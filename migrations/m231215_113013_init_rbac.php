<?php

use yii\db\Migration;
use yii\rbac\PhpManager;

/**
 * Class m231215_113013_init_rbac
 */
class m231215_113013_init_rbac extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function SafeUp()
    {
        $auth = new PhpManager;//Yii::$app->authManager;

        $rule = new \app\rbac\UserTypeRule;
        $auth->add($rule);

        //add "updateResult" permission
        $updateResult = $auth->createPermission('updateResult');
        $updateResult->description = 'Update result';
        $auth->add($updateResult);

        //add "viewResultDashboard" permission
        $viewResultDashboard = $auth->createPermission('viewResultDashboard');
        $viewResultDashboard->description = 'View Result Dashboard';
        $auth->add($viewResultDashboard);

        // add "admin" role and give this role the "viewResultDashboard" and "updateResult" permission
        $admin = $auth->createRole('admin');
        $admin->ruleName = $rule->name;
        $auth->add($admin);
        $auth->addChild($admin, $updateResult);
        $auth->addChild($admin, $viewResultDashboard);

        // add "user" role
        $user = $auth->createRole('user');
        $user->ruleName = $rule->name;
        $auth->add($user);

    }

    public function safeDown()
    {
        $auth = Yii::$app->authManager;
        $auth->removeAll();
    }
}