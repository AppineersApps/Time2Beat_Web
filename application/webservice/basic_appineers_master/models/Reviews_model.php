<?php
defined('BASEPATH') || exit('No direct script access allowed');

/**
 * Description of User review Model
 *
 * @category webservice
 *
 * @package basic_appineers_master
 *
 * @subpackage models
 *
 * @module User review
 *
 * @class User_review_model.php
 *
 * @path application\webservice\basic_appineers_master\models\User_review_model.php
 *
 * @version 4.4
 *
 * @author CIT Dev Team
 *
 * @since 18.09.2019
 */

class Reviews_model extends CI_Model
{
    public $default_lang = 'EN';

    /**
     * __construct method is used to set model preferences while model object initialization.
     */
    public function __construct()
    {
        parent::__construct();
        $this->load->helper('listing');
        $this->default_lang = $this->general->getLangRequestValue();
    }

    /**
     * post_a_feedback method is used to execute database queries for Post a Feedback API.
     * @created CIT Dev Team
     * @modified priyanka chillakuru | 16.09.2019
     * @param array $params_arr params_arr array to process review block.
     * @return array $return_arr returns response of review block.
     */
    public function set_review($params_arr = array())
    {
        try
        {
            $result_arr = array();
            if (!is_array($params_arr) || count($params_arr) == 0)
            {
                throw new Exception("Insert data not found.");
            }

            $this->db->set("dAddedAt", $params_arr["_dtaddedat"]);
            $this->db->set("eStatus", $params_arr["_estatus"]);
            $this->db->set("bIsClaimed", $params_arr["is_claimed"]);
            if (isset($params_arr["user_id"]))
            {
                $this->db->set("iUserId", $params_arr["user_id"]);
            }
            if (isset($params_arr["first_name"]))
            {
                $this->db->set("vFirstName", $params_arr["first_name"]);
            }
            if (isset($params_arr["last_name"]))
            {
                $this->db->set("vLastName", $params_arr["last_name"]);
            }
            if (isset($params_arr["mobile_number"]))
            {
                $this->db->set("vMobileNo", $params_arr["mobile_number"]);
            }
            if (isset($params_arr["email"]))
            {
                $this->db->set("vEmail", $params_arr["email"]);
            }
            if (isset($params_arr["position"]))
            {
                $this->db->set("vPosition", $params_arr["position"]);
            }
            if (isset($params_arr["street_address"]))
            {
                $this->db->set("tAddress", $params_arr["street_address"]);
            }
            if (isset($params_arr["city"]))
            {
                $this->db->set("vCity", $params_arr["city"]);
            }
            if (isset($params_arr["state"]))
            {
                $this->db->set("iStateId", $params_arr["state"]);
            }
            if (isset($params_arr["google_placeid"]))
            {
                $this->db->set("vPlaceId", $params_arr["google_placeid"]);
            }
            if (isset($params_arr["business_name"]))
            {
                $this->db->set("vBussinessName", $params_arr["business_name"]);
            }
            if (isset($params_arr["business_typeid"]))
            {
                $this->db->set("iBussinessType", $params_arr["business_typeid"]);
            }
            if (isset($params_arr["review_stars"]))
            {
                $this->db->set("iStars", $params_arr["review_stars"]);
            }
            if (isset($params_arr["description"]))
            {
                $this->db->set("vDescription", $params_arr["description"]);
            }
            if (isset($params_arr["review_type"]))
            {
                $this->db->set("vReviewType", $params_arr["review_type"]);
            }
            if (isset($params_arr["latitude"]))
            {
                $this->db->set("dLatitude", $params_arr["latitude"]);
            }
            if (isset($params_arr["longitude"]))
            {
                $this->db->set("dLongitude", $params_arr["longitude"]);
            }
             if (isset($params_arr["profile_image"]) && !empty($params_arr["profile_image"]))
            {
                $this->db->set("vProfileImage", $params_arr["profile_image"]);
            }
            if (isset($params_arr["zipcode"]))
            {
                $this->db->set("vZipCode", $params_arr["zipcode"]);
            }
            
            $this->db->insert("review");
            $insert_id = $this->db->insert_id();
            if (!$insert_id)
            {
                throw new Exception("Failure in insertion.");
            }
            $result_param = "review_id";
            $result_arr[0][$result_param] = $insert_id;
            $success = 1;
        }
        catch(Exception $e)
        {
            $success = 0;
            $message = $e->getMessage();
        }

        $this->db->_reset_all();
        #echo $this->db->last_query();exit;
        $return_arr["success"] = $success;
        $return_arr["message"] = $message;
        $return_arr["data"] = $result_arr;
        return $return_arr;
    }


