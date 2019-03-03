<?php

class Auth{
    private static $validations = [
        'username' => [
            'min' => 5,
            'max' => 40
        ],

        'email'    => [
            'min' => 5,
            'max' => 40,
            'regex' => '/@/',
            'regex_description' => 'Email must be valid'
        ],
        
        'fullname' => [
            'min' => 2,
            'max' => 60
        ],

        'password' => [
            'min' => 2,
            'max' => 40, 
            'regex' => '/((?:[a-zA-Z]+[0-9]|[0-9]+[a-zA-Z])[a-zA-Z0-9]*)/', 
            'regex_description' => 'Password must contain both letters and numbers'
        ]
    ];

    public static $requiredDetails = ['username', 'email', 'id'];

    public static function handleToken($dbh){
        if (!empty($_COOKIE['auth_data'])){
            $data = json_decode($_COOKIE['auth_data']);

            $token = $data->token;
            $selector = $data->selector;
 
            // Find the token
            $stmt = $dbh->prepare('SELECT user_id, token FROM auth_tokens WHERE selector=:selector');
            $stmt->execute([':selector' => $selector]);
            $tokenData = $stmt->fetch(PDO::FETCH_ASSOC);

            // Check to see of the tokens match
            if (hash('sha256', $token) == $tokenData['token']){
                $stmt = $dbh->prepare('SELECT username, email FROM users WHERE id=:id');
                $stmt->execute([':id' => $tokenData['user_id']]);
                $user = $stmt->fetch(PDO::FETCH_ASSOC);

                // Log the user in
                Auth::authenticate($user, false, $dbh);
            }
        }
    }

    public static function authenticated(){
        return !empty($session['user']);    
    }

    public static function validate($fields, $redirect){
        $msgs;
        
        foreach(array_keys($fields) as $field){
            $formattedName = ucfirst(str_replace('_', ' ', $field));

            // Empty check
            if (empty($fields[$field])) {
                $msgs[] = ['success' => false, 'message' => "{$formattedName} must be provided"];
                continue;
            }

            // Only apply these checks to fields with specified constraints
            if (in_array($field, Auth::$validations)){
                
                // Whitespace check
                if (strlen(trim($fields[$field])) != strlen($fields[$field])){ 
                    $msgs[] = ['success' => false, 'message' => "{$formattedName} must not have any leading or trailing whitespace"];
                }

                // Length check
                if (strlen($fields[$field]) < Auth::$validations[$field]['min'] || strlen($fields[$field]) > Auth::$validations[$field]['max']){
                    $msgs[] = [
                        'success' => false, 
                        'message' => "{$formattedName} must be between " . Auth::$validations[$field]['min'] . " and " . Auth::$validations[$field]['max'] . " characters"
                    ];
                }

                // Regex check
                if (!empty(Auth::$validations[$field]['regex']) && !preg_match(Auth::$validations[$field]['regex'], $fields[$field])){
                    $msgs[] = ['success' => false, 'message' => Auth::$validations[$field]['regex_description']];
                }
            }
        }

        if (empty($msgs)){ 
            return true;
        } 
        else {
            Auth::message($msgs, $fields, $redirect);
        }
    }

    public static function message($msgs, $fields, $redirect){
        $_SESSION['msgs'] = $msgs;

        if (!empty($fields)){
            unset($fields['password']);
            header('Location: ' . $redirect . '?' . http_build_query($fields));
            exit();
        }
        else {
            header('Location: ' . $redirect);
            exit();
        }
    }

    public static function authenticate($details, $persistent, $dbh){
        // Get the user's id and username
        $stmt = $dbh->prepare('SELECT id, username FROM users WHERE email=:email');
        $stmt->execute([':email'=>$details['email']]);

        $extraDetails = $stmt->fetch(PDO::FETCH_ASSOC);
        $details['id'] = $extraDetails['id'];
        $details['username'] = $extraDetails['username'];

        // Remember me token setup
        if ($persistent){
            $tokenLength = 20;

            // Generate a random access token
            $token = bin2hex(random_bytes($tokenLength));
            
            /*  Generate a unique selector. We use a selector to match the client cookie with the hashed token on the database
                because if we looked up tokens by the client cookie's plaintext token, it would be insecure (timing leaks) */
            $selector = uniqid('token', true);

            // Set the client cookie for storing the token and the selector. (json encode is just a nice way to store an array on a cookie)
            setcookie('auth_data', json_encode(['token' => $token, 'selector' => $selector]), time() + (60*60*24*30), '/');

            
            // If a token record already exists, update the values. Otherwise create a new record.
            $stmt = $dbh->prepare('SELECT COUNT(id) AS count FROM auth_tokens WHERE user_id=:user_id');
            $stmt->execute([':user_id' => $details['id']]);
            
            $count = $stmt->fetch(PDO::FETCH_ASSOC)['count'];
            
            if ($count > 0){
                $stmt = $dbh->prepare('UPDATE auth_tokens SET token=:token, selector=:selector WHERE user_id=:user_id');
                $stmt->execute([':token' => hash('sha256', $token), ':selector' => $selector, ':user_id' => $details['id']]);
            }
            else {
                $stmt = $dbh->prepare('INSERT INTO auth_tokens VALUES(:token, :selector, :user_id, NULL)');
                $stmt->execute([':token' => hash('sha256', $token), ':selector' => $selector, ':user_id' => $details['id']]);
            }
        }

        // Remove all unnecessary values
        foreach (array_keys($details) as $detail){
            if (!in_array($detail, Auth::$requiredDetails)){
                unset($details[$detail]);
            }
        }

        $_SESSION['user'] = $details;
    }

    public static function unauthenticate($dbh){
        // If a remember token exists, delete it
        if (!empty($_COOKIE['auth_data'])) setcookie('auth_data', null, time()-3600, '/');

        $stmt = $dbh->prepare('DELETE FROM auth_tokens WHERE user_id=:id');
        $stmt->execute([':id' => $_SESSION['user']['id']]);

        // Delete all session data
        unset($_SESSION['user']);
    }
}