<?php
interface Controller {
    function listar();
    function detalhar($id);
    function criar();
    function editar($id);
    function remover($id);
}
?>