    /**
     * get_review_details method is used to execute database queries for Post a Feedback API.
     * @created priyanka chillakuru | 16.09.2019
     * @modified priyanka chillakuru | 16.09.2019
     * @param string $review_id review_id is used to process review block.
     * @return array $return_arr returns response of review block.
     */
    public function get_updated_reviews($arrResult)
    {
       // print_r($arrResult); exit;
        try
        {
            $result_arr = array();
            if(true == empty($arrResult)){
                return false;
            }
            $strWhere ='';
            $this->db->start_cache();
            $this->db->from("review AS r");
            $this->db->join("mod_state AS ms", "r.iStateId = ms.iStateId", "left");
            $this->db->join("users AS u", "u.vEmail = r.vClaimedEmail OR FIND_IN_SET(u.vEmail, r.vClaimedEmail)", "left");
            $this->db->select("r.iReviewId AS review_id");
            $this->db->select("(concat(r.vFirstName,' ',r.vLastName)) AS consumer_full_name", FALSE);
            $this->db->select("r.vMobileNo AS consumer_mobile_numer");            
            $this->db->select("r.vEmail AS consumer_email_address");            
            $this->db->select("r.vBussinessName AS consumer_business_name");
            $this->db->select("r.vProfileImage AS consumer_profile_image");
            $this->db->select("r.iStars AS review_rating");
            $this->db->select("r.vDescription AS review_description");
            $this->db->select("r.dAddedAt AS review_adddate");
            $this->db->select("r.iUserId AS user_id");  
            $this->db->select("r.vClaimedEmail AS claimed_email");
            $this->db->select("r.bIsClaimed AS is_claimed");
            $this->db->select("u.dTotalStarCount AS total_star_count");
            $this->db->select("u.dAverageRating AS average_rating");
            $this->db->select("u.iTotalReviewCount AS total_review_count");
           if (isset($arrResult['updated_review_id'] ) && $arrResult['updated_review_id']  != "")
            {
               $this->db->where_in("r.iReviewId", $arrResult['updated_review_id']);
            }  
            if (isset($arrResult['registered_user_id'] ) && $arrResult['registered_user_id']  != "")
            {
              $this->db->where_in("u.iUserId", $arrResult['registered_user_id']);
            }
           
            $result_obj = $this->db->get();
            //echo $this->db->last_query();exit;
            $result_arr = is_object($result_obj) ? $result_obj->result_array() : array();
            if (!is_array($result_arr) || count($result_arr) == 0)
            {
                throw new Exception('No records found.');
            }
            $success = 1;
        }
        catch(Exception $e)
        {
            $success = 0;
            $message = $e->getMessage();
        }

        $this->db->stop_cache();
        $this->db->flush_cache();
        $this->db->_reset_all();
        $return_arr["success"] = $success;
        $return_arr["message"] = $message;
        $return_arr["data"] = $result_arr;
        return $return_arr;
    }

