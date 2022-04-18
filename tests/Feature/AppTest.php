<?php

namespace Tests\Feature;

use App\Traits\AppTrait;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class AppTest extends TestCase
{
    use AppTrait;
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_count_csv()
    {
        $response = $this->ReadCSV(storage_path("app/input.csv"));
        $this->assertGreaterThan(0,count($response));
    }

    public function test_rates_exist()
    {
        $response = Http::get("https://developers.paysera.com/tasks/api/currency-exchange-rates")->json();
        $this->assertArrayHasKey('rates', $response);
        $this->assertGreaterThan(0,count($response['rates']));
    }

    public function test_result()
    {
        $response = $this->get('/');
        $response->assertStatus(200);
        $csv = $this->ReadCSV(storage_path("app/input.csv"));

        $this->assertEquals(count($response->json()), count($csv));

        $this->assertEquals($response->json(), ["3.60","3.00","3.00","0.06","1.50","0.69","3.00","0.27","0.30","3.00","3.00","0.90","68.77"]);

    }
}
