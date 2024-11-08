<?php

namespace App\Utilities;

use Illuminate\Support\Facades\Log;

class LoanCalculator
{

    public $payable_amount;
    private $apply_amount;
    private $first_payment_date;
    private $interest_rate;
    private $term;
    private $term_period;
    private $interest_type;

    public function __construct($apply_amount, $first_payment_date, $interest_rate, $term, $term_period, $interest_type)
    {
        $this->apply_amount           = $apply_amount;
        $this->first_payment_date     = $first_payment_date;
        $this->interest_rate          = $interest_rate;
        $this->term                   = $term;
        $this->term_period            = $term_period;
        $this->interest_type = $interest_type;
    }


    //version 3
    public function get_fixed_rate()
    {
        // Convert interest rate to a fraction
        $interest_rate = $this->interest_rate / 100;
        $apply_amount = $this->apply_amount;  // Loan amount
        $term = $this->term;  // Number of terms

        // Determine the interest rate per day based on interest type
        switch ($this->interest_type) {
            case 'monthly':
                $daily_interest_rate = $interest_rate / 28;  // Convert to monthly interest and then to daily
                break;

            case 'yearly':
            default:
                $daily_interest_rate = $interest_rate / 364;  // Yearly interest converted to daily
                break;
        }

        // Calculate total payable amount (principal + interest)
        switch ($this->interest_type) {
            case 'monthly':

                if ($this->term_period == '+1 week') {
                    $total_interest = $daily_interest_rate * $term * $apply_amount * 7;
                } else {
                    $total_interest = $daily_interest_rate * $term * $apply_amount * 28;
                }
                break;

            case 'yearly':
            default:

                if ($this->term_period == '+1 week') {
                    $total_interest = $daily_interest_rate * $term * $apply_amount * 7;
                } else {
                    $total_interest = ($interest_rate / 12) * $term * $apply_amount;
                }
                break;
        }

        $this->payable_amount = $total_interest + $apply_amount;

        // Prepare calculation variables
        $date = $this->first_payment_date;
        $principle_amount = $apply_amount / $term;
        $interest_per_term = $total_interest / $term;
        $amount_to_pay = $principle_amount + $interest_per_term;  // Amount to pay per term
        $balance = $this->payable_amount;

        $data = array();

        $principle_amount_total = 0;
        $interest_total = 0;
        $amount_to_pay_total = 0;
        $capital_total = 0;

        // Loop through each term to calculate payments
        for ($i = 0; $i < $term; $i++) {
            $balance = $balance - floor($amount_to_pay);  // Subtract paid amount from balance


            if (is_numeric($balance) && is_numeric($amount_to_pay)) {
                // Cast to float for comparison
                $balance = (float)$balance;
                $amount_to_pay = (float)$amount_to_pay;

                // Using rounding to handle floating point precision issues
                if (round($balance, 2) < round($amount_to_pay, 2)) {
                    Log::info("Balance is less than the amount to pay.");
                    $amount_to_pay = $amount_to_pay + $balance;
                    $balance = 0;
                } else if (round($balance, 2) > round($amount_to_pay, 2)) {
                    Log::info("Balance is greater than the amount to pay.");
                } else {
                    Log::info("Balance is equal to the amount to pay.");
                }
            } else {
                Log::error("Non-numeric value encountered for balance or amount to pay.");
            }

            // Collect payment data
            $data[]  = array(
                'date'             => $date,
                'amount_to_pay'    => floor($amount_to_pay),
                'principle_amount' => $amount_to_pay,
                'capital' => $amount_to_pay - $interest_per_term,
                'interest'         => $interest_per_term,
                'balance'          => floor($balance),
            );

            // Adjust the payment date based on the term period (+1 week or +1 month)
            if ($this->term_period == '+1 week') {
                $date = date("Y-m-d", strtotime("+1 week", strtotime($date)));
            } elseif ($this->term_period == '+1 month') {
                $date = date("Y-m-d", strtotime("+1 month", strtotime($date)));
            }

            // $principle_amount_total += $principle_amount;
            $principle_amount_total += floor($amount_to_pay);
            $interest_total += $interest_per_term;
            $amount_to_pay_total += floor($amount_to_pay);
            $capital_total += (floor($amount_to_pay) - $interest_per_term);
        }

        $data_total[] = array(
            'principle_amount_total' => $principle_amount_total,
            'interest_total'         => $interest_total,
            'amount_to_pay_total'    => $amount_to_pay_total,
            'capital_total'    => $capital_total,
        );

        session(['table_data_total' => $data_total]);

        return $data;
    }
}
