<?php
//C
class VorgesetzterController extends Controller
{

    public function index()
    {
        // Instanzierung des Model
        $SpesenModel  = $this->model('SpesenModel');

        // Fake Data laden
        // Validierung der Berechtigung im nächsten Schritt
        $data = $SpesenModel->getFakeSpesenDataArray();

        // View wird gerendert
        echo $this->twig->render('vorgesetzter/index.twig.html', ['title' => 'Spesen', 'urlroot' => URLROOT, 'data' => $data]);
    }

    public function unterschreiben($id)
    {
        // Instanzierung des Model
        $SpesenModel = $this->model('SpesenModel');
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            // überprüft ob die Variable "submit" Gehnemigen oder Ablehnen ist
            if ($_POST['submit'] == 'Genehmigen') {
                $SpesenModel->fakeGenehmigeSpese($id);
            } else if ($_POST['submit'] == 'Ablehnen') {
                $SpesenModel->fakeAblehnenSpese($id);
            }
        } else {
            $data = $SpesenModel->getFakeSpesen($id);
            // rendert View
            echo $this->twig->render('vorgesetzter/unterschreiben.twig.html', ['title' => 'Spesen', 'urlroot' => URLROOT, 'data' => $data]);
        }
    }
}
