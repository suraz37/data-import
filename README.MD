##  Import data [![Travis](https://travis-ci.org/suraz37/import-data.svg)](https://travis-ci.org/suraz37/import-data)
Simple script that retrieves file from a URL and imports data into database

### Requirements
- PHP 7.0.8
- Sqlite or mysql

### Directory Descriptions

|Directory | Remarks|
|----------|--------|
|src/demo/DownloadCommand| Command class|
|src/demo/CsvReader| Read from csv and Migrate|
|src/demo/Entity | Model class|
|src/demo/Repository | Class to communicate with database.|
|src/demo/Downloader | Downloader Class with source url and destination path.|
|src/demo/Exception | Exception Handler.|
|src/demo/Transformer | Data mapper.|
|log | Save log files.|
|tests | Test cases.|


### File Descriptions
| File | Remarks |
|------|---------|
|public/index.php | Start application file|
|public/dump.sql | Database script|
|public/config.php | Database configuration|
|composer.json | php package configuration |
|.travis.yml | CI configuration file |
|public/create-csv.php | Create require number of line in csv file, for testing |


### Installation
Install dev dependencies
```shell
$ composer install
```

### Configuration
1. Create database and table from script
```path
public/dump.sql
```

2. Database configuration 
```path
public/config.php
```

### Usage
Script to run application

```shell
$ php example/index.php app:download-file http://localhost:3000/public/quick-orders.csv
```

### script Descriptions
| script | Remarks |
|------|---------|
|php | Execute mode for php |
|public/index.php | Start application |
|app:download-file | Script to download |
|http://localhost:3000/public/quick-orders.csv | File url |

#### Output Response.
```shell
Downloading file
Source : http://localhost:3000/public/quick-orders.csv
Total Data import: 1000
```

#### Run the tests
```
$ ./vendor/bin/phpunit
```

#### Folder Structure
├── README.MD
├── composer.json
├── composer.lock
├── phpunit.xml
├── public
│   ├── config.php
│   ├── dump.sql
│   ├── index.php
│   └── quick-orders.csv
├── src
│   └── App
│       └── Demo
│           ├── Command
│           │   └── DownloadFileCommand.php
│           ├── CsvReader
│           │   ├── Migration.php
│           │   └── Reader.php
│           ├── DbConnection.php
│           ├── Downloader
│           │   └── StreamDownloader.php
│           ├── Entity
│           │   ├── Model.php
│           │   ├── Order.php
│           │   └── Product.php
│           ├── Exception
│           │   ├── DownloaderException.php
│           │   └── MigrationException.php
│           ├── Repository
│           │   ├── OrderRepository.php
│           │   └── ProductRepository.php
│           └── Transformer
│               └── CsvTransformer.php
└── tests
    └── App
        └── Demo
            ├── Command
            │   └── DownloadFileCommandTest.php
            ├── CsvReader
            │   └── MigrationTest.php
            ├── Downloader
            │   └── StreamDownloaderTest.php
            ├── Repository
            │   ├── OrderRepositoryTest.php
            │   └── ProductRepositoryTest.php
            └── Transformer
                └── CsvTransformerTest.php

