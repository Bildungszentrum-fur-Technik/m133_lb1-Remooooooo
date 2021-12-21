<?php

/**
 * Controller wurde von C.Inauen vorgegeben
 * Anpassung: Redirect geändert
 */
class Users extends Controller
{
    /**
     * index - User anzeigen. Man muss aber Admin sein
     *
     * @param  mixed $pagename - Pagetitel
     *
     * @return void
     */
    public function index($pagename = 'User / Index')
    {

        // Dürfen wir überhaupt diese Funktion nutzen? 
        if (!isset($_SESSION['user_id'])) {
            // Kein Login -> Weiterleitung auf Login
            redirect('Users/login');
        } else {

            if (!in_array("admin", $_SESSION['user_roles'])) {
                // Wir sind eingeloggt, aber nicht Admin: Weg von hier
                redirect('/');
            }
        }

        $userModel = $this->model('UserModel');
        $data = $userModel->getUsers();

        echo $this->twig->render('user/index.twig.html', ['title' => $pagename, 'urlroot' => URLROOT, 'data' => $data]);
    }

    /**
     * register - User-Registrierung. Man darf nicht eingeloggt sein
     *
     * @return void
     */
    public function register()
    {
        // Kein Register für User die bereits eingeloggt sind
        if (isset($_SESSION['user_id'])) {

            redirect('/');
        }

        $userModel = $this->model('UserModel');

        // Check for post or get
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            // Process Form


            // Zuerst einmal den Array "Sanitizen" <- Sind es wirklich Strings??
            $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);


            // Init Data <- Damit Daten nicht wieder neu eingegeben werden müssen
            $data = [
                'name' => trim($_POST['name']),
                'email' => trim($_POST['email']),
                'password' => trim($_POST['password']),
                'confirm_password' => trim($_POST['confirm_password']),
                'name_err' => '', // Error für Attribut
                'email_err' => '', // Error für Attribut
                'password_err' => '', // Error für Attribut
                'confirm_password_err' => '' // Error für Attribut
            ];


            // Validierung der Daten

            /**
             * TODO: Es fehlt noch die Validierung auf:
             * 
             * Komplexitätsrichtlinien für Passwörter
             */

            if (empty($data['name'])) {
                $data['name_err'] = 'Bitte Name angeben';
            }

            if (empty($data['email'])) {
                $data['email_err'] = 'Bitte Email angeben';
            } else {
                // Email checken
                if ($userModel->checkUserForEmail($data['email'])) {
                    $data['email_err'] = 'Email schon verwendet';
                }
            }

            if (empty($data['password'])) {
                $data['password_err'] = 'Bitte Passwort angeben';
            } else if (strlen($data['password']) < 6) {
                $data['password_err'] = 'Passwort muss mind. 6 Zeichen beinhalten';
            }

            if (empty($data['confirm_password'])) {
                $data['confirm_password_err'] = 'Bitte Bestätigungs-Passwort angeben';
            } else if ($data['confirm_password'] != $data['password']) {
                $data['confirm_password_err'] = 'Das Bestätigungspasswort muss mit dem Passwort übereinstimmen';
            }

