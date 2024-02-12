<?php

namespace app\controllers;

use app\models\ApiUser;
use app\models\BankForm;
use Yii;
use yii\web\Controller;
use yii\httpclient\Client;
use yii\helpers\Json;

//Profile Test ID: 956e9631-7e40-44fb-9172-82afe515daea
// UserID acces token: Neu1 vhhXNbC6ekKCGJ7TzG3_2dgGOOrQEFG7YbCTYZMqRrJDyQWbbs08GlK8wkKMvZ6ELf3CweZ8i72jwVzcefhW116Dn5chfBV2S07vEHNQr7hDOdEvBaPHWAybo71nO35m
class ApiController extends Controller
{


    public function actionClient()
    {
        return $this->render('client');
    }


    private function requestNewTokenClient()
    {
        // Request an die API schicken
        $client = new Client([
            'baseUrl' => 'https://sandbox.finapi.io/',
            'requestConfig' => [
            ],
        ]);

        // Richtige Funktion aufrufen und die daten übergeben
        $clientToken = $client->createRequest()
            ->setMethod('POST')
            ->setUrl('/api/v2/oauth/token')
            ->setData([
                'client_id' => 'a707419f-90ad-4849-80cf-d4bfdf69da33',
                'client_secret' => 'f884a2bd-5c50-43dd-9f95-3a4dfe8140a3',
                'Content-Type' => 'application/x-www-form-urlencoded',
                'grant_type' => 'client_credentials',
            ])
            ->send();
        //Überprüfung, ob alles ausgegeben wurde
        //Überprüfung, ob alles ausgegeben wurde
        if ($clientToken->isOk) {
            $accessToken = $clientToken->data['access_token'];
            $expiresIn = $clientToken->data['expires_in'];
            return [$accessToken, $expiresIn];
        } else {
            return $clientToken->statusCode;
        }
    }


    public function actionAdmin()
    {
        // Delete Token in cache
        //Yii::$app->cache->delete('admin_access_token');
        return $this->render('admin');
    }
    public function actionAdmin_list_user()
    {

        // Cache erstellen
        $cache = Yii::$app->cache;

        //Überprüfung, ob Access Token gibt
        $adminAccessToken = $cache->get('admin_access_token');
        //Überprüfung, ob Token abgelaufen ist
        if (!$adminAccessToken) {
            //Token nicht vorhanden, neuer wird angefragt
            $adminAccessToken = $this->requestNewTokenAdmin();
            $expireTime = $adminAccessToken[1];
            //Token wird mit ablaufzeit gespeichert
            $cache->set('admin_access_token', $adminAccessToken[0], $expireTime);
        }


        //$adminAccessToken = $cache->get('admin_access_token');
        try {
        $userList = $this->getUserList($adminAccessToken);
        }
        catch (\Throwable $e) {
            Yii::error($e->getMessage());
            Yii::$app->session->setFlash('error', 'Ein Fehler ist aufgetreten. Bitte versuchen Sie es später erneut.');
            return $this->redirect(['api/admin']); // oder eine andere Weiterleitung
        }

        return $this->render('admin_list_user',[
            'userList' => $userList,
            'accessToken' => $adminAccessToken,
        ]);
    }

    private function requestNewTokenAdmin()
    {

        //Request an die API schicken
        $client = new Client([
            'baseUrl' => 'https://sandbox.finapi.io/',
            'requestConfig' => [
            ],
        ]);

        // Richtige Funktion aufrufen und die daten übergeben
        $AdminToken = $client->createRequest()
            ->setMethod('POST')
            ->setUrl('/api/v2/oauth/token')
            ->setData([
                'client_id' => '9ea13fc6-6676-4826-9d46-5c61d8417323',
                'client_secret' => 'ce8bcc75-e089-43de-bdce-ce5d4a82793b',
                'Content-Type' => 'application/x-www-form-urlencoded',
                'grant_type' => 'client_credentials',
            ])
            ->send();

        //Überprüfung, ob alles ausgegeben wurde
        if ($AdminToken->isOk) {
            $accessToken = $AdminToken->data['access_token'];
            $expiresIn = $AdminToken->data['expires_in'];
            return [$accessToken, $expiresIn];
        } else {
            return $AdminToken->statusCode;
        }
    }

    private function getUserList($adminToken)
    {
        //Access Token anfragen
        $accessToken = $adminToken;
        //Request an die API schicken
        $client = new Client([
            'baseUrl' => 'https://sandbox.finapi.io/',
            'requestConfig' => [
            ],
        ]);

        $stop = 0;

        do {
            // HTTP-Anfrage erstellen
            $response = $client->createRequest()
                ->setMethod('GET')
                ->setUrl('/api/v2/mandatorAdmin/getUserList')
                ->setData([])
                ->setHeaders(['Authorization' => 'Bearer ' . $accessToken])
                ->send();


            // Überprüfen, ob die Anfrage erfolgreich war und die Benutzerliste erhalten wurde
            if ($response->isOk) {
                // Die Benutzerliste aus der Antwort extrahieren
                $userList = $response->data;
                return $userList;
            } elseif ($response->statusCode === 401) {
                // TODO new token
                $this->requestNewTokenAdmin();
                $stop ++;
            } else {
                throw new \Exception('Fehler aufgetreten');
            }
        } while ($stop < 5);
        throw new \Exception('Maximale Anzahl von Versuchen überschritten');
    }

