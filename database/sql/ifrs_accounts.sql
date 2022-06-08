﻿--
-- Script was generated by Devart dbForge Studio 2019 for MySQL, Version 8.2.23.0
-- Product home page: http://www.devart.com/dbforge/mysql/studio
-- Script date 6/4/2022 7:57:16 AM
-- Server version: 5.7.24
-- Client version: 4.1
--


SET NAMES 'utf8';

INSERT INTO quickbilling.accounts(id, entity_id, category_id, currency_id, code, name, description, account_type, destroyed_at, deleted_at, created_at, updated_at) VALUES
(2, 1, 5, 1, 10501, 'Cash IDR', NULL, 'CURRENT_ASSET', NULL, NULL, '2022-04-11 00:43:12', '2022-04-11 00:43:12'),
(4, 1, NULL, 1, 40101, 'Bank Account', NULL, 'OPERATING_REVENUE', NULL, NULL, '2022-04-11 00:49:43', '2022-04-11 00:49:43'),
(5, 1, NULL, 1, 10601, 'Account Receivable', NULL, 'RECEIVABLE', NULL, NULL, '2022-04-11 01:01:14', '2022-04-11 01:01:14'),
(6, 1, NULL, 1, 10602, 'Unbilled Accounts Receivable', NULL, 'RECEIVABLE', NULL, NULL, '2022-04-11 01:01:53', '2022-04-11 01:01:53'),
(7, 1, NULL, 1, 10603, 'Doubtful Receivable', NULL, 'RECEIVABLE', NULL, NULL, '2022-04-11 01:02:29', '2022-04-11 01:02:29'),
(8, 1, NULL, 1, 10301, 'Inventory', NULL, 'INVENTORY', NULL, NULL, '2022-04-11 01:02:53', '2022-04-11 01:02:53'),
(9, 1, NULL, 1, 10101, 'Others Receivables', NULL, 'NON_CURRENT_ASSET', NULL, NULL, '2022-04-11 01:06:43', '2022-04-22 07:41:04'),
(10, 1, NULL, 1, 10102, 'Employee Receivables', NULL, 'NON_CURRENT_ASSET', NULL, NULL, '2022-04-11 01:07:14', '2022-04-11 01:07:14'),
(11, 1, NULL, 1, 10103, 'Undeposited Funds', NULL, 'NON_CURRENT_ASSET', NULL, NULL, '2022-04-11 01:07:36', '2022-04-11 01:07:36'),
(12, 1, NULL, 1, 10104, 'Other current assets', NULL, 'NON_CURRENT_ASSET', NULL, NULL, '2022-04-11 01:08:18', '2022-04-11 01:08:18'),
(13, 1, NULL, 1, 10105, 'Prepaid expenses', NULL, 'NON_CURRENT_ASSET', NULL, NULL, '2022-04-11 01:08:34', '2022-04-11 01:08:34'),
(14, 1, NULL, 1, 10106, 'Advances', NULL, 'NON_CURRENT_ASSET', NULL, NULL, '2022-04-11 01:08:54', '2022-04-11 01:08:54'),
(15, 1, NULL, 1, 10107, 'VAT In', NULL, 'NON_CURRENT_ASSET', NULL, NULL, '2022-04-11 01:09:31', '2022-04-11 01:09:31'),
(16, 1, NULL, 1, 10108, 'Prepaid Income Tax - PPh 22', NULL, 'NON_CURRENT_ASSET', NULL, NULL, '2022-04-11 01:09:53', '2022-04-11 01:09:53'),
(17, 1, NULL, 1, 10109, 'Prepaid Income Tax - PPh 23', NULL, 'NON_CURRENT_ASSET', NULL, NULL, '2022-04-11 01:10:20', '2022-04-11 01:10:20'),
(18, 1, NULL, 1, 10110, 'Prepaid Income Tax - PPh 25', NULL, 'NON_CURRENT_ASSET', NULL, NULL, '2022-04-11 01:10:56', '2022-04-11 01:10:56'),
(19, 1, NULL, 1, 10201, 'Fixed Assets - Land', NULL, 'CONTRA_ASSET', NULL, NULL, '2022-04-11 01:13:00', '2022-04-11 01:13:00'),
(20, 1, NULL, 1, 20401, 'Trade Payable', NULL, 'PAYABLE', NULL, NULL, '2022-04-11 01:17:30', '2022-04-11 01:17:30'),
(21, 1, NULL, 1, 20402, 'Unbilled Accounts Payable', NULL, 'PAYABLE', NULL, NULL, '2022-04-11 01:23:19', '2022-04-11 01:23:19'),
(22, 1, NULL, 1, 20101, 'Other Payables', NULL, 'NON_CURRENT_LIABILITY', NULL, NULL, '2022-04-11 01:24:08', '2022-04-11 01:24:08'),
(23, 1, NULL, 1, 20102, 'Salaries Payable', NULL, 'NON_CURRENT_LIABILITY', NULL, NULL, '2022-04-11 01:25:03', '2022-04-11 01:25:03'),
(24, 1, NULL, 1, 20103, 'Dividends Payable', NULL, 'NON_CURRENT_LIABILITY', NULL, NULL, '2022-04-11 01:25:26', '2022-04-11 01:25:26'),
(25, 1, NULL, 1, 20104, 'Unearned Revenue', NULL, 'NON_CURRENT_LIABILITY', NULL, NULL, '2022-04-11 01:25:45', '2022-04-11 01:25:45'),
(26, 1, NULL, 1, 20105, 'Accrued Utilities', NULL, 'NON_CURRENT_LIABILITY', NULL, NULL, '2022-04-11 01:26:04', '2022-04-11 01:26:04'),
(27, 1, NULL, 1, 20106, 'Accrued Interest', NULL, 'NON_CURRENT_LIABILITY', NULL, NULL, '2022-04-11 01:26:39', '2022-04-11 01:26:39'),
(28, 1, NULL, 1, 20107, 'Other Accrued Expenses', NULL, 'NON_CURRENT_LIABILITY', NULL, NULL, '2022-04-11 01:27:55', '2022-04-11 01:27:55'),
(29, 1, NULL, 1, 20108, 'Bank Loans', NULL, 'NON_CURRENT_LIABILITY', NULL, NULL, '2022-04-11 01:28:18', '2022-04-11 01:28:18'),
(30, 1, NULL, 1, 20109, 'VAT Out', NULL, 'NON_CURRENT_LIABILITY', NULL, NULL, '2022-04-11 01:28:36', '2022-04-11 01:28:36'),
(31, 1, NULL, 1, 20110, 'Tax Payable - PPh 21', NULL, 'NON_CURRENT_LIABILITY', NULL, NULL, '2022-04-11 01:28:57', '2022-04-11 01:28:57'),
(32, 1, NULL, 1, 20111, 'Tax Payable - PPh 22', NULL, 'NON_CURRENT_LIABILITY', NULL, NULL, '2022-04-11 01:29:14', '2022-04-11 01:29:14'),
(33, 1, NULL, 1, 20112, 'Tax Payable - PPh 23', NULL, 'NON_CURRENT_LIABILITY', NULL, NULL, '2022-04-11 01:29:32', '2022-04-11 01:29:32'),
(34, 1, NULL, 1, 20113, 'Tax Payable - PPh 29', NULL, 'NON_CURRENT_LIABILITY', NULL, NULL, '2022-04-11 01:30:01', '2022-04-11 01:30:01'),
(35, 1, NULL, 1, 20114, 'Other Taxes Payable', NULL, 'NON_CURRENT_LIABILITY', NULL, NULL, '2022-04-11 01:30:21', '2022-04-11 01:30:21'),
(36, 1, NULL, 1, 20115, 'Loan from Shareholders', NULL, 'NON_CURRENT_LIABILITY', NULL, NULL, '2022-04-11 01:30:38', '2022-04-11 01:30:38'),
(37, 1, NULL, 1, 20116, 'Other Current Liabilities', NULL, 'NON_CURRENT_LIABILITY', NULL, NULL, '2022-04-11 01:30:59', '2022-04-11 01:30:59'),
(38, 1, NULL, 1, 20201, 'Employee Benefit Liabilities', NULL, 'CONTROL', NULL, NULL, '2022-04-11 01:31:31', '2022-04-11 01:31:31'),
(39, 1, NULL, 1, 30101, 'Paid In Capital', NULL, 'EQUITY', NULL, NULL, '2022-04-11 01:34:01', '2022-04-11 01:34:01'),
(40, 1, NULL, 1, 30102, 'Additional Paid In Capital', NULL, 'EQUITY', NULL, NULL, '2022-04-11 01:34:38', '2022-04-11 01:34:38'),
(41, 1, NULL, 1, 30103, 'Retained Earnings', NULL, 'EQUITY', NULL, NULL, '2022-04-11 01:37:43', '2022-04-11 01:37:43'),
(42, 1, NULL, 1, 30104, 'Dividends', NULL, 'EQUITY', NULL, NULL, '2022-04-11 01:41:06', '2022-04-11 01:41:06'),
(43, 1, NULL, 1, 30105, 'Other Comprehensive Income', NULL, 'EQUITY', NULL, NULL, '2022-04-11 01:41:20', '2022-04-11 01:41:20'),
(44, 1, NULL, 1, 30106, 'Opening Balance Equity', NULL, 'EQUITY', NULL, NULL, '2022-04-11 01:42:17', '2022-04-11 01:42:17'),
(45, 1, NULL, 1, 40102, 'Service Revenue', NULL, 'OPERATING_REVENUE', NULL, NULL, '2022-04-11 01:54:12', '2022-04-11 01:54:12'),
(46, 1, NULL, 1, 40103, 'Sales Discount', NULL, 'OPERATING_REVENUE', NULL, NULL, '2022-04-11 01:55:01', '2022-04-11 01:55:01'),
(47, 1, NULL, 1, 40104, 'Sales Return', NULL, 'OPERATING_REVENUE', NULL, NULL, '2022-04-11 01:55:27', '2022-04-11 01:55:27'),
(48, 1, NULL, 1, 40105, 'Unbilled Revenues', NULL, 'OPERATING_REVENUE', NULL, NULL, '2022-04-11 01:55:52', '2022-04-11 01:55:52'),
(49, 1, NULL, 1, 50101, 'Cost of Sales', NULL, 'OPERATING_EXPENSE', NULL, NULL, '2022-04-11 01:56:24', '2022-04-11 01:56:24'),
(50, 1, NULL, 1, 50102, 'Purchase Discounts', NULL, 'OPERATING_EXPENSE', NULL, NULL, '2022-04-11 01:56:41', '2022-04-11 01:56:41'),
(51, 1, NULL, 1, 50103, 'Purchase Return', NULL, 'OPERATING_EXPENSE', NULL, NULL, '2022-04-11 01:56:57', '2022-04-11 01:56:57'),
(52, 1, NULL, 1, 50104, 'Shipping/Freight & Delivery', NULL, 'OPERATING_EXPENSE', NULL, NULL, '2022-04-11 01:57:11', '2022-04-11 01:57:11'),
(53, 1, NULL, 1, 50105, 'Import Charges', NULL, 'OPERATING_EXPENSE', NULL, NULL, '2022-04-11 01:57:28', '2022-04-11 01:57:28'),
(54, 1, NULL, 1, 50106, 'Cost of Production', NULL, 'OPERATING_EXPENSE', NULL, NULL, '2022-04-11 01:57:44', '2022-04-11 01:57:44'),
(55, 1, NULL, 1, 60101, 'Selling Expenses', NULL, 'DIRECT_EXPENSE', NULL, NULL, '2022-04-11 01:58:34', '2022-04-11 01:58:34'),
(56, 1, NULL, 1, 60102, 'General & Administrative Expenses', NULL, 'DIRECT_EXPENSE', NULL, NULL, '2022-04-11 01:58:58', '2022-04-11 01:58:58'),
(57, 1, NULL, 1, 60103, 'Waste Goods Expense', NULL, 'DIRECT_EXPENSE', NULL, NULL, '2022-04-11 02:03:37', '2022-04-11 02:03:37'),
(58, 1, NULL, 1, 40501, 'Interest Income - Bank', NULL, 'NON_OPERATING_REVENUE', NULL, NULL, '2022-04-11 02:04:14', '2022-04-11 02:04:14'),
(59, 1, NULL, 1, 40502, 'Interest Income - Time deposit', NULL, 'NON_OPERATING_REVENUE', NULL, NULL, '2022-04-11 02:04:37', '2022-04-11 02:04:37'),
(60, 1, NULL, 1, 40503, 'Rounding', NULL, 'NON_OPERATING_REVENUE', NULL, NULL, '2022-04-11 02:11:08', '2022-04-11 02:11:08'),
(61, 1, NULL, 1, 40504, 'Other Income', NULL, 'NON_OPERATING_REVENUE', NULL, NULL, '2022-04-11 02:11:24', '2022-04-11 02:11:24'),
(62, 1, NULL, 1, 80101, 'Interest Expense', NULL, 'OTHER_EXPENSE', NULL, NULL, '2022-04-11 02:11:50', '2022-04-11 02:11:50'),
(63, 1, NULL, 1, 80102, 'Provision', NULL, 'OTHER_EXPENSE', NULL, NULL, '2022-04-11 02:12:12', '2022-04-11 02:12:12'),
(64, 1, NULL, 1, 80103, '(Gain)/Loss on Disposal of Fixed Assets', NULL, 'OTHER_EXPENSE', NULL, NULL, '2022-04-11 02:12:27', '2022-04-11 02:12:27'),
(65, 1, NULL, 1, 80104, 'Inventory Adjustments', NULL, 'OTHER_EXPENSE', NULL, NULL, '2022-04-11 02:12:44', '2022-04-11 02:12:44'),
(66, 1, NULL, 1, 80105, 'Other Miscellaneous Expense', NULL, 'OTHER_EXPENSE', NULL, NULL, '2022-04-11 02:13:02', '2022-04-11 02:13:02'),
(67, 1, NULL, 1, 80106, 'Income Taxes - Current', NULL, 'OTHER_EXPENSE', NULL, NULL, '2022-04-11 02:13:18', '2022-04-11 02:13:18'),
(68, 1, NULL, 1, 80107, 'Income Taxes - Deferred', NULL, 'OTHER_EXPENSE', NULL, NULL, '2022-04-11 02:13:45', '2022-04-11 02:13:45'),
(69, 1, NULL, 1, 80108, 'Bank Revaluation', NULL, 'OTHER_EXPENSE', NULL, NULL, '2022-04-11 02:14:06', '2022-04-11 02:14:06'),
(70, 1, NULL, 1, 80109, 'Foreign Exchange (Profit)/ Loss - Unrealised', NULL, 'OTHER_EXPENSE', NULL, NULL, '2022-04-11 02:14:53', '2022-04-11 02:14:53'),
(71, 1, NULL, 1, 80110, 'Foreign Exchange (Profit)/Loss - Realised', NULL, 'OTHER_EXPENSE', NULL, '2022-04-11 03:42:28', '2022-04-11 02:15:11', '2022-04-11 03:42:28'),
(72, 1, NULL, 1, 10502, 'Fixed Assets - Office Equipment', NULL, 'CURRENT_ASSET', NULL, NULL, '2022-04-11 03:53:38', '2022-04-11 03:53:38'),
(73, 1, NULL, 1, 20202, 'Sales VAT Account', NULL, 'CONTROL', NULL, NULL, '2022-04-11 05:44:49', '2022-04-11 05:44:49');