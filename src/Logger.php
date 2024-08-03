<?php
date_default_timezone_set("Asia/Kolkata");

class Logger {
    private $logDirectoryPath;
    private $logFileName;

    private $logDirectoryPathStatus = false;
    private $logFileStatus = false;
    private $logContent;

    public function __construct($logDirectoryPath, $logFileName) {
        $this->clearFileCache();
        $this->logDirectoryPath = $logDirectoryPath;
        $this->logFileName = $logFileName;
        $this->logContent = null;
        $this->verifyPath();
        $this->verifyFile();
    }

    private function verifyPath() {
        if (is_dir($this->logDirectoryPath) === true) {
            $this->logDirectoryPathStatus = true;
        } elseif (is_dir($this->logDirectoryPath) === false) {
            $this->makeDirectory($this->logDirectoryPath);
        }
    }

    private function verifyFile() {
        $fileCheck = file_exists(
            sprintf("%s/%s", $this->logDirectoryPath, $this->logFileName)
        );

        if ($fileCheck === true) {
            $this->logFileStatus = true;
        } else {
            file_put_contents(
                sprintf("%s/%s", $this->logDirectoryPath, $this->logFileName),
                "__log_file_created__@" . $this->getDateTime()
            );
            $this->logFileStatus = true;
        }
    }

    private function makeDirectory($path) {
        $folderPathArr = explode("/", $path);
        $folderToCreate = "";

        foreach ($folderPathArr as $folderName) {
            $folderToCreate = sprintf(
				"%s%s%s",
                $folderToCreate,
                $folderName,
                "/"
            );
            if (!is_dir($folderToCreate)) {
                mkdir($folderToCreate, 0777);
                chmod("$folderToCreate", 0755);
            }
        }

        if (is_dir($this->logDirectoryPath) === false) {
            echo "failed.to.make.directory : " . $this->logDirectoryPath . '\n\n';
        }
    }

    public function writeLog($content) {
        $this->logContent = sprintf("%s -- %s", $this->getDateTime(), $content);
        file_put_contents(
            sprintf("%s/%s", $this->logDirectoryPath, $this->logFileName),
            PHP_EOL . $this->logContent,
            FILE_APPEND
        );
        $this->clearFileCache();
    }

    private function getDateTime() {
        $DateTimeAtNow = \DateTime::createFromFormat(
            "U.u",
            sprintf("%.6f", microtime(true))
        );
        $DateTimeAtNow->setTimezone(new \DateTimeZone("Asia/Kolkata"));
        return $DateTimeAtNow->format("d-m-Y H:i:s.u");
    }

    private function clearFileCache() {
        clearstatcache();
    }
}
