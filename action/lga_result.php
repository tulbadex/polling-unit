<?php
header("Access-Control-Allow-Origin: *");
include_once('../config/database.php');
session_start();

if ($_SERVER['REQUEST_METHOD'] == "POST") {
    $_POST = json_decode(file_get_contents('php://input'), true);
    $error = $success = false;
    if (isset($_POST['action'], $_POST['lg_id'], $_POST['state_id']) && $_POST['action'] == "get-lga-total-result" && $_POST !== '') {
        $lga_id = $_POST['lg_id'];
        $state_id = $_POST['state_id'];

        if (!empty($_POST['lg_id'] && !empty($_POST['state_id']))) {
            // $query = "SELECT lga_id from polling_unit pu inner join announced_pu_results ap on ap.polling_unit_uniqueid = pu.polling_unit_id";
            $query = "SELECT polling_unit_uniqueid, party_abbreviation, sum(party_score) as score from announced_pu_results ap 
                        inner join  polling_unit pu on pu.polling_unit_id = ap.polling_unit_uniqueid and pu.lga_id = :lga_id
                        inner join  lga lg on lg.lga_id = pu.lga_id and lg.state_id = :state_id
                        group by ap.party_abbreviation
                        order by ap.party_abbreviation";
            $statement = $conn->prepare($query);
            $statement->execute(array(
                ':lga_id' => $lga_id,
                ':state_id' => $state_id
            ));

            $result = $statement->fetchAll(PDO::FETCH_ASSOC);
            echo json_encode($result);
        }
    }
}