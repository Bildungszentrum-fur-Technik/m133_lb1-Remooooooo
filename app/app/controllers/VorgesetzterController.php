<?php

/**
 * Controller für das Vorgesetzten Formular
 */
class VorgesetzterController extends Controller
{

    // Übergabeparameter wird für die Rückmeldung beim Absenden genutzt
    public function index($information = '')
    {
        // Dürfen wir überhaupt diese Funktion nutzen? 
        if (!isset($_SESSION['user_id'])) {
            // Redirect home
            redirect('');
        } else {

            if (!in_array("vorgesetzter", $_SESSION['user_roles'])) {
                redirect('');
            }
            // Instanzierung des Model
            $SpesenModel  = $this->model('SpesenModel');

            // Daten Laden , zeigt alle Spesen an
            // Berechtigungsvalidierung ist noch ausstehend
            $data = $SpesenModel->getSpesenAlle();

            // View wird gerendert
            echo $this->twig->render('vorgesetzter/index.twig.html', ['title' => 'Spesen', 'urlroot' => URLROOT, 'data' => $data, 'information' => $information]);
        }
    }

    public function unterschreiben($id)
    {
        // Dürfen wir überhaupt diese Funktion nutzen? 
        if (!isset($_SESSION['user_id'])) {
            // Redirect home
            redirect('');
        } else {

            if (!in_array("vorgesetzter", $_SESSION['user_roles'])) {
                redirect('');
            }
            // Instanzierung des Model
            $SpesenModel = $this->model('SpesenModel');
            if ($_SERVER['REQUEST_METHOD'] == 'POST') {
                // überprüft ob die Variable "submit" Gehnemigen oder Ablehnen ist
                if ($_POST['submit'] == 'Genehmigen') {
                    $result = $SpesenModel->genehmigenSpesen($id);
                } else if ($_POST['submit'] == 'Ablehnen') {
                    $result = $SpesenModel->ablehnenSpesen($id);
                }
                if ($result) {
                    $this->index('Formular erfolgreich bearbeitet.');
                } else {
                    $this->index('Formular konnte nicht bearbeitet werden.');
                }
            } else {

                // Funktion gibt Daten des ausgewählten Formulars aus
                $data = $SpesenModel->getSpesenById($id);

                // Überprüft ob das Formular existiert, falls ja wird es gerendert
                if (count($data) > 0) {
                    echo $this->twig->render('vorgesetzter/unterschreiben.twig.html', ['title' => 'Spesen', 'urlroot' => URLROOT, 'data' => $data[0]]);
                } else {
                    $this->index('Spesenformular nicht gefunden');
                }
                // rendert View

            }
        }
    }
}
