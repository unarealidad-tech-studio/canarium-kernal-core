# CanariumCore

The base of canarium that includes the framework and the abstraction layers that is crucial for building canarium-based applications.This is the core of canarium that installs and initializes dependencies for Canarium Modules to work. This is the the base requirement of any canarium appinstance or appmaster. 

# Installation

Install via composer: 

`composer require unarealidad/canarium-kernal-core dev-master`

Add `CanariumCore` to your Appmaster's `config/application.config.php` or your Appinstance's `config/instance.config.php` under the key `modules`

Copy the sample config `config/canariumcontactus.local.php.dist` to your Appinstance's `config/autoload/` directory and remove the `.dist` extension.

Go to your Appinstance directory and run the following to update your database:

`./doctrine-module orm:schema-tool:update --force`

Import data/initial_rows.sql to your database. This will add 2 new users in the database:

User: root@root.com
Password: 123456
User Level: Super User

User: admin@admin.com
Password: 123456
User Level: Admin

Remember to change the password for each account.

# Configuration

Configuration main key: `canariumcore`
Sample Config file: `config/canariumcore.local.php.dist`

Config Item | Sample Value | Required | Description
--- | --- | --- | ---
site_name | 'Sample Site' | true | The name of your site that will be displayed in the page title
verbose_title | true | false | Whether we show the controller and action in the page title for debugging purpose
is_authentication_required | true | false | Whether all pages needs to be accessed by logged in users only. Defaults to false. If set to true, then all request to a page by a guest user will redirect them to the login page (except for register and forgot password page).
is_authentication_whitelist | array('login') | false | Array of routes to exclude from the redirection caused by `is_authentication_required`.
login_on_denied_access | true | false | Whether we redirect the user to the login page when a 403 forbidden is issued. Defaults to true. If set to false, the 403 message and trace will be seen which is good for debugging purposes.
denied_access_redirect_route | 'home' | The route to redirect to when a 403 is issued. This will be overriden if login_on_denied_access is set to true.
logout_third_party_login_too | false | false | Whether we logout third party login sessions too like Google after the user logs out.
application_hash | '#1HKS)>>#EAZJR' | false | The hash that will be used when creating authentication token
default_app_id | 11 | false | The default app id to use on api calls
default_app_secret | '50b9d04e28e1380bf522a7430b7a9b5c08a8cc16' | false | The default app secret to use on api calls


# Exposed Pages

URL | Template | Access | Description
----- | ----- | ----- | ----- | -----
/user/login | user/login.phtml | Guest | Displays the login page
/user/logout | _none_ | User | Logs the user out
/user/register | user/register.phtml | Guest | Displays the registration page
/user/changepassword | user/changepassword.phtml | User | Displays the change password page
/user/changeemail | user/changeemail.phtml | User | Displays the change email page
user/update-profile | user/update-profile.phtml | User | Displays the profile update page
/admin | admin/index.phtml | Admin | Displays the landing page for the admin panel

\* All template locations are relative to the Appinstance root's /public/templates/canarium-core/. Sample templates are provided in the module's view/ directory.

# Exposed Services
`canariumcore_user_service` - The user service that handles user related operations like loggin in, registration etc.
`canariumcore_app_service` - Api service of canarium

# Canarium Exposed Endpoints

All endpoints of canarium are exposed in '/canarium-api'. The returned data is in json in the following format

```javascript
{
    code: 200,
    message: 'Some message',
    data: {}
}
```

Where `data` contains the output of the endpoint.

Endpoint | Method | Parameters | Description
---- | ---- | ---- | ---- 
/canarium-api/get-current-user | GET | _none_ | Returns the current logged in user information.
/canarium-api/login | POST | id, secret, email | Returns an access token to the user
/canarium-api/delete-account | POST | access_token | Deletes the current logged in user. This requires a valid access token
