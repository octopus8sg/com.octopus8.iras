<?php

/**
 * @package CRM
 * @copyright CiviCRM LLC https://civicrm.org/licensing
 *
 * Generated from com.octopus8.iras
 * DO NOT EDIT.  Generated by CRM_Core_CodeGen
 * (GenCodeChecksum:2e86fc5e5946d9c99fccd185342cdf3b)
 */
use CRM_Irasdonation_ExtensionUtil as E;

/**
 * Database access object for the IrasConfig entity.
 */
class CRM_Irasdonation_DAO_IrasConfig extends CRM_Core_DAO {
  const EXT = E::LONG_NAME;
  const TABLE_ADDED = '';

  /**
   * Static instance to hold the table name.
   *
   * @var string
   */
  public static $_tableName = 'civicrm_o8_iras_config';
        /**
         * Primary key field(s).
         *
         * @var string[]
         */
        public static $_primaryKey = [];

        /**
         * Should CiviCRM log any modifications to this table in the civicrm_log table.
         *
         * @var bool
         */
        public static $_log = TRUE;

        /**
         * parameter name
         *
         * @var string
         *   (SQL type: text)
         *   Note that values will be retrieved from the database as a string.
         */
        public $param_name;

        /**
         * value of parameter
         *
         * @var string|null
         *   (SQL type: text)
         *   Note that values will be retrieved from the database as a string.
         */
        public $param_value;

        /**
         * Class constructor.
         */
        public function __construct() {
          $this->__table = 'civicrm_o8_iras_config';
          parent::__construct();
        }

        /**
         * Returns localized title of this entity.
         *
         * @param bool $plural
         *   Whether to return the plural version of the title.
         */
        public static function getEntityTitle($plural = FALSE) {
          return $plural ? E::ts('Iras Configs') : E::ts('Iras Config');
        }

        /**
         * Returns all the column names of this table
         *
         * @return array
         */
        public static function &fields() {
          if (!isset(Civi::$statics[__CLASS__]['fields'])) {
            Civi::$statics[__CLASS__]['fields'] = [
              'param_name' => [
                'name' => 'param_name',
                'type' => CRM_Utils_Type::T_TEXT,
                'title' => E::ts('Param Name'),
                'description' => E::ts('parameter name'),
                'required' => TRUE,
                'where' => 'civicrm_o8_iras_config.param_name',
                'table_name' => 'civicrm_o8_iras_config',
                'entity' => 'IrasConfig',
                'bao' => 'CRM_Irasdonation_DAO_IrasConfig',
                'localizable' => 0,
                'html' => [
                  'type' => 'ParamName',
                ],
                'add' => NULL,
              ],
              'param_value' => [
                'name' => 'param_value',
                'type' => CRM_Utils_Type::T_TEXT,
                'title' => E::ts('Param Value'),
                'description' => E::ts('value of parameter'),
                'where' => 'civicrm_o8_iras_config.param_value',
                'table_name' => 'civicrm_o8_iras_config',
                'entity' => 'IrasConfig',
                'bao' => 'CRM_Irasdonation_DAO_IrasConfig',
                'localizable' => 0,
                'html' => [
                  'type' => 'Value',
                ],
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
          $r = CRM_Core_DAO_AllCoreTables::getImports(__CLASS__, 'o8_iras_config', $prefix, []);
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
          $r = CRM_Core_DAO_AllCoreTables::getExports(__CLASS__, 'o8_iras_config', $prefix, []);
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
      