    public function actionCreate_user()
    {
        $model = new ApiUser();

        if($model->load(Yii::$app->request->post()) && $model->validate()) {
            // Get Client Token
            $clientToken = $this->requestNewTokenClient();
            // Request an die API schicken
            $client = new Client([
                'baseUrl' => 'https://sandbox.finapi.io/',
                'requestConfig' => [
                ],
            ]);

            // HTTP Anfrage erstellen
            $response = $client->createRequest()
                ->setMethod('post')
                ->setUrl('/api/v2/users')
                ->setFormat(Client::FORMAT_JSON)
                ->setData([
                    'id' => $model['user_id'],
                    'password' => $model['password'],
                    'email' => $model['email'],
                ])
                ->setHeaders([
                    'Authorization' => 'Bearer '. $clientToken,
                    'Content-Type' => 'application/json',
                ])
                ->send();
            if($response->isOk)
            {
                if ($model->save(false)) {
                    Yii::$app->session->setFlash('success', 'API User erfolgreich erstellt!');
                    return $this->refresh();
                }
            }else {
                var_dump($response);
                Yii::$app->session->setFlash('error', 'Beim Speichern des Benutzers ist ein Fehler aufgetreten.');
            }
        }
        return $this->render('create_user',[
            'model' => $model,
        ]);
    }

    public function actionUserPasswordAndToken()
    {
        $userId = Yii::$app->request->post('userId');
        $user = ApiUser::findOne(['user_id' => $userId]);

        if ($user){
            $password = $user->password;
            // Request an die API schicken
            $client = new Client([
                'baseUrl' => 'https://sandbox.finapi.io/',
                'requestConfig' => [
                ],
            ]);

            // Richtige Funktion aufrufen und die daten übergeben
            $userToken = $client->createRequest()
                ->setMethod('POST')
                ->setUrl('/api/v2/oauth/token')
                ->setData([
                    'client_id' => 'a707419f-90ad-4849-80cf-d4bfdf69da33',
                    'client_secret' => 'f884a2bd-5c50-43dd-9f95-3a4dfe8140a3',
                    'username' => $userId,
                    'password' => $password,
                    'Content-Type' => 'application/x-www-form-urlencoded',
                    'grant_type' => 'password',
                ])
                ->send();
            if ($userToken->isOk){
                return $userToken->data['access_token'];
            }else{
                return $userToken->statusCode;
            }
        }else{
            return 'There was a problem';
        }
    }


    private function clientTokenCache()
    {
        // Cache erstellen
        $cache = Yii::$app->cache;
        //$cache->delete('client_access_token'); die;
        //Überprüfung, ob Access Token gibt
        $clientAccessToken = $cache->get('client_access_token');
        //Überprüfung, ob Token abgelaufen ist
        //var_dump($clientAccessToken); die;
        if (!$clientAccessToken)
        {
            //Token nicht vorhanden, neuer wird angefragt
            $clientAccessToken = $this->requestNewTokenClient();
            //var_dump($clientAccessToken);die;
            $expireTime = $clientAccessToken[1];
            //var_dump($expireTime);die;


            //Token wird mit ablaufzeit gespeichert
            $cache->set('client_access_token', $clientAccessToken[0], $expireTime);
        }
        return true;
    }

    public function actionBanks()
    {
        $this->clientTokenCache();
        $cache = Yii::$app->cache;
        $accessToken = $cache->get('client_access_token');

        $request = Yii::$app->request;

        $banks = $this->getAllBanks($accessToken);
        $bankNames = array_column($banks['banks'], 'name');
        $bankIds = array_column($banks['banks'], 'id');

        $bankId = $request->get('bankId');
        $model = new BankForm();

        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            $bankId = $model->bankId;

            $response = $this->getBank($bankId, $accessToken);
            $bankInfo = $response->data;
        } else {
            $bankInfo = null;
        }

        return $this->render('banks', [
            'bankNames' => $bankNames,
            'bankInfo' => $bankInfo,
            'bankIds' => $bankIds,
            'model' => $model,
            'accessToken' => $accessToken,
            ]);
    }

    private function getAllBanks($accessToken)
    {

        $client = new Client([
            'baseUrl' => 'https://sandbox.finapi.io/',
            'requestConfig' => [
            ],
        ]);
        do {
            $stop = 0;
        $response = $client->createRequest()
            ->setMethod('GET')
            ->setUrl('/api/v2/banks/')
            ->setHeaders([
                'Authorization' => 'Bearer '. $accessToken,
                'Content-Type' => 'application/json'
            ])
            ->send();
        if ($response->isOk) {
            // Die Benutzerliste aus der Antwort extrahieren
            $bankList = $response->data;
            return $bankList;
        }
        elseif ($response->statusCode === 401) {
            // TODO new token
            $this->requestNewTokenAdmin();
            $stop++;
        }
         else {
            throw new \Exception('Fehler aufgetreten');
        }
        } while ($stop < 5);
        throw new \Exception('Maximale Anzahl von Versuchen überschritten');
    }

    private function getBank($bankId, $accessToken)
    {
        $client = new Client([
            'baseUrl' => 'https://sandbox.finapi.io/',
            'requestConfig' => [
            ],
        ]);
        $response = $client->createRequest()
            ->setMethod('GET')
            ->setUrl('/api/v2/banks/' . $bankId)
            ->setHeaders([
                'Authorization' => 'Bearer '. $accessToken,
                'Content-Type' => 'application/json'
            ])
            ->send();

        return $response;
    }

}