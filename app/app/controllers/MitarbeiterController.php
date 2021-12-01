<?php
//Controller für die View der Ansicht von einem Mitarbeiter ausgefüllten Formular
//Wurde nicht in den Vorgaben definiert, wurde noch zuzätzlich hinzugefügt
class MitarbeiterController extends Controller
{

    public function index()
    {
        // Instanzierung des Model
        $SpesenModel  = $this->model('SpesenModel');

        // Daten werden Geladen
        // In einem späteren Schritt wird die UserID verwendet, im Moment ist der Parameter hardcoded.
        $data = $SpesenModel->getSpesenUser(2);


        // View wird gerendert
        echo $this->twig->render('mitarbeiter/index.twig.html', ['title' => 'Spesen', 'urlroot' => URLROOT, 'data' => $data]);
    }
}
