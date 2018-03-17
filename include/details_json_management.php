<?php
include_once('config.php');
include_once('details_functions.php');
include_once('database_getters.php');

if(!empty($_POST['user'])) {
    $username = htmlspecialchars($_POST['user']);
    $result = array();
    
    if(!empty($_POST['id'])  or (isset($_POST['id']) and $_POST['id'] == '0'))
        $annonce_id = htmlspecialchars($_POST['id']);
    
    if(!empty($_POST['available']) or (isset($_POST['available']) and $_POST['available'] == '0'))
        $available = htmlspecialchars($_POST['available']);
    
    if(isset($annonce_id) and isset($available)) {
        set_available($annonce_id, $available);
        $add_update_field = true;
    }
    
    if(isset($annonce_id) and (!empty($_POST['note_input']) or (isset($_POST['note_input']) and $_POST['note_input'] == '0'))) {
        $note_to_set = htmlspecialchars($_POST['note_input']);
        vote($annonce_id, $username, $note_to_set);
        $add_update_field = true;
    }
    
    $result = annonces_query($username);
    
    if(isset($add_update_field) and $add_update_field)
        $result['updatethisid'] = $annonce_id;
    
    echo json_encode($result);
}
?>