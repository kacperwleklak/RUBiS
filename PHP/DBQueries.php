<?php

require_once 'DBConnection.php';
require_once 'UUID.php';
require_once 'Stats.php';

class DBQueries {

    function __construct() {
        $this->database = new DBConnection();
        $this->database->connect();
        $this->connection = $this->database->get_connection();
        $this->stats = Stats::getInstance();

    }

    function __destruct() {
        $this->database->close_connection();
    }

    function insert_users($firstname, $lastname, $nickname, $password, $email, $regionId) {
        $start = microtime(true);
        $result = $this->connection->query("INSERT INTO users VALUES ('".$this->randomUUID()."', '".$firstname."', '".$lastname."', '".$nickname."', '".$password."', '".$email."',0, 0, NOW(), '".$regionId."');");
        $duration = microtime(true) - $start;
        $this->stats->add_insert_users($duration);
        return $result ? 1 : 0;
    }

    function insert_items($name, $description, $initialPrice, $qty, $reservePrice, $buyNow, $end, $userId, $categoryId) {
        $start = microtime(true);
        $result = $this->connection->query("INSERT INTO items VALUES ('".$this->randomUUID()."', '".$name."', '".$description."', '".$initialPrice."', '".$qty."', '".$reservePrice."', '".$buyNow."', 0, 0, NOW(), '".$end."', '".$userId."', '".$categoryId."');");
        $duration = microtime(true) - $start;
        $this->stats->add_insert_items($duration);
        return $result ? 1 : 0;
    }

    function process_comment($from, $to, $itemId, $rating, $comment) {
        $start = microtime(true);
        $this->connection->query("CALL PROCESS_COMMENT('".$this->randomUUID()."', '".$from."', '".$to."', '".$itemId."', '".$rating."', '".$comment."');");
        $duration = microtime(true) - $start;
        $this->stats->add_process_comment($duration);
        return 1;
    }

    function process_bid($itemId, $maxBid, $userId, $qty, $bid) {
        $start = microtime(true);
        $this->connection->query("CALL PROCESS_BID('".$this->randomUUID()."', '".$itemId."', '".$maxBid."', '".$userId."', '".$qty."', '".$bid."')");
        $duration = microtime(true) - $start;
        $this->stats->add_process_bid($duration);
    }

    function process_buyNow($itemId, $qty, $userId) {
        $start = microtime(true);
        $this->connection->query("CALL BUY_NOW('".$this->randomUUID()."', '".$itemId."', '".$qty."', '".$userId."')");
        $duration = microtime(true) - $start;
        $this->stats->add_process_buyNow($duration);
        return 1;
    }

    function user_authenticate($nickname, $password) {
        $start = microtime(true);
        $stmt = $this->connection->query("SELECT id FROM users WHERE nickname='".$nickname."' AND password='".$password."';");
        $this->stats->add_user_authenticate(microtime(true) - $start);
        $result = $stmt->fetchAll();

        return isset($result[0]['id']) ? $result[0]['id'] : -1;
    }

    function selectFrom($table) {
        $start = microtime(true);
        $stmt = $this->connection->query("SELECT * FROM ".$table.";");
        $this->stats->add_selectFrom(microtime(true) - $start);
        return $stmt->fetchAll();
    }

    function selectCountFrom($table) {
        $start = microtime(true);
        $stmt= $this->connection->query("SELECT count(*) AS count FROM ".$table.";");
        $result = $stmt->fetchAll();
        $this->stats->add_selectCountFrom(microtime(true) - $start);
        return $result[0]['count'];
    }

    function selectItemsByCategory($categoryId, $page, $nbOfItems) {
        $startTime = microtime(true);
        if ($this->database->get_host_type() == "mssql") {
            $query = "SELECT items.id,items.name,items.initial_price,items.max_bid,items.nb_of_bids,items.end_date FROM items WHERE category=:categoryId AND end_date>=NOW() BETWEEN :start AND :end";

            $start = $page * $nbOfItems;
            $end = $start + $nbOfItems;
        } else {
            $end = $nbOfItems;
            $start = $page * $nbOfItems;
            $query = "SELECT items.id,items.name,items.initial_price,items.max_bid,items.nb_of_bids,items.end_date FROM items WHERE category=".$categoryId." LIMIT ".$start.", ".$end.";";
        }
        $stmt = $this->connection->query($query);
        $result = $stmt->fetchAll();
        $this->stats->add_selectItemsByCategory(microtime(true) - $startTime);
        return $result;
    }

