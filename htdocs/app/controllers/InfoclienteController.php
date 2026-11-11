<?php
class InfoclienteController extends Controller {

    public function nosotros() {
        $this->data['title'] = 'Quiénes Somos – San Plácido';
        $this->render('nosotros');
    }

    public function ubicacion() {
        $this->data['title'] = 'Dónde Estamos – San Plácido';
        $this->render('ubicacion');
    }
}