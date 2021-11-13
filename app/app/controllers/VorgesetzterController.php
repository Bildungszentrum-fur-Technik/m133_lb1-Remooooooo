<?php
//C
class VorgesetzterController extends Controller{

    public function index(){
        // Instanzierung des Model
        $SpesenModel  = $this->model('SpesenModel');

        // Fake Data laden
        // Validierung der Berechtigung im nächsten Schritt
        $data = $SpesenModel->getFakeSpesenDataArray();

        // View wird gerendert
        echo $this->twig->render('vorgesetzter/index.twig.html', ['title' => 'Spesen', 'urlroot' => URLROOT, 'data' => $data] );                
    }

    public function unterschreiben($id){
    
        $SpesenModel = $this->model('SpesenModel');
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    }
    else{
        $data = $SpesenModel->getFakeSpesen($id);
        echo $this->twig->render('vorgesetzter/unterschreiben.twig.html', ['title' => 'Spesen', 'urlroot' => URLROOT, 'data' => $data] );     
    }
}
}