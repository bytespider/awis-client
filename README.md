# AWIS Client

## Installation
`composer require bytespider/awis-client`

## Usage
```php
<?php

$client = new AwisClient('<IAM Access Key>', '<IAM Access Secret>');
$client->getUrlInfor('yahoo.com');
```