<?php
include_once('config.php');
include_once('details_functions.php');
include_once('database_getters.php');

if(!empty($_POST['user'])) {
    $username = htmlspecialchars($_POST['user']);
    
    if((!empty($_POST['note_input']) or (isset($_POST['note_input']) and $_POST['note_input'] == '0')) and !empty($_POST['id'])) {
        $note_to_set = htmlspecialchars($_POST['note_input']);
        $annonce_id = htmlspecialchars($_POST['id']);
        
        vote($annonce_id, $username, $note_to_set);
    }
    
    $result = annonces_query($username);
    //$result['note_input'] = $note_to_set;
    //$result['annonce_id'] = $annonce_id;
}
else {
    echo 'MONGROSZBI';
    $result = annonces_query();
}

echo json_encode($result);
?>