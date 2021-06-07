<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Models\User;
use Carbon\Carbon;

class UpdateAlertsCovid implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $data = User::where('user_alert','amarillo')
        ->orWhere('user_alert','rojo')
        ->get();
        foreach ($data as $user) {
            if ($user->updated_at->diffInMinutes(Carbon::now()) >= 5) {
                $user->user_alert = 'verde';
                $user->save();
            }
        }
    }
}