    function selectItemsByRegion($categoryId, $regionId, $page, $nbOfItems) {
        $startTime = microtime(true);
        if ($this->database->get_host_type() == "mssql") {
            $query = "SELECT items.id,items.name,items.initial_price,items.max_bid,items.nb_of_bids,items.end_date FROM items,users WHERE items.category=:categoryId AND items.seller=users.id AND users.region=:regionId AND end_date>=NOW() BETWEEN :start AND :end";

            $start = $page * $nbOfItems;
            $end = $start + $nbOfItems;
        } else {
            $start = $page * $nbOfItems;
            $end = $nbOfItems;
            $query = "SELECT items.id,items.name,items.initial_price,items.max_bid,items.nb_of_bids,items.end_date FROM items,users WHERE items.category=".$categoryId." AND items.seller=users.id AND users.region=".$regionId." AND end_date>=NOW() LIMIT ".$start.",".$end.";";
        }

        $stmt = $this->connection->query($query);
        $result = $stmt->fetchAll();
        $duration = microtime(true) - $startTime;
        $this->stats->add_selectItemsByRegion($duration);
        return $result;
    }

    function selectRegionIdWhereName($region) {
        $startTime = microtime(true);
        $stmt = $this->connection->query("SELECT id FROM regions WHERE name='".$region."';");
        $result = $stmt->fetchAll();
        $duration = microtime(true) - $startTime;
        $this->stats->add_selectRegionIdWhereName($duration);
        return isset($result[0]['id']) ? $result[0]['id'] : -1;
    }

    function doesNicknameExist($nickname) {
        $startTime = microtime(true);
        $stmt = $this->connection->query("SELECT count(*) AS count FROM users WHERE nickname='".$nickname."';");
        $result = $stmt->fetchAll();
        $duration = microtime(true) - $startTime;
        $this->stats->add_doesNicknameExist($duration);
        return $result[0]['count'];
    }

    function selectUserByNickname($nickname) {
        $startTime = microtime(true);
        $stmt = $this->connection->query("SELECT * FROM users WHERE nickname='".$nickname."';");
        $result = $stmt->fetchAll();
        $duration = microtime(true) - $startTime;
        $this->stats->add_selectUserByNickname($duration);
        return $result;
    }

    function selectUserById($idUser) {
        $startTime = microtime(true);
        $stmt = $this->connection->query("SELECT * FROM users WHERE id='".$idUser."';");
        $result = $stmt->fetchAll();
        $duration = microtime(true) - $startTime;
        $this->stats->add_selectUserById($duration);
        return $result;
    }

    function selectItemById($idItem, $table) {
        $startTime = microtime(true);
        if ($table == 1)
            $stmt = $this->connection->query("SELECT * FROM items WHERE id='".$idItem."';");
        else
            $stmt = $this->connection->query("SELECT * FROM old_items WHERE id='".$idItem."';");
        $result = $stmt->fetchAll();
        $duration = microtime(true) - $startTime;
        $this->stats->add_selectItemById($duration);
        return $result;
    }

    function selectMaxItemBid($idItem) {
        $startTime = microtime(true);
        $stmt = $this->connection->query("SELECT MAX(bid) AS bid FROM bids WHERE item_id='".$idItem."';");
        $result = $stmt->fetchAll();
        $duration = microtime(true) - $startTime;
        $this->stats->add_selectMaxItemBid($duration);
        return $result[0]['bid'];
    }

    function selectNumBidsByItem($idItem) {
        $startTime = microtime(true);
        $stmt = $this->connection->query("SELECT COUNT(*) AS bid FROM bids WHERE item_id='".$idItem."';");
        $result = $stmt->fetchAll();
        $duration = microtime(true) - $startTime;
        $this->stats->add_selectNumBidsByItem($duration);
        return $result[0]['bid'];
    }

