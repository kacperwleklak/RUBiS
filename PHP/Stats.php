<?php

class Stats
{

    /**
     * @var array
     */
    private $insert_users;
    /**
     * @var array
     */
    private $insert_items;
    /**
     * @var array
     */
    private $process_comment;
    /**
     * @var array
     */
    private $process_bid;
    /**
     * @var array
     */
    private $process_buyNow;
    /**
     * @var array
     */
    private $user_authenticate;
    /**
     * @var array
     */
    private $selectFrom;
    /**
     * @var array
     */
    private $selectCountFrom;

    private $getAllUsers;

    protected function __construct()
    {
        $this->reset();
    }

    private static $instances = [];

    public function reset() {
        $this->insert_users = array();
        $this->insert_items = array();
        $this->process_comment = array();
        $this->process_bid = array();
        $this->process_buyNow = array();
        $this->user_authenticate = array();
        $this->selectItemsByCategory = array();
        $this->selectItemsByRegion = array();
        $this->selectRegionIdWhereName = array();
        $this->doesNicknameExist = array();
        $this->selectUserByNickname = array();
        $this->selectUserById = array();
        $this->selectItemById = array();
        $this->selectMaxItemBid = array();
        $this->selectNumBidsByItem = array();
        $this->selectBidsByItem = array();
        $this->selectCommentsByToUser = array();
        $this->selectUserBids = array();
        $this->selectWonItems30Days = array();
        $this->selectBoughtItems30Days = array();
        $this->selectOnSaleItems = array();
        $this->selectSoldItems = array();
        $this->selectCommentsToUser = array();
        $this->selectFrom = array();
        $this->selectCountFrom = array();
        $this->getAllUsers = array();
    }

    public static function getInstance(): Stats
    {
        $cls = static::class;
        if (!isset(self::$instances[$cls])) {
            self::$instances[$cls] = new static();
        }

        return self::$instances[$cls];
    }

    function add_insert_users($duration) {
        $this->insert_users[] = $duration;
    }
    function add_insert_items($duration) {
        $this->insert_items[] = $duration;
    }
    function add_process_comment($duration) {
        $this->process_comment[] = $duration;
    }
    function add_process_bid($duration) {
        $this->process_bid[] = $duration;
    }
    function add_process_buyNow($duration) {
        $this->process_buyNow[] = $duration;
    }
    function add_user_authenticate($duration) {
        $this->user_authenticate[] = $duration;
    }
    function add_selectItemsByCategory($duration) {
        $this->selectItemsByCategory[] = $duration;
    }
    function add_selectItemsByRegion($duration) {
        $this->selectItemsByRegion[] = $duration;
    }
    function add_selectRegionIdWhereName($duration) {
        $this->selectRegionIdWhereName[] = $duration;
    }
    function add_doesNicknameExist($duration) {
        $this->doesNicknameExist[] = $duration;
    }
    function add_selectUserByNickname($duration) {
        $this->selectUserByNickname[] = $duration;
    }
    function add_selectUserById($duration) {
        $this->selectUserById[] = $duration;
    }
    function add_selectItemById($duration) {
        $this->selectItemById[] = $duration;
    }
    function add_selectMaxItemBid($duration) {
        $this->selectMaxItemBid[] = $duration;
    }
    function add_selectNumBidsByItem($duration) {
        $this->selectNumBidsByItem[] = $duration;
    }
    function add_selectBidsByItem($duration) {
        $this->selectBidsByItem[] = $duration;
    }
    function add_selectCommentsByToUser($duration) {
        $this->selectCommentsByToUser[] = $duration;
    }
    function add_selectUserBids($duration) {
        $this->selectUserBids[] = $duration;
    }
    function add_selectWonItems30Days($duration) {
        $this->selectWonItems30Days[] = $duration;
    }
    function add_selectBoughtItems30Days($duration) {
        $this->selectBoughtItems30Days[] = $duration;
    }
    function add_selectOnSaleItems($duration) {
        $this->selectOnSaleItems[] = $duration;
    }
    function add_selectSoldItems($duration) {
        $this->selectSoldItems[] = $duration;
    }
    function add_selectCommentsToUser($duration) {
        $this->selectCommentsToUser[] = $duration;
    }
    function add_selectFrom($duration) {
        $this->selectFrom[] = $duration;
    }
    function add_selectCountFrom($duration) {
        $this->selectCountFrom[] = $duration;
    }

    function add_getAllUsers($duration) {
        $this->getAllUsers[] = $duration;
    }


}