    /**
     * get_review_details method is used to execute database queries for Post a Feedback API.
     * @created priyanka chillakuru | 16.09.2019
     * @modified priyanka chillakuru | 16.09.2019
     * @param string $review_id review_id is used to process review block.
     * @return array $return_arr returns response of review block.
     */
    public function get_review_details($arrResult)
    {
        try
        {
            //print_r($arrResult); 
            $result_arr = array();
            if(true == empty($arrResult)){
                return false;
            }
            $strWhere ='';
            $page = (false == empty($arrResult['page_number'])) ?  $arrResult['page_number'] : 0;
            $rec_per_page =($page != '') ? 10 : 0;
            $start_from = ($page-1) * $rec_per_page;
            $strPaginationSql ='';

            
            if (false == empty($arrResult['page_name']) && ("new_user_listing" == $arrResult['page_name']))
            {
            if (false == empty($arrResult['email_address']) && false == empty($arrResult['mobile_number']))
            {
              $strWhere = "r.vEmail='" . $arrResult['email_address'] . "' OR r.vMobileNo='" . $arrResult['mobile_number'] . "'";
                if(isset($arrResult['business_name']) && $arrResult['business_name'] != "")
                {
                    $strWhere .= " AND r.vBussinessName='" . $arrResult['business_name'] . "'";
                }
                if(isset($arrResult['first_name']) && $arrResult['first_name'] != "")
                {
                    $strWhere .= " AND r.vFirstName='" . $arrResult['first_name'] . "'";
                }
                if(isset($arrResult['last_name']) && $arrResult['last_name'] != "")
                {
                    $strWhere .= " AND r.vLastName='" . $arrResult['last_name'] . "'";
                }
            }
            elseif(isset($arrResult['mobile_number']) && false == empty($arrResult['mobile_number'])  && true == empty($arrResult['email_address']))
            {
                $strWhere = "r.vMobileNo='" . $arrResult['mobile_number'] . "'";
                if(isset($arrResult['business_name']) && $arrResult['business_name'] != "")
                {
                    $strWhere .= " AND r.vBussinessName='" . $arrResult['business_name'] . "'";
                }
                if(isset($arrResult['first_name']) && $arrResult['first_name'] != "")
                {
                    $strWhere .= " AND r.vFirstName='" . $arrResult['first_name'] . "'";
                }
                if(isset($arrResult['last_name']) && $arrResult['last_name'] != "")
                {
                    $strWhere .= " AND r.vLastName='" . $arrResult['last_name'] . "'";
                }
            }elseif(isset($arrResult['email_address']) && false == empty($arrResult['email_address']) && true == empty($arrResult['mobile_number']))
            {
              $strWhere = "r.vEmail='" . $arrResult['email_address'] . "'";
                if(isset($arrResult['business_name']) && $arrResult['business_name'] != "")
                {
                    $strWhere .= " AND r.vBussinessName='" . $arrResult['business_name'] . "'";
                }
                if(isset($arrResult['first_name']) && $arrResult['first_name'] != "")
                {
                    $strWhere .= " AND r.vFirstName='" . $arrResult['first_name'] . "'";
                }
                if(isset($arrResult['last_name']) && $arrResult['last_name'] != "")
                {
                    $strWhere .= " AND r.vLastName='" . $arrResult['last_name'] . "'";
                }
            }
            if(false == empty($strWhere))
            {
              $strWhere .= " AND r.eStatus= 'Active'";
            }else{
              $strWhere = "r.eStatus= 'Active'";
            }
                 
             if("consumer_listing" == $arrResult['page_name'])
             {
              $this->db->select("(concat(u.vFirstName,' ',u.vLastName)) AS user_name", FALSE);
              $this->db->select("u.vProfileImage AS user_profile_image");
             }

            }
            if (isset($arrResult['page_name'] ) && $arrResult['page_name']  != "" && "home" == $arrResult['page_name'])
            {
                 $strWhere = "r.eStatus= 'Active'";
            }
            if (isset($arrResult['page_name'] ) && $arrResult['page_name']  != "" && "search" == $arrResult['page_name'])
            {
                  $strWhere = "r.eStatus= 'Active'";
                 if(false == empty($arrResult['first_name']))
                 {
                     $strWhere .= " AND lower(r.vFirstName) LIKE '" . strtolower($arrResult['first_name']) . "%' ";
                 }
                 if(false == empty($arrResult['last_name']))
                 {
                     $strWhere .= " AND lower(r.vLastName) LIKE '" . strtolower($arrResult['last_name']) . "%' ";
                 }
                 if(false == empty($arrResult['city']))
                 {
                     $strWhere .= " AND lower(r.vCity) LIKE '" . strtolower($arrResult['city']) . "%' ";
                 }
                 if(false == empty($arrResult['zipcode']))
                 {
                     $strWhere .= " AND r.vZipCode LIKE '" . $arrResult['zipcode'] . "%' ";
                 }
                 if(false == empty($arrResult['state']))
                 {
                     $strWhere .= " AND r.iStateId LIKE '" . $arrResult['state'] . "%' ";
                 }
                 if(false == empty($arrResult['mobile_number']))
                 {
                     $strWhere .= " AND r.vMobileNo LIKE '" . $arrResult['mobile_number'] . "%' ";
                 }
                 if(false == empty($arrResult['business_name']))
                 {
                     $strWhere .= " AND r.vBussinessName LIKE '" . $arrResult['business_name'] . "%' ";
                 }
                  if(false == empty($arrResult['email_address']))
                 {
                     $strWhere .= " AND r.vEmail LIKE '" . $arrResult['email_address'] . "%' ";
                 }
                 
            }
            if (isset($arrResult['page_name'] ) && $arrResult['page_name']  != "" && "my_review" == $arrResult['page_name'])
            {
                  $strWhere = "r.eStatus= 'Active'";
                  $strWhere .= " AND r.iUserId='" . $arrResult['reviewer_id'] . "'";
            }
            if(false == empty($arrResult['page_name']) && "new_user_listing" == $arrResult['page_name'])
            {
              $strWhere ="(". $strWhere.")"."  AND FIND_IN_SET('" . $arrResult['email_address'] . "', r.vClaimedEmail) = 0 AND r.bIsClaimed = 0 AND r.eStatus= 'Active'";
            }
            if( false == empty($arrResult['page_name']) && ("review_for_me" == $arrResult['page_name'] || "consumer_listing" == $arrResult['page_name']))
             {
               $strWhere ="FIND_IN_SET('" . $arrResult['email_address'] . "', r.vClaimedEmail) AND r.eStatus= 'Active'";

             }
            
            $this->db->from("review AS r");
            $this->db->join("mod_state AS ms", "ms.iStateId = r.iStateId", "left");
            $this->db->join("users AS u", "u.vEmail = r.vClaimedEmail OR FIND_IN_SET(u.vEmail, r.vClaimedEmail)", "left");
            //$this->db->join("users AS u", "FIND_IN_SET(`u`.`vEmail`, `r`.`vClaimedEmail`) > 0", "left");
            
            /*if(false == empty($arrResult['email_address']))
            {
               $this->db->join("users AS u", "u.vEmail = '" . $arrResult['email_address'] . "' AND FIND_IN_SET(u.vEmail, r.vClaimedEmail) > 0", "left");

            }elseif(false == empty($arrResult['mobile_number']))
            {
              $this->db->join("users AS u", "u.vMobileNo = '" . $arrResult['mobile_number'] . "' AND FIND_IN_SET(u.vEmail, r.vClaimedEmail) > 0", "left");
            }else{
              $this->db->join("users AS u", "FIND_IN_SET(`u`.`vEmail`, `r`.`vClaimedEmail`) > 0", "left");
            }*/
            $this->db->join("business_type AS bt", "bt.iBusinessTypeId = r.iBussinessType", "left");
            $this->db->select("r.iReviewId AS review_id");
            $this->db->select("(concat(r.vFirstName,' ',r.vLastName)) AS consumer_full_name", FALSE);
            $this->db->select("r.vMobileNo AS consumer_mobile_numer");            
            $this->db->select("r.vEmail AS consumer_email_address");            
            $this->db->select("r.vBussinessName AS consumer_business_name");
            $this->db->select("r.vProfileImage AS consumer_profile_image");
            $this->db->select("r.iStars AS review_rating");
            $this->db->select("r.vReviewType AS review_type");
            $this->db->select("r.vDescription AS review_description");
            $this->db->select("r.dAddedAt AS review_adddate");
            $this->db->select("r.vPosition AS position");
            $this->db->select("ms.vState AS state"); 
            $this->db->select("r.vPlaceId AS place_id");
            $this->db->select("r.dLatitude AS latitude");
            $this->db->select("r.dLongitude AS longitude");
            $this->db->select("r.vCity AS city");
            $this->db->select("r.vZipCode AS zip_code");
            $this->db->select("r.tAddress AS street_address");
            $this->db->select("bt.vName AS business_type_name");
            $this->db->select("r.vClaimedEmail AS claimed_email");
            $this->db->select("r.iUserId AS user_id");
            $this->db->select("u.dTotalStarCount AS total_star_count");
            $this->db->select("u.dAverageRating AS average_rating");
            $this->db->select("u.iTotalReviewCount AS total_review_count");
            $this->db->select("r.bIsClaimed AS is_claimed");
            $this->db->order_by("r.iReviewId Desc");
            if(false == empty($strWhere)){
              $this->db->where($strWhere); 
            }          
            
            if (isset($arrResult['page_name'] ) && $arrResult['page_name']  != "" && "search" != $arrResult['page_name'])
               {
                 $this->db->limit($rec_per_page, $start_from);
               }
            $result_obj = $this->db->get();
           //echo $this->db->last_query();exit;
            
            $result_arr = is_object($result_obj) ? $result_obj->result_array() : array();
          
           $this->db->select('COUNT(r.iReviewId) AS "total_count"')->where($strWhere)->from('review AS r');
            $result_arr['total_count'] = $this->db->get()->row()->total_count;
            //echo $this->db->last_query();exit;
            if (!is_array($result_arr) || count($result_arr) == 0)
            {
                throw new Exception('No records found.');
            }
            $success = 1;
        }
        catch(Exception $e)
        {
            $success = 0;
            $message = $e->getMessage();
        }

        $this->db->_reset_all();
        //echo $this->db->last_review();
        $return_arr["success"] = $success;
        $return_arr["message"] = $message;
        $return_arr["data"] = $result_arr;
      // print_r($return_arr["data"]); exit;
        return $return_arr;
    }
   /**
     * update_profile method is used to execute database queries for Edit Profile API.
     * @created priyanka chillakuru | 18.09.2019
     * @modified priyanka chillakuru | 25.09.2019
     * @param array $params_arr params_arr array to process query block.
     * @param array $where_arr where_arr are used to process where condition(s).
     * @return array $return_arr returns response of query block.
     */
    public function update_review($params_arr = array(), $where_arr = array())
    {
        try
        {
            $result_arr = array();
            $this->db->start_cache();
            if (false == empty($params_arr["claimed_email"]) && false == empty($params_arr["review_id"]))
            {
               
                foreach($params_arr["review_id"] as $key => $review_id)
                {
                    $sql = "UPDATE review SET vClaimedEmail = TRIM(BOTH ',' FROM CONCAT(COALESCE(vClaimedEmail,''), ',', ?)),bIsClaimed=true, dtUpdatedAt='".date('Y-m-d H:i:s')."' WHERE iReviewId = ?;";
                      $res = $this->db->query($sql, array($params_arr["claimed_email"], $review_id));
                      if($res === false) {
                          echo "ERROR at index $review_id - query: ((".$this->db->last_query()."))<br/><br/>\n\n";
                          print_r($this->db->error()); echo "<br/><br/>\n\n";
                      }
                }
            }
            else
            {
                $this->db->start_cache();
                if (isset($where_arr["review_id"]) && $where_arr["review_id"] != "")
                {
                    $this->db->where("iReviewId =", $where_arr["review_id"]);
                }
                $this->db->where_in("eStatus", array('Active'));
                $this->db->stop_cache();
                if (isset($params_arr["first_name"]))
                {
                    $this->db->set("vFirstName", $params_arr["first_name"]);
                }
                if (isset($params_arr["last_name"]))
                {
                    $this->db->set("vLastName", $params_arr["last_name"]);
                }
                if (isset($params_arr["user_profile"]) && !empty($params_arr["user_profile"]))
                {
                    $this->db->set("vProfileImage", $params_arr["user_profile"]);
                }
                if (isset($params_arr["dob"]))
                {
                    $this->db->set("dDob", $params_arr["dob"]);
                }
                if (isset($params_arr["address"]))
                {
                    $this->db->set("tAddress", $params_arr["address"]);
                }
                if (isset($params_arr["city"]))
                {
                    $this->db->set("vCity", $params_arr["city"]);
                }
                if (isset($params_arr["latitude"]))
                {
                    $this->db->set("dLatitude", $params_arr["latitude"]);
                }
                if (isset($params_arr["longitude"]))
                {
                    $this->db->set("dLongitude", $params_arr["longitude"]);
                }
                if (isset($params_arr["state_id"]))
                {
                    $this->db->set("iStateId", $params_arr["state_id"]);
                }
                if (isset($params_arr["zipcode"]))
                {
                    $this->db->set("vZipCode", $params_arr["zipcode"]);
                }
                $this->db->set($this->db->protect("dtUpdatedAt"), $params_arr["_dtupdatedat"], FALSE);
                if (isset($params_arr["user_name"]))
                {
                    $this->db->set("vUserName", $params_arr["user_name"]);
                }
                if (isset($params_arr["mobile_number"]))
                {
                    $this->db->set("vMobileNo", $params_arr["mobile_number"]);
                }
                if (isset($params_arr["business_type_id"]))
                {
                    $this->db->set("iBusinessTypeId", $params_arr["business_type_id"]);
                }
                if (isset($params_arr["business_name"]))
                {
                    $this->db->set("vBussinessName", $params_arr["business_name"]);
                }
                if (isset($params_arr["position"]))
                {
                    $this->db->set("vPosition", $params_arr["position"]);
                }
                if (isset($params_arr["description"]))
                {
                    $this->db->set("vDescription", $params_arr["description"]);
                }
                if (isset($params_arr["review_stars"]))
                {
                    $this->db->set("iStars", $params_arr["review_stars"]);
                }
                $res = $this->db->update("review");
            }
                $affected_rows = $this->db->affected_rows();
                if (!$res || $affected_rows == -1)
                {
                    throw new Exception("Failure in updation.");
                }
                $result_param = "affected_rows";
                $result_arr[0][$result_param] = $affected_rows;
                $success = 1;

        }
        catch(Exception $e)
        {
            $success = 0;
            $message = $e->getMessage();
        }
        $this->db->flush_cache();
        $this->db->_reset_all();
        //echo $this->db->last_query();
        $return_arr["success"] = $success;
        $return_arr["message"] = $message;
        $return_arr["data"] = $result_arr;
        return $return_arr;
    }


