# Lab Final Project: Event Calendar

The event calendar api hosts event information for a class project in CPTR320.
Only basic event setup is available through the API:

* Event Name
* Event Description
* Event Date
* Who is hosting event

## Public Access

### Access to this site is no longer available due to non-renewal of AWS service
The Event Calendar api can be accessed through http://18.208.163.54:8888/api/v1/calendar

## API Documentation

The API has been documented in Swagger.
The Swagger YAML configuration file is located at school-2.0.0-swagger.yaml.

## Setup

Clone this repository in your VM home directory.
The scripts shown below assume this will be the project path.

## Helpful Commands

Update and Start REST service on a remote VM.

```sh
./start_remote_service
```

SSH into the aws instance. Note, this command must be run from inside the cptr-320-final-project folder
```
ssh -i "CPTR320-Final.pem" ubuntu@ec2-18-208-163-54.compute-1.amazonaws.com
```

Update and Start REST service on a remote VM.

```sh
vagrant ssh
cd /vagrant/school_rest/
cp .env.example .env
composer install
php -S 0.0.0.0:8888 -t public
```

Create and generate fake data in the database.

```sh
php artisan migrate:refresh --seed
```

How to push your changes to repo
```
git pull

git status //shows all changed files

git add //indicate specific files here
or git add . //adds all changed files

git commit -m "message of what is changed "

git push
```

How to run tests
```
php vendor/bin/phpunit //run in vagrant ssh school_rest folder