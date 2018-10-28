###API DOCUMENTATION
####Login
To login submit email and password through POST
```php
curl -X POST api_url/login \
  -F "email=sample@email.com" \
  -F "password=mypass123" 
```

**Sample response:**
```json
    {
        "code": 200,
        "data": {
            "token": "MYjqy447FzeT8du3fgLtPOs1YTdynK",
        }
    }
```

####Get Articles
To get articles send GET request
```php
curl -X GET api_url/articles 
```

####Create Article
Parameters ``` title, category_id, user_id description ```
```php
curl -X POST api_url/login \
  -F "title=The Earth is Round" \
  -F "category_id=10" \
  -F "user_id=2" \
  -F "description=The earth is acrtually round!" 
```
