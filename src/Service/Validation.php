<?php

namespace App\Service;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\FileBag;

class Validation extends AbstractController
{
    public $errorMessage;
    private $cleaning;

    public function __construct(Cleaning $cleaning)
    {
        $this->cleaning = $cleaning;
        $this->errorMessage = '';
    }

    /*
     * types : PDate, EDate, Date, In, Int, Phone, Email, Reg, MaxLen, MinLen, Equal
     */
    public function Validate($params, $values = '')
    {
        if (empty($values))
            $values = $_POST;

        $values = $this->cleaning->P2E($values);

        if (is_array($params)) {
            foreach ($params as $param) {
                // optional value

                if (substr($param['name'], 0, 1) == '[' && substr($param['name'], strlen($param['name']) - 1, 1) == ']') {

                    $param['name'] = trim($param['name'], '[');
                    $param['name'] = rtrim($param['name'], ']');

                    if (!isset($values[$param['name']]) && !isset($values[trim($param['name'], '*')]))
                        continue;

                }

                if (substr($param['name'], 0, 1) == '*') {
                    $required = true;
                    $param['name'] = trim($param['name'], '*');
                } else
                    $required = false;

                if (!isset($values[$param['name']]))
                    return $this->ReturnMessage($param, "پارامترهای ارسالی صحیح نمی باشند.");

                $val = $values[$param['name']];

                if (!is_array($val))
                    $val = [$val];

                foreach ($val as $value) {

                    if (!strlen($value)) {
                        if ($required)
                            return $this->ReturnMessage($param, "مقدار {$param['name']}  اجباری می باشد .");
                        else
                            continue;
                    }

                    $delimiter = '\/';
                    if ($param['type'] === 'PDate') {
                        if (isset($param['delimiter']) && $param['delimiter'] == '-')
                            $delimiter = '\-';
                        if ($this->PDateAction($value, $delimiter) === false)
                            return $this->ReturnMessage($param, 'لطفا تاریخ را به صورت صحیح وارد کنید');
                    } elseif ($param['type'] === 'EDate') {
                        if (isset($param['delimiter']) && $param['delimiter'] == '-')
                            $delimiter = '\-';
                        if ($this->DateAction($value, $delimiter) === false)
                            return $this->ReturnMessage($param, 'لطفا تاریخ را به صورت صحیح وارد کنید');
                    } elseif ($param['type'] === 'Date') {
                        if (isset($param['delimiter']) && $param['delimiter'] == '-')
                            $delimiter = '\-';
                        if ($this->DateAction($value, $delimiter) === false)
                            return $this->ReturnMessage($param, 'لطفا تاریخ را به صورت صحیح وارد کنید');
                    } //////////////////////////////// INT
                    elseif ($param['type'] === 'Int') {
                        if (!is_numeric($value))
                            return $this->ReturnMessage($param);
                    } //////////////////////////////// IN
                    elseif ($param['type'] === 'In') {
                        if ($this->In($value, $param['defaults']) === false)
                            return $this->ReturnMessage($param);
                    } //////////////////////////////// Persian Phone
                    elseif ($param['type'] === 'Phone') {
                        $value = $this->cleaning->P2E($value);
                        if (!preg_match('/^09[0-9]{9}$/', $value))
                            return $this->ReturnMessage($param, 'شماره همراه وارد شده صحیح نمی باشد.');
                    } //////////////////////////////// Persian Phone
                    elseif ($param['type'] === 'Email') {
                        if (!preg_match('/^[a-zA-z0-9\._-]+@.+\.[a-zA-Z]{2,4}$/', $value))
                            return $this->ReturnMessage($param, 'ایمیل وارد شده صحیح نمی باشد.');
                    } //////////////////////////////// Regular Expression
                    elseif ($param['type'] === 'Reg') {

                        if (is_array($param['pattern'])) {
                            $all_reject = true;

                            foreach ($param['pattern'] as $pattern)
                                if (preg_match($pattern, $value)) {
                                    $all_reject = false;
                                    break;
                                }

                            if ($all_reject)
                                return $this->ReturnMessage($param);

                        } else {
                            if (!preg_match($param['pattern'], $value))
                                return $this->ReturnMessage($param);
                        }
                    } elseif ($param['type'] == 'MaxLen') {
                        if ($param['len'] < mb_strlen($value))
                            return $this->ReturnMessage($param, "حداکثر تعداد کاراکتر {$param['name']} {$param['len']} حرف می باشد.");
                    } elseif ($param['type'] == 'MinLen') {
                        if ($param['len'] > mb_strlen($value))
                            return $this->ReturnMessage($param, "حداقل تعداد کاراکتر {$param['name']} {$param['len']} حرف می باشد.");
                    } elseif ($param['type'] == 'Equal') {
                        if ($value != $param['default']) // TOOD Complete
                            return $this->ReturnMessage($param, "حداقل تعداد کاراکتر {$param['name']} {$param['len']} حرف می باشد.");
                    }
                }

            }
        }
        return ['status' => true];
    }

