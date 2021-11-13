<?php
/**
 * Definition der SpesenModel Attribute
 * 
 * id -> ID des Spesenformular, wird referenziert werden von den Formularen
 * .....
 */
class SpesenModel extends BaseModel{
    private $id; // War nicht in der Doku, wurde nachträglich hinzugefügt
    private $personalnummer;
    private $datum;
    private $reiseziel;
    private $essenskosten;
    private $fahrtkosten;
    private $kmanzahl;
    private $uebernachtung;
    private $verkehrsmittel;
    private $unterschrift;

    /**
     * TestMethode die nur Fake-Daten liefert
     *
     * @return $data : Array aus Fake-Menues
     */
    public function getFakeSpesenDataArray()
    {
        $data = [
            ['id' => '1', 'personalnummer' => '0001', 'datum' => '2021-11-13', 'reiseziel' => 'Jowa Gossau', 'essenskosten' => '20.50', 'fahrtkosten' => '40.50', 'kmanzahl' => '40', 
            'uebernachtung' => '30.20','verkehrsmittel' => 'Zug','unterschrift' => true],

            ['id' => '2', 'personalnummer' => '0002', 'datum' => '2021-11-14', 'reiseziel' => 'Jowa Volketswil', 'essenskosten' =>'30.50', 'fahrtkosten' => '25.00', 'kmanzahl' => '50', 
             'uebernachtung' => '40.20','verkehrsmittel' => 'Auto','unterschrift' => false],

            ['id' => '3', 'personalnummer' => '0003', 'datum' => '2021-11-15', 'reiseziel' => 'Jowa Gränichen', 'essenskosten' => '15.50', 'fahrtkosten' => '60.50', 'kmanzahl' => '60', 
             'uebernachtung' => '0.00','verkehrsmittel' => 'Auto','unterschrift' => false]
        ];

        return $data;
    }

    public function getFakeSpesen($id)
    {
        $data = 
            ['id' => $id, 'personalnummer' => '0001', 'datum' => '2021-11-13', 'reiseziel' => 'Jowa Gossau', 'essenskosten' => '20.50', 'fahrtkosten' => '40.50', 'kmanzahl' => '40', 'uebernachtung' => '30.20','verkehrsmittel' => 'Zug','unterschrift' => true];

        return $data; 
    }
    public function fakewriteData($data) {
        die(var_dump($data));
}

}