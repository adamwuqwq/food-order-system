<!-- Generator: Widdershins v4.0.1 -->

<h1 id="-">飲食店向け簡易オーダーシステム v1.0</h1>

> Scroll down for code samples, example requests and responses. Select a language for code samples from the tabs above or the mobile navigation menu.

Base URLs:

* <a href="http://localhost:3000">http://localhost:3000</a>

# Authentication

- HTTP Authentication, scheme: bearer 

<h1 id="--management">management</h1>

管理者（システム提供、オーナー、レジ、厨房）側

## adminLogin

<a id="opIdadminLogin"></a>

`POST /management/login`

*管理者(system, owner, counter, kitchen)ログイン*

> Body parameter

```json
{
  "login_id": "string",
  "password": "string"
}
```

<h3 id="adminlogin-parameters">Parameters</h3>

|Name|In|Type|Required|Description|
|---|---|---|---|---|
|body|body|object|false|none|
|» login_id|body|string|true|ログイン用ユーザーID|
|» password|body|string|true|ログイン用パスワード|

> Example responses

> 200 Response

```json
{
  "request_token": "string"
}
```

<h3 id="adminlogin-responses">Responses</h3>

|Status|Meaning|Description|Schema|
|---|---|---|---|
|200|[OK](https://tools.ietf.org/html/rfc7231#section-6.3.1)|OK|Inline|
|400|[Bad Request](https://tools.ietf.org/html/rfc7231#section-6.5.1)|Bad Request (リクエストに不備がある)|None|
|401|[Unauthorized](https://tools.ietf.org/html/rfc7235#section-3.1)|Unauthorized (ユーザーネームまたはパスワードが無効)|None|

<h3 id="adminlogin-responseschema">Response Schema</h3>

Status Code **200**

|Name|Type|Required|Restrictions|Description|
|---|---|---|---|---|
|» request_token|string|false|none|JWTトークン|

<aside class="success">
This operation does not require authentication
</aside>

## restaurantList

<a id="opIdrestaurantList"></a>

`GET /management/restaurant`

*店舗の一覧取得 (ownerは自分の店舗のみ取得可能)*

<h3 id="restaurantlist-parameters">Parameters</h3>

|Name|In|Type|Required|Description|
|---|---|---|---|---|
|authorization|header|string|true|/management/login で取得したトークン|

> Example responses

> 200 Response

```json
{
  "restaurants": [
    {
      "restaurant_id": 0,
      "restaurant_name": "string",
      "owner_admin_id": 0,
      "restaurant_image_url": "string"
    }
  ]
}
```

<h3 id="restaurantlist-responses">Responses</h3>

|Status|Meaning|Description|Schema|
|---|---|---|---|
|200|[OK](https://tools.ietf.org/html/rfc7231#section-6.3.1)|OK|Inline|
|401|[Unauthorized](https://tools.ietf.org/html/rfc7235#section-3.1)|Unauthorized (トークンが無効)|None|
|403|[Forbidden](https://tools.ietf.org/html/rfc7231#section-6.5.3)|Permission Denied (counter, kitchenはアクセス権がない)|None|

<h3 id="restaurantlist-responseschema">Response Schema</h3>

Status Code **200**

|Name|Type|Required|Restrictions|Description|
|---|---|---|---|---|
|» restaurants|[object]|false|none|none|
|»» restaurant_id|integer|false|none|none|
|»» restaurant_name|string|false|none|none|
|»» owner_admin_id|integer|false|none|none|
|»» restaurant_image_url|string|false|none|none|

<aside class="warning">
To perform this operation, you must be authenticated by means of one of the following methods:
bearerAuth
</aside>

## restaurantSignUp

<a id="opIdrestaurantSignUp"></a>

`POST /management/restaurant`

*新規店舗登録*

> Body parameter

```json
{
  "owner_admin_id": 0,
  "restaurant_name": "string",
  "restaurant_address": "string",
  "restaurant_image_url": "string"
}
```

<h3 id="restaurantsignup-parameters">Parameters</h3>

|Name|In|Type|Required|Description|
|---|---|---|---|---|
|authorization|header|string|true|/management/login で取得したトークン|
|body|body|object|false|none|
|» owner_admin_id|body|integer|true|オーナーの管理者ID|
|» restaurant_name|body|string|true|店舗の名称|
|» restaurant_address|body|string|false|none|
|» restaurant_image_url|body|string|false|none|

> Example responses

> 200 Response

```json
{
  "restaurant_id": 0
}
```

<h3 id="restaurantsignup-responses">Responses</h3>

|Status|Meaning|Description|Schema|
|---|---|---|---|
|200|[OK](https://tools.ietf.org/html/rfc7231#section-6.3.1)|OK|Inline|
|400|[Bad Request](https://tools.ietf.org/html/rfc7231#section-6.5.1)|Bad Request (リクエストに不備がある)|None|
|401|[Unauthorized](https://tools.ietf.org/html/rfc7235#section-3.1)|Unauthorized (トークンが無効)|None|
|403|[Forbidden](https://tools.ietf.org/html/rfc7231#section-6.5.3)|Permission Denied (owner, counter, kitchenはアクセス権がない)|None|

<h3 id="restaurantsignup-responseschema">Response Schema</h3>

Status Code **200**

|Name|Type|Required|Restrictions|Description|
|---|---|---|---|---|
|» restaurant_id|integer|false|none|店舗のID|

<aside class="warning">
To perform this operation, you must be authenticated by means of one of the following methods:
bearerAuth
</aside>

## restaurantGet

<a id="opIdrestaurantGet"></a>

`GET /management/restaurant/{restaurant_id}`

*指定した店舗情報取得*

<h3 id="restaurantget-parameters">Parameters</h3>

|Name|In|Type|Required|Description|
|---|---|---|---|---|
|authorization|header|string|true|/management/login で取得したトークン|
|restaurant_id|path|integer|true|情報取得したい店舗のID|

> Example responses

> 200 Response

```json
{
  "restaurant_id": 0,
  "restaurant_name": "string",
  "owner_admin_id": 0,
  "owner_name": "string",
  "restaurant_address": "string",
  "restaurant_image_url": "string"
}
```

<h3 id="restaurantget-responses">Responses</h3>

|Status|Meaning|Description|Schema|
|---|---|---|---|
|200|[OK](https://tools.ietf.org/html/rfc7231#section-6.3.1)|OK|Inline|
|400|[Bad Request](https://tools.ietf.org/html/rfc7231#section-6.5.1)|Bad Request (リクエストに不備がある)|None|
|401|[Unauthorized](https://tools.ietf.org/html/rfc7235#section-3.1)|Unauthorized (トークンが無効)|None|
|403|[Forbidden](https://tools.ietf.org/html/rfc7231#section-6.5.3)|Permission Denied (owner, kitchen, counterは自分の店舗のみ取得可能)|None|

<h3 id="restaurantget-responseschema">Response Schema</h3>

Status Code **200**

|Name|Type|Required|Restrictions|Description|
|---|---|---|---|---|
|» restaurant_id|integer|false|none|店舗のID|
|» restaurant_name|string|false|none|店舗の名称|
|» owner_admin_id|integer|false|none|オーナーの管理者ID|
|» owner_name|string|false|none|オーナーの名前|
|» restaurant_address|string|false|none|none|
|» restaurant_image_url|string|false|none|none|

<aside class="warning">
To perform this operation, you must be authenticated by means of one of the following methods:
bearerAuth
</aside>

## restaurantModify

<a id="opIdrestaurantModify"></a>

`PUT /management/restaurant/{restaurant_id}`

*店舗情報編集*

> Body parameter

```json
{
  "restaurant_name": "string",
  "owner_admin_id": 0,
  "restaurant_address": "string",
  "restaurant_image_url": "string"
}
```

<h3 id="restaurantmodify-parameters">Parameters</h3>

|Name|In|Type|Required|Description|
|---|---|---|---|---|
|authorization|header|string|true|/management/login で取得したトークン|
|restaurant_id|path|integer|true|情報編集したい店舗のID|
|body|body|object|false|none|
|» restaurant_name|body|string|false|店舗の名称|
|» owner_admin_id|body|integer|false|オーナーの管理者ID|
|» restaurant_address|body|string|false|none|
|» restaurant_image_url|body|string|false|none|

<h3 id="restaurantmodify-responses">Responses</h3>

|Status|Meaning|Description|Schema|
|---|---|---|---|
|200|[OK](https://tools.ietf.org/html/rfc7231#section-6.3.1)|OK|None|
|400|[Bad Request](https://tools.ietf.org/html/rfc7231#section-6.5.1)|Bad Request (リクエストに不備がある)|None|
|401|[Unauthorized](https://tools.ietf.org/html/rfc7235#section-3.1)|Unauthorized (トークンが無効)|None|
|403|[Forbidden](https://tools.ietf.org/html/rfc7231#section-6.5.3)|Permission Denied (ownerは自分の店舗のみ編集可。counter, kitchenはアクセス権がない)|None|

<aside class="warning">
To perform this operation, you must be authenticated by means of one of the following methods:
bearerAuth
</aside>

## restaurantDelete

<a id="opIdrestaurantDelete"></a>

`DELETE /management/restaurant/{restaurant_id}`

*店舗の削除*

<h3 id="restaurantdelete-parameters">Parameters</h3>

|Name|In|Type|Required|Description|
|---|---|---|---|---|
|authorization|header|string|true|/management/login で取得したトークン|
|restaurant_id|path|integer|true|削除したい店舗のID|

<h3 id="restaurantdelete-responses">Responses</h3>

|Status|Meaning|Description|Schema|
|---|---|---|---|
|200|[OK](https://tools.ietf.org/html/rfc7231#section-6.3.1)|OK|None|
|400|[Bad Request](https://tools.ietf.org/html/rfc7231#section-6.5.1)|Bad Request (restaurant_idに不備がある)|None|
|401|[Unauthorized](https://tools.ietf.org/html/rfc7235#section-3.1)|Unauthorized (トークンが無効)|None|
|403|[Forbidden](https://tools.ietf.org/html/rfc7231#section-6.5.3)|Permission Denied (system管理者のみアクセス可)|None|

<aside class="warning">
To perform this operation, you must be authenticated by means of one of the following methods:
bearerAuth
</aside>

## adminList

<a id="opIdadminList"></a>

`GET /management/account`

*管理者アカウントの一覧取得 (ownerは自分の店舗に所属しているアカウントのみ取得可能)*

<h3 id="adminlist-parameters">Parameters</h3>

|Name|In|Type|Required|Description|
|---|---|---|---|---|
|authorization|header|string|true|/management/login で取得したトークン|

> Example responses

> 200 Response

```json
{
  "admins": [
    {
      "admin_id": 0,
      "name": "string",
      "login_id": "string",
      "restaurants_id": [
        0
      ],
      "restaurants_name": [
        "string"
      ],
      "admin_role": "string"
    }
  ]
}
```

<h3 id="adminlist-responses">Responses</h3>

|Status|Meaning|Description|Schema|
|---|---|---|---|
|200|[OK](https://tools.ietf.org/html/rfc7231#section-6.3.1)|OK|Inline|
|401|[Unauthorized](https://tools.ietf.org/html/rfc7235#section-3.1)|Unauthorized (トークンが無効)|None|
|403|[Forbidden](https://tools.ietf.org/html/rfc7231#section-6.5.3)|Permission Denied (counter, kitchenはアクセス権がない)|None|

<h3 id="adminlist-responseschema">Response Schema</h3>

Status Code **200**

|Name|Type|Required|Restrictions|Description|
|---|---|---|---|---|
|» admins|[object]|false|none|none|
|»» admin_id|integer|false|none|none|
|»» name|string|false|none|none|
|»» login_id|string|false|none|none|
|»» restaurants_id|[integer]|false|none|none|
|»» restaurants_name|[string]|false|none|none|
|»» admin_role|string|false|none|none|

<aside class="warning">
To perform this operation, you must be authenticated by means of one of the following methods:
bearerAuth
</aside>

## adminSignUp

<a id="opIdadminSignUp"></a>

`POST /management/account`

*新規管理者アカウント(owner, counter, kitchen)発行*

> Body parameter

```json
{
  "admin_name": "string",
  "login_id": "string",
  "password": "string",
  "restaurants_id": [
    0
  ],
  "admin_role": "string"
}
```

<h3 id="adminsignup-parameters">Parameters</h3>

|Name|In|Type|Required|Description|
|---|---|---|---|---|
|authorization|header|string|true|/management/login で取得したトークン|
|body|body|object|false|none|
|» admin_name|body|string|true|管理者名前|
|» login_id|body|string|true|ログイン用ユーザーID|
|» password|body|string|true|ログイン用パスワード|
|» restaurants_id|body|[integer]|false|所属している店舗のID|
|» admin_role|body|string|true|管理者のロール(owner, counter, kitchen)|

> Example responses

> 200 Response

```json
{
  "admin_id": 0
}
```

<h3 id="adminsignup-responses">Responses</h3>

|Status|Meaning|Description|Schema|
|---|---|---|---|
|200|[OK](https://tools.ietf.org/html/rfc7231#section-6.3.1)|OK|Inline|
|400|[Bad Request](https://tools.ietf.org/html/rfc7231#section-6.5.1)|Bad Request (リクエストに不備がある)|None|
|401|[Unauthorized](https://tools.ietf.org/html/rfc7235#section-3.1)|Unauthorized (トークンが無効)|None|
|403|[Forbidden](https://tools.ietf.org/html/rfc7231#section-6.5.3)|Permission Denied (counter, kitchenはアクセス権がない。ownerはownerアカウントを発行できない)|None|

<h3 id="adminsignup-responseschema">Response Schema</h3>

Status Code **200**

|Name|Type|Required|Restrictions|Description|
|---|---|---|---|---|
|» admin_id|integer|false|none|管理者ID|

<aside class="warning">
To perform this operation, you must be authenticated by means of one of the following methods:
bearerAuth
</aside>

## adminGet

<a id="opIdadminGet"></a>

`GET /management/account/{admin_id}`

*管理者アカウント情報取得*

<h3 id="adminget-parameters">Parameters</h3>

|Name|In|Type|Required|Description|
|---|---|---|---|---|
|authorization|header|string|true|/management/login で取得したトークン|
|admin_id|path|integer|true|情報取得したい管理者アカウントのID|

> Example responses

> 200 Response

```json
{
  "admin_id": 0,
  "name": "string",
  "login_id": "string",
  "restaurants_id": [
    0
  ],
  "restaurants_name": [
    "string"
  ],
  "admin_role": "string"
}
```

<h3 id="adminget-responses">Responses</h3>

|Status|Meaning|Description|Schema|
|---|---|---|---|
|200|[OK](https://tools.ietf.org/html/rfc7231#section-6.3.1)|OK|Inline|
|400|[Bad Request](https://tools.ietf.org/html/rfc7231#section-6.5.1)|Bad Request (リクエストに不備がある)|None|
|401|[Unauthorized](https://tools.ietf.org/html/rfc7235#section-3.1)|Unauthorized (トークンが無効)|None|
|403|[Forbidden](https://tools.ietf.org/html/rfc7231#section-6.5.3)|Permission Denied (counter, kitchenはアクセス権がない。ownerは自分の店舗のアカウントのみ取得可能)|None|

<h3 id="adminget-responseschema">Response Schema</h3>

Status Code **200**

|Name|Type|Required|Restrictions|Description|
|---|---|---|---|---|
|» admin_id|integer|false|none|管理者ID|
|» name|string|false|none|管理者名前|
|» login_id|string|false|none|ログイン用ユーザーID|
|» restaurants_id|[integer]|false|none|所属している店舗のID|
|» restaurants_name|[string]|false|none|所属している店舗の名称|
|» admin_role|string|false|none|管理者のロール(system, owner, counter, kitchen)|

<aside class="warning">
To perform this operation, you must be authenticated by means of one of the following methods:
bearerAuth
</aside>

## adminModify

<a id="opIdadminModify"></a>

`PUT /management/account/{admin_id}`

*管理者アカウント(owner, counter, kitchen)情報編集*

> Body parameter

```json
{
  "admin_name": "string",
  "login_id": "string",
  "password": "string",
  "restaurants_id": [
    0
  ]
}
```

<h3 id="adminmodify-parameters">Parameters</h3>

|Name|In|Type|Required|Description|
|---|---|---|---|---|
|authorization|header|string|true|/management/login で取得したトークン|
|admin_id|path|integer|true|編集したい管理者アカウントのID|
|body|body|object|false|none|
|» admin_name|body|string|false|管理者名前|
|» login_id|body|string|false|ログイン用ユーザーID|
|» password|body|string|false|ログイン用パスワード|
|» restaurants_id|body|[integer]|false|所属している店舗のID|

<h3 id="adminmodify-responses">Responses</h3>

|Status|Meaning|Description|Schema|
|---|---|---|---|
|200|[OK](https://tools.ietf.org/html/rfc7231#section-6.3.1)|OK|None|
|400|[Bad Request](https://tools.ietf.org/html/rfc7231#section-6.5.1)|Bad Request (リクエストに不備がある)|None|
|401|[Unauthorized](https://tools.ietf.org/html/rfc7235#section-3.1)|Unauthorized (トークンが無効)|None|
|403|[Forbidden](https://tools.ietf.org/html/rfc7231#section-6.5.3)|Permission Denied (counter, kitchenは自分以外のアカウント情報を編集できない。ownerは自分の店舗のアカウントのみ編集可能)|None|

<aside class="warning">
To perform this operation, you must be authenticated by means of one of the following methods:
bearerAuth
</aside>

## adminDelete

<a id="opIdadminDelete"></a>

`DELETE /management/account/{admin_id}`

*管理者アカウント(owner, counter, kitchen)削除*

<h3 id="admindelete-parameters">Parameters</h3>

|Name|In|Type|Required|Description|
|---|---|---|---|---|
|authorization|header|string|true|/management/login で取得したトークン|
|admin_id|path|integer|true|削除したい管理者アカウントのID|

<h3 id="admindelete-responses">Responses</h3>

|Status|Meaning|Description|Schema|
|---|---|---|---|
|200|[OK](https://tools.ietf.org/html/rfc7231#section-6.3.1)|OK|None|
|400|[Bad Request](https://tools.ietf.org/html/rfc7231#section-6.5.1)|Bad Request (admin_idに不備がある)|None|
|401|[Unauthorized](https://tools.ietf.org/html/rfc7235#section-3.1)|Unauthorized (トークンが無効)|None|
|403|[Forbidden](https://tools.ietf.org/html/rfc7231#section-6.5.3)|Permission Denied (counter, kitchenはアクセス権がない。ownerは自分の店舗に所属しているアカウントのみ削除可能)|None|

<aside class="warning">
To perform this operation, you must be authenticated by means of one of the following methods:
bearerAuth
</aside>

## menuList

<a id="opIdmenuList"></a>

`GET /management/dish/byRestaurant/{restaurant_id}`

*料理メニューの一覧取得*

一人のオーナーが複数の店舗を経営している可能性を考慮し、restaurant_idをパラメータとして追加している。

<h3 id="menulist-parameters">Parameters</h3>

|Name|In|Type|Required|Description|
|---|---|---|---|---|
|authorization|header|string|true|/management/login で取得したトークン|
|restaurant_id|path|string|true|メニューの一覧を取得したい店舗のID|

> Example responses

> 200 Response

```json
{
  "categories": [
    {
      "category_name": "string",
      "dishes": [
        {
          "dish_id": 0,
          "dish_name": "string",
          "image_url": "string",
          "dish_price": 0,
          "available_num": 0,
          "dish_description": "string"
        }
      ]
    }
  ]
}
```

<h3 id="menulist-responses">Responses</h3>

|Status|Meaning|Description|Schema|
|---|---|---|---|
|200|[OK](https://tools.ietf.org/html/rfc7231#section-6.3.1)|OK|Inline|
|400|[Bad Request](https://tools.ietf.org/html/rfc7231#section-6.5.1)|Bad Request (リクエストに不備がある)|None|
|401|[Unauthorized](https://tools.ietf.org/html/rfc7235#section-3.1)|Unauthorized (トークンが無効)|None|
|403|[Forbidden](https://tools.ietf.org/html/rfc7231#section-6.5.3)|Permission Denied (owner, kitchen, counterは自分の店舗のみ取得可能)|None|

<h3 id="menulist-responseschema">Response Schema</h3>

Status Code **200**

|Name|Type|Required|Restrictions|Description|
|---|---|---|---|---|
|» categories|[object]|false|none|none|
|»» category_name|string|false|none|カテゴリ名|
|»» dishes|[object]|false|none|none|
|»»» dish_id|integer|false|none|料理ID|
|»»» dish_name|string|false|none|料理名|
|»»» image_url|string|false|none|料理画像のURL|
|»»» dish_price|integer|false|none|料理の価格|
|»»» available_num|integer|false|none|料理の在庫数|
|»»» dish_description|string|false|none|料理の説明|

<aside class="warning">
To perform this operation, you must be authenticated by means of one of the following methods:
bearerAuth
</aside>

## menuCreate

<a id="opIdmenuCreate"></a>

`POST /management/dish/byRestaurant/{restaurant_id}`

*メニューに料理を追加(新規作成)*

> Body parameter

```json
{
  "dishes": [
    {
      "dish_name": "string",
      "dish_category": "string",
      "dish_price": 0,
      "available_num": 0,
      "image_url": "string",
      "dish_description": "string"
    }
  ]
}
```

<h3 id="menucreate-parameters">Parameters</h3>

|Name|In|Type|Required|Description|
|---|---|---|---|---|
|authorization|header|string|true|/management/login で取得したトークン|
|restaurant_id|path|string|true|料理を追加したい店舗のID|
|body|body|object|false|none|
|» dishes|body|[object]|false|none|
|»» dish_name|body|string|true|料理の名称|
|»» dish_category|body|string|false|料理の種類|
|»» dish_price|body|integer|true|料理の値段|
|»» available_num|body|integer|true|料理の在庫数|
|»» image_url|body|string|false|料理の画像|
|»» dish_description|body|string|false|料理の説明|

> Example responses

> 200 Response

```json
{
  "dish_id": 0
}
```

<h3 id="menucreate-responses">Responses</h3>

|Status|Meaning|Description|Schema|
|---|---|---|---|
|200|[OK](https://tools.ietf.org/html/rfc7231#section-6.3.1)|OK|Inline|
|400|[Bad Request](https://tools.ietf.org/html/rfc7231#section-6.5.1)|Bad Request (リクエストに不備がある)|None|
|401|[Unauthorized](https://tools.ietf.org/html/rfc7235#section-3.1)|Unauthorized (トークンが無効)|None|
|403|[Forbidden](https://tools.ietf.org/html/rfc7231#section-6.5.3)|Permission Denied (counter, kitchenはアクセス権がない。ownerは自分以外の店舗のメニューにアクセスできない)|None|

<h3 id="menucreate-responseschema">Response Schema</h3>

Status Code **200**

|Name|Type|Required|Restrictions|Description|
|---|---|---|---|---|
|» dish_id|integer|false|none|追加された料理のID|

<aside class="warning">
To perform this operation, you must be authenticated by means of one of the following methods:
bearerAuth
</aside>

## dishGet

<a id="opIddishGet"></a>

`GET /management/dish/{dish_id}`

*指定した料理情報取得*

<h3 id="dishget-parameters">Parameters</h3>

|Name|In|Type|Required|Description|
|---|---|---|---|---|
|authorization|header|string|true|/management/login で取得したトークン|
|dish_id|path|integer|true|情報取得したい料理のID|

> Example responses

> 200 Response

```json
{
  "dish_id": 0,
  "dish_name": "string",
  "dish_category": "string",
  "dish_price": 0,
  "available_num": 0,
  "image_url": "string",
  "dish_description": "string"
}
```

<h3 id="dishget-responses">Responses</h3>

|Status|Meaning|Description|Schema|
|---|---|---|---|
|200|[OK](https://tools.ietf.org/html/rfc7231#section-6.3.1)|OK|Inline|
|400|[Bad Request](https://tools.ietf.org/html/rfc7231#section-6.5.1)|Bad Request (リクエストに不備がある)|None|
|401|[Unauthorized](https://tools.ietf.org/html/rfc7235#section-3.1)|Unauthorized (トークンが無効)|None|
|403|[Forbidden](https://tools.ietf.org/html/rfc7231#section-6.5.3)|Permission Denied (owner, kitchen, counterは自分の店舗のみ取得可能)|None|

<h3 id="dishget-responseschema">Response Schema</h3>

Status Code **200**

|Name|Type|Required|Restrictions|Description|
|---|---|---|---|---|
|» dish_id|integer|false|none|料理ID|
|» dish_name|string|false|none|料理の名称|
|» dish_category|string|false|none|料理の種類|
|» dish_price|integer|false|none|料理の値段|
|» available_num|integer|false|none|料理の在庫数|
|» image_url|string|false|none|料理の画像|
|» dish_description|string|false|none|料理の説明|

<aside class="warning">
To perform this operation, you must be authenticated by means of one of the following methods:
bearerAuth
</aside>

## dishModify

<a id="opIddishModify"></a>

`PUT /management/dish/{dish_id}`

*料理情報編集*

> Body parameter

```json
{
  "dish_name": "string",
  "dish_category": "string",
  "dish_price": 0,
  "available_num": 0,
  "image_url": "string",
  "dish_description": "string"
}
```

<h3 id="dishmodify-parameters">Parameters</h3>

|Name|In|Type|Required|Description|
|---|---|---|---|---|
|authorization|header|string|true|/management/login で取得したトークン|
|dish_id|path|integer|true|編集したい料理のID|
|body|body|object|false|none|
|» dish_name|body|string|false|料理の名称|
|» dish_category|body|string|false|料理の種類|
|» dish_price|body|integer|false|料理の値段|
|» available_num|body|integer|false|料理の在庫数|
|» image_url|body|string|false|料理の画像|
|» dish_description|body|string|false|料理の説明|

<h3 id="dishmodify-responses">Responses</h3>

|Status|Meaning|Description|Schema|
|---|---|---|---|
|200|[OK](https://tools.ietf.org/html/rfc7231#section-6.3.1)|OK|None|
|400|[Bad Request](https://tools.ietf.org/html/rfc7231#section-6.5.1)|Bad Request (リクエストに不備がある)|None|
|401|[Unauthorized](https://tools.ietf.org/html/rfc7235#section-3.1)|Unauthorized (トークンが無効)|None|
|403|[Forbidden](https://tools.ietf.org/html/rfc7231#section-6.5.3)|Permission Denied (counter, kitchenは自分の店舗の料理の在庫数のみ変更可。ownerは自分の店舗の料理のみ編集可能)|None|

<aside class="warning">
To perform this operation, you must be authenticated by means of one of the following methods:
bearerAuth
</aside>

## dishDelete

<a id="opIddishDelete"></a>

`DELETE /management/dish/{dish_id}`

*料理の削除*

<h3 id="dishdelete-parameters">Parameters</h3>

|Name|In|Type|Required|Description|
|---|---|---|---|---|
|authorization|header|string|true|/management/login で取得したトークン|
|dish_id|path|integer|true|削除したい料理のID|

<h3 id="dishdelete-responses">Responses</h3>

|Status|Meaning|Description|Schema|
|---|---|---|---|
|200|[OK](https://tools.ietf.org/html/rfc7231#section-6.3.1)|OK|None|
|400|[Bad Request](https://tools.ietf.org/html/rfc7231#section-6.5.1)|Bad Request (dish_idに不備がある)|None|
|401|[Unauthorized](https://tools.ietf.org/html/rfc7235#section-3.1)|Unauthorized (トークンが無効)|None|
|403|[Forbidden](https://tools.ietf.org/html/rfc7231#section-6.5.3)|Permission Denied (counter, kitchenはアクセス権がない。ownerは自分の店舗の料理のみ削除可能)|None|

<aside class="warning">
To perform this operation, you must be authenticated by means of one of the following methods:
bearerAuth
</aside>

## seatList

<a id="opIdseatList"></a>

`GET /management/seat/byRestaurant/{restaurant_id}`

*指定した店舗の座席情報一覧を取得*

<h3 id="seatlist-parameters">Parameters</h3>

|Name|In|Type|Required|Description|
|---|---|---|---|---|
|authorization|header|string|true|/management/login で取得したトークン|
|restaurant_id|path|integer|true|座席情報を取得したい店舗のID|

> Example responses

> 200 Response

```json
{
  "seats": [
    {
      "seat_id": 0,
      "seat_name": "string",
      "is_available": true,
      "order_id": 0,
      "Seat-Token": "string"
    }
  ]
}
```

<h3 id="seatlist-responses">Responses</h3>

|Status|Meaning|Description|Schema|
|---|---|---|---|
|200|[OK](https://tools.ietf.org/html/rfc7231#section-6.3.1)|OK|Inline|
|400|[Bad Request](https://tools.ietf.org/html/rfc7231#section-6.5.1)|Bad Request (restaurant_idに不備がある)|None|
|401|[Unauthorized](https://tools.ietf.org/html/rfc7235#section-3.1)|Unauthorized (トークンが無効)|None|
|403|[Forbidden](https://tools.ietf.org/html/rfc7231#section-6.5.3)|Permission Denied (owner, kitchen, counterは自分の店舗のみ取得可能)|None|

<h3 id="seatlist-responseschema">Response Schema</h3>

Status Code **200**

|Name|Type|Required|Restrictions|Description|
|---|---|---|---|---|
|» seats|[object]|false|none|none|
|»» seat_id|integer|false|none|座席のID|
|»» seat_name|string|false|none|座席の名称|
|»» is_available|boolean|false|none|座席の空席状況 (空席true, 満席false)|
|»» order_id|integer|false|none|座席の注文ID|
|»» Seat-Token|string|false|none|座席のQRコードのトークン|

<aside class="warning">
To perform this operation, you must be authenticated by means of one of the following methods:
bearerAuth
</aside>

## seatAdd

<a id="opIdseatAdd"></a>

`POST /management/seat/byRestaurant/{restaurant_id}`

*座席の追加*

> Body parameter

```json
{
  "seat_name": "string"
}
```

<h3 id="seatadd-parameters">Parameters</h3>

|Name|In|Type|Required|Description|
|---|---|---|---|---|
|authorization|header|string|true|/management/login で取得したトークン|
|restaurant_id|path|integer|true|座席を追加したい店舗のID|
|body|body|object|false|none|
|» seat_name|body|string|true|座席の名称|

> Example responses

> 200 Response

```json
{
  "seat_id": 0,
  "Seat-Token": "string"
}
```

<h3 id="seatadd-responses">Responses</h3>

|Status|Meaning|Description|Schema|
|---|---|---|---|
|200|[OK](https://tools.ietf.org/html/rfc7231#section-6.3.1)|OK|Inline|
|400|[Bad Request](https://tools.ietf.org/html/rfc7231#section-6.5.1)|Bad Request (リクエストに不備がある)|None|
|401|[Unauthorized](https://tools.ietf.org/html/rfc7235#section-3.1)|Unauthorized (トークンが無効)|None|
|403|[Forbidden](https://tools.ietf.org/html/rfc7231#section-6.5.3)|Permission Denied (counter, kitchenはアクセス権がない。ownerは自分の店舗のみ座席を追加可能)|None|

<h3 id="seatadd-responseschema">Response Schema</h3>

Status Code **200**

|Name|Type|Required|Restrictions|Description|
|---|---|---|---|---|
|» seat_id|integer|false|none|追加された座席のID|
|» Seat-Token|string|false|none|追加された座席のQRコードのトークン|

<aside class="warning">
To perform this operation, you must be authenticated by means of one of the following methods:
bearerAuth
</aside>

## multipleSeatAdd

<a id="opIdmultipleSeatAdd"></a>

`POST /management/seat/byRestaurant/{restaurant_id}/{seat_num}`

*新規店舗の座席一括追加*

<h3 id="multipleseatadd-parameters">Parameters</h3>

|Name|In|Type|Required|Description|
|---|---|---|---|---|
|authorization|header|string|true|/management/login で取得したトークン|
|restaurant_id|path|integer|true|座席を追加したい店舗のID|
|seat_num|path|integer|true|座席を追加したい店舗の座席数|

> Example responses

> 200 Response

```json
[
  {
    "seat_id": 0,
    "Seat-Token": "string"
  }
]
```

<h3 id="multipleseatadd-responses">Responses</h3>

|Status|Meaning|Description|Schema|
|---|---|---|---|
|200|[OK](https://tools.ietf.org/html/rfc7231#section-6.3.1)|OK|Inline|
|400|[Bad Request](https://tools.ietf.org/html/rfc7231#section-6.5.1)|Bad Request (リクエストに不備がある)|None|
|401|[Unauthorized](https://tools.ietf.org/html/rfc7235#section-3.1)|Unauthorized (トークンが無効)|None|
|403|[Forbidden](https://tools.ietf.org/html/rfc7231#section-6.5.3)|Permission Denied (counter, kitchenはアクセス権がない。ownerは自分の店舗のみ座席を追加可能)|None|

<h3 id="multipleseatadd-responseschema">Response Schema</h3>

Status Code **200**

|Name|Type|Required|Restrictions|Description|
|---|---|---|---|---|
|» seat_id|integer|false|none|追加された座席のID|
|» Seat-Token|string|false|none|追加された座席のQRコードのトークン|

<aside class="warning">
To perform this operation, you must be authenticated by means of one of the following methods:
bearerAuth
</aside>

## seatInfo

<a id="opIdseatInfo"></a>

`GET /management/seat/{seat_id}`

*指定した座席の情報を取得*

<h3 id="seatinfo-parameters">Parameters</h3>

|Name|In|Type|Required|Description|
|---|---|---|---|---|
|authorization|header|string|true|/management/login で取得したトークン|
|seat_id|path|integer|true|座席情報を取得したい座席のID|

> Example responses

> 200 Response

```json
{
  "seat_id": 0,
  "seat_name": "string",
  "is_available": true,
  "order_id": 0,
  "Seat-Token": "string"
}
```

<h3 id="seatinfo-responses">Responses</h3>

|Status|Meaning|Description|Schema|
|---|---|---|---|
|200|[OK](https://tools.ietf.org/html/rfc7231#section-6.3.1)|OK|Inline|
|400|[Bad Request](https://tools.ietf.org/html/rfc7231#section-6.5.1)|Bad Request (seat_idに不備がある)|None|
|401|[Unauthorized](https://tools.ietf.org/html/rfc7235#section-3.1)|Unauthorized (トークンが無効)|None|
|403|[Forbidden](https://tools.ietf.org/html/rfc7231#section-6.5.3)|Permission Denied (owner, kitchen, counterは自分の店舗のみ取得可能)|None|

<h3 id="seatinfo-responseschema">Response Schema</h3>

Status Code **200**

|Name|Type|Required|Restrictions|Description|
|---|---|---|---|---|
|» seat_id|integer|false|none|座席のID|
|» seat_name|string|false|none|座席の名称|
|» is_available|boolean|false|none|座席の空席状況 (空席true, 満席false)|
|» order_id|integer|false|none|座席の注文ID|
|» Seat-Token|string|false|none|座席のQRコードのトークン|

<aside class="warning">
To perform this operation, you must be authenticated by means of one of the following methods:
bearerAuth
</aside>

## seatEdit

<a id="opIdseatEdit"></a>

`PUT /management/seat/{seat_id}`

*座席の編集*

空席 -> 使用不可に変更したい場合にも使える

> Body parameter

```json
{
  "seat_name": "string",
  "is_available": true
}
```

<h3 id="seatedit-parameters">Parameters</h3>

|Name|In|Type|Required|Description|
|---|---|---|---|---|
|authorization|header|string|true|/management/login で取得したトークン|
|seat_id|path|integer|true|編集したい座席のID|
|body|body|object|false|none|
|» seat_name|body|string|false|座席の名称|
|» is_available|body|boolean|false|座席の空席状況 (空席true, 満席、利用不可false)|

> Example responses

> 200 Response

```json
{
  "Seat-Token": "string"
}
```

<h3 id="seatedit-responses">Responses</h3>

|Status|Meaning|Description|Schema|
|---|---|---|---|
|200|[OK](https://tools.ietf.org/html/rfc7231#section-6.3.1)|OK|Inline|
|400|[Bad Request](https://tools.ietf.org/html/rfc7231#section-6.5.1)|Bad Request (リクエストに不備がある、もしくは座席が利用中で編集禁止)|None|
|401|[Unauthorized](https://tools.ietf.org/html/rfc7235#section-3.1)|Unauthorized (トークンが無効)|None|
|403|[Forbidden](https://tools.ietf.org/html/rfc7231#section-6.5.3)|Permission Denied (counter, kitchenはアクセス権がない。ownerは自分の店舗のみ座席を編集可能)|None|

<h3 id="seatedit-responseschema">Response Schema</h3>

Status Code **200**

|Name|Type|Required|Restrictions|Description|
|---|---|---|---|---|
|» Seat-Token|string|false|none|編集された座席のQRコードのトークン|

<aside class="warning">
To perform this operation, you must be authenticated by means of one of the following methods:
bearerAuth
</aside>

## seatDelete

<a id="opIdseatDelete"></a>

`DELETE /management/seat/{seat_id}`

*座席の削除*

<h3 id="seatdelete-parameters">Parameters</h3>

|Name|In|Type|Required|Description|
|---|---|---|---|---|
|authorization|header|string|true|/management/login で取得したトークン|
|seat_id|path|integer|true|削除したい座席のID|

<h3 id="seatdelete-responses">Responses</h3>

|Status|Meaning|Description|Schema|
|---|---|---|---|
|200|[OK](https://tools.ietf.org/html/rfc7231#section-6.3.1)|OK|None|
|400|[Bad Request](https://tools.ietf.org/html/rfc7231#section-6.5.1)|Bad Request (seat_idに不備がある、もしくは座席が利用中で削除禁止)|None|
|401|[Unauthorized](https://tools.ietf.org/html/rfc7235#section-3.1)|Unauthorized (トークンが無効)|None|
|403|[Forbidden](https://tools.ietf.org/html/rfc7231#section-6.5.3)|Permission Denied (counter, kitchenはアクセス権がない。ownerは自分の店舗のみ座席を削除可能)|None|

<aside class="warning">
To perform this operation, you must be authenticated by means of one of the following methods:
bearerAuth
</aside>

## seatRefresh

<a id="opIdseatRefresh"></a>

`PUT /management/seat/{seat_id}/refresh`

*座席のQRコードトークンを再発行*

<h3 id="seatrefresh-parameters">Parameters</h3>

|Name|In|Type|Required|Description|
|---|---|---|---|---|
|authorization|header|string|true|/management/login で取得したトークン|
|seat_id|path|integer|true|QRコードトークンを再発行したい座席のID|

> Example responses

> 200 Response

```json
{
  "Seat-Token": "string"
}
```

<h3 id="seatrefresh-responses">Responses</h3>

|Status|Meaning|Description|Schema|
|---|---|---|---|
|200|[OK](https://tools.ietf.org/html/rfc7231#section-6.3.1)|OK|Inline|
|400|[Bad Request](https://tools.ietf.org/html/rfc7231#section-6.5.1)|Bad Request (seat_idに不備がある, もしくは座席が利用中で再発行禁止)|None|
|401|[Unauthorized](https://tools.ietf.org/html/rfc7235#section-3.1)|Unauthorized (トークンが無効)|None|
|403|[Forbidden](https://tools.ietf.org/html/rfc7231#section-6.5.3)|Permission Denied (counter, ownerは自分の店舗の座席のQRコードトークンを再発行可能。kitchenはアクセス不可)|None|

<h3 id="seatrefresh-responseschema">Response Schema</h3>

Status Code **200**

|Name|Type|Required|Restrictions|Description|
|---|---|---|---|---|
|» Seat-Token|string|false|none|再発行された座席のQRコードのトークン|

<aside class="warning">
To perform this operation, you must be authenticated by means of one of the following methods:
bearerAuth
</aside>

## orderList

<a id="opIdorderList"></a>

`GET /management/order/byRestaurant/{restaurant_id}`

*指定した店舗の注文一覧の取得*

<h3 id="orderlist-parameters">Parameters</h3>

|Name|In|Type|Required|Description|
|---|---|---|---|---|
|authorization|header|string|true|/management/login で取得したトークン|
|restaurant_id|path|integer|true|注文一覧を取得したい店舗のID|

> Example responses

> 200 Response

```json
{
  "orders": [
    {
      "id": 0,
      "ordered_date": "string",
      "seat_id": 0,
      "seat_name": "string",
      "total_price": 0,
      "is_all_delivered": true,
      "is_order_finished": true,
      "is_paid": true
    }
  ]
}
```

<h3 id="orderlist-responses">Responses</h3>

|Status|Meaning|Description|Schema|
|---|---|---|---|
|200|[OK](https://tools.ietf.org/html/rfc7231#section-6.3.1)|OK|Inline|
|400|[Bad Request](https://tools.ietf.org/html/rfc7231#section-6.5.1)|Bad Request (restaurant_idに不備がある)|None|
|401|[Unauthorized](https://tools.ietf.org/html/rfc7235#section-3.1)|Unauthorized (トークンが無効)|None|
|403|[Forbidden](https://tools.ietf.org/html/rfc7231#section-6.5.3)|Permission Denied (owner, kitchen, counterは自分の店舗のみ取得可能)|None|

<h3 id="orderlist-responseschema">Response Schema</h3>

Status Code **200**

|Name|Type|Required|Restrictions|Description|
|---|---|---|---|---|
|» orders|[object]|false|none|none|
|»» id|integer|false|none|注文ID|
|»» ordered_date|string|false|none|注文日時|
|»» seat_id|integer|false|none|座席ID|
|»» seat_name|string|false|none|座席名|
|»» total_price|integer|false|none|合計金額|
|»» is_all_delivered|boolean|false|none|全ての料理が配達済みかどうか|
|»» is_order_finished|boolean|false|none|注文が完了しているかどうか|
|»» is_paid|boolean|false|none|支払いが完了しているかどうか|

<aside class="warning">
To perform this operation, you must be authenticated by means of one of the following methods:
bearerAuth
</aside>

## unservedDishList

<a id="opIdunservedDishList"></a>

`GET /management/order/byRestaurant/{restaurant_id}/unserved`

*指定した店舗の未提供料理一覧を取得 (注文時間順)*

<h3 id="unserveddishlist-parameters">Parameters</h3>

|Name|In|Type|Required|Description|
|---|---|---|---|---|
|authorization|header|string|true|/management/login で取得したトークン|
|restaurant_id|path|integer|true|未提供料理一覧を取得したい店舗のID|

> Example responses

> 200 Response

```json
{
  "unserved_dishes": [
    {
      "order_id": 0,
      "ordered_dish_id": 0,
      "dish_id": 0,
      "dish_name": "string",
      "quantity": 0,
      "seat_id": 0,
      "seat_name": "string",
      "ordered_date": "string"
    }
  ]
}
```

<h3 id="unserveddishlist-responses">Responses</h3>

|Status|Meaning|Description|Schema|
|---|---|---|---|
|200|[OK](https://tools.ietf.org/html/rfc7231#section-6.3.1)|OK|Inline|
|400|[Bad Request](https://tools.ietf.org/html/rfc7231#section-6.5.1)|Bad Request (restaurant_idに不備がある)|None|
|401|[Unauthorized](https://tools.ietf.org/html/rfc7235#section-3.1)|Unauthorized (トークンが無効)|None|
|403|[Forbidden](https://tools.ietf.org/html/rfc7231#section-6.5.3)|Permission Denied (owner, counter, kitchenは自分の店舗のみ取得可能)|None|

<h3 id="unserveddishlist-responseschema">Response Schema</h3>

Status Code **200**

|Name|Type|Required|Restrictions|Description|
|---|---|---|---|---|
|» unserved_dishes|[object]|false|none|none|
|»» order_id|integer|false|none|注文ID|
|»» ordered_dish_id|integer|false|none|注文した料理のID|
|»» dish_id|integer|false|none|注文した料理の料理ID|
|»» dish_name|string|false|none|注文した料理の名前|
|»» quantity|integer|false|none|注文した料理の数量|
|»» seat_id|integer|false|none|座席ID|
|»» seat_name|string|false|none|座席名|
|»» ordered_date|string|false|none|注文日時|

<aside class="warning">
To perform this operation, you must be authenticated by means of one of the following methods:
bearerAuth
</aside>

## orderGet

<a id="opIdorderGet"></a>

`GET /management/order/{order_id}`

*注文詳細の取得*

<h3 id="orderget-parameters">Parameters</h3>

|Name|In|Type|Required|Description|
|---|---|---|---|---|
|authorization|header|string|true|/management/login で取得したトークン|
|order_id|path|integer|true|注文詳細を取得したい注文のID|

> Example responses

> 200 Response

```json
{
  "order_id": 0,
  "ordered_date": "string",
  "seat_id": 0,
  "seat_name": "string",
  "is_all_delivered": true,
  "is_order_finished": true,
  "is_paid": true,
  "ordered_items": [
    {
      "ordered_dish_id": 0,
      "dish_id": 0,
      "dish_name": "string",
      "dish_price": 0,
      "dish_quantity": 0,
      "is_delivered": true
    }
  ],
  "total_price": 0
}
```

<h3 id="orderget-responses">Responses</h3>

|Status|Meaning|Description|Schema|
|---|---|---|---|
|200|[OK](https://tools.ietf.org/html/rfc7231#section-6.3.1)|OK|Inline|
|400|[Bad Request](https://tools.ietf.org/html/rfc7231#section-6.5.1)|Bad Request (order_idに不備がある)|None|
|401|[Unauthorized](https://tools.ietf.org/html/rfc7235#section-3.1)|Unauthorized (トークンが無効)|None|
|403|[Forbidden](https://tools.ietf.org/html/rfc7231#section-6.5.3)|Permission Denied (owner, kitchen, counterは自分の店舗のみ取得可能)|None|

<h3 id="orderget-responseschema">Response Schema</h3>

Status Code **200**

|Name|Type|Required|Restrictions|Description|
|---|---|---|---|---|
|» order_id|integer|false|none|注文ID|
|» ordered_date|string|false|none|注文日時|
|» seat_id|integer|false|none|座席ID|
|» seat_name|string|false|none|座席名|
|» is_all_delivered|boolean|false|none|全ての料理が配達済みかどうか|
|» is_order_finished|boolean|false|none|注文が完了しているかどうか|
|» is_paid|boolean|false|none|支払いが完了しているかどうか|
|» ordered_items|[object]|false|none|none|
|»» ordered_dish_id|integer|false|none|注文した料理のID|
|»» dish_id|integer|false|none|注文した料理の料理ID|
|»» dish_name|string|false|none|注文した料理の名前|
|»» dish_price|integer|false|none|注文した料理の単価|
|»» dish_quantity|integer|false|none|注文した料理の数量|
|»» is_delivered|boolean|false|none|その料理が配達済みかどうか|
|» total_price|integer|false|none|合計金額|

<aside class="warning">
To perform this operation, you must be authenticated by means of one of the following methods:
bearerAuth
</aside>

## orderPut

<a id="opIdorderPut"></a>

`PUT /management/order/{order_id}/checkout`

*(会計済みボタン) 注文を完了する*

注文状態(is_paid)、座席のQRコードトークンを更新する。

<h3 id="orderput-parameters">Parameters</h3>

|Name|In|Type|Required|Description|
|---|---|---|---|---|
|authorization|header|string|true|/management/login で取得したトークン|
|order_id|path|integer|true|注文のID|

<h3 id="orderput-responses">Responses</h3>

|Status|Meaning|Description|Schema|
|---|---|---|---|
|200|[OK](https://tools.ietf.org/html/rfc7231#section-6.3.1)|OK|None|
|400|[Bad Request](https://tools.ietf.org/html/rfc7231#section-6.5.1)|Bad Request (order_idに不備がある)|None|
|401|[Unauthorized](https://tools.ietf.org/html/rfc7235#section-3.1)|Unauthorized (トークンが無効)|None|
|403|[Forbidden](https://tools.ietf.org/html/rfc7231#section-6.5.3)|Permission Denied (owner, counterは自分の店舗のみアクセス可能、kitchenはアクセス不可)|None|

<aside class="warning">
To perform this operation, you must be authenticated by means of one of the following methods:
bearerAuth
</aside>

## orderedDishCancel

<a id="opIdorderedDishCancel"></a>

`PUT /management/order/byOrderedDish/{ordered_dish_id}/cancel`

*注文した(+未提供)料理のキャンセル*

<h3 id="ordereddishcancel-parameters">Parameters</h3>

|Name|In|Type|Required|Description|
|---|---|---|---|---|
|authorization|header|string|true|/management/login で取得したトークン|
|ordered_dish_id|path|integer|true|キャンセルしたい料理の注文ID|

<h3 id="ordereddishcancel-responses">Responses</h3>

|Status|Meaning|Description|Schema|
|---|---|---|---|
|200|[OK](https://tools.ietf.org/html/rfc7231#section-6.3.1)|OK|None|
|400|[Bad Request](https://tools.ietf.org/html/rfc7231#section-6.5.1)|Bad Request (ordered_dish_idに不備がある、もしくは提供済みでキャンセルできない)|None|
|401|[Unauthorized](https://tools.ietf.org/html/rfc7235#section-3.1)|Unauthorized (トークンが無効)|None|
|403|[Forbidden](https://tools.ietf.org/html/rfc7231#section-6.5.3)|Permission Denied (owner, counterは自分の店舗の注文のみキャンセル可能、kitchenはアクセス不可)|None|

<aside class="warning">
To perform this operation, you must be authenticated by means of one of the following methods:
bearerAuth
</aside>

## orderedDishDelivery

<a id="opIdorderedDishDelivery"></a>

`PUT /management/order/byOrderedDish/{ordered_dish_id}/deliver`

*注文ステータスを提供済みにする*

<h3 id="ordereddishdelivery-parameters">Parameters</h3>

|Name|In|Type|Required|Description|
|---|---|---|---|---|
|authorization|header|string|true|/management/login で取得したトークン|
|ordered_dish_id|path|integer|true|注文した料理の注文ID|

<h3 id="ordereddishdelivery-responses">Responses</h3>

|Status|Meaning|Description|Schema|
|---|---|---|---|
|200|[OK](https://tools.ietf.org/html/rfc7231#section-6.3.1)|OK|None|
|400|[Bad Request](https://tools.ietf.org/html/rfc7231#section-6.5.1)|Bad Request (ordered_dish_idに不備がある、もしくは既に提供済み)|None|
|401|[Unauthorized](https://tools.ietf.org/html/rfc7235#section-3.1)|Unauthorized (トークンが無効)|None|
|403|[Forbidden](https://tools.ietf.org/html/rfc7231#section-6.5.3)|Permission Denied (owner, counter, kitchenは自分の店舗のみアクセス可)|None|

<aside class="warning">
To perform this operation, you must be authenticated by means of one of the following methods:
bearerAuth
</aside>

<h1 id="--customer">customer</h1>

来客側

## customerMenuGet

<a id="opIdcustomerMenuGet"></a>

`GET /customer/dish`

*メニューの取得*

<h3 id="customermenuget-parameters">Parameters</h3>

|Name|In|Type|Required|Description|
|---|---|---|---|---|
|Seat-Token|header|string|true|座席のQRコードに含まれているトークン|

> Example responses

> 200 Response

```json
[
  {
    "category_name": "string",
    "dishes": [
      {
        "dish_id": 0,
        "dish_name": "string",
        "image_url": "string",
        "dish_price": 0,
        "available_num": 0
      }
    ]
  }
]
```

<h3 id="customermenuget-responses">Responses</h3>

|Status|Meaning|Description|Schema|
|---|---|---|---|
|200|[OK](https://tools.ietf.org/html/rfc7231#section-6.3.1)|OK|Inline|
|401|[Unauthorized](https://tools.ietf.org/html/rfc7235#section-3.1)|Unauthorized (トークンが無効)|None|

<h3 id="customermenuget-responseschema">Response Schema</h3>

Status Code **200**

|Name|Type|Required|Restrictions|Description|
|---|---|---|---|---|
|» category_name|string|false|none|カテゴリ名|
|» dishes|[object]|false|none|none|
|»» dish_id|integer|false|none|料理ID|
|»» dish_name|string|false|none|料理名|
|»» image_url|string|false|none|料理画像のURL|
|»» dish_price|integer|false|none|料理の価格|
|»» available_num|integer|false|none|料理の在庫数|

<aside class="success">
This operation does not require authentication
</aside>

## customerMenuDishGet

<a id="opIdcustomerMenuDishGet"></a>

`GET /customer/dish/{dish_id}`

*料理の詳細を取得*

<h3 id="customermenudishget-parameters">Parameters</h3>

|Name|In|Type|Required|Description|
|---|---|---|---|---|
|Seat-Token|header|string|true|座席のQRコードに含まれているトークン|
|dish_id|path|integer|true|料理のID|

> Example responses

> 200 Response

```json
{
  "dish_id": 0,
  "dish_name": "string",
  "image_url": "string",
  "dish_price": 0,
  "available_num": 0
}
```

<h3 id="customermenudishget-responses">Responses</h3>

|Status|Meaning|Description|Schema|
|---|---|---|---|
|200|[OK](https://tools.ietf.org/html/rfc7231#section-6.3.1)|OK|Inline|
|400|[Bad Request](https://tools.ietf.org/html/rfc7231#section-6.5.1)|Bad Request (dish_idに不備がある)|None|
|401|[Unauthorized](https://tools.ietf.org/html/rfc7235#section-3.1)|Unauthorized (トークンが無効)|None|

<h3 id="customermenudishget-responseschema">Response Schema</h3>

Status Code **200**

|Name|Type|Required|Restrictions|Description|
|---|---|---|---|---|
|» dish_id|integer|false|none|料理ID|
|» dish_name|string|false|none|料理名|
|» image_url|string|false|none|料理画像のURL|
|» dish_price|integer|false|none|料理の価格|
|» available_num|integer|false|none|料理の在庫数|

<aside class="success">
This operation does not require authentication
</aside>

## customerOrderGet

<a id="opIdcustomerOrderGet"></a>

`GET /customer/order`

*注文履歴、合計金額の取得*

<h3 id="customerorderget-parameters">Parameters</h3>

|Name|In|Type|Required|Description|
|---|---|---|---|---|
|Seat-Token|header|string|true|座席のQRコードに含まれているトークン|

> Example responses

> 200 Response

```json
{
  "order_id": 0,
  "ordered_dishes": [
    {
      "ordered_dish_id": 0,
      "ordered_dish_name": "string",
      "ordered_dish_price": 0,
      "ordered_dish_num": 0,
      "ordered_dish_status": "string"
    }
  ],
  "total_price": 0,
  "is_paid": true,
  "created_at": "string"
}
```

<h3 id="customerorderget-responses">Responses</h3>

|Status|Meaning|Description|Schema|
|---|---|---|---|
|200|[OK](https://tools.ietf.org/html/rfc7231#section-6.3.1)|OK|Inline|
|401|[Unauthorized](https://tools.ietf.org/html/rfc7235#section-3.1)|Unauthorized (トークンが無効)|None|

<h3 id="customerorderget-responseschema">Response Schema</h3>

Status Code **200**

|Name|Type|Required|Restrictions|Description|
|---|---|---|---|---|
|» order_id|integer|false|none|注文ID|
|» ordered_dishes|[object]|false|none|none|
|»» ordered_dish_id|integer|false|none|注文した料理の注文ID|
|»» ordered_dish_name|string|false|none|注文した料理の名称|
|»» ordered_dish_price|integer|false|none|注文した料理の価格|
|»» ordered_dish_num|integer|false|none|注文した料理の個数|
|»» ordered_dish_status|string|false|none|注文した料理のステータス|
|» total_price|integer|false|none|合計金額|
|» is_paid|boolean|false|none|会計済みかどうか|
|» created_at|string|false|none|注文日時|

<aside class="success">
This operation does not require authentication
</aside>

## customerOrderPost

<a id="opIdcustomerOrderPost"></a>

`POST /customer/order`

*注文・追加注文送信*

> Body parameter

```json
{
  "ordered_dishes": [
    {
      "dish_id": 0,
      "quantity": 0
    }
  ]
}
```

<h3 id="customerorderpost-parameters">Parameters</h3>

|Name|In|Type|Required|Description|
|---|---|---|---|---|
|Seat-Token|header|string|true|座席のQRコードに含まれているトークン|
|body|body|object|true|none|
|» ordered_dishes|body|[object]|false|none|
|»» dish_id|body|integer|true|注文した料理のID|
|»» quantity|body|integer|true|注文した料理の数量|

> Example responses

> 200 Response

```json
{
  "order_id": 0,
  "ordered_dishes": [
    {
      "ordered_dish_id": 0,
      "ordered_dish_name": "string"
    }
  ]
}
```

<h3 id="customerorderpost-responses">Responses</h3>

|Status|Meaning|Description|Schema|
|---|---|---|---|
|200|[OK](https://tools.ietf.org/html/rfc7231#section-6.3.1)|OK|Inline|
|400|[Bad Request](https://tools.ietf.org/html/rfc7231#section-6.5.1)|Bad Request (注文リクエストに不備がある)|None|
|401|[Unauthorized](https://tools.ietf.org/html/rfc7235#section-3.1)|Unauthorized (トークンが無効)|None|

<h3 id="customerorderpost-responseschema">Response Schema</h3>

Status Code **200**

|Name|Type|Required|Restrictions|Description|
|---|---|---|---|---|
|» order_id|integer|false|none|注文ID|
|» ordered_dishes|[object]|false|none|none|
|»» ordered_dish_id|integer|false|none|注文した料理の注文ID|
|»» ordered_dish_name|string|false|none|注文した料理の名称|

<aside class="success">
This operation does not require authentication
</aside>

## customerOrderFinish

<a id="opIdcustomerOrderFinish"></a>

`POST /customer/order/finish`

*注文確定・会計依頼*

お会計ボタンを押すとそこで注文が締め切られる。is_order_finishedの状態を変更する(false → true)。

<h3 id="customerorderfinish-parameters">Parameters</h3>

|Name|In|Type|Required|Description|
|---|---|---|---|---|
|Seat-Token|header|string|true|座席のQRコードに含まれているトークン|

<h3 id="customerorderfinish-responses">Responses</h3>

|Status|Meaning|Description|Schema|
|---|---|---|---|
|200|[OK](https://tools.ietf.org/html/rfc7231#section-6.3.1)|OK|None|
|400|[Bad Request](https://tools.ietf.org/html/rfc7231#section-6.5.1)|Bad Request (注文が既に締め切られている、もしくは未注文)|None|
|401|[Unauthorized](https://tools.ietf.org/html/rfc7235#section-3.1)|Unauthorized (トークンが無効)|None|

<aside class="success">
This operation does not require authentication
</aside>

