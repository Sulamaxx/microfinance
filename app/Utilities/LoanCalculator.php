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
    // private $late_payment_penalties;
    private $interest_type;

    public function __construct($apply_amount, $first_payment_date, $interest_rate, $term, $term_period, $interest_type)
    {
        $this->apply_amount           = $apply_amount;
        $this->first_payment_date     = $first_payment_date;
        $this->interest_rate          = $interest_rate;
        $this->term                   = $term;
        $this->term_period            = $term_period;
        // $this->late_payment_penalties = $late_payment_penalties;
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
        if ($this->term_period == '+1 week') {
            $total_interest = $daily_interest_rate * $term * $apply_amount * 7;
        } else {
            $total_interest = $daily_interest_rate * $term * $apply_amount * 28;
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

                Log::info($balance);
                Log::info($amount_to_pay);

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
            // $data[]  = array(
            //     'date'             => $date,
            //     'amount_to_pay'    => floor($amount_to_pay),
            //     'principle_amount' => $principle_amount,
            //     'capital' => $principle_amount - $interest_per_term,
            //     'interest'         => $interest_per_term,
            //     'balance'          => floor($balance),
            // );

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



    // version 2
    // public function get_fixed_rate()
    // {
    //     // Convert interest rate to a fraction
    //     $interest_rate = $this->interest_rate / 100;
    //     $apply_amount = $this->apply_amount;  // Loan amount
    //     $term = $this->term;  // Number of terms
    //     $late_payment_penalties = $this->late_payment_penalties / 100;

    //     // Determine the interest rate per day based on interest type
    //     switch ($this->interest_type) {
    //         case 'weekly':
    //             $daily_interest_rate = $interest_rate / 7;  // Convert to weekly interest and then to daily
    //             break;

    //         case 'monthly':
    //             $daily_interest_rate = $interest_rate / 30;  // Convert to monthly interest and then to daily
    //             break;

    //         case 'yearly':
    //         default:
    //             $daily_interest_rate = $interest_rate / 365;  // Yearly interest converted to daily
    //             break;
    //     }

    //     // Calculate total payable amount (principal + interest)
    //     $total_interest = $daily_interest_rate * $term * $apply_amount;
    //     $this->payable_amount = $total_interest + $apply_amount;

    //     // Prepare calculation variables
    //     $date = $this->first_payment_date;
    //     $principle_amount = $apply_amount / $term;
    //     $interest_per_term = $total_interest / $term;
    //     $amount_to_pay = $principle_amount + $interest_per_term;  // Amount to pay per term
    //     $balance = $this->payable_amount;
    //     $penalty = $late_payment_penalties * $apply_amount;

    //     $data = array();

    //     // Loop through each term to calculate payments
    //     for ($i = 0; $i < $term; $i++) {
    //         $balance = $balance - floor($amount_to_pay);  // Subtract paid amount from balance

    //         // Handle final payment if balance is less than amount to pay
    //         if ($balance < $amount_to_pay) {
    //             $amount_to_pay = $amount_to_pay + $balance;  // Adjust final payment
    //             $balance = 0;
    //         }

    //         // Collect payment data
    //         $data[]  = array(
    //             'date'             => $date,
    //             'amount_to_pay'    => floor($amount_to_pay),
    //             'penalty'          => $penalty,
    //             'principle_amount' => $principle_amount,
    //             'interest'         => $interest_per_term,
    //             'balance'          => floor($balance),
    //         );

    //         // Adjust the payment date based on the term period (+1 week or +1 month)
    //         if ($this->term_period == '+1 week') {
    //             $date = date("Y-m-d", strtotime("+1 week", strtotime($date)));
    //         } elseif ($this->term_period == '+1 month') {
    //             $date = date("Y-m-d", strtotime("+1 month", strtotime($date)));
    //         }
    //     }

    //     return $data;
    // }

    //version 1
    // public function get_fixed_rate()
    // {
    //     $this->payable_amount = ((($this->interest_rate / 100) * $this->apply_amount) * $this->term) + $this->apply_amount;
    //     $date                 = $this->first_payment_date;
    //     $principle_amount     = $this->apply_amount / $this->term;
    //     $amount_to_pay        = $principle_amount + (($this->interest_rate / 100) * $this->apply_amount);
    //     $interest             = (($this->interest_rate / 100) * $this->apply_amount);
    //     $balance              = $this->payable_amount;
    //     $penalty              = (($this->late_payment_penalties / 100) * $this->apply_amount);

    //     $data = array();
    //     for ($i = 0; $i < $this->term; $i++) {

    //         $balance = $balance - floor($amount_to_pay);
    //         if ($balance < $amount_to_pay) {
    //             $amount_to_pay = $amount_to_pay + $balance;
    //             $balance = 0;
    //         }
    //         $data[]  = array(
    //             'date'             => $date,
    //             'amount_to_pay'    => floor($amount_to_pay),
    //             'penalty'          => $penalty,
    //             'principle_amount' => $principle_amount,
    //             'interest'         => $interest,
    //             'balance'          => $balance,
    //         );

    //         $date = date("Y-m-d", strtotime($this->term_period, strtotime($date)));
    //     }

    //     return $data;
    // }

    // .....................................................................................................


    // public function get_flat_rate()
    // {
    //     $this->payable_amount = (($this->interest_rate / 100) * $this->apply_amount) + $this->apply_amount;

    //     $date             = $this->first_payment_date;
    //     $principle_amount = $this->apply_amount / $this->term;
    //     $amount_to_pay    = $principle_amount + (($this->interest_rate / 100) * $principle_amount);
    //     $interest         = (($this->interest_rate / 100) * $this->apply_amount) / $this->term;
    //     $balance          = $this->payable_amount;
    //     $penalty          = (($this->late_payment_penalties / 100) * $this->apply_amount);

    //     $data = array();
    //     for ($i = 0; $i < $this->term; $i++) {
    //         $balance = $balance - $amount_to_pay;
    //         $data[]  = array(
    //             'date'             => $date,
    //             'amount_to_pay'    => $amount_to_pay,
    //             'penalty'          => $penalty,
    //             'principle_amount' => $principle_amount,
    //             'interest'         => $interest,
    //             'balance'          => $balance,
    //         );

    //         $date = date("Y-m-d", strtotime($this->term_period, strtotime($date)));
    //     }

    //     return $data;
    // }

    // public function get_mortgage() {
    //     $interestRate = $this->interest_rate / 100;

    //     //Calculate the per month interest rate
    //     $monthlyRate = $interestRate / 12;

    //     //Calculate the payment
    //     $payment = $this->apply_amount * ($monthlyRate / (1 - pow(1 + $monthlyRate, -$this->term)));

    //     $this->payable_amount = $payment * $this->term;

    //     $date    = $this->first_payment_date;
    //     $balance = $this->apply_amount;
    //     $penalty = (($this->late_payment_penalties / 100) * $this->apply_amount);

    //     $data = array();
    //     for ($count = 0; $count < $this->term; $count++) {
    //         $interest         = $balance * $monthlyRate;
    //         $monthlyPrincipal = $payment - $interest;
    //         $amount_to_pay    = $interest + $monthlyPrincipal;

    //         $balance = $balance - $monthlyPrincipal;
    //         $data[]  = array(
    //             'date'             => $date,
    //             'amount_to_pay'    => $amount_to_pay,
    //             'penalty'          => $penalty,
    //             'principle_amount' => $monthlyPrincipal,
    //             'interest'         => $interest,
    //             'balance'          => $balance,
    //         );

    //         $date = date("Y-m-d", strtotime($this->term_period, strtotime($date)));
    //     }

    //     return $data;
    // }

    // public function get_one_time() {
    //     $this->payable_amount = (($this->interest_rate / 100) * $this->apply_amount) + $this->apply_amount;
    //     $date                 = $this->first_payment_date;
    //     $principle_amount     = $this->apply_amount;
    //     $amount_to_pay        = $principle_amount + (($this->interest_rate / 100) * $principle_amount);
    //     $interest             = (($this->interest_rate / 100) * $this->apply_amount);
    //     $balance              = $this->payable_amount;
    //     $penalty              = (($this->late_payment_penalties / 100) * $this->apply_amount);

    //     $data    = array();
    //     $balance = $balance - $amount_to_pay;
    //     $data[]  = array(
    //         'date'             => $date,
    //         'amount_to_pay'    => $amount_to_pay,
    //         'penalty'          => $penalty,
    //         'principle_amount' => $principle_amount,
    //         'interest'         => $interest,
    //         'balance'          => $balance,
    //     );

    //     $date = date("Y-m-d", strtotime($this->term_period, strtotime($date)));

    //     return $data;
    // }

    // public function get_reducing_amount() {
    //     $interestRate = $this->interest_rate / 100;

    //     //Calculate the per month interest rate
    //     $monthlyRate = $interestRate / 12;

    //     //Calculate the payment
    //     $payment = $this->apply_amount * ($monthlyRate / (1 - pow(1 + $monthlyRate, -$this->term)));
    //     $monthlyPrincipal = $this->apply_amount / $this->term;

    //     $this->payable_amount = $payment * $this->term;

    //     $date    = $this->first_payment_date;
    //     $balance = $this->apply_amount;
    //     $penalty = (($this->late_payment_penalties / 100) * $this->apply_amount);

    //     $data = array();
    //     for ($count = 0; $count < $this->term; $count++) {
    //         $interest         = $balance * $monthlyRate;
    //         $amount_to_pay    = $interest + $monthlyPrincipal;

    //         $balance = $balance - $monthlyPrincipal;
    //         $data[]  = array(
    //             'date'             => $date,
    //             'amount_to_pay'    => $amount_to_pay,
    //             'penalty'          => $penalty,
    //             'principle_amount' => $monthlyPrincipal,
    //             'interest'         => $interest,
    //             'balance'          => $balance,
    //         );

    //         $date = date("Y-m-d", strtotime($this->term_period, strtotime($date)));
    //     }

    //     return $data;
    // }

}
