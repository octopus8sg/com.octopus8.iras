<?php

use CRM_Irasdonation_ExtensionUtil as E;
use CRM_Irasdonation_Utils as U;

/**
 * Form controller class
 *
 * @see https://docs.civicrm.org/dev/en/latest/framework/quickform/
 */
class CRM_Irasdonation_Form_IrasOnlineReport extends CRM_Core_Form
{

    public function buildQuickForm()
    {
        $url = CRM_Utils_System::url('civicrm/irasdonation/iras_online_report');
        $session = CRM_Core_Session::singleton();
        $session->pushUserContext($url);
        $validate_only = U::getValidateOnly();
        if (!$validate_only) {
            $iras_access_token = $session->get(U::ACCESSTOKEN);
            U::writeLog($iras_access_token, "iras_access_token");
            $iras_login_time = $session->get(U::LOGINTIME);
            U::writeLog($iras_login_time, "iras_login_time");
            $iras_login_time_diff = time() - $iras_login_time;
            U::writeLog($iras_login_time_diff, "iras_login_time_diff");
            $irasLoginURL = U::getIrasLoginURL();
            $callbackURL = U::getCallbackURL();
            $iras_logged = true;
//        CRM_Core_Error::debug_var('decoded', $decoded);
            if (!$iras_access_token) {
                $iras_logged = false;
            }
            if ($iras_login_time_diff > 300) {
                $iras_logged = false;
            }
            if (!$iras_logged) {
                $state = uniqid();
                $session->set(U::STATE, $state);
                $irasLoginFullURL = "$irasLoginURL?scope=DonationSub&callback_url=$callbackURL&tax_agent=false&state=$state";
                U::writeLog($irasLoginFullURL, 'irasLoginFullURL');
                $loginresponse = U::getLoginResponse($irasLoginFullURL);

                try {
                    $irasLoginRealURL = $loginresponse['data']['url'];
                } catch (Exception $e) {
                    throw new CRM_Core_Exception('Error: Not a JSON in Response error: ' . $e->getMessage());
                }
                U::writeLog($irasLoginRealURL, 'irasLoginRealURL');
                CRM_Core_Session::setStatus('You have no CorpPASS access token', ts('IRAS LOG IN'), 'warning', array('expires' => 5000));
                CRM_Utils_System::redirect($irasLoginRealURL);
                CRM_Utils_System::civiExit();
                exit;
            }
        }
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

    public
    function postProcess()
    {
        $settings = U::getSettings();
        $organisation_id = CRM_Utils_Array::value(U::ORGANISATION_ID['slug'], $settings);
        if (!$organisation_id) {
            CRM_Core_Session::setStatus('Please configure extension before using', ts('Extension configuration'), 'warning', array('expires' => 5000));
            return;
        }

        $values = $this->exportValues();
        $firstDayOfYear = date('Y-01-01');
        $lastDayOfYear = date('Y-12-31');
        $startDate = CRM_Utils_Array::value('start_date', $values, null);
        $endDate = CRM_Utils_Array::value('end_date', $values, null);
        $includePrevious = $values["include_previous"];
        $reportYear = date("Y");
        if (!$startDate) {
            $startDate = $firstDayOfYear;
        };
        if (!$endDate) {
            $endDate = $lastDayOfYear;
        };
        if ($startDate != null) {
            $reportYear = date("Y", strtotime($startDate));
        };
//        U::writeLog($startDate, "startDate");
//        U::writeLog($endDate, "endDate");
//        U::writeLog(strval($includePrevious), "includePrevious");
        if (date("Y", strtotime($endDate)) != date("Y", strtotime($startDate))) {
            CRM_Core_Session::setStatus('Selected Date must be in the same year', ts('Date range incorrect'), 'warning', array('expires' => 5000));
            return;
        }

        //get donations

        list($totalRows, $total, $counter, $generatedDate, $donations, $online_donations, $offline_donations) = U::prepareDonations($startDate, $endDate, $includePrevious);
        if ($totalRows > 5000) {
            CRM_Core_Session::setStatus('You have more than 5000 records, please select smaller period of time', ts('Date range incorrect'), 'warning', array('expires' => 5000));
        }
        //prepare header
        $session = CRM_Core_Session::singleton();
        $header = U::prepareHeader();
        $validate_only = U::getValidateOnly();
        if (!$validate_only) {
            $iras_access_token = $session->get(U::ACCESSTOKEN);
            $header = U::prepareHeader($iras_access_token);
            U::writeLog($iras_access_token, "iras_access_token");
        }

        //prepare online_report_body
        $report_url = U::getIrasReportURL();
        list($online_report_body,
            $validate_only,
            $basis_year,
            $organisation_id_type,
            $organisation_id_no,
            $organisation_name,
            $batch_indicator,
            $authorised_person_name,
            $authorised_person_designation,
            $telephone,
            $authorised_person_email,
            $num_of_records,
            $total_donation_amount) = U::prepareOnlineReportBody($reportYear, $counter, $total, $online_donations);
        U::writeLog(json_encode($online_donations), "prepared_details");
//        U::writeLog($donations, "prepared_donations");
        U::writeLog(json_encode($online_report_body), "prepared_body");
//        U::writeLog($header, "prepared_header");
//        U::writeLog($report_url, "report_url");
        $url = CRM_Utils_System::url('civicrm/irasdonation/iras_online_report');

        $session->pushUserContext($url);

        $response = null;
        if ($counter > 0) {

            $decoded = U::guzzlePost($report_url, $header, $online_report_body);

            $sentMessage = ' > ' . json_encode($decoded);
            $response_code = $decoded['returnCode'];
            if ($response_code == 10) {
                CRM_Core_Session::setStatus('Data sucessfully sent to IRAS', ts('Report sending status'), 'success', array('expires' => 5000));
            } else {
                CRM_Core_Session::setStatus($sentMessage, ts('Report sending status'), 'error', array('expires' => 5000));
            }
        } else CRM_Core_Session::setStatus('No data to generate report', ts('All reports are generated'), 'success', array('expires' => 5000));

        parent::postProcess();

        if ($decoded == null) {
            return;
        }
            $response_body = json_encode($decoded);
            $is_api = 1;
            $validate_only = intval($validate_only);
        U::saveDonationLogs($is_api,
            $validate_only,
            $basis_year,
            $organisation_id_type,
            $organisation_id_no,
            $organisation_name,
            $batch_indicator,
            $authorised_person_name,
            $authorised_person_designation,
            $telephone,
            $authorised_person_email,
            $num_of_records,
            $total_donation_amount,
            $response_body,
            $response_code,
            $generatedDate,
            $donations);
    }

    /**
     * Get the fields/elements defined in this form.
     *
     * @return array (string)
     */
    public
    function getRenderableElementNames()
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
