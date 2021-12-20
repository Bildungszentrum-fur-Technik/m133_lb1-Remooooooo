<?php
//Controller für die View der Ansicht von einem Mitarbeiter ausgefüllten Formular
//Wurde nicht in den Vorgaben definiert, wurde noch zuzätzlich hinzugefügt
class MitarbeiterController extends Controller
{

    public function index()
    {
        // Dürfen wir überhaupt diese Funktion nutzen? 
        if (!isset($_SESSION['user_id'])) {
            // Kein Login, Keine Bestellungen -> möglich wäre auch eine Weiterleitung auf Login
            redirect('Users/login');
        } else {

            // Instanzierung des Model
            $SpesenModel  = $this->model('SpesenModel');

            // Daten werden Geladen
            // In einem späteren Schritt wird die UserID verwendet, im Moment ist der Parameter hardcoded.
            $data = $SpesenModel->getSpesenUser($_SESSION["user_id"]);


            // View wird gerendert
            echo $this->twig->render('mitarbeiter/index.twig.html', ['title' => 'Spesen', 'urlroot' => URLROOT, 'data' => $data]);
        }
    }
}
