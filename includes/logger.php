<?php
require_once 'config.php';

class AuthLogger {
    private $logFile;
    
    public function __construct() {
        $this->logFile = LOGS_FILE;
    }
    
    private function writeLog($login, $action, $details = '') {
        $time = date('d-m-Y H:i:s');
        $ip = getClientIP();
        
        $logLine = sprintf(
            "[%s] | IP: %s | LOGIN: %s | ACTION: %s%s%s",
            $time,
            $ip,
            $login,
            $action,
            $details ? ' | DETAILS: ' : '',
            $details
        );
        
        $logLine .= PHP_EOL;
        
        file_put_contents($this->logFile, $logLine, FILE_APPEND | LOCK_EX);
    }
    
    public function logSuccessLogin($login) {
        $this->writeLog($login, 'SUCCESS_LOGIN', 'Успешный вход в систему');
    }
    
    public function logFailedLogin($login, $reason = '') {
        $reasonText = $reason ? "Причина: $reason" : "Неверный логин или пароль";
        $this->writeLog($login, 'FAIL_LOGIN', $reasonText);
    }
    
    public function logLogout($login) {
        $this->writeLog($login, 'LOGOUT', 'Выход из системы');
    }
    
    public function logRegister($login) {
        $this->writeLog($login, 'REGISTER', 'Регистрация нового пользователя');
    }
    
    public function logAccessDenied($login, $page = '') {
        $details = $page ? "Попытка доступа к: $page" : '';
        $this->writeLog($login, 'ACCESS_DENIED', $details);
    }
    
    public function getLogs($lines = 100) {
        if (!file_exists($this->logFile)) {
            return [];
        }
        
        $logs = file($this->logFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
        return array_slice($logs, -$lines);
    }
}

$logger = new AuthLogger();
?>