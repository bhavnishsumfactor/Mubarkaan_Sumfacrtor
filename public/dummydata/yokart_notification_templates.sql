
-- phpMyAdmin SQL Dump
-- version 4.8.5
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Mar 08, 2021 at 10:08 AM
-- Server version: 10.1.38-MariaDB
-- PHP Version: 7.3.4

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `tr1beyok4rt_demo`
--

--
-- Dumping data for table `yokart_notification_templates`
--

INSERT INTO `yokart_notification_templates` (`ntpl_id`, `ntpl_slug`, `ntpl_name`, `ntpl_body`, `ntpl_default_body`, `ntpl_replacements`, `ntpl_priority`, `ntpl_publish`, `ntpl_updated_by_id`, `ntpl_updated_at`) VALUES
(1, 'review_posted', 'Review Posted', 'New review posted by {userName} for {productName} on order #{orderNumber}', 'New review posted by {userName} for {productName} on order #{orderNumber}', '{\"{userName}\":\"User Name\", \"{productName}\":\"Product Name\", \"{orderNumber}\":\"Order Number\"}', 'normal', 1, 1, '2020-09-03 12:24:38'),
(2, 'review_edited', 'Review Edited', '{userName} has made edits to their review {reviewTitle}', '{userName} has made edits to their review {reviewTitle}', '{\"{userName}\":\"User Name\", \"{reviewTitle}\":\"Review Title\"}', 'normal', 1, 1, '2020-09-03 12:24:38'),
(3, 'order_created_by_user', 'Order Created By User', 'New order #{orderNumber} received for amount {orderAmount}', 'New order #{orderNumber} received for amount {orderAmount}', '{\"{orderNumber}\":\"Order Number\", \"{orderAmount}\":\"Order Amount\"}', 'normal', 1, 1, '2020-09-03 12:24:38'),
(4, 'order_created_by_admin', 'Order Created By Admin', 'New order #{orderNumber} created for {userName} with amount {orderAmount}', 'New order #{orderNumber} created for {userName} with amount {orderAmount}', '{\"{orderNumber}\":\"Order Number\", \"{userName}\":\"User Name\", \"{orderAmount}\":\"Order Amount\"}', 'normal', 1, 1, '2020-09-03 12:24:38'),
(5, 'user_requested_cancellation', 'User Requested Cancellation', 'User {userName} has requested cancellation for order #{orderNumber} amount being {orderAmount}', 'User {userName} has requested cancellation for order #{orderNumber} amount being {orderAmount}', '{\"{userName}\":\"User Name\", \"{orderNumber}\":\"Order Number\", \"{orderAmount}\":\"Order Amount\"}', 'normal', 1, 1, '2020-09-03 12:24:38'),
(6, 'user_requested_partial_cancellation', 'User Requested Partial Cancellation', 'User {userName} has requested partial cancellation for order #{orderNumber} amount being {orderAmount}', 'User {userName} has requested partial cancellation for order #{orderNumber} amount being {orderAmount}', '{\"{userName}\":\"User Name\", \"{orderNumber}\":\"Order Number\", \"{orderAmount}\":\"Order Amount\"}', 'normal', 1, 1, '2020-09-03 12:24:38'),
(7, 'user_requested_return', 'User Requested Return', 'User {userName} has requested return for order #{orderNumber} amount being {orderAmount}', 'User {userName} has requested return for order #{orderNumber} amount being {orderAmount}', '{\"{userName}\":\"User Name\", \"{orderNumber}\":\"Order Number\", \"{orderAmount}\":\"Order Amount\"}', 'normal', 1, 1, '2020-09-03 12:24:38'),
(8, 'user_requested_partial_return', 'User Requested Partial Return', 'User {userName} has requested partial return for order #{orderNumber} amount being {orderAmount}', 'User {userName} has requested partial return for order #{orderNumber} amount being {orderAmount}', '{\"{userName}\":\"User Name\", \"{orderNumber}\":\"Order Number\", \"{orderAmount}\":\"Order Amount\"}', 'normal', 1, 1, '2020-09-03 12:24:38'),
(9, 'new_comment_on_order', 'New comment on Order', 'New comment received for order #{orderNumber} by {userName}', 'New comment received for order #{orderNumber} by {userName}', '{\"{orderNumber}\":\"Order Number\", \"{userName}\":\"User Name\"}', 'normal', 1, 1, '2020-09-03 12:24:38'),
(10, 'new_comment_on_order_cancellation_request', 'New comment on Order Cancellation Request', 'New comment received on cancellation request #{orderNumber} from {userName}', 'New comment received on cancellation request #{orderNumber} from {userName}', '{\"{orderNumber}\":\"Order Number\", \"{userName}\":\"User Name\"}', 'normal', 1, 1, '2020-09-03 12:24:38'),
(11, 'new_comment_on_order_return_request', 'New comment on Order Return Request', 'New comment received on return request #{orderNumber} from {userName}', 'New comment received on return request #{orderNumber} from {userName}', '{\"{orderNumber}\":\"Order Number\", \"{userName}\":\"User Name\"}', 'normal', 1, 1, '2020-09-03 12:24:38'),
(12, 'new_comment_on_blog', 'New comment on Blog', 'New comment received for blog {blogTitle} by {userName}', 'New comment received for blog {blogTitle} by {userName}', '{\"{blogTitle}\":\"Blog Title\", \"{userName}\":\"User Name\"}', 'normal', 1, 1, '2020-09-03 12:24:38'),
(13, 'new_user_registered', 'New User Registered', 'New user {userName} registered with email {email}', 'New user {userName} registered with email {email}', '{\"{userName}\":\"User Name\", \"{email}\":\"Email Address\"}', 'normal', 1, 1, '2020-09-03 12:24:38'),
(14, 'newsletter_marketing_subscription', 'Newsletter Marketing Subscription', '{email} has subscribed for newsletter marketing', '{email} has subscribed for newsletter marketing', '{\"{email}\":\"Email Address\"}', 'normal', 1, 1, '2020-09-03 12:24:38'),
(15, 'gdpr_data_request', 'GDPR Data Request', '{userName} has requested account data under GDPR guidelines', '{userName} has requested account data under GDPR guidelines', '{\"{userName}\":\"User Name\"}', 'normal', 1, 1, '2020-09-03 12:24:38'),
(16, 'gdpr_deletion_request', 'GDPR Deletion Request', '{userName} has deleted their account under GDPR guidelines', '{userName} has deleted their account under GDPR guidelines', '{\"{userName}\":\"User Name\"}', 'normal', 1, 1, '2020-09-03 12:24:38'),
(17, 'todo_task_reminder', 'Todo Task Reminder', 'Reminder: {todoTitle} @ {dateTime}', 'Reminder: {todoTitle} @ {dateTime}', '{\"{todoTitle}\":\"Todo Title\", \"{dateTime}\":\"Date Time\"}', 'normal', 1, 1, '2020-09-03 12:24:38'),
(18, 'order_payment_received', 'Order Payment Received', 'Payment of {orderAmount} received against order #{orderNumber} via {paymentMethod}', 'Payment of {orderAmount} received against order #{orderNumber} via {paymentMethod}', '{\"{orderAmount}\":\"Order Amount\", \"{orderNumber}\":\"Order Number\", \"{paymentMethod}\":\"Payment Method\"}', 'normal', 1, 1, '2020-09-03 12:24:38');
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;