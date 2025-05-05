# INI CARA KERJA BACKEND MIDTRANS HUHUY CAPE NJIR

## Top Ups SNAP (1)

siapin postman sama ngrok

endpoint : http://localhost:8000/api/top_ups

untuk request diperlukan login terlebih dahulu dengan auth=bearer <token>

request :

```
{
    "amount" : 15000,
    "pin" : "111122",
    "payment_method_code" : "bni_va"
}
```

response :

```
{
    "success": false,
    "message": "Transaksi berhasil dibuat",
    "redirect_url": "https://app.sandbox.midtrans.com/snap/v4/redirection/95b7dca4-8c3a-406e-b198-65e17978da6a",
    "snap_token": "95b7dca4-8c3a-406e-b198-65e17978da6a"
}
```

#### Ambil redirect_url nya agar terbuka otomatis di browser

https://app.sandbox.midtrans.com/snap/v4/redirection/ed8034db-765b-4075-9e90-8877278d5c4d

#### Copy paste virtual account number kemudian paste di bagian bank va (pilih sesuai request misal bca_va ke bagian va bca)

https://simulator.sandbox.midtrans.com/

#### jika sudah beres di simulatornya, maka link snap tadi otomati berubah menjadi succes (status)

#### jika success maka api/webhooks akan merespon dan mengganti transaksi bagian status pending menjadi sukses.
