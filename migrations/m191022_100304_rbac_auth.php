<?php

use app\models\Client;
use yii\db\Migration;

/**
 * Class m191022_100304_rbac_auth
 */
class m191022_100304_rbac_auth extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $auth = Yii::$app->authManager;

        // добавляем разрешение "createBook"
        $createBook = $auth->createPermission('createBook');
        $createBook->description = 'Создать книгу';
        $auth->add($createBook);

        // добавляем разрешение "updateBook"
        $updateBook = $auth->createPermission('updateBook');
        $updateBook->description = 'Редактировать книгу';
        $auth->add($updateBook);

        // добавляем разрешение "deleteBook"
        $deleteBook = $auth->createPermission('deleteBook');
        $deleteBook->description = 'Удалить книгу';
        $auth->add($deleteBook);

        // добавляем роль admin и даём роли разрешение updatePost, createBook, deleteBook
        $admin = $auth->createRole('admin');
        $auth->add($admin);
        $auth->addChild($admin, $createBook);
        $auth->addChild($admin, $updateBook);
        $auth->addChild($admin, $deleteBook);

        // добавляем разрешение "createClient"
        $createClient = $auth->createPermission('createClient');
        $createClient->description = 'Создать клиента';
        $auth->add($createClient);

        // добавляем разрешение "updateClient"
        $updateClient = $auth->createPermission('updateClient');
        $updateClient->description = 'Редактировать клиента';
        $auth->add($updateClient);

        // добавляем разрешение "deleteClient"
        $deleteClient = $auth->createPermission('deleteClient');
        $deleteClient->description = 'Удалить клиента';
        $auth->add($deleteClient);

        // даём роли admin разрешение createClient, updateClient, deleteClient
        $auth->addChild($admin, $createClient);
        $auth->addChild($admin, $updateClient);
        $auth->addChild($admin, $deleteClient);

        // добавляем разрешение "borrowBook"
        $borrowBook = $auth->createPermission('borrowBook');
        $borrowBook->description = 'Запросить книгу';
        $auth->add($borrowBook);

        // добавляем разрешение "bringBackBook"
        $bringBackBook = $auth->createPermission('bringBackBook');
        $bringBackBook->description = 'Вернуть книгу';
        $auth->add($bringBackBook);

        // добавляем разрешение "cancelBorrow"
        $cancelBorrow = $auth->createPermission('cancelBorrow');
        $cancelBorrow->description = 'Отменить запрос на книгу';
        $auth->add($cancelBorrow);

        // добавляем роль client и даём роли разрешение borrowBook, bringBackBook, cancelBorrow
        $client = $auth->createRole('client');
        $auth->add($client);
        $auth->addChild($client, $borrowBook);
        $auth->addChild($client, $bringBackBook);
        $auth->addChild($client, $cancelBorrow);

        // добавляем разрешение "resolveBorrow"
        $resolveBorrow = $auth->createPermission('resolveBorrow');
        $resolveBorrow->description = 'Выдать книгу';
        $auth->add($resolveBorrow);

        // добавляем разрешение "rejectBorrow"
        $rejectBorrow = $auth->createPermission('rejectBorrow');
        $rejectBorrow->description = 'Отказать в выдаче';
        $auth->add($rejectBorrow);

        // даём роли admin разрешение resolveBorrow, rejectBorrow
        $auth->addChild($admin, $resolveBorrow);
        $auth->addChild($admin, $rejectBorrow);

        // Назначение роли client пользователям
        $clientIds = Client::find()->select('id')->column();
        foreach ($clientIds as $clientId) {
            $auth->assign($client, $clientId);
        }

        // Назначение роли admin
        $adminUser = new Client([
            'name' => 'admin',
            'email' => Yii::$app->params['adminEmail'],
            'password' => '111111',
        ]);
        $adminUser->save();
        $auth->assign($admin, $adminUser->id);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->truncateTable('auth_assignment');
        $this->truncateTable('auth_item');
        $this->truncateTable('auth_rule');

        $adminUser = Client::findOne(['email' => Yii::$app->params['adminEmail']]);
        if ($adminUser !== null) {
            $adminUser->delete();
        }

        return true;
    }
}
