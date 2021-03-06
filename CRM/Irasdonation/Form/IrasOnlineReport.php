<?php

use Civi\Api4\IrasDonation;
use CRM_Irasdonation_ExtensionUtil as E;

/**
 * Form controller class
 *
 * @see https://docs.civicrm.org/dev/en/latest/framework/quickform/
 */
class CRM_Irasdonation_Form_IrasOnlineReport extends CRM_Core_Form
{
  public function buildQuickForm()
  {
    //start report from 
    $this->add('datepicker', 'start_date', ts('Start date'), [], FALSE, ['time' => FALSE]);

    //end report to
    $this->add('datepicker', 'end_date', ts('End date'), [], FALSE, ['time' => FALSE]);

    // //include previously generateed reports 
    $this->add('advcheckbox', 'include_previous', ts('Include receipts previously generated'));

    $this->addButtons(array(
      array(
        'type' => 'submit',
        'name' => E::ts('Generate and send report'),
        'isDefault' => TRUE,
      ),
    ));

    // export form elements
    //$this->assign('elementNames', $this->getRenderableElementNames());
    parent::buildQuickForm();
  }

  public function postProcess()
  {
    $values = $this->exportValues();
    $startDate = $values["start_date"];
    $endDate = $values["end_date"];
    $includePrevious = $values["include_previous"];
    $sql =  "SELECT * FROM civicrm_o8_iras_config ic";
    $result = CRM_Core_DAO::executeQuery($sql, CRM_Core_DAO::$_nullArray);

    $params = array();
    while ($result->fetch()) {
      $params[$result->param_name] = $result->param_value;
    }

    $repYear = date("Y");

    if ($startDate != null)
      $repYear = date("Y", strtotime($startDate));

    if (empty($params['organisation_id'])) {
      CRM_Core_Session::setStatus('Please configure extension before using', ts('Extension configuration'), 'warning', array('expires' => 5000));
      return;
    }

    if (date("Y", strtotime($endDate)) != date("Y", strtotime($startDate))) {
      CRM_Core_Session::setStatus('Selected Date must be in the same year', ts('Date range incorrect'), 'warning', array('expires' => 5000));
      return;
    }

    if ($endDate == null || $startDate == null) {
      CRM_Core_Session::setStatus('Please select date range', ts('Date range incorrect'), 'warning', array('expires' => 5000));
      return;
    }

    $wword = '1=1';

    if ($startDate != null && $endDate != null) {
      if ($includePrevious == 0) {
        $wword .= " AND trxn.id NOT IN (SELECT ci.financial_trxn_id FROM civicrm_o8_iras_donation ci WHERE ci.created_date IS NOT NULL) AND trxn.trxn_date >= '$startDate' AND trxn.trxn_date <= '$endDate'";
      } else {
        $wword .= " AND trxn.trxn_date >= '$startDate' AND trxn.trxn_date <= '$endDate'";
      }
    } else {
      $wword .= " AND trxn.id NOT IN (SELECT ci.financial_trxn_id FROM civicrm_o8_iras_donation ci WHERE ci.created_date IS NOT NULL)";
    }

    //get reporting url
    $url = $params['report_url'];

    $client_id = $params['client_id'];
    $client_secret = $params['client_secret'];

    //prepare header      
    $header = [
      "Accept: application/json",
      "charset: UTF-8",
      "Content-Type: application/json",
      "X-IBM-Client-Id: $client_id",
      "X-IBM-Client-Secret: $client_secret",
      "access_token: ",
    ];

    //generate header of report   
    $sql = "SELECT 
    trxn.id, 
    cont.sort_name, 
    cont.external_identifier,
    addr.supplemental_address_1,
    addr.supplemental_address_2,
    addr.postal_code,
    trxn.total_amount,
    contrib.trxn_id,
    trxn.trxn_date,
    contrib.receive_date
    FROM civicrm_financial_trxn trxn 
    INNER JOIN civicrm_contribution contrib ON contrib.trxn_id = trxn.trxn_id  
    INNER JOIN civicrm_contact cont ON cont.id = contrib.contact_id 
    INNER JOIN civicrm_financial_type fintype ON fintype.id = contrib.financial_type_id   
    LEFT JOIN civicrm_address addr ON addr.id = cont.addressee_id
    WHERE $wword
    AND trxn.status_id = 1 AND fintype.is_deductible = 1
    AND cont.external_identifier IS NOT NULL 
    LIMIT 5000";

    $result = CRM_Core_DAO::executeQuery($sql, CRM_Core_DAO::$_nullArray);
    $insert = '';
    $total = 0;
    $incer = 0;
    $genDate = date('Y-m-d H:i:s');

    $dataBody = [];
    $saveReport = array();
    //generate body of th report
    $details = array();
    while ($result->fetch()) {
      $config = new CRM_Irasdonation_Form_IrasConfiguration();
      $idType = $config->parsUENNumber($result->external_identifier);
      if ($idType > 0) {
        $dataBody = array(
          'recordID' => $incer + 1,
          'idType' => $idType,
          'idNumber' => $result->external_identifier,
          'individualIndicator' => '',
          'name' => $result->sort_name,
          'addressLine1' => $result->supplemental_address_1,
          'addressLine2' => $result->supplemental_address_2,
          'postalCode' => '',
          'donationAmount' => round($result->total_amount),
          'dateOfDonation' => date("Ymd", strtotime($result->receive_date)),
          'receiptNum' => substr('asdf', 0, 10),
          'typeOfDonation' => 'O',
          'namingDonation' => 'Z'
        );

        array_push($saveReport, $result->id);

        array_push($details, $dataBody);
        $total += $result->total_amount;
        $incer++;
      }
    }

    //prepare body
    $body = array(
      'orgAndSubmissionInfo' => [
        'validateOnly' => 'true',
        'basisYear' => $repYear,
        'organisationIDType' => $params['organization_type'],
        'organisationIDNo' => $params['organisation_id'],
        'organisationName' => $params['organisation_name'],
        'batchIndicator' => 'O',
        'authorisedPersonIDNo' => $params['authorised_person_id'],
        'authorisedPersonName' => $params['authorised_person_name'],
        'authorisedPersonDesignation' => $params['authorised_person_designation'],
        'telephone' => $params['authorised_person_phone'],
        'authorisedPersonEmail' => $params['authorised_person_email'],
        'numOfRecords' => $incer,
        'totalDonationAmount' => $total
      ],
      "donationDonorDtl" => $details
    );

    $response = null;
    if (count($details) > 0) {
      $response = $this->curl_post($url, $header, $body);
      $sentMessage = '';
      $sentMessage .= $response->info->message;
      if($response->info->fieldInfoList != null)
      $sentMessage .= ' > ' . json_encode($response->info->fieldInfoList);

      if ($response->returnCode == 10) {
        CRM_Core_Session::setStatus('Data sucessfully sent to IRAS', ts('Report sending status'), 'success', array('expires' => 5000));
      } else {        
        CRM_Core_Session::setStatus($sentMessage, ts('Report sending status'), 'error', array('expires' => 5000));
      }
    } else CRM_Core_Session::setStatus('No any data to generate report', ts('All reports are generated'), 'success', array('expires' => 5000));

    if ($response != null) {
      $log_id = 0;

      $insert = "INSERT INTO civicrm_o8_iras_response_log(response_body, response_code, created_date) VALUES ('" . json_encode($response) . "', $response->returnCode, '$genDate');";
      CRM_Core_DAO::executeQuery($insert, CRM_Core_DAO::$_nullArray);
      $result = CRM_Core_DAO::executeQuery('SELECT LAST_INSERT_ID() id;', CRM_Core_DAO::$_nullArray);

      while ($result->fetch()) {
        $log_id = $result->id;
      }

      foreach ($saveReport as $value) {
        $insert = "INSERT INTO civicrm_o8_iras_donation(financial_trxn_id, is_api, log_id, created_date) VALUES ($value, 1, $log_id, '$genDate');";
        CRM_Core_DAO::executeQuery($insert, CRM_Core_DAO::$_nullArray);
      }

      parent::postProcess();
    }
  }

