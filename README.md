<h1 align="center">
Cache Billing
    <br>
    <img src="https://github.com/medunes/cache-billing/blob/master/logo.png" width="200">
</h1>

<h5>Cache billing system</h3>

[![Build Status](https://github.com/medunes/cache-billing/workflows/build/badge.svg?style=flat-square)](https://github.com/medunes/cache-billing/actions?query=workflow%3A%22build%22)
[![Author](https://img.shields.io/badge/author-@medunes-blue.svg?style=flat-square)](https:/blog.medunes.net)
[![codecov](https://codecov.io/gh/medunes/cache-billing/branch/master/graph/badge.svg?token=RWOA8OR0B9)](https://codecov.io/gh/medunes/cache-billing)
[![PHPStan](https://img.shields.io/badge/PHPStan-Level%205-brightgreen.svg?style=flat&logo=php)](https://shields.io/#/)
[![Psalm](https://img.shields.io/badge/Psalm-Level%205-brightgreen.svg?style=flat&logo=php)](https://shields.io/#/)
[![Psalm Coverage](https://shepherd.dev/github/medunes/cache-billing/coverage.svg)](https://shepherd.dev/github/medunes/cache-billing/coverage.svg)

<br>

## 📦 Installation

To install this application, first ensure you have [Composer](https://getcomposer.org/download//) installed, then: 


```bash
# clone the repository
$ git clone git@github.com:medunes/cache-billing.git

# Enter your app folder
$ cd cache-billing

# Install dependencies
$ composer setup:clean

# Run the build script (phpunit, psalm, phpstan  etc.)
$ composer build

```

## ℹ️ Documentation

### Business background

* Cache Billing (CB) customers are receiving a bill, which is based on the page impressions (PI) their online shop made in the last month. 
* The billing amount of the invoice depends on the PI, and the cost is based per 100,000 PI.
* Currently, Cache Billing is generating a CSV "CacheBilling_1.csv", which includes date, URL, pi / day, and pi / day.
* Based on this CSV, Cache billing system will generate a Bill, in PDF format, which will be delivered to the customer.

### What does the Bill report contain?
* The bill report contains the following information:
* The current effort when a new customer signs a contract is to open the file Template_Bill.odt and create a new template for that new customer editing following items:

  * Invoice address of the client
  * Sender address
  * Phone
  * Fax
  * Email
  * Header
  * Footer
  * Column heading
  * Text in column "Usage"
  * Calculation of the invoice amount
  * Due date of the payment
  * Signature

### How is the cost calculated?

* ```100,000 PI x Unit Price = CacheBilling-cost per month excluding VAT```
Depending on the BC package the costs varies:

* Basic = 50, - €
* Professional = 120, - €
* Enterprise = custom

## Usage with Docker

### Build the container
```bash
docker compose up --build -d
```
### Login to the box
```bash
 docker compose exec --user=1000:1000  php-cli bash
 ```

### Configuring the setup

* ```PHP_VERSION``` can be changed from the [.env](./.env) (default is ```PHP_VERSION=8.0```)
*  If you face permission issues, most probably it is about your host's UID is not 1000. (1000 is the Debian/Ubuntu/Linux deafault UID).
As this is not a fully featured project, you might adjust this to your needs by changing the ```1000``` to your UID.
Changes should go under the ```Dockerfile``` and ```docker-compose.yml```.


## Usage of the application

This is an example of a bill generation command:

```bash
 bin/console cache:bill:generate john_doe -y 2020 -m 12 -x odt -x html -vvv 2>&1 >/dev/null
```

And the result will look like this:

```bash
16:16:59 INFO      [app] Successfully generated Cache Bill
[
  "customer" => "john_doe",
  "billMonth" => "12",
  "billYear" => "2020",
  "exportType" => [
    "odt",
    "html"
  ],
  "generatedFiles" => [
    "cache-billing/public/export/john_doe/2020/12/2021-M1234.odt",
    "cache-billing/public/export/john_doe/2020/12/2021-M1234.html"
  ]
]
```
<br>

## Where can I find the exported files?

Under the public/export folder.

Everytime you generate an export for a customer names "John Doe" for  the month December of year 2020, a folder tree:
public/export/john_doe/2020/12 will be automatically created, and the last version of the exported bills (html and/or odt)
can be found there


## Does it export to PDF?

Yes, but not out of the box.
The application exports the bill to an HTML file, fitted to an A4 size.
You only have to launch the File->Print menu from your browser and save it as a PDF ;)

## How to add customers to the billing system?

This is very easy!

Open the download/customer/customers.ods file, and then add rows corresponding to customers' data


## Can the system process usage cache from more than one CSV file at once?

Yes, the -f or --files option accepts a list of file paths.

Once you provide these files, hte application will combine them all and make a one "table" out of them

## Can the system download configuration files automatically from a remote site?

Yes!

Just edit and activate the cron under etc/crontab/sync.cron
It is currently configured to download the latest cache export file every start of a month, it should 
hold the name cache-2021-01.csv for example for the month February 2021

It will also download customers data file data daily (customers.ods)

Take your time to adjust this schedules from sites like this one https://crontab.guru/

Also do not forget to replace the placeholders of the remote file locations in the crontab file

##  Is this system configurable?

Definitely. It follows the principle of configuration over code.

Mainly, the system is highly and easily configurable through the config/services.yaml file.

There you can explore and edit many parameters. Just as an example you can manually change the placeholders of the 
template, the headers of the cache CSV or the export path.


## How the system is internally designed?

Although a navigation throughout the code can (hopefully) answer this question, here is a dataflow
diagram explaining how the bill is generated.
<br>
<img src="https://github.com/medunes/cache-billing/blob/master/doc/diagram.png" width="800" height="600">

## Additional information
- [Open Document technical specification](https://en.wikipedia.org/wiki/OpenDocument_technical_specification)
- [Comma sparated values](https://en.wikipedia.org/wiki/Comma-separated_values)
