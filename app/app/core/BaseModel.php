<?php
/**
 * Übernommen von C.Inauen und als gut befunden
 * Keine Änderungen vorgenommen
 */
class BaseModel {
    protected $db;

    public function __construct()
    {
        $this->db = new Database;
    }
}