    function selectBidsByItem($itemId, $quantity) {
        $startTime = microtime(true);
        if ($this->database->get_host_type() == "mssql") {
            $stmt = $this->connection->query("SELECT * FROM bids WHERE item_id='".$itemId."' ORDER BY bid DESC BETWEEN 0 AND '".$quantity."';");
        } else {
            $stmt = $this->connection->query("SELECT * FROM bids WHERE item_id='".$itemId."' ORDER BY bid DESC LIMIT '".$quantity."';");
        }
        $result = $stmt->fetchAll();
        $duration = microtime(true) - $startTime;
        $this->stats->add_selectBidsByItem($duration);
        return $result;
    }

    function selectCommentsByToUser($userId) {
        $startTime = microtime(true);
        $stmt = $this->connection->query("SELECT * FROM comments WHERE comments.to_user_id='".$userId."';");
        $result = $stmt->fetchAll();
        $duration = microtime(true) - $startTime;
        $this->stats->add_selectCommentsByToUser($duration);
        return $result;
    }

    function selectUserBids($userId) {
        $startTime = microtime(true);
        $stmt = $this->connection->query("SELECT item_id, bids.max_bid FROM bids, items WHERE bids.user_id='".$userId."' AND bids.item_id=items.id AND items.end_date>=NOW() GROUP BY item_id;");
        $result = $stmt->fetchAll();
        $duration = microtime(true) - $startTime;
        $this->stats->add_selectUserBids($duration);
        return $result;
    }

    function selectWonItems30Days($userId) {
        $startTime = microtime(true);
        $stmt = $this->connection->query("SELECT item_id FROM bids, old_items WHERE bids.user_id='".$userId."' AND bids.item_id=old_items.id AND TO_DAYS(NOW()) - TO_DAYS(old_items.end_date) < 30 GROUP BY item_id;");
        $result = $stmt->fetchAll();
        $duration = microtime(true) - $startTime;
        $this->stats->add_selectWonItems30Days($duration);
        return $result;
    }

    function selectBoughtItems30Days($userId) {
        $startTime = microtime(true);
        $stmt = $this->connection->query("SELECT * FROM buy_now WHERE buy_now.buyer_id='".$userId."' AND TO_DAYS(NOW()) - TO_DAYS(buy_now.date)<=30;");
        $result = $stmt->fetchAll();
        $duration = microtime(true) - $startTime;
        $this->stats->add_selectBoughtItems30Days($duration);
        return $result;
    }

    function selectOnSaleItems($userId) {
        $startTime = microtime(true);
        $stmt = $this->connection->query("SELECT * FROM items WHERE items.seller='".$userId."' AND items.end_date>=NOW()");
        $result = $stmt->fetchAll();
        $duration = microtime(true) - $startTime;
        $this->stats->add_selectBoughtItems30Days($duration);
        return $result;
    }

    function selectSoldItems($userId) {
        $startTime = microtime(true);
        $stmt = $this->connection->query("SELECT * FROM old_items WHERE old_items.seller='".$userId."' AND TO_DAYS(NOW()) - TO_DAYS(old_items.end_date) < 30");
        $result = $stmt->fetchAll();
        $duration = microtime(true) - $startTime;
        $this->stats->add_selectSoldItems($duration);
        return $result;
    }

    function selectCommentsToUser($userId) {
        $startTime = microtime(true);
        $stmt = $this->connection->query("SELECT * FROM comments WHERE comments.to_user_id='".$userId."'");
        $result = $stmt->fetchAll();
        $duration = microtime(true) - $startTime;
        $this->stats->add_selectCommentsToUser($duration);
        return $result;
    }

    function getAllUsers() {
        $startTime = microtime(true);
        $stmt = $this->connection->query("SELECT id FROM users;");
        $result = $stmt->fetchAll();
        $duration = microtime(true) - $startTime;
        $this->stats->add_getAllUsers($duration);
        return $result;
    }

    private function randomUUID() : string {
        return UUID::generate();
    }

}
