<?php

namespace YoKartInstall\Installer\Helpers;

class InstalledFileManager
{
    /**
     * Create installed file.
     *
     * @return int
     */
    public function create()
    {
        $installedLogFile = storage_path('installed');

        $dateStamp = date("Y/m/d h:i:sa");

        if (!file_exists($installedLogFile)) {
            $message = trans('Laravel Installer successfully INSTALLED on') . ' ' . $dateStamp . "\n";

            file_put_contents($installedLogFile, $message);
        } else {
            $message = trans('Laravel Installer successfully UPDATED on') . ' ' . $dateStamp;

            file_put_contents($installedLogFile, $message.PHP_EOL, FILE_APPEND | LOCK_EX);
        }

        return $message;
    }

    /**
     * Update installed file.
     *
     * @return int
     */
    public function update()
    {
        return $this->create();
    }
}
