<?php
require_once 'server/database.php';
require_once 'server/functions.php';

if (isset($_POST['mainway_id'])) {
    $mainway_id = $_POST['mainway_id'];
    $sub_ways = getSubWays($mainway_id);

    foreach ($sub_ways as $sub_way) {
        echo '<option value="' . $sub_way['id'] . '">' . htmlspecialchars($sub_way['subway_name']) . '</option>';
    }
}
?>
