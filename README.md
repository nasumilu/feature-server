# nasumilu/feature-server

This project is a php implementation of the OGC API Features.

## Usage

### Clone the project

```shell
$ git clone https://github.com/nasumilu/feature-server.git
$ cd feature-server
```

### Setup Development Environment

First copy the contained `.env` file to `.env.local` and edit to match the local development environment. 

> The `.env.local` **MUST** never be committed since it contains 
sensitive information about the local development environment. (e.g. database password, secret keys, ...)

```shell
$ cp .env .env.local
$ nano .env.local
```
Next start the development server. It is assumed that Symfony CLI is installed. To find out more about installing
Symfony CLI, see [https://symfony.com/download](https://symfony.com/download)

```shell
$ symfony server:start
```

### Resources
[Feature Server Documenation](/docs/index.md)
[OGC API - Features - Part 1: Core corrigendum](https://docs.opengeospatial.org/is/17-069r4/17-069r4.html)
[Symfony](https://symfony.com/)
[Doctrine DBAL](https://www.doctrine-project.org/projects/dbal.html)