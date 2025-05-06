# **REST API Kawan-bayar**

Backend Kawan bayar yang menggunakan laravel 10. Untuk mengakses api nya gunakan authentikasi bearer token. Test on postman.

URL lengkap diberikan dalam respons, respons tersebut akan ditampilkan seolah-olah layanan tersebut berjalan pada 'http://localhost:8000/'.

## Tech Stack

**Language:** PHP 8.2\
**Server:** Laravel \
**Framework:** Laravel 10 \
**ORM:** Elaquent \
**Database:** Mysql \
**Service:**

-   mailtrap
-   midtrans

## Akses Admin

### Filament for admin (Finish)

![Screenshot 2025-05-03 220703](https://github.com/user-attachments/assets/3e34de96-eff9-4bf3-aa19-1c8e461eea19)
![Screenshot 2025-05-03 220740](https://github.com/user-attachments/assets/67d69551-e896-49c6-a091-bc732a434842)

### Hak Akses

### Tabel Hak Akses Admin

| No  | Modul / Fitur            | Deskripsi Akses                                | Hasil Pengujian |
| --- | ------------------------ | ---------------------------------------------- | --------------- |
| 1   | Sign In                  | Admin dapat masuk ke dalam sistem              | ✅ Lulus        |
| 2   | Manajemen Payment Method | Tambah, ubah, dan hapus data metode pembayaran | ✅ Lulus        |
| 3   | Manajemen Operator Kartu | Kelola data operator kartu                     | ✅ Lulus        |
| 4   | Manajemen Tipe Transaksi | Kelola data jenis/tipe transaksi               | ✅ Lulus        |
| 5   | Manajemen Pengguna       | Tambah, ubah, dan hapus data pengguna          | ✅ Lulus        |
| 6   | Manajemen Produk         | Kelola data produk (CRUD)                      | ✅ Lulus        |
| 7   | Manajemen Transaksi      | Melihat dan memproses data transaksi           | ✅ Lulus        |
| 8   | Laporan Transfer         | Melihat laporan transfer dan data lainnya      | ✅ Lulus        |

## Akses User

### Spek API USER - API Structure

| Routes                                 | HTTP | Deskrips                                           | Dibuat | Hasil Test |
| -------------------------------------- | ---- | -------------------------------------------------- | ------ | ---------- |
| `/api/login`                           | POST | signin                                             | Ya     | Ya         |
| `/api/register`                        | POST | signup                                             | Ya     | Ya         |
| `/api/forgot-password`                 | POST | link lupa password                                 | Ya     | Ya         |
| `/api/reset-password`                  | POST | reset password                                     | Ya     | Ya         |
| `/api/user/profile`                    | GET  | user profile                                       | Ya     | Ya         |
| `/api/user/update-profile`             | PUT  | user update profile                                | Ya     | Ya         |
| `/api/user/update-pin`                 | PUT  | Update pin                                         | Ya     | Ya         |
| `/api/transfer`                        | POST | Transfer ke sesama user                            | Ya     | Ya         |
| `/api/top_ups`                         | POST | Top up melalui midtrans                            | Ya     | Ya         |
| `/api/user/transaction-history`        | GET  | Melihat segala transaksi berdasarkan user          | Ya     | Ya         |
| `/api/user/transaction-history/search` | GET  | seacrh transaksi berdasarkan login dan kode        | Ya     | Ya         |
| `/api/user/transfer-history`           | GET  | Melihat segala transaksi berdasarkan user          | Ya     | Ya         |
| `/api/providers`                       | GET  | Nampilin provider misal telkomsel,tri,dll          | Ya     | Ya         |
| `/api/internet/{provider}`             | GET  | Nampilin paket data setiap provider                | Ya     | Ya         |
| `/api/internet-payment`                | POST | Bayar internet bisa pake midtrans atau kawan bayar | Ya     | Ya         |
| `/api/user/internet-history`           | GET  | Nampilin history transaksi internet                | Ya     | Ya         |

## Auth Endpoints

Endpoint untuk authentikasi:

### Login

Path : `/login` \
Method : `POST`

#### endpoint

```
curl http://localhost:8000/login
```

Request dari client (json)

```
{
  "email" : "fuad_updated@gmail.com",
  "password" : "newpassword123"
}
```

#### Successful Responses

Code : `200 OK` \
Content examples

```json
{
    "id": 1,
    "name": "Fuad G. Updated",
    "email": "fuad_updated@gmail.com",
    "username": "fuadgrimaldi",
    "verified": 1,
    "profile_picture": "",
    "ktp": "",
    "created_at": "2025-05-01T20:11:08.000000Z",
    "updated_at": "2025-05-03T13:27:13.000000Z",
    "balance": 20000,
    "card_number": "91232939213",
    "pin": "111122",
    "token": "eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJodHRwOi8vbG9jYWxob3N0OjgwMDAvYXBpL2xvZ2luIiwiaWF0IjoxNzQ2MjgxNjEzLCJleHAiOjE3NDYyODUyMTMsIm5iZiI6MTc0NjI4MTYxMywianRpIjoiZkJMM3Ewc2hSelpFVGc1VyIsInN1YiI6IjEiLCJwcnYiOiIyM2JkNWM4OTQ5ZjYwMGFkYjM5ZTcwMWM0MDA4NzJkYjdhNTk3NmY3In0.w5J8NdreZLwFh62cArC22mZ2E4Emz6CPCtcJsm8H0Ro",
    "token_expires_in": 3600,
    "token_type": "bearer"
}
```

#### Error Responses

Code : `500 Internal Server Error` atau `404 Email not found `

### Register

Register otomatis membuat card_number wallet dengan saldo 0

Path : `/register` \
Method : `POST`

#### endpoint

```
curl http://localhost:8000/register
```

Request dari client (json)

```
{
  "name": "Asep",
  "email": "asep@example.com",
  "password": "password123",
  "pin": "121212"
}
```

#### Successful Responses

Code : `201 Created` \
Content examples

```json
{
    "id": 6,
    "name": "Asep",
    "email": "asep@example.com",
    "username": "Asep",
    "verified": 0,
    "profile_picture": "",
    "ktp": "",
    "created_at": "2025-05-03T13:48:11.000000Z",
    "updated_at": "2025-05-03T13:48:11.000000Z",
    "balance": 0,
    "card_number": "9705481146037640",
    "pin": "121212",
    "token": "eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJodHRwOi8vbG9jYWxob3N0OjgwMDAvYXBpL3JlZ2lzdGVyIiwiaWF0IjoxNzQ2MjgwMDkyLCJleHAiOjE3NDYyODM2OTIsIm5iZiI6MTc0NjI4MDA5MiwianRpIjoicDN4cUNFWFp6d2pwS292QSIsInN1YiI6IjYiLCJwcnYiOiIyM2JkNWM4OTQ5ZjYwMGFkYjM5ZTcwMWM0MDA4NzJkYjdhNTk3NmY3In0.ueN5a-hRiLjDe95wjWf78fJ5mr3Z25fcyH8BnFOOGIk",
    "token_expires_in": 3600,
    "token_type": "bearer"
}
```

#### Error Responses

Code : `500 Internal Server Error`

### Reset Password

Reset password kalo udah lupa

Path : `/reset-password` \
Method : `POST`

#### endpoint

```
curl http://localhost:8000/reset-password
```

Request dari client (json)

```
{
  "token": "d3c878ec6971dbdee859f7301a14673c9639838e66122651d79546704695a943",
  "email": "fuadgrimaldi145@gmail.com",
  "password": "12345678",
  "password_confirmation": "12345678"
}
```

#### Successful Responses

Code : `200 OK` \
Content examples

```json
{
    "meta": {
        "code": 200,
        "status": "success",
        "message": "Your password has been reset."
    },
    "data": null
}
```

#### Error Responses

Code : `500 Internal Server Error`

### Forgot-Password

Fitur kalo lupa password

Path : `/forgot-password` \
Method : `POST`

#### endpoint

```
curl http://localhost:8000/forgot-password
```

Request dari client (json)

```
{
  "email": "fuadgrimaldi123@gmail.com"
}
```

#### Successful Responses

Code : `200 OK` \
Content examples

```json
{
    "meta": {
        "code": 200,
        "status": "success",
        "message": "We have emailed your password reset link."
    },
    "data": null
}
```

#### Error Responses

Code : `500 Internal Server Error`

## User Endpoints

### Get User Profil

tidak ada params id karena berdasarkan yang login

Path : `/user/profile` \
Method : `GET`

#### Request

```
curl http://localhost:8000/api/user/profile
```

#### Successful Responses

Code : `200 OK` \
Content examples

```json
{
    "meta": {
        "code": 200,
        "status": "success",
        "message": "User retrieved successfully"
    },
    "data": {
        "id": 1,
        "email": "fuad_updated@gmail.com",
        "name": "Fuad G. Updated",
        "username": "fuadgrimaldi",
        "verified": 1,
        "profile_picture": null,
        "ktp": null,
        "wallet": {
            "balance": 20000,
            "card_number": "91232939213"
        },
        "created_at": "2025-05-01 20:11:08",
        "updated_at": "2025-05-03 13:27:13"
    }
}
```

#### Error Responses

Code : `500 Internal Server Error` or `404 Not found`

### Update A User profile

Path : `user/update-profile` \
Method : `PUT`

#### Request

```
curl http://localhost:8000/api/user/update-profile
```

This is a client request to update a user profile

```json
{
    "name": "Fuad G. Updated",
    "email": "fuad_updated@gmail.com",
    "username": "fuadgrimaldi",
    "password": "newpassword123",
    "password_confirmation": "newpassword123"
}
```

#### Successful Responses

Code : `200 OK` \
Content examples

```json
{
    "meta": {
        "code": 200,
        "status": "success",
        "message": "User updated successfully"
    },
    "data": {
        "id": 1,
        "email": "fuad_updated@gmail.com",
        "name": "Fuad G. Updated",
        "username": "fuadgrimaldi",
        "verified": 1,
        "profile_picture": null,
        "ktp": null,
        "created_at": "2025-05-01 20:11:08",
        "updated_at": "2025-05-03 13:27:13"
    }
}
```

#### Error Responses

Code : `500 Internal Server Error` or `400 Bad request`

### Update user pin wallet

Path : `user/update-pin` \
Method : `PUT`

#### Request

```
curl http://localhost:8000/api/user/update-pin
```

This is a client request to update a user profile

```json
{
    "pin": "111111",
    "new_pin": "111122",
    "confirm_new_pin": "111122"
}
```

#### Successful Responses

Code : `200 OK` \
Content examples

```json
{
    "meta": {
        "code": 200,
        "status": "success",
        "message": "Pin wallet updated successfully"
    },
    "data": null
}
```

#### Error Responses

Code : `500 Internal Server Error` or `400 Bad request`

### Transfer customer-to-customer

Path : `/transfer` \
Method : `POST`

#### Request

```
curl http://localhost:8000/api/transfer
```

request dari sisi client seperti ini

```json
{
    "amount": 10000,
    "pin": "111122",
    "send_to": "bel" // bisa username atau card_number tujuan
}
```

#### Successful Responses

Code : `200 Created` \
Content examples

```json
{
    "message": "Transfer success"
}
```

#### Error Responses

Code : `500 Internal Server Error` or `400 Bad request`

### Top Ups

Deskripsi lengkap [klik](https://github.com/FuadGrimaldi/BE-KawanBayar/blob/main/docs%20api/API%20MIDTRANS/readme.md)

Path : `/Top Up` \
Method : `POST`

#### Request

```
curl http://localhost:8000/api/top_ups
```

request dari sisi client seperti ini

```json
{
    "amount": 15000,
    "pin": "111122",
    "payment_method_code": "bni_va"
}
```

#### Successful Responses

Code : `200 Created` \
Content examples

```json
{
    "success": false,
    "message": "Transaksi berhasil dibuat",
    "redirect_url": "https://app.sandbox.midtrans.com/snap/v4/redirection/95b7dca4-8c3a-406e-b198-65e17978da6a",
    "snap_token": "95b7dca4-8c3a-406e-b198-65e17978da6a"
}
```

#### Error Responses

Code : `500 Internal Server Error` or `400 Bad request`

### Get Transaction by User Login

tidak ada params id karena berdasarkan yang login

Path : `/user/transaction-history` \
Method : `GET`

#### Request

```
curl http://localhost:8000/api/user/transaction-history
```

#### Successful Responses

Code : `200 OK` \
Content examples

```json
{
    "meta": {
        "code": 200,
        "status": "success",
        "message": "All transactions retrieved successfully"
    },
    "data": [
        {
            "id": 2,
            "user": {
                "email": "bel@gmail.com",
                "name": "Abelz",
                "username": "bel"
            },
            "transaction_type": {
                "name": "Receive",
                "code": "receive"
            },
            "payment_method": {
                "name": "Bank BCA",
                "code": "bca_va"
            },
            "product": {
                "name": "ciki",
                "description": "ciki berhadiah",
                "price": 10000
            },
            "amount": 10000,
            "transaction_code": "NVXCOETLVB",
            "description": "Receive funds toadmin",
            "status": "success",
            "transaction_date": "2025-05-02 05:29:54"
        },
        {
            "id": 21,
            "user": {
                "email": "bel@gmail.com",
                "name": "Abelz",
                "username": "bel"
            },
            "transaction_type": {
                "name": "Receive",
                "code": "receive"
            },
            "payment_method": {
                "name": "Bank BCA",
                "code": "bca_va"
            },
            "product": null,
            "amount": 10000,
            "transaction_code": "0M0IW2PKLF",
            "description": "Receive funds tofuadgrimaldi",
            "status": "success",
            "transaction_date": "2025-05-03 14:14:25"
        }
    ]
}
```

#### Error Responses

Code : `500 Internal Server Error` or `404 Not found`

### Get Transaction by User Login seacrh kode

tidak ada params id karena berdasarkan yang login

Path : `/user/transaction-history/search` \
Method : `GET`
Params : `transaction_code`

#### Request

```
http://localhost:8000/api/user/transaction-history/search?transaction_code=NVXCOETLVB
```

#### Successful Responses

Code : `200 OK` \
Content examples

```json
{
    "meta": {
        "code": 200,
        "status": "success",
        "message": "Transaction retrieved successfully"
    },
    "data": {
        "id": 2,
        "user": {
            "email": "bel@gmail.com",
            "name": "Abelz",
            "username": "bel"
        },
        "transaction_type": {
            "name": "Receive",
            "code": "receive"
        },
        "payment_method": {
            "name": "Bank BCA",
            "code": "bca_va"
        },
        "product": {
            "name": "ciki",
            "description": "ciki berhadiah",
            "price": 10000
        },
        "amount": 10000,
        "transaction_code": "NVXCOETLVB",
        "description": "Receive funds toadmin",
        "status": "success",
        "transaction_date": "2025-05-02 05:29:54"
    }
}
```

#### Error Responses

Code : `500 Internal Server Error` or `404 Not found`

### Get Transfer by User Login

tidak ada params id karena berdasarkan yang login

Path : `/user/transfer-history` \
Method : `GET`

#### Request

```
curl http://localhost:8000/api/user/transfers-history
```

#### Successful Responses

Code : `200 OK` \
Content examples

```json
{
    "meta": {
        "code": 200,
        "status": "success",
        "message": "All transfer history retrieved successfully"
    },
    "data": [
        {
            "id": 1,
            "sender_id": 1,
            "receiver_id": 3,
            "transaction_code": "NVXCOETLVB",
            "created_at": "2025-05-02T05:29:54.000000Z",
            "updated_at": "2025-05-02T05:29:54.000000Z",
            "sender": {
                "id": 1,
                "username": "fuadgrimaldi"
            },
            "receiver": {
                "id": 3,
                "username": "bel"
            }
        },
        {
            "id": 3,
            "sender_id": 1,
            "receiver_id": 3,
            "transaction_code": "0M0IW2PKLF",
            "created_at": "2025-05-03T14:14:25.000000Z",
            "updated_at": "2025-05-03T14:14:25.000000Z",
            "sender": {
                "id": 1,
                "username": "fuadgrimaldi"
            },
            "receiver": {
                "id": 3,
                "username": "bel"
            }
        }
    ]
}
```

#### Error Responses

Code : `500 Internal Server Error` or `404 Not found`
