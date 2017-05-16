This project Buil in laravel 5.3 for demo purpose

Use composer to Install/Update third party library like 'tymon/jwt-auth'

Use 'php artisan migrate' to run the DB migration

The Api routes:

Method : POST
http://domainname/v1/api/user
payload : 
{
	'name':'xyz',
	'email': 'xyz@gmail.com'
	'password': 'password'
}
Method : POST
http://domainname/v1/api/user/signin
payload : 
{
	'email' : 'xyz@gmail.com',
	'password' : 'password'
	
}

Method : POST
http://domainname/v1/api/character?token=
payload
{
	"name": "Boxing",
	"description": "Boxing player",
	"power": "75"
}

Method : PUT
http://domainname/v1/api/character/1?token=
payload
{
	"name": "Boxing2",
	"description": "Boxing player",
	"power": "85"
}


Method : GET
http://domainname/v1/api/character

Method : DELETE
http://domainname/v1/api/character/1?token=