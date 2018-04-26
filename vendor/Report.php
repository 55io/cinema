<?php
class Report extends Request {
    private $query;
    private $description;

    public function __construct(Database $database, $description = '', $query = '') {
        parent::__construct($database);
        $this->query = $query;
        $this->description = $description;
    }

    public function generate() {
        $tablePart = $this->select($this->query);
        return ['description' => $this->description, 'table' => $tablePart];
    }
}