  /**
   * Get the fields/elements defined in this form.
   *
   * @return array (string)
   */
  public function getRenderableElementNames()
  {
    // The _elements list includes some items which should not be
    // auto-rendered in the loop -- such as "qfKey" and "buttons".  These
    // items don't have labels.  We'll identify renderable by filtering on
    // the 'label'.
    $elementNames = array();
    foreach ($this->_elements as $element) {
      /** @var HTML_QuickForm_Element $element */
      $label = $element->getLabel();
      if (!empty($label)) {
        $elementNames[] = $element->getName();
      }
    }
    return $elementNames;
  }

  function curl_post($url, $header, $body)
  {
    $c_type = '';
    if (!is_null($header)) {
      foreach ($header as $item) {
        $row = explode(':', $item);
        if (strcmp(strtolower(trim($row[0])), 'content-type') == 0) {
          $c_type = trim($row[1]);
        }
      }
      switch ($c_type) {
        case 'application/x-www-form-urlencoded':
          $content_body = http_build_query($body);
          break;
        case 'application/json':
          $content_body = json_encode($body);
          break;
      }
    } else {
      $header = array();
    }

    $curlOptions = array(
      CURLOPT_URL => $url,
      CURLOPT_RETURNTRANSFER => TRUE,
      CURLOPT_FOLLOWLOCATION => TRUE,
      CURLOPT_VERBOSE => TRUE,
      CURLOPT_STDERR => $verbose = fopen('php://temp', 'rw+'),
      CURLOPT_FILETIME => TRUE,
      CURLOPT_POST => TRUE,
      CURLOPT_HTTPHEADER => $header,
      CURLOPT_POSTFIELDS => $content_body
    );
    $curl = curl_init();
    curl_setopt_array($curl, $curlOptions);
    $response = curl_exec($curl);
    curl_close($curl);

    return json_decode($response);
  }
}
