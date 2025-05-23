<?php

namespace App\Http\Controllers;

use App\Models\Tripay;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class TripayController extends Controller
{
    public $tripay;
    public $baseUrl;

    public function __construct()
    {
        $this->tripay = Tripay::first();
        $this->baseUrl = $this->tripay->tripay_mode === 'sandbox'
            ? 'https://tripay.co.id/api-sandbox'
            : 'https://tripay.co.id/api';
    }

    /**
     * Check if response is successful
     */
    private function isSuccessfulResponse($response)
    {
        return isset($response['success']) && $response['success'] == true;
    }

    /**
     * Get available payment channels from Tripay
     */
    public function getPaymentChannels()
    {
        try {
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $this->tripay->tripay_api,
            ])->get($this->baseUrl . '/merchant/payment-channel');

            $data = $response->json();

            if (!$this->isSuccessfulResponse($data)) {
                Log::error('Failed to get payment channels', ['response' => $data]);
                return ['success' => false, 'data' => []];
            }

            return $data;
        } catch (\Exception $e) {
            Log::error('Exception when getting payment channels: ' . $e->getMessage());
            return ['success' => false, 'message' => 'Failed to communicate with payment gateway', 'data' => []];
        }
    }

    /**
     * Create transaction request at Tripay
     */
    public function requestTransactions($data)
    {
        try {
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $this->tripay->tripay_api,
            ])->post($this->baseUrl . '/transaction/create', $data);
            $responseData = $response->json();

            return response()->json($responseData);
        } catch (\Exception $e) {
            Log::error('Exception when creating transaction: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to communicate with payment gateway: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get transaction details from Tripay
     */
    public function getTransactionDetails($reference)
    {
        try {
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $this->tripay->tripay_api,
            ])->get($this->baseUrl . '/transaction/detail', [
                'reference' => $reference
            ]);

            return $response->json();
        } catch (\Exception $e) {
            Log::error('Exception when getting transaction details: ' . $e->getMessage());
            return ['success' => false, 'message' => 'Failed to get transaction details'];
        }
    }

    /**
     * Calculate fee for a payment method
     */
    public function calculateFee($code, $amount)
    {
        try {
            $payload = [
                'code' => $code,
                'amount' => $amount
            ];

            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $this->tripay->tripay_api,
            ])->get($this->baseUrl . '/merchant/fee-calculator', $payload);
            $data = $response->json();

            if ($this->isSuccessfulResponse($data) && isset($data['data']) && is_array($data['data'])) {
                foreach ($data['data'] as $method) {
                    if ($method['code'] === $code) {
                        $customerFee = $method['total_fee']['customer'] ?? 0;

                        return $customerFee;
                    }
                }
            }

            return $this->manualFeeCalculation($code, $amount);
        } catch (\Exception $e) {
            Log::error('Exception when calculating fee: ' . $e->getMessage());
            return $this->manualFeeCalculation($code, $amount);
        }
    }

    /**
     * Manual calculation of payment fee - uses customer fee only
     */
    private function manualFeeCalculation($code, $amount)
    {
        $paymentChannels = $this->getPaymentChannels();
        if (isset($paymentChannels['data']) && is_array($paymentChannels['data'])) {
            foreach ($paymentChannels['data'] as $channel) {
                if ($channel['code'] === $code && isset($channel['fee'])) {
                    if (isset($channel['fee_for']) && $channel['fee_for'] === 'customer') {
                        $fee = 0;

                        if (isset($channel['fee']['flat'])) {
                            $fee += (float)$channel['fee']['flat'];
                        }

                        if (isset($channel['fee']['percent'])) {
                            $fee += ($amount * ((float)$channel['fee']['percent'] / 100));
                        }
                        $fee = ceil($fee / 100) * 100;

                        return $fee;
                    } else {
                        return 0;
                    }
                }
            }
        }

        return 0;
    }
}