            // Keine Errors vorhanden
            if (empty($data['name_err']) && empty($data['email_err']) && empty($data['password_err']) && empty($data['confirm_password_err'])) {
                // Alles gut -> Passwort hashen
                $options = [
                    'cost' => 12,
                ];
                $data['password'] = password_hash($data['password'], PASSWORD_BCRYPT, $options);
                //die($data['password']);

                // Register User
                if ($userModel->registerUser($data)) {
                    redirect('Users/login');
                } else {
                    die('Schlimm');
                }
            } else {

                // View laden mit Fehlern
                echo $this->twig->render('user/register.twig.html', ['title' => "User / Register", 'urlroot' => URLROOT, 'data' => $data]);
            }
        } else {
            // Init Data <- Damit Daten nicht wieder neu eingegeben werden müssen
            $data = [
                'name' => '',
                'email' => '',
                'password' => '',
                'confirm_password' => '',
                'name_err' => '', // Error für Attribut
                'email_err' => '', // Error für Attribut
                'password_err' => '', // Error für Attribut
                'confirm_password_err' => '' // Error für Attribut
            ];

            echo $this->twig->render('user/register.twig.html', ['title' => "User / Register", 'urlroot' => URLROOT, 'data' => $data]);
        }
    }

    /**
     * login - Login-Funktion. Man darf nicht eingeloggt sein.
     *
     * @return void
     */
    public function login()
    {
        // Kein Login für User die bereits eingeloggt sind
        if (isset($_SESSION['user_id'])) {

            redirect('/');
        }

        $userModel = $this->model('UserModel');

        // Check for post or get
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            // Process Form

            // Zuerst einmal den Array "Sanitizen" <- Sind es wirklich Strings??
            $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);


            // Init Data <- Damit Daten nicht wieder neu eingegeben werden müssen
            $data = [
                'email' => trim($_POST['email']),
                'password' => trim($_POST['password']),
                'email_err' => '', // Error für Attribut
                'password_err' => '', // Error für Attribut
            ];

            // Validating

            if (empty($data['email'])) {
                $data['email_err'] = 'Bitte Email angeben';
            }


            if (empty($data['password'])) {
                $data['password_err'] = 'Bitte Passwort angeben';
            }

            // Email/Password checken
            if ($userModel->getUserForEmail($data['email'])) {
                // Benutzer gefunden

            } else {
                $data['email_err'] = 'Kein Benutzer gefunden';
            }


            // Keine Errors vorhanden
            if (empty($data['email_err']) && empty($data['password_err'])) {

                // Passwort überprüfen
                $loggedInBenutzer = $userModel->login($data['email'], $data['password']);

                //die(var_dump($loggedInBenutzer));

                if ($loggedInBenutzer) {
                    // Session erstellen
                    $this->createUserSession($loggedInBenutzer);
                } else {
                    $data['password_err'] = 'Passwort/Benutzername falsch';
                    echo $this->twig->render('user/login.twig.html', ['title' => "User / Login", 'urlroot' => URLROOT, 'data' => $data]);
                }
            } else {

                // View laden mit Fehlern
                echo $this->twig->render('user/login.twig.html', ['title' => "User / Login", 'urlroot' => URLROOT, 'data' => $data]);
            }
        } else {
            // Init Data <- Damit Daten nicht wieder neu eingegeben werden müssen
            $data = [
                'email' => '',
                'password' => '',
                'email_err' => '', // Error für Attribut
                'password_err' => '', // Error für Attribut
            ];

            echo $this->twig->render('user/login.twig.html', ['title' => "User / Login", 'urlroot' => URLROOT, 'data' => $data]);
        }
    }


    /**
     * createUserSession - Für einen erfolgreichen Login die Session-Variablen setzen. Man darf nicht bereits eingeloggt sein
     *
     * @param  mixed $user
     *
     * @return void
     */
    private function createUserSession($user)
    {
        // Keine Usersession für User die bereits eingeloggt sind
        if (isset($_SESSION['user_id'])) {

            redirect('/');
        }

        // Der JSON-Inhalt aus MySQL kommt etwas komisch daher...Array machen und später in der Session speichern
        $rolesarray = json_decode($user['roles']);

        $_SESSION['user_id'] = $user['id'];
        $_SESSION['user_email'] = $user['email'];
        $_SESSION['user_name'] = $user['name'];
        $_SESSION['user_roles'] = $rolesarray;

        redirect('/');
    }

    /**
     * logout - Ausloggen-Funktion. Man muss eingeloggt sein.
     *
     * @return void
     */
    public function logout()
    {
        // Kein Logout für User die nicht eingeloggt sind
        if (!isset($_SESSION['user_id'])) {

            redirect('/');
        }

        unset($_SESSION['user_id']);
        unset($_SESSION['user_email']);
        unset($_SESSION['user_name']);
        session_destroy();
        redirect('/');
    }

    /**
     * isLoggedIn - Helper-Funktion. Ist ein User eingeloggt.
     *
     * @return void
     */
    public function isLoggedIn()
    {
        if (isset($_SESSION['user_id'])) {
            return true;
        } else {
            return false;
        }
    }
}