    public function ReturnMessage($param, $message = '')
    {

        if (!isset($param['msg'])) {
            if (empty($message)) {
                $this->errorMessage = "مقدار {$param['name']} صحیح نمی باشد.";
            } else {
                $this->errorMessage = $message;
            }
        } else
            $this->errorMessage = $param['msg'];

        return false;
    }

    public function PDateAction($Dates, $delimiter)
    {
        if (!is_array($Dates))
            $Dates = [$Dates];

        foreach ($Dates as $date) {
            if (!preg_match('/^1[0-9]{3}' . $delimiter . '[0-9]{2}' . $delimiter . '[0-9]{2}$/', $date))
                return false;
        }
        return true;
    }

    public function EDateAction($Dates, $delimiter)
    {
        if (!is_array($Dates))
            $Dates = [$Dates];

        foreach ($Dates as $date) {
            if (!preg_match('/^[1-2]{1}[0-9]{3}' . $delimiter . '[0-9]{2}' . $delimiter . '[0-9]{2}$/', $date))
                return false;
        }
        return true;
    }

    public function DateAction($Dates, $delimiter)
    {
        if (!is_array($Dates))
            $Dates = [$Dates];

        foreach ($Dates as $date) {
            if (!$this->EDateAction($date, $delimiter) && !$this->PDateAction($date, $delimiter))
                return false;
        }

        return true;
    }

    public function In($value, $defaults)
    {
        if (!in_array($value, $defaults))
            return false;
        return true;

    }

    public function ValidateFiles($params, FileBag $files) //TODO complete validation by type max size count and ...
    {
        if (is_array($params)) {
            foreach ($params as $param) {
                if ($param['type'] == 'globals') {

                }

                if (substr($param['name'], 0, 1) == '[' && substr($param['name'], strlen($param['name']) - 1, 1) == ']') {

                    $param['name'] = trim($param['name'], '[');
                    $param['name'] = rtrim($param['name'], ']');

                    if (!isset($values[$param['name']]) && !isset($values[trim($param['name'], '*')]))
                        continue;

                }

                if (substr($param['name'], 0, 1) == '*') {
                    $required = true;
                    $param['name'] = trim($param['name'], '*');
                } else
                    $required = false;

                if (!isset($values[$param['name']]))
                    return ['message' => 'پارامترهای ارسالی صحیح نمی باشند.', 'status' => false];

                $val = $values[$param['name']];

                if (!is_array($val))
                    $val = [$val];

                foreach ($files as $file) {

                    if (!strlen($value)) {
                        if ($required)
                            return $this->ReturnMessage($param, "مقدار {$param['name']}  اجباری می باشد .");
                        else
                            continue;
                    }

                    $delimiter = '\/';
                    if ($param['type'] === 'image') {
                        if (isset($param['delimiter']) && $param['delimiter'] == '-')
                            $delimiter = '\-';
                        if ($this->PDateAction($value, $delimiter) === false)
                            return $this->ReturnMessage($param, 'لطفا تاریخ را به صورت صحیح وارد کنید');
                    }
                }

            }
        }
        return ['status' => true];
    }


}