      /**
     * delete review method is used to execute database queries for Edit Profile API.
     * @created priyanka chillakuru | 18.09.2019
     * @modified priyanka chillakuru | 25.09.2019
     * @param array $params_arr params_arr array to process query block.
     * @param array $where_arr where_arr are used to process where condition(s).
     * @return array $return_arr returns response of query block.
     */
    public function delete_review($params_arr = array())
    {
        try
        {
            $result_arr = array();
            $this->db->start_cache();
            if (isset($params_arr["review_id"]))
            {
                $this->db->where("iReviewId =", $params_arr["review_id"]);
            }
            $this->db->stop_cache();
            $this->db->set("eStatus", 'InActive');
            $this->db->set($this->db->protect("dtUpdatedAt"), $params_arr["dtUpdatedAt"], FALSE);
           
            $res = $this->db->update("review");

            $affected_rows = $this->db->affected_rows();
            if (!$res || $affected_rows == -1)
            {
                throw new Exception("Failure in updation.");
            }
            $result_param = "affected_rows";
            $result_arr[0][$result_param] = $affected_rows;
            $success = 1;

        }
        catch(Exception $e)
        {
            $success = 0;
            $message = $e->getMessage();
        }
        $this->db->flush_cache();
        $this->db->_reset_all();
        //echo $this->db->last_query();
        $return_arr["success"] = $success;
        $return_arr["message"] = $message;
        $return_arr["data"] = $result_arr;
        return $return_arr;
    }

