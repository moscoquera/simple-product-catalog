# Simple product catalog

simple product catalog built using microservices, laravel, vue and docker-compose.

## How to use
 clone me:	

    git clone https://github.com/moscoquera/simple-product-catalog.git

start the containers:

	docker-compose up -V
wait until  the application is initialized and seeded. the initialization process runs after the first container start. you can run **docker-compose start** safely without reinitilizing the whole site.

## Access:
The backend is located at http://localhost:8001. the default user credentials are:
email: **admin@admin.com**
password:**admin**
the site is going to generate a 6-digits code that's going to be sent though email. use the mailhog server (http://localhost:8025) to read it. use the code to finish the login.

the admin interface is designed as a Single Page Application to make it easier to move to another url if need, basic crud operations included in the admin.

The Frontend is located at http://localhost:8002, there you can see the category and products generated in the backend. the uses laravel as a base but it's designed as a SPA as well.

# Problems
* if the COP price is set as 0 or is equal to the USD price, kindly update to conversion api key. you can get one [here](https://currencylayer.com/), set the new api key inside **/frontend/resources/js/globals.js** 

	    export const remoteServer = {  
		    baseUrl: 'http://localhost:8001',  
		  baseAjax: 'http://localhost:8001/api'  
		}  
	  
		export const currency = {  
		    key:'API_KEY_HERE',  
		  endpoint:'http://apilayer.net/api/live',  
		  base:'USD',  
		  to:'COP'  
		}
* if Database connection errors raise up, just run 

> composer up -V

do not forget the -V parameter.