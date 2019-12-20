<?php

function getStatistics()
{
    $data = [];
    $data['users'] = [];
    $allTptp = TariffProviderTariffMatch::all();
    foreach ($allTptp->groupBy('user_id') as $each) {
        $one = [];
        $one['name'] = $each[0]->user->first_name . " " . $each[0]->user->last_name;
        $one['valid'] = $one['pending'] = $one['invalid'] = $one['total'] = $one['cash'] = 0;

        array_push($data['users'], $this->getActiveStatus($each, $one));
    }
    return $data;
}

function getActiveStatus($each, $one)
{
    foreach ($each as $single) {
        switch ($single->active_status) {
            case ActiveStatus::ACTIVE: // 1
                $one['valid']++;
                $one['cash'] += floatval(GlobalVariable::getById(GlobalVariable::STANDARDIZATION_UNIT_PRICE)->value);
                break;
            case ActiveStatus::PENDING: // 2
                $one['pending']++;
                break;
            case ActiveStatus::DELETED: // 3
                $one['invalid']++;
                break;
        }
        $one['total']++;
    }
    $one['cash'] = number_format($one['cash'], 2);

    return $one;
}