## Laravel Slack API wrapper

This package provides a simple way to use [Slack API](https://api.slack.com).

## Instalation 

`composer require doroshenkoss/slack-api`

## Instalation on Laravel 5
Add to `config/app.php`:

```php
<?php 

[
    'providers' => [
        SlackLite\SlackApi\SlackApiServiceProvider::class,
    ]
]

?>
```
> The ::class notation is optional.


and add the Facades to your aliases, if you need it

```php
<?php

[
    'aliases' => [
        'SlackApi'              => SlackLite\SlackApi\Facades\SlackApi::class,
        'SlackChannel'          => SlackLite\SlackApi\Facades\SlackChannel::class,
        'SlackChat'             => SlackLite\SlackApi\Facades\SlackChat::class,
        'SlackGroup'            => SlackLite\SlackApi\Facades\SlackGroup::class,
        'SlackFile'             => SlackLite\SlackApi\Facades\SlackFile::class,
        'SlackSearch'           => SlackLite\SlackApi\Facades\SlackSearch::class,
        'SlackInstantMessage'   => SlackLite\SlackApi\Facades\SlackInstantMessage::class,
        'SlackUser'             => SlackLite\SlackApi\Facades\SlackUser::class,
        'SlackStar'             => SlackLite\SlackApi\Facades\SlackStar::class,
        'SlackUserAdmin'        => SlackLite\SlackApi\Facades\SlackUserAdmin::class,
        'SlackRealTimeMessage'  => SlackLite\SlackApi\Facades\SlackRealTimeMessage::class,
        'SlackTeam'             => SlackLite\SlackApi\Facades\SlackTeam::class,
    ]
]

?>
```
> The ::class notation is optional.

## Instalation on Lumen

Add that line on `bootstrap/app.php`:

```php
<?php 
// $app->register('App\Providers\AppServiceProvider'); (by default that comes commented)
$app->register('SlackLite\SlackApi\SlackApiServiceProvider');

?>
```

If you want to use facades, add this lines on <code>bootstrap/app.php</code>

```php
<?php

class_alias('SlackLite\SlackApi\Facades\SlackApi', 'SlackApi');
class_alias('SlackLite\SlackApi\Facades\SlackChannel', 'SlackChannel');
class_alias('SlackLite\SlackApi\Facades\SlackChat', 'SlackChat');
class_alias('SlackLite\SlackApi\Facades\SlackGroup', 'SlackGroup');
class_alias('SlackLite\SlackApi\Facades\SlackUser', 'SlackUser');
class_alias('SlackLite\SlackApi\Facades\SlackTeam', 'SlackTeam');
//... and others

?>
```

Otherwise, just use the singleton shortcuts:

```php
<?php

/** @var \SlackLite\SlackApi\Contracts\SlackApi $slackapi */
$slackapi     = app('slack.api');

/** @var \SlackLite\SlackApi\Contracts\SlackChat $slackchat */
$slackchat    = app('slack.chat');

/** @var \SlackLite\SlackApi\Contracts\SlackChannel $slackchannel */
$slackchannel = app('slack.channel');

//or 

/** @var \SlackLite\SlackApi\Contracts\SlackApi $slackapi */
$slackapi  = slack();

/** @var \SlackLite\SlackApi\Contracts\SlackChat $slackchat */
$slackchat = slack('chat'); // or slack('slack.chat')

//...
//...

?>
```

## Configuration

configure your slack team token in <code>config/services.php</code> 

```php 
<?php

[
    //...,
    'slack' => [
        'token' => 'your token here'
    ]
]

?>
```

## Usage

```php
<?php

//Lists all users on your team
SlackUser::lists(); //all()

//Lists all channels on your team
SlackChannel::lists(); //all()

//List all groups
SlackGroup::lists(); //all()

//Invite a new member to your team
SlackUserAdmin::invite("example@example.com", [
    'first_name' => 'John', 
    'last_name' => 'Doe'
]);

//Send a message to someone or channel or group
SlackChat::message('#general', 'Hello my friends!');

//Upload a file/snippet
SlackFile::upload([
    'filename' => 'sometext.txt', 
    'title' => 'text', 
    'content' => 'Nice contents',
    'channels' => 'C0440SZU6' //can be channel, users, or groups ID
]);

// Search for files or messages
SlackSearch::all('my message');

// Search for files
SlackSearch::files('my file');

// Search for messages
SlackSearch::messages('my message');

// or just use the helper

//Autoload the api
slack()->post('chat.postMessage', [...]);

//Autoload a Slack Method
slack('Chat')->message([...]);
slack('Team')->info();

?>
```

## Using Dependency Injection

```php
<?php 

namespace App\Http\Controllers;    
    
use SlackLite\SlackApi\Contracts\SlackUser;

class YourController extends Controller{
    /** @var  SlackUser */
    protected $slackUser;
    
    public function __construct(SlackUser as $slackUser){
        $this->slackUser = $slackUser;   
    }
    
    public function controllerMethod(){
        $usersList = $this->slackUser->lists();
    }
}

?>
```

## All Injectable Contracts:

### Generic API
`SlackLite\SlackApi\Contracts\SlackApi`

Allows you to do generic requests to the api with the following http verbs:
`get`, `post`, `put`, `patch`, `delete` ... all allowed api methods you could see here: [Slack Web API Methods](https://api.slack.com/methods).

And is also possible load a SlackMethod contract:

```php
<?php 

/** @var SlackChannel $channel **/
$channel = $slack->load('Channel');
$channel->lists();

/** @var SlackChat $chat **/
$chat = $slack->load('Chat');
$chat->message('D98979F78', 'Hello my friend!');

/** @var SlackUserAdmin $chat **/
$admin = $slack('UserAdmin'); //Minimal syntax (invokable)
$admin->invite('jhon.doe@example.com'); 

?>
```

### Channels API
`SlackLite\SlackApi\Contracts\SlackChannel`

Allows you to operate channels:
`invite`, `archive`, `rename`, `join`, `kick`, `setPurpose` ...


### Chat API
`SlackLite\SlackApi\Contracts\SlackChat`

Allows you to send, update and delete messages with methods:
`delete`, `message`, `update`.

### Files API
`SlackLite\SlackApi\Contracts\SlackFile`

Allows you to send, get info, delete,  or just list files:
`info`, `lists`, `upload`, `delete`.

### Groups API
`SlackLite\SlackApi\Contracts\SlackGroup`

Same methods of the SlackChannel, but that operates with groups and have adicional methods:
`open`, `close`, `createChild`

### Instant Messages API (Direct Messages)
`SlackLite\SlackApi\Contracts\SlackInstantMessage`

Allows you to manage direct messages to your team members.

### Real Time Messages API
`SlackLite\SlackApi\Contracts\SlackRealTimeMessage`

Allows you list all channels and user presence at the moment.


### Search API
`SlackLite\SlackApi\Contracts\SlackSearch`

Find messages or files.

### Stars API
`SlackLite\SlackApi\Contracts\SlackStar`

List all of starred itens.

### Team API
`SlackLite\SlackApi\Contracts\SlackTeam`

Get information about your team.

### Users API
`SlackLite\SlackApi\Contracts\SlackUser`

Get information about an user on your team or just check your presence ou status.

### Users Admin API
`SlackLite\SlackApi\Contracts\SlackUserAdmin`

Invite new members to your team.

## License

MIT License

Copyright (c) 2020 Doroshenko Serhii

Permission is hereby granted, free of charge, to any person obtaining a copy
of this software and associated documentation files (the "Software"), to deal
in the Software without restriction, including without limitation the rights
to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
copies of the Software, and to permit persons to whom the Software is
furnished to do so, subject to the following conditions:

The above copyright notice and this permission notice shall be included in all
copies or substantial portions of the Software.

THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE
SOFTWARE.
