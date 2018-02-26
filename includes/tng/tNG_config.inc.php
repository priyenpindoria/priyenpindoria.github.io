<?php
// Array definitions
  $tNG_login_config = array();
  $tNG_login_config_session = array();
  $tNG_login_config_redirect_success  = array();
  $tNG_login_config_redirect_failed  = array();
  $tNG_login_config_redirect_success = array();
  $tNG_login_config_redirect_failed = array();

// Start Variable definitions
  $tNG_debug_mode = "DEVELOPMENT";
  $tNG_debug_log_type = "";
  $tNG_debug_email_to = "you@yoursite.com";
  $tNG_debug_email_subject = "[BUG] The site went down";
  $tNG_debug_email_from = "webserver@yoursite.com";
  $tNG_email_host = "";
  $tNG_email_user = "";
  $tNG_email_port = "25";
  $tNG_email_password = "";
  $tNG_email_defaultFrom = "nobody@nobody.com";
  $tNG_login_config["connection"] = "connMISDS";
  $tNG_login_config["table"] = "login_admin";
  $tNG_login_config["pk_field"] = "logid";
  $tNG_login_config["pk_type"] = "NUMERIC_TYPE";
  $tNG_login_config["email_field"] = "email";
  $tNG_login_config["user_field"] = "username";
  $tNG_login_config["password_field"] = "password";
  $tNG_login_config["level_field"] = "levelid";
  $tNG_login_config["level_type"] = "NUMERIC_TYPE";
  $tNG_login_config["randomkey_field"] = "";
  $tNG_login_config["activation_field"] = "";
  $tNG_login_config["password_encrypt"] = "false";
  $tNG_login_config["autologin_expires"] = "30";
  $tNG_login_config["redirect_failed"] = "login.php";
  $tNG_login_config["redirect_success"] = "superadmin/index.php";
  $tNG_login_config["login_page"] = "login.php";
  $tNG_login_config["max_tries"] = "";
  $tNG_login_config["max_tries_field"] = "";
  $tNG_login_config["max_tries_disableinterval"] = "";
  $tNG_login_config["max_tries_disabledate_field"] = "";
  $tNG_login_config["registration_date_field"] = "";
  $tNG_login_config["expiration_interval_field"] = "";
  $tNG_login_config["expiration_interval_default"] = "";
  $tNG_login_config["logger_pk"] = "";
  $tNG_login_config["logger_table"] = "";
  $tNG_login_config["logger_user_id"] = "";
  $tNG_login_config["logger_ip"] = "";
  $tNG_login_config["logger_datein"] = "";
  $tNG_login_config["logger_datelastactivity"] = "";
  $tNG_login_config["logger_session"] = "";
  $tNG_login_config_redirect_success["1"] = "superadmin/index.php";
  $tNG_login_config_redirect_failed["1"] = "login.php";
  $tNG_login_config_redirect_success["2"] = "admin/index.php";
  $tNG_login_config_redirect_failed["2"] = "login.php";
  $tNG_login_config_session["kt_login_id"] = "logid";
  $tNG_login_config_session["kt_login_user"] = "username";
  $tNG_login_config_session["kt_login_level"] = "levelid";
  $tNG_login_config_redirect_success["3"] = "regular_user/index.php";
  $tNG_login_config_redirect_failed["3"] = "login.php";
// End Variable definitions
?>