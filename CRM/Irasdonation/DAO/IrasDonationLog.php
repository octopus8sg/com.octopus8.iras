<?php

/**
 * @package CRM
 * @copyright CiviCRM LLC https://civicrm.org/licensing
 *
 * Generated from com.octopus8.iras/xml/schema/CRM/Irasdonation/03IrasDonationLog.xml
 * DO NOT EDIT.  Generated by CRM_Core_CodeGen
 * (GenCodeChecksum:eca91437d96e71ac27f2b842058529e6)
 */
use CRM_Irasdonation_ExtensionUtil as E;

/**
 * Database access object for the IrasDonationLog entity.
 */
class CRM_Irasdonation_DAO_IrasDonationLog extends CRM_Core_DAO {
  const EXT = E::LONG_NAME;
  const TABLE_ADDED = '';

  /**
   * Static instance to hold the table name.
   *
   * @var string
   */
  public static $_tableName = 'civicrm_o8_iras_donation_log';

  /**
   * Should CiviCRM log any modifications to this table in the civicrm_log table.
   *
   * @var bool
   */
  public static $_log = TRUE;

  /**
   * Unique IrasDonationLog ID
   *
   * @var int|string|null
   *   (SQL type: int unsigned)
   *   Note that values will be retrieved from the database as a string.
   */
  public $id;

  /**
   * recordID
   *
   * @var int|string
   *   (SQL type: int unsigned)
   *   Note that values will be retrieved from the database as a string.
   */
  public $record_id;

  /**
   * idType
   *
   * @var string
   *   (SQL type: varchar(4))
   *   Note that values will be retrieved from the database as a string.
   */
  public $id_type;

  /**
   * idNumber
   *
   * @var string
   *   (SQL type: varchar(12))
   *   Note that values will be retrieved from the database as a string.
   */
  public $id_number;

  /**
   * individualIndicator
   *
   * @var string
   *   (SQL type: varchar(3))
   *   Note that values will be retrieved from the database as a string.
   */
  public $individual_indicator;

  /**
   * name
   *
   * @var string
   *   (SQL type: varchar(80))
   *   Note that values will be retrieved from the database as a string.
   */
  public $contact_name;

  /**
   * addressLine1
   *
   * @var string
   *   (SQL type: varchar(160))
   *   Note that values will be retrieved from the database as a string.
   */
  public $address_line1;

  /**
   * addressLine2
   *
   * @var string
   *   (SQL type: varchar(160))
   *   Note that values will be retrieved from the database as a string.
   */
  public $address_line2;

  /**
   * postalCode
   *
   * @var string
   *   (SQL type: varchar(60))
   *   Note that values will be retrieved from the database as a string.
   */
  public $postal_code;

  /**
   * totalDonationAmount
   *
   * @var int|string
   *   (SQL type: int unsigned)
   *   Note that values will be retrieved from the database as a string.
   */
  public $donation_amount;

  /**
   * dateOfDonation
   *
   * @var string
   *   (SQL type: varchar(8))
   *   Note that values will be retrieved from the database as a string.
   */
  public $date_of_donation;

  /**
   * receiptNum
   *
   * @var string
   *   (SQL type: varchar(10))
   *   Note that values will be retrieved from the database as a string.
   */
  public $receipt_num;

  /**
   * typeOfDonation
   *
   * @var string
   *   (SQL type: varchar(1))
   *   Note that values will be retrieved from the database as a string.
   */
  public $type_of_donation;

  /**
   * namingDonation
   *
   * @var string
   *   (SQL type: varchar(1))
   *   Note that values will be retrieved from the database as a string.
   */
  public $naming_donation;

  /**
   * FK to IRAS Responses
   *
   * @var int|string|null
   *   (SQL type: int unsigned)
   *   Note that values will be retrieved from the database as a string.
   */
  public $iras_response_id;

  /**
   * FK to IRAS Donations
   *
   * @var int|string|null
   *   (SQL type: int unsigned)
   *   Note that values will be retrieved from the database as a string.
   */
  public $iras_donation_id;

  /**
   * Class constructor.
   */
  public function __construct() {
    $this->__table = 'civicrm_o8_iras_donation_log';
    parent::__construct();
  }

  /**
   * Returns localized title of this entity.
   *
   * @param bool $plural
   *   Whether to return the plural version of the title.
   */
  public static function getEntityTitle($plural = FALSE) {
    return $plural ? E::ts('Iras Donation Logs') : E::ts('Iras Donation Log');
  }

