<!DOCTYPE html>

<head>
    <title>
        Home Page
    </title>
    <style>
        body {
            display: flex;
            flex-direction: column;
            width: 100vw;
            height: 100vh;
            justify-content: center;
            align-items: center;
        }
    </style>
</head>

<body>
    <h1>Choose a Transaction Option</h1>
    <div style="width: 400px; height: 220px; display: flex; flex-direction: column; justify-content: space-between;">
        <div style="width: 100%; height: 100px; display: flex; flex-direction: row; justify-content:space-between">
            <a href="bank2bank.php"> <button style="width: 190px; height:100px"> Bank To Bank</button> </a>
            <a href="bank2momo.php"> <button style="width: 190px; height:100px"> Bank To Other MoMo</button> </a>
        </div>
        <div style="width: 100%; height: 100px; display: flex; flex-direction: row; justify-content:space-between">
            <a href="bank2self.php"> <button style="width: 190px; height:100px"> Bank To My MoMo</button> </a>
            <a href="transactionHistory.php"> <button style="width: 190px; height:100px">Transaction History</button> </a>
        </div>
    </div>

</body>