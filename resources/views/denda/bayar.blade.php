<!DOCTYPE html>
<html>
<head>
    <title>Pembayaran Denda</title>
    <script src="https://app.sandbox.midtrans.com/snap/snap.js"
        data-client-key="{{ config('midtrans.client_key') }}"></script>
</head>
<body>

<button id="pay-button">Bayar Denda</button>

<script>
document.getElementById('pay-button').onclick = function () {
    snap.pay('{{ $snapToken }}', {
        onSuccess: function(result){
            alert("Pembayaran berhasil");
            console.log(result);
        },
        onPending: function(result){
            alert("Menunggu pembayaran");
            console.log(result);
        },
        onError: function(result){
            alert("Pembayaran gagal");
            console.log(result);
        }
    });
};
</script>

</body>
</html>
