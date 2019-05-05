<?php

namespace App\Console;

use App\Console\Commands\Update;
use App\Console\Commands\ExportAll;
use App\Console\Commands\ImportCSV;
use App\Console\Commands\SetupTest;
use App\Console\Commands\GetVersion;
use App\Console\Scheduling\CronEvent;
use App\Console\Commands\ImportVCards;
use App\Console\Commands\LangGenerate;
use App\Console\Commands\SetUserAdmin;
use App\Console\Commands\Deactivate2FA;
use App\Console\Commands\SendReminders;
use App\Console\Commands\SentryRelease;
use App\Console\Commands\SendStayInTouch;
use App\Console\Commands\SetupProduction;
use App\Console\Commands\PingVersionServer;
use App\Console\Commands\SetPremiumAccount;
use App\Console\Commands\SetupFrontEndTest;
use Illuminate\Console\Scheduling\Schedule;
use App\Console\Commands\CalculateStatistics;
use App\Console\Commands\OneTime\MoveAvatars;
use App\Console\Commands\MigrateDatabaseCollation;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
        CalculateStatistics::class,
        Deactivate2FA::class,
        ExportAll::class,
        GetVersion::class,
        ImportCSV::class,
        ImportVCards::class,
        LangGenerate::class,
        MigrateDatabaseCollation::class,
        MoveAvatars::class,
        PingVersionServer::class,
        SendReminders::class,
        SendStayInTouch::class,
        SentryRelease::class,
        SetPremiumAccount::class,
        SetupFrontEndTest::class,
        SetupProduction::class,
        SetupTest::class,
        SetUserAdmin::class,
        Update::class,
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        $schedule->command('send:reminders')->when(function () {
            return CronEvent::command('send:reminders')
                ->hourly()
                ->isDue();
        });
        $schedule->command('send:stay_in_touch')->when(function () {
            return CronEvent::command('send:stay_in_touch')
                ->hourly()
                ->isDue();
        });
        $schedule->command('monica:calculatestatistics')->when(function () {
            return CronEvent::command('monica:calculatestatistics')
                ->daily()
                ->isDue();
        });
        $schedule->command('monica:ping')->when(function () {
            return CronEvent::command('monica:ping')
                ->daily()
                ->isDue();
        });
        if (config('trustedproxy.cloudflare')) {
            $schedule->command('cloudflare:reload')->when(function () {
                return CronEvent::command('cloudflare:reload')
                    ->daily()
                    ->isDue();
            }); // @codeCoverageIgnore
        }
    }
}
