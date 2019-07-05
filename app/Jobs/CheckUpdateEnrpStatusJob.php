<?php

namespace App\Jobs;

use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Client;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
// use App;
use App\Arc;
use Carbon\Carbon;

class CheckUpdateEnrpStatusJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $arc_id;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($arc_id)
    {
        $this->arc_id = $arc_id;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $arc = Arc::findOrFail($this->arc_id);

        if(app()->environment('production')) {
            $check_enrp_url = "https://go.curlec.com/curlec-services";
            $merchant_id = 70150;
        }else {
            $check_enrp_url = "https://demo.curlec.com/curlec-services";
            $merchant_id = 40020;
        }

        $client = new Client();
        $result = $client->post($check_enrp_url, [
            'form_params' => [
                'ex_order_no' => $arc->ex_order_no,
                'order_no' => '',
                'merchantId' => $merchant_id,
                'method' => '03'
            ]
        ]);

        $resultObj = json_decode($result->getBody()->getContents());
        // dd($arc->id, $resultObj, $resultObj->Status[0], $resultObj->Response[0]->enrp_status);
        if($resultObj->Status[0] == '200') {
            $response = $resultObj->Response[0];
            if($response->enrp_status[0] == 'Approved' or $response->enrp_status[0] == '00') {
                $arc->enrp_status = '00';
                $arc->status = 5;
            }else {
                $arc->status = 6;
            }
            $arc->enrp_status_updated_at = Carbon::now();
            $arc->save();
        }
    }
}
