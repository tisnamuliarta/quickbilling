--
-- Script was generated by Devart dbForge Studio 2019 for MySQL, Version 8.2.23.0
-- Product home page: http://www.devart.com/dbforge/mysql/studio
-- Script date 6/4/2022 7:58:16 AM
-- Server version: 5.7.24
-- Client version: 4.1
--


SET NAMES 'utf8';

INSERT INTO quickbilling.users(id, name, email, username, email_verify_at, password, two_factor_secret, two_factor_recovery_codes, enabled, avatar, user_type, remember_token, last_logged_in_at, created_at, updated_at, deleted_at, entity_id, destroyed_at, stripe_id, pm_type, pm_last_four, trial_ends_at) VALUES
(1, 'manager', 'manager@email.com', 'manager2', NULL, '$2y$10$qYMN.cXChmv83MNZHD48K.IHAU3/VAoOaWS7LLjiagbWcVQsPluqG', NULL, NULL, 1, NULL, NULL, 'CPGjQ1Toa5fDUH0fMOwCInuFMOcdT6Tjjuc2uHbavNvSIoCMOglt0srYWLWo', '2022-06-03 13:02:18', '2022-04-07 01:17:00', '2022-06-03 13:02:18', NULL, 1, NULL, NULL, NULL, NULL, NULL),
(2, 'PT JAYA INVESTAMA', 'jaya1@gmail.com', 'jaya1@gmail.com', NULL, '$2y$10$XH.bcLCbn1FNnbBsyxaeEuHvwf4i./o6yZKZ1jrqX2s5PPhYUJZZm', NULL, NULL, 1, NULL, NULL, NULL, NULL, '2022-04-07 07:01:47', '2022-04-22 07:51:39', NULL, NULL, NULL, NULL, NULL, NULL, NULL);