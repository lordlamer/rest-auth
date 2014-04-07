rest-auth
=========

A php REST Interface for authentification against ldap or possible other services

Building from source
--------------------

Follow the steps below in order to build rest-auth from source. This requires git and php:

1. Checkout the source from git
<pre><code> git clone git&#64;github.com:lordlamer/rest-auth.git
</code></pre>

2. Install vendor software
<pre><code> php composer.phar update
</code></pre>

Configuration
-------------

1. Go to config folder and create a app.ini. You could use the app.ini.dist as template.

	<pre><code> cd config
	</code></pre>
	<pre><code> cp app.ini.dist app.ini
	</code></pre>

	Change app.ini to your needs.

2. Enable access to public folder from webserver

Authentication
--------------

In order to authenticate the user, the application can pass username and password as a HTTP GET or POST request like below:

### Passing username and password as a GET request
#### Route /auth/ldap must be defined
Below is how to make GET request
<pre><code> http://domain/auth/ldap/username/password
</code></pre>

### Passing username and password as a POST request
#### Route /auth/ldap must be defined
The username and password can be passed as a POST request to the following URL.
<pre><code> http://domain/auth/ldap
</code></pre>

### Server response
The server response is in json format, and returns the following on successful authentication
<pre><code> {"status":"SUCCESS","message":""}
</code></pre>

The server response is in json format, and returns the following on invalid authentication
<pre><code> {"status":"ERROR", "message":"......"}
</code></pre>

License
-------

Copyright (c) 2014, lordlamer
All rights reserved.

Redistribution and use in source and binary forms, with or without
modification, are permitted provided that the following conditions are met:

* Redistributions of source code must retain the above copyright notice, this
  list of conditions and the following disclaimer.

* Redistributions in binary form must reproduce the above copyright notice,
  this list of conditions and the following disclaimer in the documentation
  and/or other materials provided with the distribution.

* Neither the name of the {organization} nor the names of its
  contributors may be used to endorse or promote products derived from
  this software without specific prior written permission.

THIS SOFTWARE IS PROVIDED BY THE COPYRIGHT HOLDERS AND CONTRIBUTORS "AS IS"
AND ANY EXPRESS OR IMPLIED WARRANTIES, INCLUDING, BUT NOT LIMITED TO, THE
IMPLIED WARRANTIES OF MERCHANTABILITY AND FITNESS FOR A PARTICULAR PURPOSE ARE
DISCLAIMED. IN NO EVENT SHALL THE COPYRIGHT HOLDER OR CONTRIBUTORS BE LIABLE
FOR ANY DIRECT, INDIRECT, INCIDENTAL, SPECIAL, EXEMPLARY, OR CONSEQUENTIAL
DAMAGES (INCLUDING, BUT NOT LIMITED TO, PROCUREMENT OF SUBSTITUTE GOODS OR
SERVICES; LOSS OF USE, DATA, OR PROFITS; OR BUSINESS INTERRUPTION) HOWEVER
CAUSED AND ON ANY THEORY OF LIABILITY, WHETHER IN CONTRACT, STRICT LIABILITY,
OR TORT (INCLUDING NEGLIGENCE OR OTHERWISE) ARISING IN ANY WAY OUT OF THE USE
OF THIS SOFTWARE, EVEN IF ADVISED OF THE POSSIBILITY OF SUCH DAMAGE.
