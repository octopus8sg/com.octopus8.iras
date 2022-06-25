-- +--------------------------------------------------------------------+
-- | Copyright CiviCRM LLC. All rights reserved.                        |
-- |                                                                    |
-- | This work is published under the GNU AGPLv3 license with some      |
-- | permitted exceptions and without any warranty. For full license    |
-- | and copyright information, see https://civicrm.org/licensing       |
-- +--------------------------------------------------------------------+
--
-- Generated from schema.tpl
-- DO NOT EDIT.  Generated by CRM_Core_CodeGen
--
-- /*******************************************************
-- *
-- * Clean up the existing tables - this section generated from drop.tpl
-- *
-- *******************************************************/

SET FOREIGN_KEY_CHECKS=0;

DROP TABLE IF EXISTS `civicrm_o8_iras_donation`;
DROP TABLE IF EXISTS `civicrm_o8_iras_config`;

SET FOREIGN_KEY_CHECKS=1;
-- /*******************************************************
-- *
-- * Create new tables
-- *
-- *******************************************************/

-- /*******************************************************
-- *
-- * civicrm_o8_iras_config
-- *
-- * IRAS connection congiguration settings
-- *
-- *******************************************************/
CREATE TABLE `civicrm_o8_iras_config` (
  `param_name` text NOT NULL COMMENT 'parameter name',
  `param_value` text COMMENT 'value of parameter'
)
ENGINE=InnoDB;

-- /*******************************************************
-- *
-- * civicrm_o8_iras_donation
-- *
-- * IRAS Donation Reporting tool
-- *
-- *******************************************************/
CREATE TABLE `civicrm_o8_iras_donation` (
  `financial_trxn_id` int unsigned COMMENT 'FK to Contact',
  `created_date` datetime COMMENT 'Created date',
  CONSTRAINT FK_civicrm_o8_iras_donation_financial_trxn_id FOREIGN KEY (`financial_trxn_id`) REFERENCES `civicrm_financial_trxn`(`id`) ON DELETE CASCADE
)
ENGINE=InnoDB;