    /**
     * delete review method is used to execute database queries for Edit Profile API.
     * @created priyanka chillakuru | 18.09.2019
     * @modified priyanka chillakuru | 25.09.2019
     * @param array $params_arr params_arr array to process query block.
     * @param array $where_arr where_arr are used to process where condition(s).
     * @return array $return_arr returns response of query block.
     */
    public function update_users_after_review_insert($params_arr = array(),$where_arr = array())
    {
        try
        {
          $result_arr = array();
            $this->db->start_cache();
          if(true == $params_arr["is_claimed"])
              {
                $this->db->from("users");
                $this->db->select("dTotalStarCount AS total_star_count");
                $this->db->select("iTotalReviewCount AS total_review_count");
                if (isset($params_arr['registered_user_id'] ) && $params_arr['registered_user_id']  != "")
                {
                   $this->db->where("iUserId", $params_arr['registered_user_id']);
                }  
               
                $result_obj = $this->db->get();
                $result_arr = is_object($result_obj) ? $result_obj->result_array() : array();
                $intStarCount = $result_arr['0']['total_star_count'];
                $intReviewCount = $result_arr['0']['total_review_count'];
                
                if (false == empty($params_arr["review_stars"]))
                {
                  $intStarCount= $intStarCount + $params_arr["review_stars"];
                  $this->db->set("dTotalStarCount", $intStarCount);
                }
                if (false == empty($where_arr["review_id"]))
                {
                  $intReviewCount= $intReviewCount + 1;
                  $this->db->set("iTotalReviewCount", $intReviewCount);
                }
                
                if(false == empty($intStarCount) && false == empty($intReviewCount))
                {
                  $dAvgRating = $intStarCount/$intReviewCount;
                  if(false == empty($dAvgRating))
                  {
                    $this->db->set("dAverageRating", $dAvgRating);
                  }

                }
                 $this->db->set($this->db->protect("dtUpdatedAt"), $params_arr["_dtupdatedat"], FALSE);
                //$this->db->set("dAddededAt", $params_arr["_dtaddedat"]);
                $res=$this->db->update("users");
                $affected_rows = $this->db->affected_rows();
                if (!$res || $affected_rows == -1)
                {
                    throw new Exception("Failure in updation.");
                }
                $result_param = "affected_rows";
                $result_arr[0][$result_param] = $affected_rows;
                $result_arr[0]['review_id'] = $where_arr["review_id"];
                $success = 1;
              }
            }
            catch(Exception $e)
        {
            $success = 0;
            $message = $e->getMessage();
        }
        $this->db->flush_cache();
        $this->db->_reset_all();
        //echo $this->db->last_query();
        $return_arr["success"] = $success;
        $return_arr["message"] = $message;
        $return_arr["data"] = $result_arr;
        return $return_arr;
  }
  /**
     * delete review method is used to execute database queries for Edit Profile API.
     * @created priyanka chillakuru | 18.09.2019
     * @modified priyanka chillakuru | 25.09.2019
     * @param array $params_arr params_arr array to process query block.
     * @param array $where_arr where_arr are used to process where condition(s).
     * @return array $return_arr returns response of query block.
     */
    public function update_users_after_review_update($params_arr = array(),$where_arr = array())
    {
        try
        {
          $result_arr = array();
            $this->db->start_cache();
          if(true == $params_arr["is_claimed"])
              {
                $params_arr["claimed_email"] = explode(",",$params_arr["claimed_email"]);
                if (count($params_arr["claimed_email"]) > 0) {
                  $conditions = "'" . implode("', '", $params_arr["claimed_email"]) . "'";
                }
                $strSql="SELECT SUM(iStars) AS total_star_count,
                          Count(iReviewId) AS total_review_count
                        FROM review 
                        WHERE eStatus='active' AND bIsClaimed=1 AND vClaimedEmail IN ($conditions) 
                        GROUP BY vClaimedEmail";

                $result_obj =  $this->db->query($strSql);
                $result_arr = is_object($result_obj) ? $result_obj->result_array() : array();
                $intStarCount = $result_arr['0']['total_star_count'];

                $intReviewCount = $result_arr['0']['total_review_count'];

                if (isset($where_arr['registered_user_id'] ) && $where_arr['registered_user_id']  != "")
                {
                   $this->db->where("iUserId", $where_arr['registered_user_id']);
                }  
                if (false == empty($intStarCount))
                {
                  $this->db->set("dTotalStarCount", $intStarCount);
                }
                if (false == empty($intReviewCount))
                {
                  $this->db->set("iTotalReviewCount", $intReviewCount);
                }
                
                
                if(false == empty($intStarCount) && false == empty($intReviewCount))
                {
                  $dAvgRating = $intStarCount/$intReviewCount;
                  if(false == empty($dAvgRating))
                  {
                    $this->db->set("dAverageRating", $dAvgRating);
                  }

                }
                 $this->db->set($this->db->protect("dtUpdatedAt"), $params_arr["_dtupdatedat"], FALSE);
                //$this->db->set("dAddededAt", $params_arr["_dtaddedat"]);
                $res=$this->db->update("users");
                //echo $this->db->last_query();exit;
               
                $affected_rows = $this->db->affected_rows();
                if (!$res || $affected_rows == -1)
                {
                    throw new Exception("Failure in updation.");
                }
                $result_param = "affected_rows";
                $result_arr[0][$result_param] = $affected_rows;
                $result_arr[0]['review_id'] = $where_arr["review_id"];
                $success = 1;
              }
            }
            catch(Exception $e)
        {
            $success = 0;
            $message = $e->getMessage();
        }
        $this->db->flush_cache();
        $this->db->_reset_all();
        //echo $this->db->last_query();
        $return_arr["success"] = $success;
        $return_arr["message"] = $message;
        $return_arr["data"] = $result_arr;
        return $return_arr;
  }
   /**
     * delete review method is used to execute database queries for Edit Profile API.
     * @created priyanka chillakuru | 18.09.2019
     * @modified priyanka chillakuru | 25.09.2019
     * @param array $params_arr params_arr array to process query block.
     * @param array $where_arr where_arr are used to process where condition(s).
     * @return array $return_arr returns response of query block.
     */
    public function update_register_user_review($params_arr = array(),$where_arr = array())
    {
        try
        {
          $this->db->start_cache();
          if (isset($where_arr["review_id"]) && $where_arr["review_id"] != "")
          {
              $this->db->where("iReviewId =", $where_arr["review_id"]);
          }
          if (isset($params_arr["is_claimed"]))
          {
              $this->db->set("bIsClaimed", $params_arr["is_claimed"]);
          }
          if (isset($params_arr["claimed_email"]))
          {
              $this->db->set("vClaimedEmail", $params_arr["claimed_email"]);
          }
           $this->db->set($this->db->protect("dtUpdatedAt"), $params_arr["_dtupdatedat"], FALSE);
          $res=$this->db->update("review");
          $affected_rows = $this->db->affected_rows();
          if (!$res || $affected_rows == -1)
          {
              throw new Exception("Failure in updation.");
          }
          $result_param = "affected_rows";
          $result_arr[0][$result_param] = $affected_rows;
          
          $success = 1;
          
        }
            catch(Exception $e)
        {
            $success = 0;
            $message = $e->getMessage();
        }
        $this->db->flush_cache();
        $this->db->_reset_all();
        //echo $this->db->last_query();
        $return_arr["success"] = $success;
        $return_arr["message"] = $message;
        $return_arr["data"] = $result_arr;

        return $return_arr;
  }
}
