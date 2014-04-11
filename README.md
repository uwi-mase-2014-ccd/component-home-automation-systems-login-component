Project H.A.S Component - Authentication Documentation
------------------------------------------------------------
Author Group: **Home Automation Systems**

Prepared for: Dr. Curtis Busby-Earle

Prepared by: Aston Hamilton, Renee Whitelocke, Orane Edwards

April 1, 2014

Version number: 000-0001


##Component Description
The purpose of this component is to authenticate the credentials of a registered HAS user account.
This component uses the functionality from the User Management component that was built by the **Jamaica Rugby League** team.

Note: The `username` from this component is mapped to the `email` input of the User Management component.

    User Management Component: https://github.com/uwi-mase-2014-ccd/component-jamaican-rugby-league-system-user-management-services

##Services
The _Authenticate Credentials_ web service is exposed by this component

###Endpoint
This component has been deployed to the UWI server at the endpoint: 

    POST http://cs-proj-srv:8083/service-login/src/login.php

###Arguments
    username: 
        This STRING argument specifies the username to authenticate.

    password:
        This STRING argument specifies the password associated with the given username, to authenticate.

        
    
###Description:
Authenticate Credentials
    This web service will check if the submitted username and password are registered in the User Management Component.
    
###Responses:
####Successful Authentication
On Successful Authentication a response similar to the following sample response is returned:
```javascript    
{
    "code": 200,
    "data": {
        "success": true
    },
    "debug": {}
}
```
    Refer to schema: response-200.json

####Unsuccessful Authentication
If the credentials are not authentic a response similar to the following sample response is returned:
```javascript    
{
    "code": 403,
    "data": {
        "success": false
    },
    "debug": {}
}
```
    Refer to schema: response-403.json

####Invalid HTTP Method
On An Invalid HTTP Method a response similar to the following sample response is returned:
```javascript
{
    "code": 400,
    "data": {},
    "debug": {
        "data": {},
        "message": "This service only accepts a POST Request."
    }
}
```
    Refer to schema: response-400.json
    
####Missing Arguments
If a required argument is not submitted, a response similar to the following sample response is returned:
```javascript
{
    "code": 400,
    "data": {},
    "debug": {
        "data": {},
        "message": "This service requires the following arguments [username, password]."
    }
}
```
    Refer to schema: response-400.json
    
####Unexpected Error
On Any Unexpected Error a response similar to the following sample response is returned:
```javascript
{
    "code": 500,
    "data": {},
    "debug": {
        "data": {},
        "message": "An exception has occured"
    }
}
```
    Refer to schema: response-500.json