  /**
   * Returns foreign keys and entity references.
   *
   * @return array
   *   [CRM_Core_Reference_Interface]
   */
  public static function getReferenceColumns() {
    if (!isset(Civi::$statics[__CLASS__]['links'])) {
      Civi::$statics[__CLASS__]['links'] = static::createReferenceColumns(__CLASS__);
      Civi::$statics[__CLASS__]['links'][] = new CRM_Core_Reference_Basic(self::getTableName(), 'iras_response_id', 'civicrm_o8_iras_response_log', 'id');
      Civi::$statics[__CLASS__]['links'][] = new CRM_Core_Reference_Basic(self::getTableName(), 'iras_donation_id', 'civicrm_o8_iras_donation', 'id');
      CRM_Core_DAO_AllCoreTables::invoke(__CLASS__, 'links_callback', Civi::$statics[__CLASS__]['links']);
    }
    return Civi::$statics[__CLASS__]['links'];
  }

  /**
   * Returns all the column names of this table
   *
   * @return array
   */
  public static function &fields() {
    if (!isset(Civi::$statics[__CLASS__]['fields'])) {
      Civi::$statics[__CLASS__]['fields'] = [
        'id' => [
          'name' => 'id',
          'type' => CRM_Utils_Type::T_INT,
          'description' => E::ts('Unique IrasDonationLog ID'),
          'required' => TRUE,
          'where' => 'civicrm_o8_iras_donation_log.id',
          'table_name' => 'civicrm_o8_iras_donation_log',
          'entity' => 'IrasDonationLog',
          'bao' => 'CRM_Irasdonation_DAO_IrasDonationLog',
          'localizable' => 0,
          'html' => [
            'type' => 'Number',
          ],
          'readonly' => TRUE,
          'add' => NULL,
        ],
        'record_id' => [
          'name' => 'record_id',
          'type' => CRM_Utils_Type::T_INT,
          'description' => E::ts('recordID'),
          'required' => FALSE,
          'where' => 'civicrm_o8_iras_donation_log.record_id',
          'default' => '1',
          'table_name' => 'civicrm_o8_iras_donation_log',
          'entity' => 'IrasDonationLog',
          'bao' => 'CRM_Irasdonation_DAO_IrasDonationLog',
          'localizable' => 0,
          'add' => NULL,
        ],
        'id_type' => [
          'name' => 'id_type',
          'type' => CRM_Utils_Type::T_STRING,
          'description' => E::ts('idType'),
          'required' => FALSE,
          'maxlength' => 4,
          'size' => CRM_Utils_Type::FOUR,
          'where' => 'civicrm_o8_iras_donation_log.id_type',
          'default' => '5',
          'table_name' => 'civicrm_o8_iras_donation_log',
          'entity' => 'IrasDonationLog',
          'bao' => 'CRM_Irasdonation_DAO_IrasDonationLog',
          'localizable' => 0,
          'add' => NULL,
        ],
        'id_number' => [
          'name' => 'id_number',
          'type' => CRM_Utils_Type::T_STRING,
          'description' => E::ts('idNumber'),
          'required' => FALSE,
          'maxlength' => 12,
          'size' => CRM_Utils_Type::TWELVE,
          'where' => 'civicrm_o8_iras_donation_log.id_number',
          'default' => '',
          'table_name' => 'civicrm_o8_iras_donation_log',
          'entity' => 'IrasDonationLog',
          'bao' => 'CRM_Irasdonation_DAO_IrasDonationLog',
          'localizable' => 0,
          'add' => NULL,
        ],
        'individual_indicator' => [
          'name' => 'individual_indicator',
          'type' => CRM_Utils_Type::T_STRING,
          'title' => E::ts('Individual Indicator'),
          'description' => E::ts('individualIndicator'),
          'required' => FALSE,
          'maxlength' => 3,
          'size' => CRM_Utils_Type::FOUR,
          'where' => 'civicrm_o8_iras_donation_log.individual_indicator',
          'default' => '',
          'table_name' => 'civicrm_o8_iras_donation_log',
          'entity' => 'IrasDonationLog',
          'bao' => 'CRM_Irasdonation_DAO_IrasDonationLog',
          'localizable' => 0,
          'add' => NULL,
        ],
        'contact_name' => [
          'name' => 'contact_name',
          'type' => CRM_Utils_Type::T_STRING,
          'title' => E::ts('Contact Name'),
          'description' => E::ts('name'),
          'required' => FALSE,
          'maxlength' => 80,
          'size' => CRM_Utils_Type::HUGE,
          'where' => 'civicrm_o8_iras_donation_log.contact_name',
          'default' => '',
          'table_name' => 'civicrm_o8_iras_donation_log',
          'entity' => 'IrasDonationLog',
          'bao' => 'CRM_Irasdonation_DAO_IrasDonationLog',
          'localizable' => 0,
          'add' => NULL,
        ],
        'address_line1' => [
          'name' => 'address_line1',
          'type' => CRM_Utils_Type::T_STRING,
          'title' => E::ts('Address Line1'),
          'description' => E::ts('addressLine1'),
          'required' => FALSE,
          'maxlength' => 160,
          'size' => CRM_Utils_Type::HUGE,
          'where' => 'civicrm_o8_iras_donation_log.address_line1',
          'default' => NULL,
          'table_name' => 'civicrm_o8_iras_donation_log',
          'entity' => 'IrasDonationLog',
          'bao' => 'CRM_Irasdonation_DAO_IrasDonationLog',
          'localizable' => 0,
          'add' => NULL,
        ],
        'address_line2' => [
          'name' => 'address_line2',
          'type' => CRM_Utils_Type::T_STRING,
          'title' => E::ts('Address Line2'),
          'description' => E::ts('addressLine2'),
          'required' => FALSE,
          'maxlength' => 160,
          'size' => CRM_Utils_Type::HUGE,
          'where' => 'civicrm_o8_iras_donation_log.address_line2',
          'default' => NULL,
          'table_name' => 'civicrm_o8_iras_donation_log',
          'entity' => 'IrasDonationLog',
          'bao' => 'CRM_Irasdonation_DAO_IrasDonationLog',
          'localizable' => 0,
          'add' => NULL,
        ],
        'postal_code' => [
          'name' => 'postal_code',
          'type' => CRM_Utils_Type::T_STRING,
          'title' => E::ts('Postal Code'),
          'description' => E::ts('postalCode'),
          'required' => FALSE,
          'maxlength' => 60,
          'size' => CRM_Utils_Type::BIG,
          'where' => 'civicrm_o8_iras_donation_log.postal_code',
          'default' => NULL,
          'table_name' => 'civicrm_o8_iras_donation_log',
          'entity' => 'IrasDonationLog',
          'bao' => 'CRM_Irasdonation_DAO_IrasDonationLog',
          'localizable' => 0,
          'add' => NULL,
        ],
        'donation_amount' => [
          'name' => 'donation_amount',
          'type' => CRM_Utils_Type::T_INT,
          'title' => E::ts('Donation Amount'),
          'description' => E::ts('totalDonationAmount'),
          'required' => FALSE,
          'where' => 'civicrm_o8_iras_donation_log.donation_amount',
          'default' => '0',
          'table_name' => 'civicrm_o8_iras_donation_log',
          'entity' => 'IrasDonationLog',
          'bao' => 'CRM_Irasdonation_DAO_IrasDonationLog',
          'localizable' => 0,
          'add' => NULL,
        ],
        'date_of_donation' => [
          'name' => 'date_of_donation',
          'type' => CRM_Utils_Type::T_STRING,
          'title' => E::ts('Date Of Donation'),
          'description' => E::ts('dateOfDonation'),
          'required' => FALSE,
          'maxlength' => 8,
          'size' => CRM_Utils_Type::EIGHT,
          'where' => 'civicrm_o8_iras_donation_log.date_of_donation',
          'default' => NULL,
          'table_name' => 'civicrm_o8_iras_donation_log',
          'entity' => 'IrasDonationLog',
          'bao' => 'CRM_Irasdonation_DAO_IrasDonationLog',
          'localizable' => 0,
          'add' => NULL,
        ],
        'receipt_num' => [
          'name' => 'receipt_num',
          'type' => CRM_Utils_Type::T_STRING,
          'title' => E::ts('Receipt Num'),
          'description' => E::ts('receiptNum'),
          'required' => FALSE,
          'maxlength' => 10,
          'size' => CRM_Utils_Type::TWELVE,
          'where' => 'civicrm_o8_iras_donation_log.receipt_num',
          'default' => NULL,
          'table_name' => 'civicrm_o8_iras_donation_log',
          'entity' => 'IrasDonationLog',
          'bao' => 'CRM_Irasdonation_DAO_IrasDonationLog',
          'localizable' => 0,
          'add' => NULL,
        ],
        'type_of_donation' => [
          'name' => 'type_of_donation',
          'type' => CRM_Utils_Type::T_STRING,
          'title' => E::ts('Type Of Donation'),
          'description' => E::ts('typeOfDonation'),
          'required' => FALSE,
          'maxlength' => 1,
          'size' => CRM_Utils_Type::TWO,
          'where' => 'civicrm_o8_iras_donation_log.type_of_donation',
          'default' => 'O',
          'table_name' => 'civicrm_o8_iras_donation_log',
          'entity' => 'IrasDonationLog',
          'bao' => 'CRM_Irasdonation_DAO_IrasDonationLog',
          'localizable' => 0,
          'add' => NULL,
        ],
        'naming_donation' => [
          'name' => 'naming_donation',
          'type' => CRM_Utils_Type::T_STRING,
          'title' => E::ts('Naming Donation'),
          'description' => E::ts('namingDonation'),
          'required' => FALSE,
          'maxlength' => 1,
          'size' => CRM_Utils_Type::TWO,
          'where' => 'civicrm_o8_iras_donation_log.naming_donation',
          'default' => 'Z',
          'table_name' => 'civicrm_o8_iras_donation_log',
          'entity' => 'IrasDonationLog',
          'bao' => 'CRM_Irasdonation_DAO_IrasDonationLog',
          'localizable' => 0,
          'add' => NULL,
        ],
        'iras_response_id' => [
          'name' => 'iras_response_id',
          'type' => CRM_Utils_Type::T_INT,
          'description' => E::ts('FK to IRAS Responses'),
          'where' => 'civicrm_o8_iras_donation_log.iras_response_id',
          'table_name' => 'civicrm_o8_iras_donation_log',
          'entity' => 'IrasDonationLog',
          'bao' => 'CRM_Irasdonation_DAO_IrasDonationLog',
          'localizable' => 0,
          'FKClassName' => 'CRM_Irasdonation_DAO_IrasResponseLog',
          'add' => NULL,
        ],
        'iras_donation_id' => [
          'name' => 'iras_donation_id',
          'type' => CRM_Utils_Type::T_INT,
          'description' => E::ts('FK to IRAS Donations'),
          'where' => 'civicrm_o8_iras_donation_log.iras_donation_id',
          'table_name' => 'civicrm_o8_iras_donation_log',
          'entity' => 'IrasDonationLog',
          'bao' => 'CRM_Irasdonation_DAO_IrasDonationLog',
          'localizable' => 0,
          'FKClassName' => 'CRM_Irasdonation_DAO_IrasDonation',
          'add' => NULL,
        ],
      ];
      CRM_Core_DAO_AllCoreTables::invoke(__CLASS__, 'fields_callback', Civi::$statics[__CLASS__]['fields']);
    }
    return Civi::$statics[__CLASS__]['fields'];
  }

