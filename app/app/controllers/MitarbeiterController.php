<?php
//Controller für die View der Ansicht von einem Mitarbeiter ausgefüllten Formular
//Wurde nicht in den Vorgaben definiert, wurde noch zuzätzlich hinzugefügt
class MitarbeiterController extends Controller
{

    public function index()
    {
        // Instanzierung des Model
        $SpesenModel  = $this->model('SpesenModel');

        // Fake Data laden
        // Validierung der Berechtigung im nächsten Schritt
        $data = $SpesenModel->getFakeSpesenDataArray();

        // View wird gerendert
        echo $this->twig->render('mitarbeiter/index.twig.html', ['title' => 'Spesen', 'urlroot' => URLROOT, 'data' => $data]);
    }
}
