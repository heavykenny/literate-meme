Run Composer to download dependencies

``composer install``

``//Request Body for Requesting Account.``

``$body = [
    "accountReference" => "abc123",
    "accountName" => "Test Reserved Account",
    "currencyCode" => "NGN",
    "contractCode" => "8389328412",
    "customerEmail" => "test@tester.com",
    "incomeSplitConfig" => [
        [
            "subAccountCode" => "MFY_SUB_319452883228",
            "feePercentage" => 10.5,
            "splitPercentage" => 20,
            "feeBearer" => true
        ]
    ]
];``

``//Call to reserve an account.``

``$reserveAccount = $endpoint->reserveAnAccount($body);
json_decode($reserveAccount);``

``//Call to deallocate an account.``
``$accountNumber = "9900725554";``

``$deallocateAccount = $endpoint->deallocateAccount($accountNumber);
json_decode($deallocateAccount);``


``//Call to Get transaction status.``
``$transactionCode = "MNFY|20191212112508|000291";
$transactionStatus = $endpoint->transactionStatus($transactionCode);
json_decode($transactionStatus);``