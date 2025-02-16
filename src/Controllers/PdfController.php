<?php
namespace App\Controllers;
use App\Models\PdfModel;

class PdfController
{
    private $PdfModel;
    public function __construct(){
        $this->PdfModel = new PdfModel();
    }

    public function create($id)
    {
        $this->PdfModel->create($id);
    }
    
    public function search()
    {
        $id = $_POST['id'] ?? '';
        $this->PdfModel->search($id);
    }
}