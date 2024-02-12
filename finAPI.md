# finAPI Workflow

## Step 1

### Client Access_Token generieren

[Client Access-Token](https://sandbox.finapi.io/#post-/api/v2/oauth/token)

- In 'grant_type' "client_credentials" eintragen
- client_id und client_secret übergeben
- Auf Try drücken

### Admin Acces_Token generieren

[Client Access-Token](https://sandbox.finapi.io/#post-/api/v2/oauth/token)

- In 'grant_type' "client_credentials" eintragen
- admin_id und admin_secret übergeben
- Auf Try drücken

## Step 2

### User erstellen

- Um ein User zu erstellen muss im Header der Anfrage folgendes vorhanden sein:
      'Authorization':'Bearer"client_access_token"
  In Yii2 kann man das wie folgt machen:


        ```
        $client = new Client([
                      'baseUrl' => 'https://sandbox.finapi.io/',
                      'requestConfig' => [
                      ],
                  ]);
  Für die Anfrage:
    ```
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
    ```
  Auf der Seite sieht man immer welche URL man Benutzen soll für den Service.
  Das gleiche gilt auch für den Content-Type.
  
  Es ist zu empfehlen, die User id (username) und password zu speichern, weil man das Password im nachhinein nur noch als 'XXXXX' wiederkriegt.

- Als antwort kriegt man ein Statuscode und mit
  ```
  $response->statusCode;
  ```

### User_Token

Um mit dem User arbeiten zu können brauch man ein User-Token, den kriegen wir fast genau so wie ein client-token

Dafür die `grant_type` auf 'password' setzten und `username` und `password` eingeben, der rest bleibt gleich wie bei [Client Access-Token generieren](#client-access_token-generieren)


