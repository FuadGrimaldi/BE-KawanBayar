# INI CARA KERJA BACKEND MIDTRANS 
Webhook digunakan untuk update status transaksi setelah pembayaran midtrans berhasil.

endpoint webhook : http://<harus public/ngrok>/api/webhooks

karena webhook masih menggunakan ngrok jadi harus ngubah manual endpoint di midtransnya (abaikan dlu karena hanya berpengaruh ke status transaksi di database)

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
![Screenshot 2025-05-05 172425](https://github.com/user-attachments/assets/28f1e5ef-10cd-4028-94d9-e56a3202cf44)

#### Copy paste virtual account number kemudian paste di bagian bank va (pilih sesuai request misal bca_va ke bagian va bca)

https://simulator.sandbox.midtrans.com/
![Screenshot 2025-05-05 172837](https://github.com/user-attachments/assets/787b1042-dc0c-439f-bd0b-c18d06bd3fca)

#### jika sudah beres di simulatornya, maka link snap tadi otomati berubah menjadi succes (status)
![Screenshot 2025-05-05 172858](https://github.com/user-attachments/assets/a838d2c6-fd62-48c4-a9be-b937a4721d14)

#### jika success maka api/webhooks akan merespon dan mengganti transaksi bagian status pending menjadi sukses.
