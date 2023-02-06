# Requirements

## Table of Contents

1. [OGC API - Feature](#ogc-api-feature)
2. [Server API](#server-api)
   - [Drivers](#drivers)
   - [Datasources](#datasources)


## OGC API Feature

The requirements for the OGC API Feature are found in the [OGC API - Features - Part 1: Core corrigendum](https://docs.opengeospatial.org/is/17-069r4/17-069r4.html)

## Server API

### Drivers

Drivers represent an implementation of vendor specific implementation of the database abstract layer interface. The 
availability of drivers depends on the extension install. 

| Requirement(s) | Description                                                                                                                 | Status |
|----------------|-----------------------------------------------------------------------------------------------------------------------------|--------|
| 2.1.A          | The server **SHALL** support the HTTP GET method at path `{root}/api/drivers                                                | 🟢     |
| 2.2.A          | The successful execution of the operation **SHALL** be reported as a response with an HTTP status code `200`                | 🟢     |
| 2.2.B          | The response **SHALL** be an array of objects based upon the OpenAPI 3.0 schema, [driver.yaml](openapi/schema/driver.yaml). | 🟢     |
| 2.3.A          | The response **MUST** only include drivers which are installed.                                                             | 🟡     |

### Datasource(s)
| Requirement(s) | Description                                                                                                                                                                                                                                                                                                                                                                               | Status |
|----------------|-------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------|--------|
| 2.4.A          | The server **SHALL** support the HTTP GET method at path `{root}/api/datasources`                                                                                                                                                                                                                                                                                                         | 🟢     |
| 2.5.A          | The successful execution of the operation **SHALL** be reported as a response with an HTTP status code `200`                                                                                                                                                                                                                                                                              | 🟢     |
| 2.5.B          | The response **SHALL** be an objects based upon the OpenAPI 3.0 schema with the following properties: <ol><li>`datasources` which **MUST** be an array of objects based upon the OpenAPI 3.0 schema, [datasource.yaml](openapi/schema/datasource.yaml).</li><li>`links` which **MUST** be an array of object base upon the OpenAPI 3.0 schema, [link.yaml](openapi/schema/link.yaml)</ul> | 🟢     |