  /**
   * Return a mapping from field-name to the corresponding key (as used in fields()).
   *
   * @return array
   *   Array(string $name => string $uniqueName).
   */
  public static function &fieldKeys() {
    if (!isset(Civi::$statics[__CLASS__]['fieldKeys'])) {
      Civi::$statics[__CLASS__]['fieldKeys'] = array_flip(CRM_Utils_Array::collect('name', self::fields()));
    }
    return Civi::$statics[__CLASS__]['fieldKeys'];
  }

  /**
   * Returns the names of this table
   *
   * @return string
   */
  public static function getTableName() {
    return self::$_tableName;
  }

  /**
   * Returns if this table needs to be logged
   *
   * @return bool
   */
  public function getLog() {
    return self::$_log;
  }

  /**
   * Returns the list of fields that can be imported
   *
   * @param bool $prefix
   *
   * @return array
   */
  public static function &import($prefix = FALSE) {
    $r = CRM_Core_DAO_AllCoreTables::getImports(__CLASS__, 'o8_iras_donation_log', $prefix, []);
    return $r;
  }

  /**
   * Returns the list of fields that can be exported
   *
   * @param bool $prefix
   *
   * @return array
   */
  public static function &export($prefix = FALSE) {
    $r = CRM_Core_DAO_AllCoreTables::getExports(__CLASS__, 'o8_iras_donation_log', $prefix, []);
    return $r;
  }

  /**
   * Returns the list of indices
   *
   * @param bool $localize
   *
   * @return array
   */
  public static function indices($localize = TRUE) {
    $indices = [];
    return ($localize && !empty($indices)) ? CRM_Core_DAO_AllCoreTables::multilingualize(__CLASS__, $indices) : $indices;
  }

}