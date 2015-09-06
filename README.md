SimpleErrorHandler
==================

SimpleErrorHandler provides an error handler that outputs error messages.

HHVM has no default error handler. This can be used with hhvm to provide a default.

Usage
-----

Include something like the following in an auto prepend file:

```
require_once '/path/to/file/SimpleErrorHandler/SimpleErrorHandler.php';
set_error_handler( 'SimpleErrorHandler::error_handler' );
```

In php.ini, set something like:

```
auto_prepend_file = /path/to/file/prepend.php
```

To report all errors in your php application, add something like:

```
ini_set( "display_errors", 1 );
error_reporting( -1 );
```
